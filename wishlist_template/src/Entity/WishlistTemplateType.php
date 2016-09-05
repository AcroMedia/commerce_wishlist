<?php

namespace Drupal\wishlist_template\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the wishlist template type entity class.
 *
 * @ConfigEntityType(
 *   id = "wishlist_template_type",
 *   label = @Translation("Wishlist template type"),
 *   label_singular = @Translation("Wishlist template type"),
 *   label_plural = @Translation("Wishlist template types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count wishlist template type",
 *     plural = "@count wishlist template types",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\wishlist_template\WishlistTemplateTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\wishlist_template\Form\WishlistTemplateTypeForm",
 *       "edit" = "Drupal\wishlist_template\Form\WishlistTemplateTypeForm",
 *       "delete" = "Drupal\wishlist_template\Form\WishlistTemplateTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer wishlist template types",
 *   config_prefix = "wishlist_template_type",
 *   bundle_of = "wishlist_template",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "uuid",
 *     "description",
 *   },
 *   links = {
 *     "add-form" = "/admin/commerce/config/wishlist-template-types/add",
 *     "edit-form" = "/admin/commerce/config/wishlist-template-types/{wishlist_template_type}/edit",
 *     "delete-form" = "/admin/commerce/config/wishlist-template-types/{wishlist_template_type}/delete",
 *     "collection" = "/admin/commerce/config/wishlist-template-types",
 *   }
 * )
 */
class WishlistTemplateType extends ConfigEntityBundleBase implements WishlistTemplateTypeInterface {

  /**
   * The wishlist template type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The wishlist template type label.
   *
   * @var string
   */
  protected $label;

  /**
   * A brief description of this wishlist template type.
   *
   * @var string
   */
  protected $description;

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * {@inheritdoc}
   */
  public function setDescription($description) {
    $this->description = $description;
    return $this;
  }

}
