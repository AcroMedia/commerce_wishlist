<?php
/**
 * @file
 * Contains \Drupal\commerce_wishlist\Controller\WishlistController class.
 */

namespace Drupal\commerce_wishlist\Controller;

use Drupal\commerce_wishlist\WishlistProviderInterface;
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
    $wishlists = $this->wishlistProvider->getWishlists();
    $wishlists = array_filter($wishlists, function ($wishlist) {
      // Wishlists are orders and orders have all kinds of methods.
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
      }
    }
    else {
      $build['empty'] = [
        '#prefix' => '<div class="wishlist-empty-page">',
        '#markup' => $this->t('Your wishlist is empty.'),
        '#suffix' => '</div>',
      ];
    }

    return $build;
  }

  /**
   * Gets the wishlist views for each wishlist.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface[] $wishlists
   *   The wishlist orders.
   *
   * @return array
   *   An array of view ids keyed by cart order ID.
   */
  protected function getWishlistViews(array $wishlists) {
    $order_type_ids = array_map(function ($wishlist) {
      return $wishlist->bundle();
    }, $wishlists);
    $order_type_storage = $this->entityTypeManager()->getStorage('commerce_order_type');
    $order_types = $order_type_storage->loadMultiple(array_unique($order_type_ids));
    $wishlist_views = [];
    foreach ($order_type_ids as $wishlist_id => $order_type_id) {
      /** @var \Drupal\commerce_order\Entity\OrderTypeInterface $order_type */
      $order_type = $order_types[$order_type_id];
      $wishlist_views[$wishlist_id] = $order_type->getThirdPartySetting('commerce_wishlist', 'wishlist_form_view', 'commerce_wishlist_form');
    }

    return $wishlist_views;
  }

}
