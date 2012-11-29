<?php

/**
 * @file
 * Provides the wishlist for use in Drupal Commerce.
 */

function commerce_wishlist_admin_form($form, &$form_state) {

  $form['commerce_wishlist_weight'] = array(
    '#type' => 'textfield',
    '#title' => t('Button weight'),
    '#description' => t('Set the position of the button "Add to wishlist".'),
    '#default_value' => variable_get('commerce_wishlist_weight', 0),
    '#required' => TRUE,
  );

  $form['commerce_wishlist_remove_product'] = array(
    '#type' => 'checkbox',
    '#title' => t('Remove product when added to cart'),
    '#description' => t('Remove the product from the wishlist when the product will be added to cart.'),
    '#default_value' => variable_get('commerce_wishlist_remove_product', 1),
  );

  return system_settings_form($form);
}

