<?php
/**
 * @file
 * Contains \Drupal\commerce_wishlist\Controller\WishlistController class.
 */

namespace Drupal\commerce_wishlist\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides the wishlist page.
 */
class WishlistController extends ControllerBase {

  /**
   * Outputs a wishlist view for each non-empty wishlist belonging to the current user.
   *
   * @return array
   *   A render array.
   */
  public function wishlistPage() {

    $build = [];
    // @todo Create a wishlistProvider.
    /*$wishlists = $this->wishlistProvider->getWishlists();
    $wishlists = array_filter($wishlists, function ($wishlist) {
      return $wishlist->hasLineItems();
    });
    if (!empty($wishlists)) {
      $wishlist_views = $this->getCartViews($wishlists);
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
    else { */
      $build['empty'] = [
        '#prefix' => '<div class="wishlist-empty-page">',
        '#markup' => $this->t('Your wishlist is empty.'),
        '#suffix' => '</div.',
      ];
    // }

    return $build;
  }
}
