<?php

namespace Drupal\commerce_wishlist\Event;

use Drupal\commerce\PurchasableEntityInterface;
use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_order\Entity\LineItemInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Defines the wishlist entity add event.
 *
 * @see \Drupal\commerce_wishlist\Event\WishlistEvents
 */
class WishlistEntityAddEvent extends Event {

  /**
   * The wishlist order.
   *
   * @var \Drupal\commerce_order\Entity\OrderInterface
   */
  protected $wishlist;

  /**
   * The added entity.
   *
   * @var \Drupal\commerce\PurchasableEntityInterface
   */
  protected $entity;

  /**
   * The quantity.
   *
   * @var float
   */
  protected $quantity;

  /**
   * The destination line item.
   *
   * @var \Drupal\commerce_order\Entity\LineItemInterface
   */
  protected $lineItem;

  /**
   * Constructs a new WishlistLineItemEvent.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface $wishlist
   *   The wishlist order.
   * @param \Drupal\commerce\PurchasableEntityInterface $entity
   *   The added entity.
   * @param float $quantity
   *   The quantity.
   * @param \Drupal\commerce_order\Entity\LineItemInterface $line_item
   *   The destination line item.
   */
  public function __construct(OrderInterface $wishlist, PurchasableEntityInterface $entity, $quantity, LineItemInterface $line_item) {
    $this->wishlist = $wishlist;
    $this->entity = $entity;
    $this->quantity = $quantity;
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
   * Gets the added entity.
   *
   * @return \Drupal\commerce\PurchasableEntityInterface
   *   The added entity.
   */
  public function getEntity() {
    return $this->entity;
  }

  /**
   * Gets the quantity.
   *
   * @return float
   *   The quantity.
   */
  public function getQuantity() {
    return $this->quantity;
  }

  /**
   * Gets the destination line item.
   *
   * @return \Drupal\commerce_order\Entity\LineItemInterface
   *   The destination line item.
   */
  public function getLineItem() {
    return $this->lineItem;
  }

}
