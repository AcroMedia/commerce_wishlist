<?php

namespace Drupal\commerce_wishlist;

use Drupal\commerce_wishlist\Exception\DuplicateWishlistException;
use Drupal\commerce_store\Entity\StoreInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Default implementation of the wishlist provider.
 */
class WishlistProvider implements WishlistProviderInterface {

  /**
   * The order storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $orderStorage;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The session.
   *
   * @var \Drupal\commerce_wishlist\WishlistSessionInterface
   */
  protected $wishlistSession;

  /**
   * The loaded wishlist data, keyed by wishlist order ID, then grouped by uid.
   *
   * Each data item is an array with the following keys:
   * - type: The order type.
   * - store_id: The store ID.
   *
   * Example:
   * @code
   * 1 => [
   *   10 => ['type' => 'default', 'store_id' => '1'],
   * ]
   * @endcode
   *
   * @var array
   */
  protected $wishlistData = [];

  /**
   * Constructs a new WishlistProvider object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\commerce_wishlist\WishlistSessionInterface $wishlist_session
   *   The wishlist session.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, AccountInterface $current_user, WishlistSessionInterface $wishlist_session) {
    $this->orderStorage = $entity_type_manager->getStorage('commerce_order');
    $this->currentUser = $current_user;
    $this->wishlistSession = $wishlist_session;
  }

  /**
   * {@inheritdoc}
   */
  public function createWishlist($order_type, StoreInterface $store, AccountInterface $account = NULL) {
    $account = $account ?: $this->currentUser;
    $uid = $account->id();
    $store_id = $store->id();
    // @todo Remove this limitation.
    if ($this->getWishlistId($order_type, $store, $account)) {
      // Don't allow multiple wishlist orders matching the same criteria.
      throw new DuplicateWishlistException("A wishlist order for type '$order_type', store '$store_id' and account '$uid' already exists.");
    }

    // Create the new wishlist order.
    $wishlist = $this->orderStorage->create([
      'type' => $order_type,
      'store_id' => $store_id,
      'uid' => $uid,
      'wishlist' => TRUE,
    ]);
    $wishlist->save();
    // Store the new wishlist order id in the anonymous user's session so that
    // it can be retrieved on the next page load.
    if ($account->isAnonymous()) {
      $this->wishlistSession->addWishlistId($wishlist->id());
    }
    // Wishlist data has already been loaded, add the new wishlist order to
    // the list.
    if (isset($this->wishlistData[$uid])) {
      $this->wishlistData[$uid][$wishlist->id()] = [
        'type' => $order_type,
        'store_id' => $store_id,
      ];
    }

    return $wishlist;
  }

  /**
   * {@inheritdoc}
   */
  public function getWishlist($order_type, StoreInterface $store, AccountInterface $account = NULL) {
    $wishlist = NULL;
    $wishlist_id = $this->getWishlistId($order_type, $store, $account);
    if ($wishlist_id) {
      $wishlist = $this->orderStorage->load($wishlist_id);
    }

    return $wishlist;
  }

  /**
   * {@inheritdoc}
   */
  public function getWishlistId($order_type, StoreInterface $store, AccountInterface $account = NULL) {
    $wishlist_id = NULL;
    $wishlist_data = $this->loadWishlistData($account);
    if ($wishlist_data) {
      $search = [
        'type' => $order_type,
        'store_id' => $store->id(),
      ];
      $wishlist_id = array_search($search, $wishlist_data);
    }

    return $wishlist_id;
  }

  /**
   * {@inheritdoc}
   */
  public function getWishlists(AccountInterface $account = NULL) {
    $wishlists = [];
    $wishlist_ids = $this->getWishlistIds($account);
    if ($wishlist_ids) {
      $wishlists = $this->orderStorage->loadMultiple($wishlist_ids);
    }

    return $wishlists;
  }

  /**
   * {@inheritdoc}
   */
  public function getWishlistIds(AccountInterface $account = NULL) {
    $wishlist_data = $this->loadWishlistData($account);
    return array_keys($wishlist_data);
  }

  /**
   * Loads the wishlist data for the given user.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user. If empty, the current user is assumed.
   *
   * @return array
   *   The wishlist data.
   */
  protected function loadWishlistData(AccountInterface $account = NULL) {
    $account = $account ?: $this->currentUser;
    $uid = $account->id();
    if (isset($this->wishlistData[$uid])) {
      return $this->wishlistData[$uid];
    }

    if ($account->isAuthenticated()) {
      $query = $this->orderStorage->getQuery()
        ->condition('wishlist', TRUE)
        ->condition('uid', $account->id())
        ->sort('order_id', 'DESC');
      $wishlist_ids = $query->execute();
    }
    else {
      $wishlist_ids = $this->wishlistSession->getWishlistIds();
    }

    $this->wishlistData[$uid] = [];
    if (!$wishlist_ids) {
      return [];
    }
    // Getting the wishlist data and validating the wishlist ids received from
    // the session requires loading the entities. This is a performance hit, but
    // it's assumed that these entities would be loaded at one point anyway.
    /** @var \Drupal\commerce_order\Entity\OrderInterface[] $wishlists */
    $wishlists = $this->orderStorage->loadMultiple($wishlist_ids);
    foreach ($wishlists as $wishlist) {
      if ($wishlist->getOwnerId() != $uid || empty($wishlist->wishlist)) {
        // Skip orders that are no longer eligible.
        continue;
      }

      $this->wishlistData[$uid][$wishlist->id()] = [
        'type' => $wishlist->bundle(),
        'store_id' => $wishlist->getStoreId(),
      ];
    }

    return $this->wishlistData[$uid];
  }

}
