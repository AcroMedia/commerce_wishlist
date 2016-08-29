<?php

namespace Drupal\commerce_wishlist;

use Drupal\commerce_wishlist\Event\WishlistAssignEvent;
use Drupal\commerce_wishlist\Event\WishlistEvents;
use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\user\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class WishlistAssignment implements WishlistAssignmentInterface {

  /**
   * The wishlist provider.
   *
   * @var \Drupal\commerce_wishlist\WishlistSessionInterface
   */
  protected $wishlistSession;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * Constructs a new WishlistAssignment object.
   *
   * @param \Drupal\commerce_wishlist\WishlistSessionInterface $wishlist_provider
   *   The wishlist provider.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
   *   The event dispatcher.
   */
  public function __construct(WishlistSessionInterface $wishlist_provider, EntityTypeManagerInterface $entity_type_manager, EventDispatcherInterface $event_dispatcher) {
    $this->wishlistSession = $wishlist_provider;
    $this->entityTypeManager = $entity_type_manager;
    $this->eventDispatcher = $event_dispatcher;
  }

  /**
   * {@inheritdoc}
   */
  public function assignAll(UserInterface $account) {
    $wishlist_ids = $this->wishlistSession->getWishlistIds();
    if ($wishlist_ids) {
      $wishlist_storage = $this->entityTypeManager->getStorage('commerce_order');
      /** @var \Drupal\commerce_order\Entity\OrderInterface[] $wishlists */
      $wishlists = $wishlist_storage->loadMultiple($wishlist_ids);
      foreach ($wishlists as $wishlist) {
        $this->assign($wishlist, $account);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function assign(OrderInterface $wishlist, UserInterface $account) {
    if (!empty($wishlist->getOwnerId())) {
      // Skip wishlist orders which already have an owner.
      return;
    }

    $wishlist->setOwner($account);
    $wishlist->setEmail($account->getEmail());
    // Update the referenced billing profile.
    $billing_profile = $wishlist->getBillingProfile();
    if ($billing_profile && empty($billing_profile->getOwnerId())) {
      $billing_profile->setOwner($account);
      $billing_profile->save();
    }
    // Notify other modules.
    $event = new WishlistAssignEvent($wishlist, $account);
    $this->eventDispatcher->dispatch(WishlistEvents::WISHLIST_ASSIGN, $event);

    $wishlist->save();
  }

}
