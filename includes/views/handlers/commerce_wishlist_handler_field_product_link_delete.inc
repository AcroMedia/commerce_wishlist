<?php

/**
 * Field handler to present a link to delete a product.
 */
class commerce_wishlist_handler_field_product_link_delete extends views_handler_field {
  function construct() {
    $this->additional_fields['uid'] = 'uid';
  }

  function render($values) {
    // Ensure the user has access to delete this product.
    $wishlist_id = $this->get_value($values, 'wishlist_id');
    $uid = $this->get_value($values, 'uid');


    // TODO: access
    /*if (!commerce_product_access('delete', $product)) {
      return;
    }*/

    $text = !empty($this->options['text']) ? $this->options['text'] : t('delete');

    return l($text, 'user/'. $uid .'/products/delete/'. $wishlist_id, array('query' => drupal_get_destination()));
  }
}
