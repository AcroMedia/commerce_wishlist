<?php

namespace Drupal\wishlist_template;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Wishlist template entity.
 *
 * @see \Drupal\wishlist_template\Entity\WishlistTemplate.
 */
class WishlistTemplateAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\wishlist_template\Entity\WishlistTemplateInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished wishlist template entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published wishlist template entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit wishlist template entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete wishlist template entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add wishlist template entities');
  }

}
