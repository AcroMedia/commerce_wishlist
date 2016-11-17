<?php

namespace Drupal\commerce_wishlist\Resolver;

use Drupal\commerce_wishlist\Entity\WishlistItemInterface;

/**
 * Defines the interface for wishlist type resolvers.
 */
interface WishlistTypeResolverInterface {

  /**
   * Resolves the wishlist type.
   *
   * @param \Drupal\commerce_wishlist\Entity\WishlistItemInterface $wishlist_item
   *   The wishlist item being added to an wishlist.
   *
   * @return \Drupal\commerce_wishlist\Entity\WishlistTypeInterface|null
   *   The wishlist type, if resolved. Otherwise NULL, indicating that the next
   *   resolver in the chain should be called.
   */
  public function resolve(WishlistItemInterface $wishlist_item);

}
