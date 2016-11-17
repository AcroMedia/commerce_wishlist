<?php

namespace Drupal\commerce_wishlist\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\profile\Entity\ProfileInterface;
use Drupal\user\UserInterface;

/**
 * Defines the wishlist entity class.
 *
 * @ContentEntityType(
 *   id = "commerce_wishlist",
 *   label = @Translation("Wishlist"),
 *   label_singular = @Translation("wishlist"),
 *   label_plural = @Translation("wishlists"),
 *   label_count = @PluralTranslation(
 *     singular = "@count wishlist",
 *     plural = "@count wishlists",
 *   ),
 *   bundle_label = @Translation("Wishlist type"),
 *   handlers = {
 *     "storage" = "Drupal\commerce\CommerceContentEntityStorage",
 *     "access" = "Drupal\commerce_wishlist\WishlistAccessControlHandler",
 *     "permission_provider" = "Drupal\commerce_wishlist\WishlistPermissionProvider",
 *     "list_builder" = "Drupal\commerce_wishlist\WishlistListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "default" = "Drupal\commerce_wishlist\Form\WishlistForm",
 *       "add" = "Drupal\commerce_wishlist\Form\WishlistForm",
 *       "edit" = "Drupal\commerce_wishlist\Form\WishlistForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *       "delete-multiple" = "Drupal\entity\Routing\DeleteMultipleRouteProvider",
 *     },
 *   },
 *   base_table = "commerce_wishlist",
 *   admin_permission = "administer commerce_wishlist",
 *   permission_granularity = "bundle",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id" = "wishlist_id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "bundle" = "type"
 *   },
 *   links = {
 *     "canonical" = "/admin/commerce/wishlists/{commerce_wishlist}",
 *     "edit-form" = "/admin/commerce/wishlists/{commerce_wishlist}/edit",
 *     "delete-form" = "/admin/commerce/wishlists/{commerce_wishlist}/delete",
 *     "delete-multiple-form" = "/admin/commerce/wishlists/delete",
 *     "collection" = "/admin/commerce/wishlists"
 *   },
 *   bundle_entity_type = "commerce_wishlist_type",
 *   field_ui_base_route = "entity.commerce_wishlist_type.edit_form"
 * )
 */
class Wishlist extends ContentEntityBase implements WishlistInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCustomer() {
    return $this->get('uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setCustomer(UserInterface $account) {
    $this->set('uid', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCustomerId() {
    return $this->get('uid')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setCustomerId($uid) {
    $this->set('uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getShippingProfile() {
    return $this->get('shipping_profile')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setShippingProfile(ProfileInterface $profile) {
    $this->set('shipping_profile', $profile);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getItems() {
    return $this->get('wishlist_items')->referencedEntities();
  }

  /**
   * {@inheritdoc}
   */
  public function setItems(array $wishlist_items) {
    $this->set('wishlist_items', $wishlist_items);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function hasItems() {
    return !$this->get('wishlist_items')->isEmpty();
  }

  /**
   * {@inheritdoc}
   */
  public function addItem(WishlistItemInterface $wishlist_item) {
    if (!$this->hasItem($wishlist_item)) {
      $this->get('wishlist_items')->appendItem($wishlist_item);
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function removeItem(WishlistItemInterface $wishlist_item) {
    $index = $this->getItemIndex($wishlist_item);
    if ($index !== FALSE) {
      $this->get('wishlist_items')->offsetUnset($index);
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function hasItem(WishlistItemInterface $wishlist_item) {
    return $this->getItemIndex($wishlist_item) !== FALSE;
  }

  /**
   * Gets the index of the given wishlist item.
   *
   * @param \Drupal\commerce_wishlist\Entity\WishlistItemInterface $wishlist_item
   *   The wishlist item.
   *
   * @return int|bool
   *   The index of the given wishlist item, or FALSE if not found.
   */
  protected function getItemIndex(WishlistItemInterface $wishlist_item) {
    $values = $this->get('wishlist_items')->getValue();
    $wishlist_item_ids = array_map(function ($value) {
      return $value['target_id'];
    }, $values);

    return array_search($wishlist_item->id(), $wishlist_item_ids);
  }

  /**
   * {@inheritdoc}
   */
  public function isDefault() {
    return (bool) $this->get('is_default')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setDefault($is_default) {
    $this->set('is_default', $is_default ? TRUE : FALSE);
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
  public function postSave(EntityStorageInterface $storage, $update = TRUE) {
    parent::postSave($storage, $update);

    // Ensure there's a back-reference on each wishlist item.
    foreach ($this->getItems() as $wishlist_item) {
      if ($wishlist_item->wishlist_id->isEmpty()) {
        $wishlist_item->wishlist_id = $this->id();
        $wishlist_item->save();
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function postDelete(EntityStorageInterface $storage, array $entities) {
    parent::postDelete($storage, $entities);

    // Delete the wishlist items of a deleted wishlist.
    $wishlist_items = [];
    /** @var \Drupal\commerce_wishlist\Entity\WishlistInterface $entity */
    foreach ($entities as $entity) {
      foreach ($entity->getItems() as $wishlist_item) {
        $wishlist_items[$wishlist_item->id()] = $wishlist_item;
      }
    }
    /** @var \Drupal\commerce_wishlist\WishlistItemStorageInterface $wishlist_item_storage */
    $wishlist_item_storage = \Drupal::service('entity_type.manager')->getStorage('commerce_wishlist_item');
    $wishlist_item_storage->delete($wishlist_items);
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The wishlist name.'))
      ->setRequired(TRUE)
      ->setDefaultValue('')
      ->setSetting('max_length', 255)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Owner'))
      ->setDescription(t('The wishlist owner.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDefaultValueCallback('Drupal\commerce_wishlist\Entity\Wishlist::getCurrentUserId')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['shipping_profile'] = BaseFieldDefinition::create('entity_reference_revisions')
      ->setLabel(t('Shipping profile'))
      ->setDescription(t('Shipping profile'))
      ->setSetting('target_type', 'profile')
      ->setSetting('handler', 'default')
      ->setSetting('handler_settings', ['target_bundles' => ['customer']])
      ->setTranslatable(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 0,
        'settings' => [],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['is_default'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Default'))
      ->setDescription(t('A boolean indicating whether the wishlist is the default one.'));

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time when the wishlist was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time when the wishlist was last edited.'));

    return $fields;
  }

  /**
   * Default value callback for 'uid' base field definition.
   *
   * @see ::baseFieldDefinitions()
   *
   * @return array
   *   An array of default values.
   */
  public static function getCurrentUserId() {
    return [\Drupal::currentUser()->id()];
  }

}
