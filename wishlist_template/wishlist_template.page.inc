<?php

/**
 * @file
 * Contains wishlist_template.page.inc.
 *
 * Page callback for Wishlist template entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Wishlist template templates.
 *
 * Default template: wishlist_template.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_wishlist_template(array &$variables) {
  // Fetch WishlistTemplate Entity Object.
  $wishlist_template = $variables['elements']['#wishlist_template'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
