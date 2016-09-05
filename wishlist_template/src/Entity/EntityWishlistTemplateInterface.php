<?php

namespace Drupal\wishlist_template\Entity;

/**
 * Defines a common interface for entities that belong to a wishlist_template.
 */
interface EntityWishlistTemplateInterface {

  /**
   * Gets the wishlist_template.
   *
   * @return \Drupal\wishlist_template\Entity\WishlistTemplateInterface|null
   *   The wishlist_template entity, or null.
   */
  public function getWishlistTemplate();

  /**
   * Sets the wishlist_template.
   *
   * @param \Drupal\wishlist_template\Entity\WishlistTemplateInterface $wishlist_template
   *   The wishlist_template entity.
   *
   * @return $this
   */
  public function setWishlistTemplate(WishlistTemplateInterface $wishlist_template);

  /**
   * Gets the wishlist_template ID.
   *
   * @return int
   *   The wishlist_template ID.
   */
  public function getWishlistTemplateId();

  /**
   * Sets the wishlist_template ID.
   *
   * @param int $wishlist_template_id
   *   The wishlist_template ID.
   *
   * @return $this
   */
  public function setWishlistTemplateId($wishlist_template_id);

}
