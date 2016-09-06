<?php

namespace Drupal\wishlist_template;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Wishlist template entities.
 *
 * @ingroup wishlist_template
 */
class WishlistTemplateListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Wishlist template ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\wishlist_template\Entity\WishlistTemplate */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.wishlist_template.edit_form', array(
          'wishlist_template' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
