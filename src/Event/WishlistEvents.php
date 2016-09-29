<?php

namespace Drupal\commerce_wishlist\Event;

/**
 * Defines events for the wishlist module.
 */
final class WishlistEvents {

  /**
   * Name of the event fired after assigning the anonymous wishlist to a user.
   *
   * Fired before the wishlist order is saved.
   *
   * Use this event to implement logic such as canceling any existing wishlist
   * orders the user might already have prior to the anonymous wishlist
   * assignment.
   *
   * @Event
   *
   * @see \Drupal\commerce_wishlist\Event\WishlistAssignEvent
   */
  const WISHLIST_ASSIGN = 'commerce_wishlist.wishlist.assign';

  /**
   * Name of the event fired after emptying the wishlist order.
   *
   * Fired before the wishlist order is saved.
   *
   * @Event
   *
   * @see \Drupal\commerce_wishlist\Event\WishlistEmptyEvent
   */
  const WISHLIST_EMPTY = 'commerce_wishlist.wishlist.empty';

  /**
   * Name of the event fired after adding a purchasable entity to the wishlist.
   *
   * Fired before the wishlist order is saved.
   *
   * @Event
   *
   * @see \Drupal\commerce_wishlist\Event\WishlistEntityAddEvent
   */
  const WISHLIST_ENTITY_ADD = 'commerce_wishlist.entity.add';

  /**
   * Name of the event fired after updating a wishlist's order item.
   *
   * Fired before the wishlist order is saved.
   *
   * @Event
   *
   * @see \Drupal\commerce_wishlist\Event\WishlistOrderItemUpdateEvent
   */
  const WISHLIST_ORDER_ITEM_UPDATE = 'commerce_wishlist.order_item.update';

  /**
   * Name of the event fired after removing a order item from the wishlist.
   *
   * Fired before the wishlist order is saved.
   *
   * @Event
   *
   * @see \Drupal\commerce_wishlist\Event\WishlistOrderItemRemoveEvent
   */
  const WISHLIST_ORDER_ITEM_REMOVE = 'commerce_wishlist.order_item.remove';

  /**
   * Name of the event fired when altering the list of comparison fields.
   *
   * Use this event to add additional field names to the list of fields used
   * to determine whether a order item can be combined into an existing order
   * item.
   *
   * @Event
   *
   * @see \Drupal\commerce_wishlist\Event\OrderItemComparisonFieldsEvent
   */
  const ORDER_ITEM_COMPARISON_FIELDS = 'commerce_wishlist.order_item.comparison_fields';

}
