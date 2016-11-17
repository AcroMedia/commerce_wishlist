<?php

namespace Drupal\commerce_wishlist\Plugin\Block;

use Drupal\commerce_wishlist\WishlistProviderInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a wishlist block.
 *
 * @Block(
 *   id = "commerce_wishlist",
 *   admin_label = @Translation("Wishlist"),
 *   category = @Translation("Commerce")
 * )
 */
class WishlistBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The wishlist provider.
   *
   * @var \Drupal\commerce_wishlist\WishlistProviderInterface
   */
  protected $wishlistProvider;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new WishlistBlock.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\commerce_wishlist\WishlistProviderInterface $wishlist_provider
   *   The wishlist provider.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, WishlistProviderInterface $wishlist_provider, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->wishlistProvider = $wishlist_provider;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('commerce_wishlist.wishlist_provider'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'dropdown' => TRUE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['commerce_wishlist_dropdown'] = [
      '#type' => 'radios',
      '#title' => $this->t('Display wishlist contents in a dropdown'),
      '#default_value' => (int) $this->configuration['dropdown'],
      '#options' => [
        $this->t('Yes'),
        $this->t('No'),
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['dropdown'] = $form_state->getValue('commerce_wishlist_dropdown');
  }

  /**
   * Builds the wishlist block.
   *
   * @return array
   *   A render array.
   */
  public function build() {
    $cachable_metadata = new CacheableMetadata();
    $cachable_metadata->addCacheContexts(['user', 'session']);

    /** @var \Drupal\commerce_wishlist\Entity\WishlistInterface[] $wishlists */
    $wishlists = $this->wishlistProvider->getWishlists();
    $wishlists = array_filter($wishlists, function ($wishlist) {
      /** @var \Drupal\commerce_wishlist\Entity\WishlistInterface $wishlist */
      // Filter empty wishlists.
      return $wishlist->hasItems();
    });

    $count = 0;
    $wishlist_views = [];
    if (!empty($wishlists)) {
      $wishlist_views = $this->getWishlistViews($wishlists);
      foreach ($wishlists as $wishlist_id => $wishlist) {
        foreach ($wishlist->getItems() as $wishlist_item) {
          $count += (int) $wishlist_item->getQuantity();
        }
        $cachable_metadata->addCacheableDependency($wishlist);
      }
    }

    $links = [];
    $links[] = [
      '#type' => 'link',
      '#title' => $this->t('Wishlist'),
      '#url' => Url::fromRoute('commerce_wishlist.page'),
    ];

    return [
      '#attached' => [
        'library' => ['commerce_wishlist/wishlist_block'],
      ],
      '#theme' => 'commerce_wishlist_block',
      '#icon' => [
        '#theme' => 'image',
        '#uri' => drupal_get_path('module', 'commerce') . '/icons/ffffff/cart.png',
        '#alt' => $this->t('Wishlist'),
      ],
      '#count' => $count,
      '#count_text' => $this->formatPlural($count, '@count item', '@count items'),
      '#url' => Url::fromRoute('commerce_wishlist.page')->toString(),
      '#content' => $wishlist_views,
      '#links' => $links,
      '#cache' => [
        'contexts' => $cachable_metadata->getCacheContexts(),
        'tags' => $cachable_metadata->getCacheTags(),
        'max-age' => $cachable_metadata->getCacheMaxAge(),
      ],
    ];
  }

  /**
   * Gets the wishlist views for each wishlist.
   *
   * @param \Drupal\commerce_wishlist\Entity\WishlistInterface[] $wishlists
   *   The wishlists.
   *
   * @return array
   *   An array of view ids keyed by wishlist wishlist ID.
   */
  protected function getWishlistViews(array $wishlists) {
    $wishlist_views = [];
    if ($this->configuration['dropdown']) {
      $wishlist_type_ids = array_map(function ($wishlist) {
        /** @var \Drupal\commerce_wishlist\Entity\WishlistInterface $wishlist */
        return $wishlist->bundle();
      }, $wishlists);
      $wishlist_type_storage = $this->entityTypeManager->getStorage('commerce_wishlist_type');
      $wishlist_types = $wishlist_type_storage->loadMultiple(array_unique($wishlist_type_ids));

      $available_views = [];
      foreach ($wishlist_type_ids as $wishlist_id => $wishlist_type_id) {
        /** @var \Drupal\commerce_wishlist\Entity\WishlistTypeInterface $wishlist_type */
        $wishlist_type = $wishlist_types[$wishlist_type_id];
        $available_views[$wishlist_id] = $wishlist_type->getWishlistBlockView();
      }

      foreach ($wishlists as $wishlist_id => $wishlist) {
        $wishlist_views[] = [
          '#prefix' => '<div class="wishlist wishlist-block">',
          '#suffix' => '</div>',
          '#type' => 'view',
          '#name' => $available_views[$wishlist_id],
          '#arguments' => [$wishlist_id],
          '#embed' => TRUE,
        ];
      }
    }
    return $wishlist_views;
  }

}
