<?php

namespace Drupal\wishlist_template\Event;

final class WishlistTemplateEvents {

  /**
   * Name of the event fired after loading a store.
   *
   * @Event
   *
   * @see \Drupal\wishlist_template\Event\WishlistTemplateEvent
   */
  const WISHLIST_TEMPLATE_LOAD = 'wishlist_template.wishlist_template.load';

  /**
   * Name of the event fired after creating a new store.
   *
   * Fired before the store is saved.
   *
   * @Event
   *
   * @see \Drupal\wishlist_template\Event\WishlistTemplateEvent
   */
  const WISHLIST_TEMPLATE_CREATE = 'wishlist_template.wishlist_template.create';

  /**
   * Name of the event fired before saving a store.
   *
   * @Event
   *
   * @see \Drupal\wishlist_template\Event\WishlistTemplateEvent
   */
  const WISHLIST_TEMPLATE_PRESAVE = 'wishlist_template.wishlist_template.presave';

  /**
   * Name of the event fired after saving a new store.
   *
   * @Event
   *
   * @see \Drupal\wishlist_template\Event\WishlistTemplateEvent
   */
  const WISHLIST_TEMPLATE_INSERT = 'wishlist_template.wishlist_template.insert';

  /**
   * Name of the event fired after saving an existing store.
   *
   * @Event
   *
   * @see \Drupal\wishlist_template\Event\WishlistTemplateEvent
   */
  const WISHLIST_TEMPLATE_UPDATE = 'wishlist_template.wishlist_template.update';

  /**
   * Name of the event fired before deleting a store.
   *
   * @Event
   *
   * @see \Drupal\wishlist_template\Event\WishlistTemplateEvent
   */
  const WISHLIST_TEMPLATE_PREDELETE = 'wishlist_template.wishlist_template.predelete';

  /**
   * Name of the event fired after deleting a store.
   *
   * @Event
   *
   * @see \Drupal\wishlist_template\Event\WishlistTemplateEvent
   */
  const WISHLIST_TEMPLATE_DELETE = 'wishlist_template.wishlist_template.delete';

  /**
   * Name of the event fired after saving a new store translation.
   *
   * @Event
   *
   * @see \Drupal\wishlist_template\Event\WishlistTemplateEvent
   */
  const WISHLIST_TEMPLATE_TRANSLATION_INSERT = 'wishlist_template.wishlist_template.translation_insert';

  /**
   * Name of the event fired after deleting a store translation.
   *
   * @Event
   *
   * @see \Drupal\wishlist_template\Event\WishlistTemplateEvent
   */
  const WISHLIST_TEMPLATE_TRANSLATION_DELETE = 'wishlist_template.wishlist_template.translation_delete';

}
