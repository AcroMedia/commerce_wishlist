<?php

namespace Drupal\wishlist_template\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the store edit form.
 */
class WishlistTemplateForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    /* @var $wishlist_template \Drupal\wishlist_template\Entity\WishlistTemplate */
    $form = parent::form($form, $form_state);
    // $wishlist_template = $this->entity;

    return $form;
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::save().
   */
  public function save(array $form, FormStateInterface $form_state) {
    $this->entity->save();
    drupal_set_message($this->t('Saved the %label wishlist template.', [
      '%label' => $this->entity->label(),
    ]));
    $form_state->setRedirect('entity.wishlist_template.collection');
  }

}
