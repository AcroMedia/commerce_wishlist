<?php

namespace Drupal\commerce_wishlist;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\user\UserInterface;

/**
 * Handles assigning anonymous wishlist orders to user accounts.
 *
 * Invoked on login.
 */
interface WishlistAssignmentInterface {

  /**
   * Assigns all anonymous wishlist orders to the given user account.
   *
   * The anonymous wishlist orders are retrieved from the wishlist session.
   *
   * @param \Drupal\user\UserInterface $account
   *   The account.
   */
  public function assignAll(UserInterface $account);

  /**
   * Assigns the anonymous wishlist order to the given user account.
   *
   * @param \Drupal\commerce_order\Entity\OrderInterface $wishlist
   *   The wishlist order.
   * @param \Drupal\user\UserInterface $account
   *   The user account.
   */
  public function assign(OrderInterface $wishlist, UserInterface $account);

}
