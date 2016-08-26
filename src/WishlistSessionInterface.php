<?php

namespace Drupal\commerce_wishlist;

/**
 * Stores wishlist ids in the anonymous user's session.
 *
 * Allows the system to keep track of which wishlist orders belong to the
 * anonymous user. The session is the only available storage in this case, since
 * all anonymous users share the same user id (0).
 *
 * @see \Drupal\commerce_wishlist\WishlistProviderInterface
 */
interface WishlistSessionInterface {

  /**
   * Gets all wishlist order ids from the session.
   *
   * @return int[]
   *   A list of wishlist orders ids.
   */
  public function getWishlistIds();

  /**
   * Adds the given wishlist order id to the session.
   *
   * @param int $wishlist_id
   *   The wishlist order ID.
   */
  public function addWishlistId($wishlist_id);

  /**
   * Checks whether the given wishlist order id exists in the session.
   *
   * @param int $wishlist_id
   *   The wishlist order ID.
   *
   * @return bool
   *   TRUE if the given wishlist order id exists in the session, FALSE
   *   otherwise.
   */
  public function hasWishlistId($wishlist_id);

  /**
   * Deletes the given wishlist order id from the session.
   *
   * @param int $wishlist_id
   *   The wishlist order ID.
   */
  public function deleteWishlistId($wishlist_id);

}
