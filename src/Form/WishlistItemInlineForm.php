<?php

namespace Drupal\commerce_wishlist\Form;

use Drupal\commerce_wishlist\Entity\WishlistItemInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\inline_entity_form\Form\EntityInlineForm;

/**
 * Defines the inline form for wishlist items.
 */
class WishlistItemInlineForm extends EntityInlineForm {

  /**
   * {@inheritdoc}
   */
  public function getEntityTypeLabels() {
    $labels = [
      'singular' => t('wishlist item'),
      'plural' => t('wishlist items'),
    ];
    return $labels;
  }

  /**
   * {@inheritdoc}
   */
  public function getTableFields($bundles) {
    $fields = parent::getTableFields($bundles);
    $fields['quantity'] = [
      'type' => 'field',
      'label' => t('Quantity'),
      'weight' => 2,
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function entityForm(array $entity_form, FormStateInterface $form_state) {
    $entity_form = parent::entityForm($entity_form, $form_state);
    $entity_form['#entity_builders'][] = [get_class($this), 'populateTitle'];

    return $entity_form;
  }

  /**
   * Entity builder: populates the wishlist item title from the purchasable entity.
   *
   * @param string $entity_type
   *   The entity type identifier.
   * @param \Drupal\commerce_wishlist\Entity\WishlistItemInterface $wishlist_item
   *   The wishlist item.
   * @param array $form
   *   The complete form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public static function populateTitle($entity_type, WishlistItemInterface $wishlist_item, array $form, FormStateInterface $form_state) {
    $purchasable_entity = $wishlist_item->getPurchasableEntity();
    if ($wishlist_item->isNew() && $purchasable_entity) {
      $wishlist_item->setTitle($purchasable_entity->getOrderItemTitle());
    }
  }

}
