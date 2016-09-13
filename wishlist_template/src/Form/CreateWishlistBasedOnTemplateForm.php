<?php

namespace Drupal\wishlist_template\Form;

use Drupal\wishlist_template\Entity\WishlistTemplateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\commerce\PurchasableEntityInterface;
use Drupal\commerce_wishlist\WishlistManagerInterface;
use Drupal\commerce_wishlist\WishlistProviderInterface;
use Drupal\commerce_order\Resolver\OrderTypeResolverInterface;
use Drupal\commerce_order\LineItemStorageInterface;
use Drupal\commerce_store\StoreContextInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Link;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CreateWishlistBasedOnTemplateForm extends FormBase {

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * The wishlist manager.
   *
   * @var \Drupal\commerce_wishlist\WishlistManagerInterface
   */
  protected $wishlistManager;

  /**
   * The wishlist provider.
   *
   * @var \Drupal\commerce_wishlist\WishlistProviderInterface
   */
  protected $wishlistProvider;

  /**
   * The order type resolver.
   *
   * @var \Drupal\commerce_order\Resolver\OrderTypeResolverInterface
   */
  protected $orderTypeResolver;

  /**
   * The store context.
   *
   * @var \Drupal\commerce_store\StoreContextInterface
   */
  protected $storeContext;

  /**
   * Constructs a new AddToWishlistForm object.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager.
   * @param \Drupal\commerce_wishlist\WishlistManagerInterface $wishlist_manager
   *   The cart manager.
   * @param \Drupal\commerce_wishlist\WishlistProviderInterface $wishlist_provider
   *   The cart provider.
   * @param \Drupal\commerce_order\Resolver\OrderTypeResolverInterface $order_type_resolver
   *   The order type resolver.
   * @param \Drupal\commerce_store\StoreContextInterface $store_context
   *   The store context.
   */
  public function __construct(EntityManagerInterface $entity_manager, WishlistManagerInterface $wishlist_manager, WishlistProviderInterface $wishlist_provider, OrderTypeResolverInterface $order_type_resolver, StoreContextInterface $store_context) {

    $this->entityManager = $entity_manager;
    $this->wishlistManager = $wishlist_manager;
    $this->wishlistProvider = $wishlist_provider;
    $this->orderTypeResolver = $order_type_resolver;
    $this->storeContext = $store_context;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager'),
      $container->get('commerce_wishlist.wishlist_manager'),
      $container->get('commerce_wishlist.wishlist_provider'),
      $container->get('commerce_order.chain_order_type_resolver'),
      $container->get('commerce_store.store_context')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'wishlist_template_create_or_update';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param \Drupal\wishlist_template\Entity\WishlistTemplateInterface $wishlist_template
   *
   * @return array The form structure.
   * The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $wishlist_template = NULL) {

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Start Shopping using this template.'),
    );

    return $form;

  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /**
     * @var \Drupal\wishlist_template\Entity\WishlistTemplateInterface $wishlist_template
     * @var \Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem[] $default_products
     */
    $wishlist_template = $form_state->getBuildInfo()['args'][0];
    $default_products = $wishlist_template->get('default_products')->referencedEntities();
    if ($default_products) {
      // Get/Create line item(s).
      $purchased_entities = array();
      $line_items = array();
      foreach ($default_products as $product) {
        /**
         * @var \Drupal\commerce_product\Entity\ProductInterface $product
         * @var \Drupal\commerce_product\Entity\ProductVariationInterface $product_variation
         * @var \Drupal\commerce_order\LineItemStorageInterface $line_item_storage
         */
        $product_variation = $product->getDefaultVariation();
        $line_item_storage = $this->entityManager->getStorage('commerce_line_item');
        $purchased_entities[] = $product_variation;
        $line_item = $line_item_storage->createFromPurchasableEntity($product_variation);

        // Now that the purchased entity is set, populate the title and price.
        $line_item->setTitle($product_variation->getLineItemTitle());
        // @todo Remove once the price calculation is in place.
        $line_item->unit_price = $product_variation->price;
        $line_items[] = $line_item;
      }

      // Use first default product for everything (facepalm).
      $order_type = $this->orderTypeResolver->resolve($line_items[0]);
      $store = $this->selectStore($purchased_entities[0]);
      $wishlist = $this->wishlistProvider->getWishlist($order_type, $store);
      if (!$wishlist) {
        $wishlist = $this->wishlistProvider->createWishlist($order_type, $store);
      }

      // Determine if wishlist has a field that can connect w/ template.
      $wishlist_fields = array_keys($wishlist->getFields());
      foreach ($wishlist_fields as $wishlist_field) {
        $order_wishlist_template_reference_field = $wishlist->get($wishlist_field);
        // Only interested in Entity References that target wishlist_templates.
        if ($order_wishlist_template_reference_field->getFieldDefinition()->getType() == "entity_reference" &&
          $order_wishlist_template_reference_field->getItemDefinition()->getSetting("target_type") == "wishlist_template") {
          // The variable $field now has the field we will use to
          // connect the template to the order.
          break;
        }
        $order_wishlist_template_reference_field = FALSE;
      }

      if ($order_wishlist_template_reference_field !== FALSE) {
        // Connect wishlist to this template.
        $wishlist->set($order_wishlist_template_reference_field->getName(),$wishlist_template->id());

        // Add the default products (or increment them if they already exist.
        foreach ($line_items as $line_item) {
          $this->wishlistManager->addLineItem($wishlist, $line_item, TRUE);
        }
        drupal_set_message("Your wishlist is now using the " . $wishlist_template->getName() . "!");
      } else {
        drupal_set_message($this->t('The order type ' . $order_type . ' must have an entity_reference that accepts a single wishlist_template.'),"error");
      }
    } else {
      drupal_set_message($this->t('Could not initiate your wishlist template because it has no default products.'),"error");
    }

    // TODO: Implement submitForm() method.
    dpm("submission happened.");
  }

  /**
   * Selects the store for the given purchasable entity.
   *
   * If the entity is sold from one store, then that store is selected.
   * If the entity is sold from multiple stores, and the current store is
   * one of them, then that store is selected.
   *
   * @param \Drupal\commerce\PurchasableEntityInterface $entity
   *   The entity being added to cart.
   *
   * @throws \Exception
   *   When the entity can't be purchased from the current store.
   *
   * @return \Drupal\commerce_store\Entity\StoreInterface
   *   The selected store.
   */
  protected function selectStore(PurchasableEntityInterface $entity) {
    $stores = $entity->getStores();
    if (count($stores) === 1) {
      $store = reset($stores);
    }
    else {
      $store = $this->storeContext->getStore();
      if (!in_array($store, $stores)) {
        // Indicates that the site listings are not filtered properly.
        throw new \Exception("The given entity can't be purchased from the current store.");
      }
    }

    return $store;
  }
}