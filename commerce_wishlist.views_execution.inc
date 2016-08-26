<?php

/**
 * @file
 * Provides views execution hooks for wishlist.
 */

use Drupal\views\ViewExecutable;
use Drupal\views\Plugin\views\query\QueryPluginBase;

/**
 * Implements hook_views_query_alter().
 */
function commerce_wishlist_views_query_alter(ViewExecutable $view, QueryPluginBase $query) {
  if ($view->id() == 'commerce_orders') {
    // Filter out wishlists, they have their own tab.
    $query->addWhere(0, 'wishlist', 1, '<>');
  }
}
