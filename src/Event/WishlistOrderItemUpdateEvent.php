<?php

namespace Drupal\commerce_wishlist\Event;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_order\Entity\OrderItemInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Defines the wishlist order item update event.
 *
 * @see \Drupal\commerce_wishlist\Event\WishlistEvents
 */
class WishlistOrderItemUpdateEvent extends Event {

  /**
   * The wishlist order.
   *
   * @var \Drupal\commerce_order\Entity\OrderInterface
   */
  protected $wishlist;

  /**
   * The updated order item.
   *
   * @var \Drupal\commerce_order\Entity\OrderItemInterface
   */
  protected $orderItem;

  /**
   * The original order item.
   *
   * @var \Drupal\commerce_order\Entity\OrderItemInterface
   */
  protected $originalOrderItem;

  /**
   * Constructs a new CartOrderItemUpdateEvent.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface $wishlist
   *   The wishlist order.
   * @param \Drupal\commerce_order\Entity\OrderItemInterface $order_item
   *   The updated order item.
   * @param \Drupal\commerce_order\Entity\OrderItemInterface $original_order_item
   *   The original order item.
   */
  public function __construct(OrderInterface $wishlist, OrderItemInterface $order_item, OrderItemInterface $original_order_item) {
    $this->wishlist = $wishlist;
    $this->orderItem = $order_item;
    $this->originalOrderItem = $original_order_item;
  }

  /**
   * Gets the cart wishlist.
   *
   * @return \Drupal\commerce_order\Entity\OrderInterface
   *   The wishlist order.
   */
  public function getWishlist() {
    return $this->wishlist;
  }

  /**
   * Gets the updated order item.
   *
   * @return \Drupal\commerce_order\Entity\OrderItemInterface
   *   The updated order item.
   */
  public function getOrderItem() {
    return $this->orderItem;
  }

  /**
   * Gets the original order item.
   *
   * @return \Drupal\commerce_order\Entity\OrderItemInterface
   *   The original order item.
   */
  public function getOriginalOrderItem() {
    return $this->originalOrderItem;
  }

}
