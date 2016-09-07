<?php

namespace Drupal\wishlist_template\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * Defines the Wishlist template entity.
 *
 * @ingroup wishlist_template
 *
 * @ContentEntityType(
 *   id = "wishlist_template",
 *   label = @Translation("Wishlist template"),
 *   bundle_label = @Translation("Wishlist template type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\wishlist_template\WishlistTemplateListBuilder",
 *     "views_data" = "Drupal\wishlist_template\Entity\WishlistTemplateViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\wishlist_template\Form\WishlistTemplateForm",
 *       "add" = "Drupal\wishlist_template\Form\WishlistTemplateForm",
 *       "edit" = "Drupal\wishlist_template\Form\WishlistTemplateForm",
 *       "delete" = "Drupal\wishlist_template\Form\WishlistTemplateDeleteForm",
 *     },
 *     "access" = "Drupal\wishlist_template\WishlistTemplateAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\wishlist_template\WishlistTemplateHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "wishlist_template",
 *   admin_permission = "administer wishlist template entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/wishlist-template/wishlist_template/{wishlist_template}",
 *     "add-page" = "/wishlist-template/wishlist_template/add",
 *     "add-form" = "/wishlist-template/wishlist_template/add/{wishlist_template_type}",
 *     "edit-form" = "/wishlist-template/wishlist_template/{wishlist_template}/edit",
 *     "delete-form" = "/wishlist-template/wishlist_template/{wishlist_template}/delete",
 *     "collection" = "/wishlist-template/wishlist_template",
 *   },
 *   bundle_entity_type = "wishlist_template_type",
 *   field_ui_base_route = "entity.wishlist_template_type.edit_form"
 * )
 */
class WishlistTemplate extends ContentEntityBase implements WishlistTemplateInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getType() {
    return $this->bundle();
  }

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
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? NODE_PUBLISHED : NODE_NOT_PUBLISHED);
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
  public function getDefaultProducts() {
    return $this->get('default_products')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setDefaultProducts($terms) {
    $this->set('default_products', $terms);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Wishlist template entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setRequired(TRUE)
      ->setDescription(t('The name of the Wishlist template.'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Wishlist template is published.'))
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

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
        'weight' => -3,
      ])
      ->setDisplayOptions('view', [
        'type' => 'hidden',
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['terms'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Categories'))
      ->setRequired(TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDescription(t('The categories used for grouping products the creating the wishlist template.'))
      ->setSetting('target_type', 'taxonomy_term')
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => -2,
      ])
      ->setDisplayOptions('view', [
        'type' => 'hidden',
      ]);

    return $fields;
  }

}
