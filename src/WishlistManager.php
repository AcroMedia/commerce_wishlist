<?php

namespace Drupal\commerce_wishlist;

use Drupal\commerce\PurchasableEntityInterface;
use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_order\Entity\OrderItemInterface;
use Drupal\commerce_wishlist\Event\WishlistEvents;
use Drupal\commerce_wishlist\Event\WishlistEmptyEvent;
use Drupal\commerce_wishlist\Event\WishlistEntityAddEvent;
use Drupal\commerce_wishlist\Event\WishlistOrderItemRemoveEvent;
use Drupal\commerce_wishlist\Event\WishlistOrderItemUpdateEvent;
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
   * The order item storage.
   *
   * @var \Drupal\commerce_order\OrderItemStorageInterface
   */
  protected $orderItemStorage;

  /**
   * The order item matcher.
   *
   * @var \Drupal\commerce_wishlist\OrderItemMatcherInterface
   */
  protected $orderItemMatcher;

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
   * @param \Drupal\commerce_wishlist\OrderItemMatcherInterface $order_item_matcher
   *   The order item matcher.
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
   *   The event dispatcher.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, OrderItemMatcherInterface $order_item_matcher, EventDispatcherInterface $event_dispatcher) {
    $this->orderItemStorage = $entity_type_manager->getStorage('commerce_order_item');
    $this->orderItemMatcher = $order_item_matcher;
    $this->eventDispatcher = $event_dispatcher;
  }

  /**
   * {@inheritdoc}
   */
  public function emptyWishlist(OrderInterface $wishlist, $save_wishlist = TRUE) {
    $order_items = $wishlist->getItems();
    foreach ($order_items as $order_item) {
      $order_item->delete();
    }
    $wishlist->setItems([]);

    $this->eventDispatcher->dispatch(WishlistEvents::WISHLIST_EMPTY, new WishlistEmptyEvent($wishlist, $order_items));
    if ($save_wishlist) {
      $wishlist->save();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function addEntity(OrderInterface $wishlist, PurchasableEntityInterface $entity, $quantity = 1, $combine = TRUE, $save_wishlist = TRUE) {
    $order_item = $this->createOrderItem($entity, $quantity);
    return $this->addOrderItem($wishlist, $order_item, $combine);
  }

  /**
   * {@inheritdoc}
   */
  public function createOrderItem(PurchasableEntityInterface $entity, $quantity = 1) {
    $order_item = $this->orderItemStorage->createFromPurchasableEntity($entity, [
      'quantity' => $quantity,
      // @todo Remove once the price calculation is in place.
      // @see CartManager.php ->createOrderItem.
      'unit_price' => $entity->getPrice(),
    ]);

    return $order_item;
  }

  /**
   * {@inheritdoc}
   */
  public function addOrderItem(OrderInterface $wishlist, OrderItemInterface $order_item, $combine = TRUE, $save_wishlist = TRUE) {
    $purchased_entity = $order_item->getPurchasedEntity();
    $quantity = $order_item->getQuantity();
    $matching_order_item = NULL;
    if ($combine) {
      $matching_order_item = $this->orderItemMatcher->match($order_item, $wishlist->getItems());
    }
    $needs_wishlist_save = FALSE;
    if ($matching_order_item) {
      $new_quantity = Calculator::add($matching_order_item->getQuantity(), $quantity);
      $matching_order_item->setQuantity($new_quantity);
      $matching_order_item->save();
    }
    else {
      $order_item->save();
      $wishlist->addItem($order_item);
      $needs_wishlist_save = TRUE;
    }

    // @todo: figure out why this produces a fatal error...
    // $event = new WishlistEntityAddEvent($wishlist, $purchased_entity, $quantity, $order_item);
    $this->eventDispatcher->dispatch(WishlistEvents::WISHLIST_ENTITY_ADD, $event);
    if ($needs_wishlist_save && $save_wishlist) {
      $wishlist->save();
    }

    return $order_item;
  }

  /**
   * {@inheritdoc}
   */
  public function updateOrderItem(OrderInterface $wishlist, OrderItemInterface $order_item) {
    /** @var \Drupal\commerce_order\Entity\OrderItemInterface $original_order_item */
    $original_order_item = $this->orderItemStorage->loadUnchanged($order_item->id());
    $order_item->save();
    $event = new WishlistOrderItemUpdateEvent($wishlist, $order_item, $original_order_item);
    $this->eventDispatcher->dispatch(WishlistEvents::WISHLIST_ORDER_ITEM_UPDATE, $event);
  }

  /**
   * {@inheritdoc}
   */
  public function removeOrderItem(OrderInterface $wishlist, OrderItemInterface $order_item, $save_wishlist = TRUE) {
    $order_item->delete();
    $wishlist->removeItem($order_item);
    $this->eventDispatcher->dispatch(WishlistEvents::WISHLIST_ORDER_ITEM_REMOVE, new WishlistOrderItemRemoveEvent($wishlist, $order_item));
    if ($save_wishlist) {
      $wishlist->save();
    }
  }

}
