<?php

namespace Drupal\wishlist_template;

use Drupal\wishlist_template\Entity\WishlistTemplateType;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Defines the list builder for wishlist_templates.
 */
class WishlistTemplateListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['name'] = t('Name');
    $header['type'] = t('Type');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\wishlist_template\Entity\WishlistTemplateInterface $entity */
    $wishlist_template_type = WishlistTemplateType::load($entity->bundle());

    $row['name']['data'] = [
        '#type' => 'link',
        '#title' => $entity->label(),
      ] + $entity->toUrl()->toRenderArray();
    $row['type'] = $wishlist_template_type->label();

    return $row + parent::buildRow($entity);
  }

}
