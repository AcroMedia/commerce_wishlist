<?php

namespace Drupal\commerce_wishlist\Event;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_order\Entity\OrderItemInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Defines the wishlist order item remove event.
 *
 * @see \Drupal\commerce_wishlist\Event\WishlistEvents
 */
class WishlistOrderItemRemoveEvent extends Event {

  /**
   * The wishlist order.
   *
   * @var \Drupal\commerce_order\Entity\OrderInterface
   */
  protected $wishlist;

  /**
   * The removed order item.
   *
   * @var \Drupal\commerce_order\Entity\OrderItemInterface
   */
  protected $orderItem;

  /**
   * Constructs a new WishlistOrderItemRemoveEvent.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface $wishlist
   *   The wishlist order.
   * @param \Drupal\commerce_order\Entity\OrderItemInterface $order_item
   *   The removed order item.
   */
  public function __construct(OrderInterface $wishlist, OrderItemInterface $order_item) {
    $this->wishlist = $wishlist;
    $this->orderItem = $order_item;
  }

  /**
   * Gets the wishlist order.
   *
   * @return \Drupal\commerce_order\Entity\OrderInterface
   *   The wishlist order.
   */
  public function getWishlist() {
    return $this->wishlist;
  }

  /**
   * Gets the removed order item.
   *
   * @return \Drupal\commerce_order\Entity\OrderItemInterface
   *   The order item entity.
   */
  public function getOrderItem() {
    return $this->orderItem;
  }

}
