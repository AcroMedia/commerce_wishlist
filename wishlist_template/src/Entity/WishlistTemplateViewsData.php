<?php

namespace Drupal\wishlist_template\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Wishlist template entities.
 */
class WishlistTemplateViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['wishlist_template']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Wishlist template'),
      'help' => $this->t('The Wishlist template ID.'),
    );

    return $data;
  }

}
