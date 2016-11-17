<?php

namespace Drupal\commerce_wishlist\Form;

use Drupal\Core\Entity\BundleEntityFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeInterface;

class WishlistItemTypeForm extends BundleEntityFormBase {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    /** @var \Drupal\commerce_wishlist\Entity\WishlistItemTypeInterface $wishlist_item_type */
    $wishlist_item_type = $this->entity;

    // Prepare the list of purchasable entity types.
    $entity_types = $this->entityTypeManager->getDefinitions();
    $purchasable_entity_types = array_filter($entity_types, function ($entity_type) {
      /** @var \Drupal\Core\Entity\EntityTypeInterface $entity_type */
      return $entity_type->isSubclassOf('\Drupal\commerce\PurchasableEntityInterface');
    });
    $purchasable_entity_types = array_map(function ($entity_type) {
      /** @var \Drupal\Core\Entity\EntityTypeInterface $entity_type */
      return $entity_type->getLabel();
    }, $purchasable_entity_types);

    // Prepare the list of wishlist types.
    $wishlist_types = $this->entityTypeManager->getStorage('commerce_wishlist_type')
      ->loadMultiple();
    $wishlist_types = array_map(function ($wishlist_type) {
      /** @var \Drupal\commerce_wishlist\Entity\WishlistTypeInterface $wishlist_type */
      return $wishlist_type->label();
    }, $wishlist_types);

    $form['#tree'] = TRUE;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $wishlist_item_type->label(),
      '#description' => $this->t('Label for the wishlist item type.'),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $wishlist_item_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\commerce_wishlist\Entity\WishlistItemType::load',
        'source' => ['label'],
      ],
      '#maxlength' => EntityTypeInterface::BUNDLE_MAX_LENGTH,
    ];
    $form['purchasableEntityType'] = [
      '#type' => 'select',
      '#title' => $this->t('Purchasable entity type'),
      '#default_value' => $wishlist_item_type->getPurchasableEntityTypeId(),
      '#options' => $purchasable_entity_types,
      '#empty_value' => '',
      '#disabled' => !$wishlist_item_type->isNew(),
    ];
    $form['wishlistType'] = [
      '#type' => 'select',
      '#title' => $this->t('Wishlist type'),
      '#default_value' => $wishlist_item_type->getWishlistTypeId(),
      '#options' => $wishlist_types,
      '#required' => TRUE,
    ];

    return $this->protectBundleIdElement($form);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $this->entity->save();
    drupal_set_message($this->t('Saved the %label wishlist item type.', [
      '%label' => $this->entity->label(),
    ]));
    $form_state->setRedirect('entity.commerce_wishlist_item_type.collection');
  }

}
