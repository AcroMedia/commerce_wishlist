<?php

namespace Drupal\commerce_wishlist\Form;

use Drupal\Core\Entity\BundleEntityFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Provides an wishlist type form.
 */
class WishlistTypeForm extends BundleEntityFormBase {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    /** @var \Drupal\commerce_wishlist\Entity\WishlistTypeInterface $wishlist_type */
    $wishlist_type = $this->entity;

    // Prepare a list of views tagged 'commerce_wishlist_form'.
    $view_storage = \Drupal::entityTypeManager()->getStorage('view');
    $available_form_views = [];
    foreach ($view_storage->loadMultiple() as $view) {
      if (strpos($view->get('tag'), 'commerce_wishlist_form') !== FALSE) {
        $available_form_views[$view->id()] = $view->label();
      }
    }

    $form['#tree'] = TRUE;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $wishlist_type->label(),
      '#description' => $this->t('Label for the wishlist type.'),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $wishlist_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\commerce_wishlist\Entity\WishlistType::load',
        'source' => ['label'],
      ],
      '#maxlength' => EntityTypeInterface::BUNDLE_MAX_LENGTH,
    ];
    $form['allowAnonymous'] = [
      '#type' => 'checkbox',
      '#default_value' => $wishlist_type->isAllowAnonymous(),
      '#title' => $this->t('Allow anonymous wishlists'),
    ];
    $form['allowMultiple'] = [
      '#type' => 'checkbox',
      '#default_value' => $wishlist_type->isAllowMultiple(),
      '#title' => $this->t('Allow multiple wishlists'),
    ];
    $form['allowPublic'] = [
      '#type' => 'checkbox',
      '#default_value' => $wishlist_type->isAllowPublic(),
      '#title' => $this->t('Allow public wishlists'),
    ];
    $form['wishlistFormView'] = [
      '#type' => 'select',
      '#title' => $this->t('Wishlist form view'),
      '#default_value' => $wishlist_type->getWishlistFormView(),
      '#options' => $available_form_views,
    ];

    return $this->protectBundleIdElement($form);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\commerce_wishlist\Entity\WishlistTypeInterface $this->entity */
    $status = $this->entity->save();
    drupal_set_message($this->t('Saved the %label wishlist type.', ['%label' => $this->entity->label()]));
    $form_state->setRedirect('entity.commerce_wishlist_type.collection');

    if ($status == SAVED_NEW) {
      commerce_wishlist_add_wishlist_items_field($this->entity);
    }
  }

}
