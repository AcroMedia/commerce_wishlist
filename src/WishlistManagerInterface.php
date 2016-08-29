<?php

namespace Drupal\commerce_wishlist;

use Drupal\commerce_order\Entity\LineItemInterface;
use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce\PurchasableEntityInterface;

/**
 * Manages the wishlist order and its line items.
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
   *   Whether the line item should be combined with an existing matching one.
   * @param bool $save_wishlist
   *   Whether the wishlist should be saved after the operation.
   *
   * @return \Drupal\commerce_order\Entity\LineItemInterface
   *   The saved line item.
   */
  public function addEntity(OrderInterface $wishlist, PurchasableEntityInterface $entity, $quantity = 1, $combine = TRUE, $save_wishlist = TRUE);

  /**
   * Creates a line item for the given purchasable entity.
   *
   * @param \Drupal\commerce\PurchasableEntityInterface $entity
   *   The purchasable entity.
   * @param int $quantity
   *   The quantity.
   *
   * @return \Drupal\commerce_order\Entity\LineItemInterface
   *   The created line item. Unsaved.
   */
  public function createLineItem(PurchasableEntityInterface $entity, $quantity = 1);

  /**
   * Adds the given line item to the given wishlist order.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface $wishlist
   *   The wishlist order.
   * @param \Drupal\commerce_order\Entity\LineItemInterface $line_item
   *   The line item.
   * @param bool $combine
   *   Whether the line item should be combined with an existing matching one.
   * @param bool $save_wishlist
   *   Whether the wishlist should be saved after the operation.
   *
   * @return \Drupal\commerce_order\Entity\LineItemInterface
   *   The saved line item.
   */
  public function addLineItem(OrderInterface $wishlist, LineItemInterface $line_item, $combine = TRUE, $save_wishlist = TRUE);

  /**
   * Updates the given line item.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface $wishlist
   *   The wishlist order.
   * @param \Drupal\commerce_order\Entity\LineItemInterface $line_item
   *   The line item.
   */
  public function updateLineItem(OrderInterface $wishlist, LineItemInterface $line_item);

  /**
   * Removes the given line item from the wishlist order.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface $wishlist
   *   The wishlist order.
   * @param \Drupal\commerce_order\Entity\LineItemInterface $line_item
   *   The line item.
   * @param bool $save_wishlist
   *   Whether the wishlist should be saved after the operation.
   */
  public function removeLineItem(OrderInterface $wishlist, LineItemInterface $line_item, $save_wishlist = TRUE);

}
