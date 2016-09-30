<?php

namespace Drupal\commerce_wishlist\Event;

use Drupal\commerce_order\Entity\OrderInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Defines the wishlist empty event.
 *
 * @see \Drupal\commerce_wishlist\Event\WishlistEvents
 */
class WishlistEmptyEvent extends Event {

  /**
   * The wishlist order.
   *
   * @var \Drupal\commerce_order\Entity\OrderInterface
   */
  protected $wishlist;

  /**
   * The removed order items.
   *
   * @var \Drupal\commerce_order\Entity\OrderItemInterface[]
   */
  protected $orderItems;

  /**
   * Constructs a new WishlistEmptyEvent.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface $wishlist
   *   The wishlist order.
   * @param \Drupal\commerce_order\Entity\OrderItemInterface[] $order_items
   *   The removed order items.
   */
  public function __construct(OrderInterface $wishlist, array $order_items) {
    $this->wishlist = $wishlist;
    $this->orderItems = $order_items;
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
   * Gets the removed order items.
   *
   * @return \Drupal\commerce_order\Entity\OrderItemInterface[]
   *   The removed order items.
   */
  public function getItems() {
    return $this->orderItems;
  }

}
