<?php

namespace Drupal\commerce_wishlist\Entity;

use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Defines the interface for wishlist items.
 */
interface WishlistItemInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * Gets the parent wishlist.
   *
   * @return \Drupal\commerce_wishlist\Entity\WishlistInterface|null
   *   The wishlist, or NULL.
   */
  public function getWishlist();

  /**
   * Gets the parent wishlist ID.
   *
   * @return int|null
   *   The wishlist ID, or NULL.
   */
  public function getWishlistId();

  /**
   * Gets the purchasable entity.
   *
   * @return \Drupal\commerce\PurchasableEntityInterface|null
   *   The purchasable entity, or NULL.
   */
  public function getPurchasableEntity();

  /**
   * Gets the purchasable entity ID.
   *
   * @return int
   *   The purchasable entity ID.
   */
  public function getPurchasableEntityId();

  /**
   * Gets the wishlist item title.
   *
   * @return string
   *   The wishlist item title
   */
  public function getTitle();

  /**
   * Sets the wishlist item title.
   *
   * @param string $title
   *   The wishlist item title.
   *
   * @return $this
   */
  public function setTitle($title);

  /**
   * Gets the wishlist item quantity.
   *
   * @return string
   *   The wishlist item quantity
   */
  public function getQuantity();

  /**
   * Sets the wishlist item quantity.
   *
   * @param string $quantity
   *   The wishlist item quantity.
   *
   * @return $this
   */
  public function setQuantity($quantity);

  /**
   * Gets the wishlist item creation timestamp.
   *
   * @return int
   *   The wishlist item creation timestamp.
   */
  public function getCreatedTime();

  /**
   * Sets the wishlist item creation timestamp.
   *
   * @param int $timestamp
   *   The wishlist item creation timestamp.
   *
   * @return $this
   */
  public function setCreatedTime($timestamp);

}
