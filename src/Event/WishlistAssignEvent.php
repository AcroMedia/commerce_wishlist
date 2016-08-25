<?php

namespace Drupal\commerce_wishlist\Event;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\user\UserInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Defines the wishlist assign event.
 *
 * @see \Drupal\commerce_wishlist\Event\WishlistEvents
 */
class WishlistAssignEvent extends Event {

  /**
   * The wishlist order.
   *
   * @var \Drupal\commerce_order\Entity\OrderInterface
   */
  protected $wishlist;

  /**
   * The user account.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $account;

  /**
   * Constructs a new WishlistAssignEvent.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface $wishlist
   *   The wishlist order.
   * @param \Drupal\user\UserInterface $account
   *   The user account.
   */
  public function __construct(OrderInterface $wishlist, UserInterface $account) {
    $this->wishlist = $wishlist;
    $this->account = $account;
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
   * Gets the user account.
   *
   * @return \Drupal\user\UserInterface
   *   The user account.
   */
  public function getAccount() {
    return $this->account;
  }

}
