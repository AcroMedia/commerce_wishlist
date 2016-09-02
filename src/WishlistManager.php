<?php

namespace Drupal\commerce_wishlist;

use Drupal\commerce\PurchasableEntityInterface;
use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_order\Entity\LineItemInterface;
use Drupal\commerce_wishlist\Event\WishlistEvents;
use Drupal\commerce_wishlist\Event\WishlistEmptyEvent;
use Drupal\commerce_wishlist\Event\WishlistEntityAddEvent;
use Drupal\commerce_wishlist\Event\WishlistLineItemRemoveEvent;
use Drupal\commerce_wishlist\Event\WishlistLineItemUpdateEvent;
use Drupal\commerce_price\Calculator;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Default implementation of the wishlist manager.
 *
 * Fires its own events, different from the order entity events by being a
 * result of user interaction (add to wishlist form, wishlist view, etc).
 */
class WishlistManager implements WishlistManagerInterface {

  /**
   * The line item storage.
   *
   * @var \Drupal\commerce_order\LineItemStorageInterface
   */
  protected $lineItemStorage;

  /**
   * The line item matcher.
   *
   * @var \Drupal\commerce_wishlist\LineItemMatcherInterface
   */
  protected $lineItemMatcher;

  /**
   * The event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * Constructs a new WishlistManager object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\commerce_wishlist\LineItemMatcherInterface $line_item_matcher
   *   The line item matcher.
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
   *   The event dispatcher.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, LineItemMatcherInterface $line_item_matcher, EventDispatcherInterface $event_dispatcher) {
    $this->lineItemStorage = $entity_type_manager->getStorage('commerce_line_item');
    $this->lineItemMatcher = $line_item_matcher;
    $this->eventDispatcher = $event_dispatcher;
  }

  /**
   * {@inheritdoc}
   */
  public function emptyWishlist(OrderInterface $wishlist, $save_wishlist = TRUE) {
    $line_items = $wishlist->getLineItems();
    foreach ($line_items as $line_item) {
      $line_item->delete();
    }
    $wishlist->setLineItems([]);

    $this->eventDispatcher->dispatch(WishlistEvents::WISHLIST_EMPTY, new WishlistEmptyEvent($wishlist, $line_items));
    if ($save_wishlist) {
      $wishlist->save();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function addEntity(OrderInterface $wishlist, PurchasableEntityInterface $entity, $quantity = 1, $combine = TRUE, $save_wishlist = TRUE) {
    $line_item = $this->createLineItem($entity, $quantity);
    return $this->addLineItem($wishlist, $line_item, $combine);
  }

  /**
   * {@inheritdoc}
   */
  public function createLineItem(PurchasableEntityInterface $entity, $quantity = 1) {
    $line_item = $this->lineItemStorage->createFromPurchasableEntity($entity, [
      'quantity' => $quantity,
      // @todo Remove once the price calculation is in place.
      // @see CartManager.php ->createLineItem.
      'unit_price' => $entity->price,
    ]);

    return $line_item;
  }

  /**
   * {@inheritdoc}
   */
  public function addLineItem(OrderInterface $wishlist, LineItemInterface $line_item, $combine = TRUE, $save_wishlist = TRUE) {
    $purchased_entity = $line_item->getPurchasedEntity();
    $quantity = $line_item->getQuantity();
    $matching_line_item = NULL;
    if ($combine) {
      $matching_line_item = $this->lineItemMatcher->match($line_item, $wishlist->getLineItems());
    }
    $needs_wishlist_save = FALSE;
    if ($matching_line_item) {
      $new_quantity = Calculator::add($matching_line_item->getQuantity(), $quantity);
      $matching_line_item->setQuantity($new_quantity);
      $matching_line_item->save();
    }
    else {
      $line_item->save();
      $wishlist->addLineItem($line_item);
      $needs_wishlist_save = TRUE;
    }

    // @todo: figure out why this produces a fatal error...
    // $event = new WishlistEntityAddEvent($wishlist, $purchased_entity, $quantity, $line_item);
    $this->eventDispatcher->dispatch(WishlistEvents::WISHLIST_ENTITY_ADD, $event);
    if ($needs_wishlist_save && $save_wishlist) {
      $wishlist->save();
    }

    return $line_item;
  }

  /**
   * {@inheritdoc}
   */
  public function updateLineItem(OrderInterface $wishlist, LineItemInterface $line_item) {
    /** @var \Drupal\commerce_order\Entity\LineItemInterface $original_line_item */
    $original_line_item = $this->lineItemStorage->loadUnchanged($line_item->id());
    $line_item->save();
    $event = new WishlistLineItemUpdateEvent($wishlist, $line_item, $original_line_item);
    $this->eventDispatcher->dispatch(WishlistEvents::WISHLIST_LINE_ITEM_UPDATE, $event);
  }

  /**
   * {@inheritdoc}
   */
  public function removeLineItem(OrderInterface $wishlist, LineItemInterface $line_item, $save_wishlist = TRUE) {
    $line_item->delete();
    $wishlist->removeLineItem($line_item);
    $this->eventDispatcher->dispatch(WishlistEvents::WISHLIST_LINE_ITEM_REMOVE, new WishlistLineItemRemoveEvent($wishlist, $line_item));
    if ($save_wishlist) {
      $wishlist->save();
    }
  }

}
