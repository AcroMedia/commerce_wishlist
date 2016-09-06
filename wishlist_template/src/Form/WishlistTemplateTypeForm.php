<?php

namespace Drupal\wishlist_template\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class WishlistTemplateTypeForm.
 *
 * @package Drupal\wishlist_template\Form
 */
class WishlistTemplateTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $wishlist_template_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $wishlist_template_type->label(),
      '#description' => $this->t("Label for the Wishlist template type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $wishlist_template_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\wishlist_template\Entity\WishlistTemplateType::load',
      ],
      '#disabled' => !$wishlist_template_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $wishlist_template_type = $this->entity;
    $status = $wishlist_template_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Wishlist template type.', [
          '%label' => $wishlist_template_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Wishlist template type.', [
          '%label' => $wishlist_template_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($wishlist_template_type->urlInfo('collection'));
  }

}
