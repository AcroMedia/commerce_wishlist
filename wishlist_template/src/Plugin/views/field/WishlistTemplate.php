<?php

namespace Drupal\wishlist_template\Plugin\views\field;

use Drupal\views\Plugin\views\field\Field;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Displays the wishlist template.
 *
 * Can be configured to show nothing when there's only one possible wishlist template.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("wishlist_template")
 */
class WishlistTemplate extends Field {

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['hide_single_wishlist_template'] = ['default' => TRUE];

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $form['hide_single_wishlist_template'] = [
      '#type' => 'checkbox',
      '#title' => t("Hide if there's only one wishlist template."),
      '#default_value' => $this->options['hide_single_wishlist_template'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function access(AccountInterface $account) {
    $wishlist_template_query = $this->entityManager->getStorage('wishlist_template')->getQuery();
    $wishlist_template_count = $wishlist_template_query->count()->execute();
    if ($this->options['hide_single_wishlist_template'] && $wishlist_template_count <= 1) {
      return FALSE;
    }

    return parent::access($account);
  }

}
