<?php

/**
 * @file
 * Wish list views.
 */

/**
 * Implements hook_views_data_alter().
 */
function commerce_wishlist_views_data_alter(&$data) {
  // Expose links to operate on the product.
  $data['commerce_line_item']['wishlist_remove'] = array(
    'field' => array(
      'title' => t('Remove'),
      'help' => t('Provide a simple link to remove a product from the wishlist.'),
      'real field' => 'line_item_id',
      'handler' => 'commerce_wishlist_handler_field_remove',
    ),
  );
  $data['commerce_line_item']['wishlist_add_to_cart'] = array(
    'field' => array(
      'title' => t('Add to Cart'),
      'help' => t('Provide a button to a product to the cart from the wishlist.'),
      'real field' => 'line_item_id',
      'handler' => 'commerce_wishlist_handler_field_add_to_cart_form',
    ),
  );
}

/**
 * Implements hook_views_plugins().
 */
function commerce_wishlist_views_plugins() {
  $plugins = array();
  $plugins['access'] = array(
    'commerce_wishlist_item' => array(
      'title'   => t('Wishlist access check'),
      'help'    => t('Use this only for checking whether or not a user can access a wishlist.'),
      'handler' => 'commerce_wishlist_views_access_plugin',
      'path'    => drupal_get_path('module', 'commerce_wishlist') . '/includes/views',
    ),
  );

  return $plugins;
}

/**
 * Custom class for declaring a custom views access plugin.
 */
class commerce_wishlist_views_access_plugin extends views_plugin_access {
  public function summary_title() {
    return t('This is summary title');
  }
  public function access($account) {
    return commerce_wishlist_user_wishlist_access($account, $this);
  }
  public function get_access_callback() {
    return array('commerce_wishlist_user_wishlist_access', array(1, $this));
  }
}
