<?php

namespace Drupal\commerce_wishlist\Resolver;

use Drupal\commerce_wishlist\Entity\WishlistItemInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Returns the wishlist type, based on wishlist item type configuration.
 */
class DefaultWishlistTypeResolver implements WishlistTypeResolverInterface {

  /**
   * The wishlist item type storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $wishlistItemTypeStorage;

  /**
   * Constructs a new DefaultWishlistTypeResolver object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->wishlistItemTypeStorage = $entity_type_manager->getStorage('commerce_wishlist_item_type');
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(WishlistItemInterface $wishlist_item) {
    /** @var \Drupal\commerce_wishlist\Entity\WishlistItemTypeInterface $wishlist_item_type */
    $wishlist_item_type = $this->wishlistItemTypeStorage->load($wishlist_item->bundle());

    return $wishlist_item_type->getWishlistTypeId();
  }

}
