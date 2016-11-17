<?php

namespace Drupal\commerce_wishlist;

use Drupal\commerce_wishlist\Entity\WishlistType;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines the list builder for wishlists.
 */
class WishlistListBuilder extends EntityListBuilder {

  /**
   * The date service.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * Constructs a new WishlistListBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Datetime\DateFormatter $date_formatter
   *   The date service.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityTypeManagerInterface $entity_type_manager, DateFormatter $date_formatter) {
    parent::__construct($entity_type, $entity_type_manager->getStorage($entity_type->id()));

    $this->dateFormatter = $date_formatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity_type.manager'),
      $container->get('date.formatter')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header = [
      'wishlist_id' => [
        'data' => $this->t('Wishlist ID'),
        'class' => [RESPONSIVE_PRIORITY_LOW],
      ],
      'type' => [
        'data' => $this->t('Type'),
        'class' => [RESPONSIVE_PRIORITY_MEDIUM],
      ],
      'customer' => [
        'data' => $this->t('Customer'),
        'class' => [RESPONSIVE_PRIORITY_LOW],
      ],
      'changed' => [
        'data' => $this->t('Changed'),
        'class' => [RESPONSIVE_PRIORITY_LOW],
      ],
      'created' => [
        'data' => $this->t('Created'),
        'class' => [RESPONSIVE_PRIORITY_LOW],
      ],
    ];

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\commerce_wishlist\Entity\Wishlist */
    $wishlistType = WishlistType::load($entity->bundle());
    $row = [
      'wishlist_id' => $entity->id(),
      'type' => $wishlistType->label(),
      'customer' => [
        'data' => [
          '#theme' => 'username',
          '#account' => $entity->getCustomer(),
        ],
      ],
      'changed' => $this->dateFormatter->format($entity->getChangedTime(), 'short'),
      'created' => $this->dateFormatter->format($entity->getCreatedTime(), 'short'),
    ];

    return $row + parent::buildRow($entity);
  }

}
