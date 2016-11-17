<?php

namespace Drupal\commerce_wishlist\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Defines the interface for wishlist item types.
 */
interface WishlistItemTypeInterface extends ConfigEntityInterface {

  /**
   * Gets the wishlist item type's purchasable entity type ID.
   *
   * E.g, if wishlist items of this type are used to hold product variations,
   * the purchasable entity type ID will be 'commerce_product_variation'.
   *
   * @return string
   *   The purchasable entity type ID.
   */
  public function getPurchasableEntityTypeId();

  /**
   * Sets the wishlist item type's purchasable entity type ID.
   *
   * @param string $purchasable_entity_type_id
   *   The purchasable entity type.
   *
   * @return $this
   */
  public function setPurchasableEntityTypeId($purchasable_entity_type_id);

  /**
   * Gets the wishlist item type's wishlist type ID.
   *
   * @return string
   *   The wishlist type.
   */
  public function getWishlistTypeId();

  /**
   * Sets the wishlist item type's wishlist type ID.
   *
   * @param string $wishlist_type_id
   *   The wishlist type ID.
   *
   * @return $this
   */
  public function setWishlistTypeId($wishlist_type_id);

}
