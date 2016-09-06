<?php

namespace Drupal\wishlist_template\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Wishlist template entities.
 *
 * @ingroup wishlist_template
 */
interface WishlistTemplateInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Wishlist template type.
   *
   * @return string
   *   The Wishlist template type.
   */
  public function getType();

  /**
   * Gets the Wishlist template name.
   *
   * @return string
   *   Name of the Wishlist template.
   */
  public function getName();

  /**
   * Sets the Wishlist template name.
   *
   * @param string $name
   *   The Wishlist template name.
   *
   * @return \Drupal\wishlist_template\Entity\WishlistTemplateInterface
   *   The called Wishlist template entity.
   */
  public function setName($name);

  /**
   * Gets the Wishlist template creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Wishlist template.
   */
  public function getCreatedTime();

  /**
   * Sets the Wishlist template creation timestamp.
   *
   * @param int $timestamp
   *   The Wishlist template creation timestamp.
   *
   * @return \Drupal\wishlist_template\Entity\WishlistTemplateInterface
   *   The called Wishlist template entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Wishlist template published status indicator.
   *
   * Unpublished Wishlist template are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Wishlist template is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Wishlist template.
   *
   * @param bool $published
   *   TRUE to set this Wishlist template to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\wishlist_template\Entity\WishlistTemplateInterface
   *   The called Wishlist template entity.
   */
  public function setPublished($published);


  /**
   * Gets the the view mode used for rendering the terms.
   *
   * @return string
   *   The wishlist_template taxonomy_term_view_mode.
   */
  public function getTaxonomyTermViewMode();

  /**
   * Sets the wishlist_template taxonomy_term_view_mode.
   *
   * @param string $taxonomy_term_view_mode
   *   The wishlist_template taxonomy_term_view_mode.
   *
   * @return $this
   */
  public function setTaxonomyTermViewMode($taxonomy_term_view_mode);

  /**
   * Gets the categories for the template.
   *
   * @return array
   *   The categories for the template.
   */
  public function getTerms();

  /**
   * Sets the categories for the template.
   *
   * @param array $terms
   *   The categories for the template.
   *
   * @return $this
   */
  public function setTerms($terms);
}
