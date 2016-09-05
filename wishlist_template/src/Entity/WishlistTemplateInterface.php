<?php

namespace Drupal\wishlist_template\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Defines the interface for wishlist_templates.
 */
interface WishlistTemplateInterface extends ContentEntityInterface, EntityOwnerInterface {

  /**
   * Gets the wishlist_template name.
   *
   * @return string
   *   The wishlist_template name.
   */
  public function getName();

  /**
   * Sets the wishlist_template name.
   *
   * @param string $name
   *   The wishlist_template name.
   *
   * @return $this
   */
  public function setName($name);

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
