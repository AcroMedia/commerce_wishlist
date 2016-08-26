<?php

namespace Drupal\commerce_wishlist;

use Drupal\commerce_wishlist\Event\WishlistEvents;
use Drupal\commerce_wishlist\Event\LineItemComparisonFieldsEvent;
use Drupal\commerce_order\Entity\LineItemInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Default implementation of the line item matcher.
 */
class LineItemMatcher implements LineItemMatcherInterface {

  /**
   * The event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * Constructs a new LineItemMatcher object.
   *
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
   *   The event dispatcher.
   */
  public function __construct(EventDispatcherInterface $event_dispatcher) {
    $this->eventDispatcher = $event_dispatcher;
  }

  /**
   * {@inheritdoc}
   */
  public function match(LineItemInterface $line_item, array $line_items) {
    $line_items = $this->matchAll($line_item, $line_items);
    return count($line_items) ? $line_items[0] : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function matchAll(LineItemInterface $line_item, array $line_items) {
    $purchased_entity = $line_item->getPurchasedEntity();
    if (empty($purchased_entity)) {
      // Don't support combining line items without a purchased entity.
      return [];
    }

    $comparison_fields = ['type', 'purchased_entity'];
    $event = new LineItemComparisonFieldsEvent($comparison_fields, $line_item);
    $this->eventDispatcher->dispatch(WishlistEvents::LINE_ITEM_COMPARISON_FIELDS, $event);
    $comparison_fields = $event->getComparisonFields();

    $matched_line_items = [];
    /** @var \Drupal\commerce_order\Entity\LineItemInterface $existing_line_item */
    foreach ($line_items as $existing_line_item) {
      foreach ($comparison_fields as $comparison_field) {
        if (!$existing_line_item->hasField($comparison_field) || !$line_item->hasField($comparison_field)) {
          // The field is missing on one of the line items.
          continue 2;
        }
        if ($existing_line_item->get($comparison_field)->getValue() !== $line_item->get($comparison_field)->getValue()) {
          // Line item doesn't match.
          continue 2;
        }
      }
      $matched_line_items[] = $existing_line_item;
    }

    return $matched_line_items;
  }

}
