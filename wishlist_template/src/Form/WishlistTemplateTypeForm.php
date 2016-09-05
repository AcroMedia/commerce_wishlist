<?php

namespace Drupal\wishlist_template\Form;

use Drupal\Core\Entity\BundleEntityFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\language\Entity\ContentLanguageSettings;

class WishlistTemplateTypeForm extends BundleEntityFormBase {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    /** @var $wishlist_template_type \Drupal\wishlist_template\Entity\WishlistTemplateType */
    $wishlist_template_type = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $wishlist_template_type->label(),
      '#description' => $this->t('Label for the wishlist template type.'),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $wishlist_template_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\wishlist_template\Entity\WishlistTemplateType::load',
      ],
      '#maxlength' => EntityTypeInterface::BUNDLE_MAX_LENGTH,
    ];
    $form['description'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Description'),
      '#default_value' => $wishlist_template_type->getDescription(),
    ];

    if ($this->moduleHandler->moduleExists('language')) {
      $form['language'] = [
        '#type' => 'details',
        '#title' => $this->t('Language settings'),
        '#group' => 'additional_settings',
      ];
      $form['language']['language_configuration'] = [
        '#type' => 'language_configuration',
        '#entity_information' => [
          'entity_type' => 'wishlist_template',
          'bundle' => $wishlist_template_type->id(),
        ],
        '#default_value' => ContentLanguageSettings::loadByEntityTypeBundle('wishlist_template', $wishlist_template_type->id()),
      ];
      $form['#submit'][] = 'language_configuration_element_submit';
    }

    return $this->protectBundleIdElement($form);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $this->entity->save();
    drupal_set_message($this->t('Saved the %label wishlist template type.', [
      '%label' => $this->entity->label(),
    ]));
    $form_state->setRedirect('entity.wishlist_template_type.collection');
  }

}
