<?php

namespace Drupal\commerce_wishlist;

use Drupal\commerce_order\Entity\OrderItemInterface;
use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce\PurchasableEntityInterface;

/**
 * Manages the wishlist order and its order items.
 */
interface WishlistManagerInterface {

  /**
   * Empties the given wishlist order.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface $wishlist
   *   The wishlist order.
   * @param bool $save_wishlist
   *   Whether the wishlist should be saved after the operation.
   */
  public function emptyWishlist(OrderInterface $wishlist, $save_wishlist = TRUE);

  /**
   * Adds the given purchasable entity to the given wishlist order.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface $wishlist
   *   The wishlist order.
   * @param \Drupal\commerce\PurchasableEntityInterface $entity
   *   The purchasable entity.
   * @param int $quantity
   *   The quantity.
   * @param bool $combine
   *   Whether the order item should be combined with an existing matching one.
   * @param bool $save_wishlist
   *   Whether the wishlist should be saved after the operation.
   *
   * @return \Drupal\commerce_order\Entity\OrderItemInterface
   *   The saved order item.
   */
  public function addEntity(OrderInterface $wishlist, PurchasableEntityInterface $entity, $quantity = 1, $combine = TRUE, $save_wishlist = TRUE);

  /**
   * Creates a order item for the given purchasable entity.
   *
   * @param \Drupal\commerce\PurchasableEntityInterface $entity
   *   The purchasable entity.
   * @param int $quantity
   *   The quantity.
   *
   * @return \Drupal\commerce_order\Entity\OrderItemInterface
   *   The created order item. Unsaved.
   */
  public function createOrderItem(PurchasableEntityInterface $entity, $quantity = 1);

  /**
   * Adds the given order item to the given wishlist order.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface $wishlist
   *   The wishlist order.
   * @param \Drupal\commerce_order\Entity\OrderItemInterface $order_item
   *   The order item.
   * @param bool $combine
   *   Whether the order item should be combined with an existing matching one.
   * @param bool $save_wishlist
   *   Whether the wishlist should be saved after the operation.
   *
   * @return \Drupal\commerce_order\Entity\OrderItemInterface
   *   The saved order item.
   */
  public function addOrderItem(OrderInterface $wishlist, OrderItemInterface $order_item, $combine = TRUE, $save_wishlist = TRUE);

  /**
   * Updates the given order item.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface $wishlist
   *   The wishlist order.
   * @param \Drupal\commerce_order\Entity\OrderItemInterface $order_item
   *   The order item.
   */
  public function updateOrderItem(OrderInterface $wishlist, OrderItemInterface $order_item);

  /**
   * Removes the given order item from the wishlist order.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface $wishlist
   *   The wishlist order.
   * @param \Drupal\commerce_order\Entity\OrderItemInterface $order_item
   *   The order item.
   * @param bool $save_wishlist
   *   Whether the wishlist should be saved after the operation.
   */
  public function removeOrderItem(OrderInterface $wishlist, OrderItemInterface $order_item, $save_wishlist = TRUE);

}
