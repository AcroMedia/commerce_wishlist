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
   * The removed line items.
   *
   * @var \Drupal\commerce_order\Entity\LineItemInterface[]
   */
  protected $lineItems;

  /**
   * Constructs a new WishlistEmptyEvent.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface $wishlist
   *   The wishlist order.
   * @param \Drupal\commerce_order\Entity\LineItemInterface[] $line_items
   *   The removed line items.
   */
  public function __construct(OrderInterface $wishlist, array $line_items) {
    $this->wishlist = $wishlist;
    $this->lineItems = $line_items;
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
   * Gets the removed line items.
   *
   * @return \Drupal\commerce_order\Entity\LineItemInterface[]
   *   The removed line items.
   */
  public function getLineItems() {
    return $this->lineItems;
  }

}
