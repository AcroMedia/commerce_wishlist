<?php

namespace Drupal\wishlist_template\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Wishlist template type entity.
 *
 * @ConfigEntityType(
 *   id = "wishlist_template_type",
 *   label = @Translation("Wishlist template type"),
 *   handlers = {
 *     "list_builder" = "Drupal\wishlist_template\WishlistTemplateTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\wishlist_template\Form\WishlistTemplateTypeForm",
 *       "edit" = "Drupal\wishlist_template\Form\WishlistTemplateTypeForm",
 *       "delete" = "Drupal\wishlist_template\Form\WishlistTemplateTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\wishlist_template\WishlistTemplateTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "wishlist_template_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "wishlist_template",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/commerce/config/wishlist_template_type/{wishlist_template_type}",
 *     "add-form" = "/admin/commerce/config/wishlist_template_type/add",
 *     "edit-form" = "/admin/commerce/config/wishlist_template_type/{wishlist_template_type}/edit",
 *     "delete-form" = "/admin/commerce/config/wishlist_template_type/{wishlist_template_type}/delete",
 *     "collection" = "/admin/commerce/config/wishlist_template_type"
 *   }
 * )
 */
class WishlistTemplateType extends ConfigEntityBundleBase implements WishlistTemplateTypeInterface {

  /**
   * The Wishlist template type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Wishlist template type label.
   *
   * @var string
   */
  protected $label;

}
