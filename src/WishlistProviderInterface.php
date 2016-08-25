<?php

namespace Drupal\commerce_wishlist;

use Drupal\commerce_store\Entity\StoreInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Creates and loads wishlists for anonymous and authenticated users.
 *
 * @see \Drupal\commerce_cart\CartProviderInterface
 * @see \Drupal\commerce_cart\CartSessionInterface
 * @see \Drupal\commerce_wishlist\WishlistSessionInterface
 */
interface WishlistProviderInterface {

  /**
   * Creates a wishlist order for the given store and user.
   *
   * @param string $order_type
   *   The order type ID.
   * @param \Drupal\commerce_store\Entity\StoreInterface $store
   *   The store.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user. If empty, the current user is assumed.
   *
   * @return \Drupal\commerce_order\Entity\OrderInterface
   *   The created wishlist order.
   *
   * @throws \Drupal\commerce_wishlist\Exception\DuplicateWishlistException
   *   When a wishlist with the given criteria already exists.
   */
  public function createWishlist($order_type, StoreInterface $store, AccountInterface $account = NULL);

  /**
   * Gets the wishlist order for the given store and user.
   *
   * @param string $order_type
   *   The order type ID.
   * @param \Drupal\commerce_store\Entity\StoreInterface $store
   *   The store.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user. If empty, the current user is assumed.
   *
   * @return \Drupal\commerce_order\Entity\OrderInterface|null
   *   The wishlist order, or NULL if none found.
   */
  public function getWishlist($order_type, StoreInterface $store, AccountInterface $account = NULL);

  /**
   * Gets the wishlist order ID for the given store and user.
   *
   * @param string $order_type
   *   The order type ID.
   * @param \Drupal\commerce_store\Entity\StoreInterface $store
   *   The store.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user. If empty, the current user is assumed.
   *
   * @return int|null
   *   The wishlist order ID, or NULL if none found.
   */
  public function getWishlistId($order_type, StoreInterface $store, AccountInterface $account = NULL);

  /**
   * Gets all wishlist orders for the given user.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user. If empty, the current user is assumed.
   *
   * @return \Drupal\commerce_order\Entity\OrderInterface[]
   *   A list of wishlist orders.
   */
  public function getWishlists(AccountInterface $account = NULL);

  /**
   * Gets all wishlist order ids for the given user.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user. If empty, the current user is assumed.
   *
   * @return int[]
   *   A list of wishlist orders ids.
   */
  public function getWishlistIds(AccountInterface $account = NULL);

}
