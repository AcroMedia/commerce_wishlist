<?php

namespace Drupal\commerce_wishlist\Event;

use Drupal\commerce\PurchasableEntityInterface;
use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_order\Entity\OrderItemInterface;
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
   * The destination order item.
   *
   * @var \Drupal\commerce_order\Entity\OrderItemInterface
   */
  protected $orderItem;

  /**
   * Constructs a new WishlistOrderItemEvent.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface $wishlist
   *   The wishlist order.
   * @param \Drupal\commerce\PurchasableEntityInterface $entity
   *   The added entity.
   * @param float $quantity
   *   The quantity.
   * @param \Drupal\commerce_order\Entity\OrderItemInterface $order_item
   *   The destination order item.
   */
  public function __construct(OrderInterface $wishlist, PurchasableEntityInterface $entity, $quantity, OrderItemInterface $order_item) {
    $this->wishlist = $wishlist;
    $this->entity = $entity;
    $this->quantity = $quantity;
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
   * Gets the destination order item.
   *
   * @return \Drupal\commerce_order\Entity\OrderItemInterface
   *   The destination order item.
   */
  public function getOrderItem() {
    return $this->orderItem;
  }

}
