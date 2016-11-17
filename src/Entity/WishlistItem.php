<?php

namespace Drupal\commerce_wishlist\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the wishlist item entity class.
 *
 * @ContentEntityType(
 *   id = "commerce_wishlist_item",
 *   label = @Translation("Wishlist item"),
 *   label_singular = @Translation("wishlist item"),
 *   label_plural = @Translation("wishlist items"),
 *   label_count = @PluralTranslation(
 *     singular = "@count wishlist item",
 *     plural = "@count wishlist items",
 *   ),
 *   bundle_label = @Translation("wishlist item type"),
 *   handlers = {
 *     "storage" = "Drupal\commerce_wishlist\WishlistItemStorage",
 *     "access" = "Drupal\commerce\EmbeddedEntityAccessControlHandler",
 *     "views_data" = "Drupal\commerce_wishlist\WishlistItemViewsData",
 *     "form" = {
 *       "default" = "Drupal\Core\Entity\ContentEntityForm",
 *     },
 *     "inline_form" = "Drupal\commerce_wishlist\Form\WishlistItemInlineForm",
 *   },
 *   base_table = "commerce_wishlist_item",
 *   admin_permission = "administer commerce_wishlist",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id" = "wishlist_item_id",
 *     "uuid" = "uuid",
 *     "bundle" = "type",
 *     "label" = "title",
 *   },
 *   links = {
 *     "canonical" = "/admin/commerce/config/wishlist-item/{commerce_wishlist_item}",
 *     "edit-form" = "/admin/commerce/config/wishlist-item/{commerce_wishlist_item}/edit",
 *     "delete-form" = "/admin/commerce/config/wishlist-item/{commerce_wishlist_item}/delete",
 *     "collection" = "/admin/commerce/config/wishlist-item"
 *   },
 *   bundle_entity_type = "commerce_wishlist_item_type",
 *   field_ui_base_route = "entity.commerce_wishlist_item_type.edit_form",
 * )
 */
class WishlistItem extends ContentEntityBase implements WishlistItemInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function getWishlist() {
    return $this->get('wishlist_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getWishlistId() {
    return $this->get('wishlist_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function getPurchasableEntity() {
    return $this->get('purchasable_entity')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getPurchasableEntityId() {
    return $this->get('purchasable_entity')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->get('title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->set('title', $title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getQuantity() {
    return (string) $this->get('quantity')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setQuantity($quantity) {
    $this->set('quantity', (string) $quantity);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // The wishlist back reference, populated by Wishlist::postSave().
    $fields['wishlist_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Wishlist'))
      ->setDescription(t('The parent wishlist.'))
      ->setSetting('target_type', 'commerce_wishlist')
      ->setReadOnly(TRUE);

    $fields['purchasable_entity'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Purchasable entity'))
      ->setDescription(t('The purchasable entity.'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => -1,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('The wishlist item title.'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 512,
      ]);

    $fields['quantity'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity'))
      ->setDescription(t('The number of units.'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE)
      ->setDefaultValue(1)
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time when the wishlist item was created.'))
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'timestamp',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time when the wishlist item was last edited.'))
      ->setRequired(TRUE);

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public static function bundleFieldDefinitions(EntityTypeInterface $entity_type, $bundle, array $base_field_definitions) {
    /** @var \Drupal\commerce_wishlist\Entity\WishlistItemTypeInterface $wishlist_item_type */
    $wishlist_item_type = WishlistItemType::load($bundle);
    $purchasable_entity_type = $wishlist_item_type->getPurchasableEntityTypeId();
    $fields = [];
    $fields['purchasable_entity'] = clone $base_field_definitions['purchasable_entity'];
    if ($purchasable_entity_type) {
      $fields['purchasable_entity']->setSetting('target_type', $purchasable_entity_type);
    }
    else {
      // This wishlist item type won't reference a purchasable entity. The field
      // can't be removed here, or converted to a configurable one, so it's
      // hidden instead. https://www.drupal.org/node/2346347#comment-10254087.
      $fields['purchasable_entity']->setRequired(FALSE);
      $fields['purchasable_entity']->setDisplayOptions('form', [
        'type' => 'hidden',
      ]);
      $fields['purchasable_entity']->setDisplayConfigurable('form', FALSE);
      $fields['purchasable_entity']->setDisplayConfigurable('view', FALSE);
      $fields['purchasable_entity']->setReadOnly(TRUE);

      // Make the title field visible and required.
      $fields['title'] = clone $base_field_definitions['title'];
      $fields['title']->setRequired(TRUE);
      $fields['title']->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -1,
      ]);
      $fields['title']->setDisplayConfigurable('form', TRUE);
      $fields['title']->setDisplayConfigurable('view', TRUE);
    }

    return $fields;
  }

}
