<?php

namespace Drupal\commerce_wishlist\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the wishlist item type entity class.
 *
 * @ConfigEntityType(
 *   id = "commerce_wishlist_item_type",
 *   label = @Translation("Wishlist item type"),
 *   label_singular = @Translation("wishlist item type"),
 *   label_plural = @Translation("wishlist item types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count wishlist item type",
 *     plural = "@count wishlist item types",
 *   ),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\commerce_wishlist\Form\WishlistItemTypeForm",
 *       "edit" = "Drupal\commerce_wishlist\Form\WishlistItemTypeForm",
 *       "delete" = "Drupal\commerce_wishlist\Form\WishlistItemTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *     "list_builder" = "Drupal\commerce_wishlist\WishlistItemTypeListBuilder",
 *   },
 *   admin_permission = "administer commerce_wishlist_type",
 *   config_prefix = "commerce_wishlist_item_type",
 *   bundle_of = "commerce_wishlist_item",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "label",
 *     "id",
 *     "purchasableEntityType",
 *     "wishlistType"
 *   },
 *   links = {
 *     "add-form" = "/admin/commerce/config/wishlist-item-types/add",
 *     "edit-form" = "/admin/commerce/config/wishlist-item-types/{commerce_wishlist_item_type}/edit",
 *     "delete-form" = "/admin/commerce/config/wishlist-item-types/{commerce_wishlist_item_type}/delete",
 *     "collection" = "/admin/commerce/config/wishlist-item-types"
 *   }
 * )
 */
class WishlistItemType extends ConfigEntityBundleBase implements WishlistItemTypeInterface {

  /**
   * The wishlist item type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The wishlist item type label.
   *
   * @var string
   */
  protected $label;

  /**
   * The purchasable entity type ID.
   *
   * @var string
   */
  protected $purchasableEntityType;

  /**
   * The wishlist type ID.
   *
   * @var string
   */
  protected $wishlistType;

  /**
   * {@inheritdoc}
   */
  public function getPurchasableEntityTypeId() {
    return $this->purchasableEntityType;
  }

  /**
   * {@inheritdoc}
   */
  public function setPurchasableEntityTypeId($purchasable_entity_type_id) {
    $this->purchasableEntityType = $purchasable_entity_type_id;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getWishlistTypeId() {
    return $this->wishlistType;
  }

  /**
   * {@inheritdoc}
   */
  public function setWishlistTypeId($wishlist_type_id) {
    $this->wishlistType = $wishlist_type_id;
    return $this;
  }

}
