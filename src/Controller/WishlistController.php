<?php

namespace Drupal\commerce_wishlist\Controller;

use Drupal\commerce_wishlist\WishlistProviderInterface;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the wishlist page.
 */
class WishlistController extends ControllerBase {

  /**
   * The wishlist provider.
   *
   * @var \Drupal\commerce_wishlist\WishlistProviderInterface
   */
  protected $wishlistProvider;

  /**
   * Constructs a new WishlistController object.
   *
   * @param \Drupal\commerce_wishlist\WishlistProviderInterface $wishlist_provider
   *   The wishlist provider.
   */
  public function __construct(WishlistProviderInterface $wishlist_provider) {
    $this->wishlistProvider = $wishlist_provider;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('commerce_wishlist.wishlist_provider')
    );
  }

  /**
   * Outputs a wishlist view for each non-empty wishlist belonging to the
   * current user.
   *
   * @return array
   *   A render array.
   */
  public function wishlistPage() {
    $build = [];
    $cacheable_metadata = new CacheableMetadata();
    $cacheable_metadata->addCacheContexts(['user', 'session']);

    $wishlists = $this->wishlistProvider->getWishlists();
    $wishlists = array_filter($wishlists, function ($wishlist) {
      /** @var \Drupal\commerce_wishlist\Entity\WishlistInterface $wishlist */
      return $wishlist->hasItems();
    });
    if (!empty($wishlists)) {
      $wishlist_views = $this->getWishlistViews($wishlists);
      foreach ($wishlists as $wishlist_id => $wishlist) {
        $build[$wishlist_id] = [
          '#prefix' => '<div class="wishlist wishlist-form">',
          '#suffix' => '</div>',
          '#type' => 'view',
          '#name' => $wishlist_views[$wishlist_id],
          '#arguments' => [$wishlist_id],
          '#embed' => TRUE,
        ];
        $cacheable_metadata->addCacheableDependency($wishlist);
      }
    }
    else {
      $build['empty'] = [
        '#prefix' => '<div class="wishlist-empty-page">',
        '#markup' => $this->t('Your wishlist is empty.'),
        '#suffix' => '</div>',
      ];
    }
    $build['#cache'] = [
      'contexts' => $cacheable_metadata->getCacheContexts(),
      'tags' => $cacheable_metadata->getCacheTags(),
      'max-age' => $cacheable_metadata->getCacheMaxAge(),
    ];

    return $build;
  }

  /**
   * Gets the wishlist views for each wishlist.
   *
   * @param \Drupal\commerce_wishlist\Entity\WishlistInterface[] $wishlists
   *   The wishlists.
   *
   * @return array
   *   An array of view ids keyed by wishlist ID.
   */
  protected function getWishlistViews(array $wishlists) {
    $wishlist_type_ids = array_map(function ($wishlist) {
      /** @var \Drupal\commerce_wishlist\Entity\WishlistInterface $wishlist */
      return $wishlist->bundle();
    }, $wishlists);
    $wishlist_type_storage = $this->entityTypeManager()->getStorage('commerce_wishlist_type');
    $wishlist_types = $wishlist_type_storage->loadMultiple(array_unique($wishlist_type_ids));
    $wishlist_views = [];
    foreach ($wishlist_type_ids as $wishlist_id => $wishlist_type_id) {
      /** @var \Drupal\commerce_wishlist\Entity\WishlistTypeInterface $wishlist_type */
      $wishlist_type = $wishlist_types[$wishlist_type_id];
      $wishlist_views[$wishlist_id] = $wishlist_type->getWishlistFormView();
    }

    return $wishlist_views;
  }

}
