<?php

namespace Drupal\commerce_wishlist\Event;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_order\Entity\LineItemInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Defines the wishlist line item update event.
 *
 * @see \Drupal\commerce_wishlist\Event\WishlistEvents
 */
class WishlistLineItemUpdateEvent extends Event {

  /**
   * The wishlist order.
   *
   * @var \Drupal\commerce_order\Entity\OrderInterface
   */
  protected $wishlist;

  /**
   * The updated line item.
   *
   * @var \Drupal\commerce_order\Entity\LineItemInterface
   */
  protected $lineItem;

  /**
   * The original line item.
   *
   * @var \Drupal\commerce_order\Entity\LineItemInterface
   */
  protected $originalLineItem;

  /**
   * Constructs a new CartLineItemUpdateEvent.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface $wishlist
   *   The wishlist order.
   * @param \Drupal\commerce_order\Entity\LineItemInterface $line_item
   *   The updated line item.
   * @param \Drupal\commerce_order\Entity\LineItemInterface $original_line_item
   *   The original line item.
   */
  public function __construct(OrderInterface $wishlist, LineItemInterface $line_item, LineItemInterface $original_line_item) {
    $this->wishlist = $wishlist;
    $this->lineItem = $line_item;
    $this->originalLineItem = $original_line_item;
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
   * Gets the updated line item.
   *
   * @return \Drupal\commerce_order\Entity\LineItemInterface
   *   The updated line item.
   */
  public function getLineItem() {
    return $this->lineItem;
  }

  /**
   * Gets the original line item.
   *
   * @return \Drupal\commerce_order\Entity\LineItemInterface
   *   The original line item.
   */
  public function getOriginalLineItem() {
    return $this->originalLineItem;
  }

}
