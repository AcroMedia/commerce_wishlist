<?php

namespace Drupal\wishlist_template\Entity;

use Drupal\user\UserInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the store entity class.
 *
 * @ContentEntityType(
 *   id = "wishlist_template",
 *   label = @Translation("Wishlist template"),
 *   label_singular = @Translation("Wishlist template"),
 *   label_plural = @Translation("Wishlist templates"),
 *   label_count = @PluralTranslation(
 *     singular = "@count wishlist template",
 *     plural = "@count wishlist templates",
 *   ),
 *   bundle_label = @Translation("Wishlist template type"),
 *   handlers = {
 *     "event" = "Drupal\wishlist_template\Event\WishlistTemplateEvent",
 *     "storage" = "Drupal\wishlist_template\WishlistTemplateStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\wishlist_template\WishlistTemplateListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\wishlist_template\Form\WishlistTemplateForm",
 *       "edit" = "Drupal\wishlist_template\Form\WishlistTemplateForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *       "delete-multiple" = "Drupal\entity\Routing\DeleteMultipleRouteProvider",
 *     },
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler"
 *   },
 *   base_table = "wishlist_template",
 *   data_table = "wishlist_template_field_data",
 *   admin_permission = "administer wishlist templates",
 *   fieldable = TRUE,
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "wishlist_id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "langcode" = "langcode",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/wishlist_template/{wishlist_template}",
 *     "add-page" = "/wishlist_template/add",
 *     "add-form" = "/wishlist_template/add/{wishlist_template_type}",
 *     "edit-form" = "/wishlist_template/{wishlist_template}/edit",
 *     "delete-form" = "/wishlist_template/{wishlist_template}/delete",
 *     "delete-multiple-form" = "/admin/commerce/wishlist_templates/delete",
 *     "collection" = "/admin/commerce/wishlist_templates",
 *   },
 *   bundle_entity_type = "wishlist_template_type",
 *   field_ui_base_route = "entity.wishlist_template_type.edit_form",
 * )
 */
class WishlistTemplate extends ContentEntityBase implements WishlistTemplateInterface {

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
  public function getOwner() {
    return $this->get('uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('uid', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('uid')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTaxonomyTermViewMode() {
    return $this->get('taxonomy_term_view_mode')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTaxonomyTermViewMode($taxonomy_term_view_mode) {
    $this->set('taxonomy_term_view_mode', $taxonomy_term_view_mode);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTerms() {
    return $this->get('terms')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTerms($terms) {
    $this->set('terms', $terms);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['type'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Type'))
      ->setDescription(t('The wishlist template type.'))
      ->setSetting('target_type', 'wishlist_template_type')
      ->setReadOnly(TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Owner'))
      ->setDescription(t('The store owner.'))
      ->setDefaultValueCallback('Drupal\commerce_store\Entity\Store::getCurrentUserId')
      ->setSetting('target_type', 'user')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 50,
      ]);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The wishlist template name.'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setSettings([
        'default_value' => '',
        'max_length' => 255,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['taxonomy_term_view_mode'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Category view mode'))
      ->setDescription(t('The view mode to be used when rendering each category in the wishlist template.'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setSettings([
        'allowed_values_function' => '_wishlist_template_term_view_mode_values',
        'multiple' => false,
      ])
      ->setDisplayOptions('form', [
        'type' => 'select',
        'weight' => 0,
      ])
      ->setDisplayOptions('view', [
        'type' => 'hidden',
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['terms'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Categories'))
      ->setDescription(t('The categories used for grouping products the creating the wishlist template.'))
      ->setSetting('target_type', 'taxonomy_term')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 50,
      ]);

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
