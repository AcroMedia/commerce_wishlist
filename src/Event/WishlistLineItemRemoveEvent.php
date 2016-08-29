<?php

namespace Drupal\commerce_wishlist\Event;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_order\Entity\LineItemInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Defines the wishlist line item remove event.
 *
 * @see \Drupal\commerce_wishlist\Event\WishlistEvents
 */
class WishlistLineItemRemoveEvent extends Event {

  /**
   * The wishlist order.
   *
   * @var \Drupal\commerce_order\Entity\OrderInterface
   */
  protected $wishlist;

  /**
   * The removed line item.
   *
   * @var \Drupal\commerce_order\Entity\LineItemInterface
   */
  protected $lineItem;

  /**
   * Constructs a new WishlistLineItemRemoveEvent.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface $wishlist
   *   The wishlist order.
   * @param \Drupal\commerce_order\Entity\LineItemInterface $line_item
   *   The removed line item.
   */
  public function __construct(OrderInterface $wishlist, LineItemInterface $line_item) {
    $this->wishlist = $wishlist;
    $this->lineItem = $line_item;
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
   * Gets the removed line item.
   *
   * @return \Drupal\commerce_order\Entity\LineItemInterface
   *   The line item entity.
   */
  public function getLineItem() {
    return $this->lineItem;
  }

}
