<?php

namespace Drupal\commerce_wishlist\Form;

use Drupal\commerce\PurchasableEntityInterface;
use Drupal\commerce_store\StoreContextInterface;
use Drupal\commerce_wishlist\WishlistManagerInterface;
use Drupal\commerce_wishlist\WishlistProviderInterface;
use Drupal\commerce_wishlist\Resolver\WishlistTypeResolverInterface;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the wishlist item add to wishlist form.
 */
class AddToWishlistForm extends ContentEntityForm {

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
   * The wishlist type resolver.
   *
   * @var \Drupal\commerce_wishlist\Resolver\WishlistTypeResolverInterface
   */
  protected $wishlistTypeResolver;

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
   *   The wishlist manager.
   * @param \Drupal\commerce_wishlist\WishlistProviderInterface $wishlist_provider
   *   The wishlist provider.
   * @param \Drupal\commerce_wishlist\Resolver\WishlistTypeResolverInterface $wishlist_type_resolver
   *   The wishlist type resolver.
   * @param \Drupal\commerce_store\StoreContextInterface $store_context
   *   The store context.
   */
  public function __construct(EntityManagerInterface $entity_manager, WishlistManagerInterface $wishlist_manager, WishlistProviderInterface $wishlist_provider, WishlistTypeResolverInterface $wishlist_type_resolver, StoreContextInterface $store_context) {
    parent::__construct($entity_manager);

    $this->wishlistManager = $wishlist_manager;
    $this->wishlistProvider = $wishlist_provider;
    $this->wishlistTypeResolver = $wishlist_type_resolver;
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
      $container->get('commerce_wishlist.chain_wishlist_type_resolver'),
      $container->get('commerce_store.store_context')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getBaseFormId() {
    return $this->entity->getEntityTypeId() . '_' . $this->operation . '_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    // @todo Wait for the solution in Commerce and then do the same: https://www.drupal.org/node/2827721
    $product_id = $this->entity->getPurchasableEntity()->getProductId();
    $form_id = $this->entity->getEntityTypeId();
    if ($this->entity->getEntityType()->hasKey('bundle')) {
      $form_id .= '_' . $this->entity->bundle();
    }
    if ($this->operation != 'default') {
      $form_id = $form_id . '_' . $this->operation;
    }
    $form_id .= '_' . $product_id;

    return $form_id . '_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    // The widgets are allowed to signal that the form should be hidden
    // (because there's no purchasable entity to select, for example).
    if ($form_state->get('hide_form')) {
      $form['#access'] = FALSE;
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function actions(array $form, FormStateInterface $form_state) {
    $actions['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add to wishlist'),
      '#submit' => ['::submitForm'],
    ];

    return $actions;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    /** @var \Drupal\commerce_wishlist\Entity\WishlistItemInterface $wishlist_item */
    $wishlist_item = $this->entity;
    /** @var \Drupal\commerce\PurchasableEntityInterface $purchasable_entity */
    $purchasable_entity = $wishlist_item->getPurchasableEntity();

    $wishlist_type = $this->wishlistTypeResolver->resolve($wishlist_item);

    $store = $this->selectStore($purchasable_entity);
    $wishlist = $this->wishlistProvider->getWishlist($wishlist_type, $store);
    if (!$wishlist) {
      $wishlist = $this->wishlistProvider->createWishlist($wishlist_type, $store);
    }
    $this->wishlistManager->addWishlistItem($wishlist, $wishlist_item, $form_state->get(['settings', 'combine']));

    drupal_set_message($this->t('@entity added to @wishlist-link.', [
      '@entity' => $purchasable_entity->label(),
      '@wishlist-link' => Link::createFromRoute($this->t('your wishlist', [], ['context' => 'wishlist link']), 'commerce_wishlist.page')->toString(),
    ]));
  }

  /**
   * {@inheritdoc}
   */
  public function buildEntity(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\commerce_wishlist\Entity\WishlistItemInterface $entity */
    $entity = parent::buildEntity($form, $form_state);
    // Now that the purchased entity is set, populate the title.
    $entity->setTitle($entity->getPurchasableEntity()->getOrderItemTitle());

    return $entity;
  }

  /**
   * Selects the store for the given purchasable entity.
   *
   * If the entity is sold from one store, then that store is selected.
   * If the entity is sold from multiple stores, and the current store is
   * one of them, then that store is selected.
   *
   * @param \Drupal\commerce\PurchasableEntityInterface $entity
   *   The entity being added to wishlist.
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
