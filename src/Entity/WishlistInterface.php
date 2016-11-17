<?php

namespace Drupal\commerce_wishlist\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\profile\Entity\ProfileInterface;
use Drupal\user\UserInterface;

/**
 * Defines the interface for wishlists.
 */
interface WishlistInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * Gets the wishlist name.
   *
   * @return string
   *   The wishlist name.
   */
  public function getName();

  /**
   * Sets the wishlist name.
   *
   * @param string $name
   *   The wishlist name.
   *
   * @return $this
   */
  public function setName($name);

  /**
   * Gets the customer user.
   *
   * @return \Drupal\user\UserInterface|null
   *   The customer user entity, or NULL in case the wishlist is anonymous.
   */
  public function getCustomer();

  /**
   * Sets the customer user.
   *
   * @param \Drupal\user\UserInterface $account
   *   The customer user entity.
   *
   * @return $this
   */
  public function setCustomer(UserInterface $account);

  /**
   * Gets the customer user ID.
   *
   * @return int|null
   *   The customer user ID, or NULL in case the wishlist is anonymous.
   */
  public function getCustomerId();

  /**
   * Sets the customer user ID.
   *
   * @param int $uid
   *   The customer user ID.
   *
   * @return $this
   */
  public function setCustomerId($uid);

  /**
   * Gets the shipping profile.
   *
   * @return \Drupal\profile\Entity\ProfileInterface|null
   *   The shipping profile, or null.
   */
  public function getShippingProfile();

  /**
   * Sets the shipping profile.
   *
   * @param \Drupal\profile\Entity\ProfileInterface $profile
   *   The shipping profile.
   *
   * @return $this
   */
  public function setShippingProfile(ProfileInterface $profile);

  /**
   * Gets the wishlist items.
   *
   * @return \Drupal\commerce_wishlist\Entity\WishlistItemInterface[]
   *   The wishlist items.
   */
  public function getItems();

  /**
   * Sets the wishlist items.
   *
   * @param \Drupal\commerce_wishlist\Entity\WishlistItemInterface[] $wishlist_items
   *   The wishlist items.
   *
   * @return $this
   */
  public function setItems(array $wishlist_items);

  /**
   * Gets whether the wishlist has wishlist items.
   *
   * @return bool
   *   TRUE if the wishlist has wishlist items, FALSE otherwise.
   */
  public function hasItems();

  /**
   * Adds an wishlist item.
   *
   * @param \Drupal\commerce_wishlist\Entity\WishlistItemInterface $wishlist_item
   *   The wishlist item.
   *
   * @return $this
   */
  public function addItem(WishlistItemInterface $wishlist_item);

  /**
   * Removes an wishlist item.
   *
   * @param \Drupal\commerce_wishlist\Entity\WishlistItemInterface $wishlist_item
   *   The wishlist item.
   *
   * @return $this
   */
  public function removeItem(WishlistItemInterface $wishlist_item);

  /**
   * Checks whether the wishlist has a given wishlist item.
   *
   * @param \Drupal\commerce_wishlist\Entity\WishlistItemInterface $wishlist_item
   *   The wishlist item.
   *
   * @return bool
   *   TRUE if the wishlist item was found, FALSE otherwise.
   */
  public function hasItem(WishlistItemInterface $wishlist_item);

  /**
   * Returns the wishlist default status indicator.
   *
   * @return bool
   *   TRUE if the wishlist is the default one, FALSE otherwise.
   */
  public function isDefault();

  /**
   * Sets the default status of a wishlist.
   *
   * @param bool $is_default
   *   TRUE to set this wishlist to default, FALSE to set it to not default.
   *
   * @return $this
   */
  public function setDefault($is_default);

  /**
   * Gets the wishlist creation timestamp.
   *
   * @return int
   *   Creation timestamp of the wishlist.
   */
  public function getCreatedTime();

  /**
   * Sets the wishlist creation timestamp.
   *
   * @param int $timestamp
   *   The wishlist creation timestamp.
   *
   * @return $this
   */
  public function setCreatedTime($timestamp);

}
