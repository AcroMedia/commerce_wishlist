<?php

namespace Drupal\commerce_wishlist;

use Drupal\views\EntityViewsData;

/**
 * Provides views data for wishlist items.
 */
class WishlistItemViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Unset the default purchasable entity relationship.
    // It does not work properly, the target type it is not defined.
    unset($data['commerce_wishlist_item']['purchasable_entity']['relationship']);

    // Collect all purchasable entity types.
    $wishlist_item_types = $this->entityManager->getStorage('commerce_wishlist_item_type')->loadMultiple();
    $entity_type_ids = [];
    /** @var \Drupal\commerce_wishlist\Entity\WishlistItemTypeInterface $wishlist_item_type */
    foreach ($wishlist_item_types as $wishlist_item_type) {
      if ($entity_type_id = $wishlist_item_type->getPurchasableEntityTypeId()) {
        $entity_type_ids[] = $entity_type_id;
      }
    }
    $entity_type_ids = array_unique($entity_type_ids);

    // Provide a relationship for each entity type found.
    foreach ($entity_type_ids as $entity_type_id) {
      /** @var \Drupal\Core\Entity\EntityTypeInterface $entity_type */
      $entity_type = $this->entityManager->getDefinition($entity_type_id);
      $data['commerce_wishlist_item'][$entity_type_id] = [
        'relationship' => [
          'title' => $entity_type->getLabel(),
          'help' => t('The purchasable entity @entity_type.', ['@entity_type' => $entity_type->getLowercaseLabel()]),
          'base' => $entity_type->getDataTable() ?: $entity_type->getBaseTable(),
          'base field' => $entity_type->getKey('id'),
          'relationship field' => 'purchasable_entity',
          'id' => 'standard',
          'label' => $entity_type->getLabel(),
        ],
      ];
    }

    return $data;
  }

}
