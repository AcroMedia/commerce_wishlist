<?php

namespace Drupal\Tests\commerce_wishlist\Kernel\Entity;

use Drupal\commerce_wishlist\Entity\Wishlist;
use Drupal\commerce_wishlist\Entity\WishlistItem;
use Drupal\commerce_wishlist\Entity\WishlistItemType;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\profile\Entity\Profile;

/**
 * Tests the Wishlist entity.
 *
 * @coversDefaultClass \Drupal\commerce_wishlist\Entity\Wishlist
 *
 * @group commerce_wishlist
 */
class WishlistTest extends EntityKernelTestBase {

  /**
   * A sample user.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $user;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'options',
    'entity',
    'entity_reference_revisions',
    'views',
    'address',
    'profile',
    'state_machine',
    'inline_entity_form',
    'commerce',
    'commerce_cart',
    'commerce_order',
    'commerce_price',
    'commerce_store',
    'commerce_product',
    'commerce_wishlist',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installEntitySchema('profile');
    $this->installEntitySchema('commerce_wishlist');
    $this->installEntitySchema('commerce_wishlist_item');
    $this->installConfig('commerce_wishlist');

    // An wishlist item type that doesn't need a purchasable entity, for simplicity.
    WishlistItemType::create([
      'id' => 'test',
      'label' => 'Test',
      'wishlistType' => 'default',
    ])->save();

    $user = $this->createUser();
    $this->user = $this->reloadEntity($user);
  }

  /**
   * Tests the wishlist entity and its methods.
   *
   * @covers ::getName
   * @covers ::setName
   * @covers ::getCustomer
   * @covers ::setCustomer
   * @covers ::getCustomerId
   * @covers ::setCustomerId
   * @covers ::getShippingProfile
   * @covers ::setShippingProfile
   * @covers ::getItems
   * @covers ::setItems
   * @covers ::hasItems
   * @covers ::addItem
   * @covers ::removeItem
   * @covers ::hasItem
   * @covers ::getCreatedTime
   * @covers ::setCreatedTime
   */
  public function testWishlist() {
    $profile = Profile::create([
      'type' => 'customer',
    ]);
    $profile->save();
    $profile = $this->reloadEntity($profile);

    /** @var \Drupal\commerce_wishlist\Entity\WishlistItemInterface $wishlist_item */
    $wishlist_item = WishlistItem::create([
      'type' => 'test',
    ]);
    $wishlist_item->save();
    $wishlist_item = $this->reloadEntity($wishlist_item);
    /** @var \Drupal\commerce_wishlist\Entity\WishlistItemInterface $another_wishlist_item */
    $another_wishlist_item = WishlistItem::create([
      'type' => 'test',
      'quantity' => '2',
    ]);
    $another_wishlist_item->save();
    $another_wishlist_item = $this->reloadEntity($another_wishlist_item);

    $wishlist = Wishlist::create([
      'type' => 'default',
      'state' => 'completed',
    ]);
    $wishlist->save();

    $wishlist->setName('My wishlist');
    $this->assertEquals('My wishlist', $wishlist->getName());

    $wishlist->setCustomer($this->user);
    $this->assertEquals($this->user, $wishlist->getCustomer());
    $this->assertEquals($this->user->id(), $wishlist->getCustomerId());
    $wishlist->setCustomerId(0);
    $this->assertEquals(NULL, $wishlist->getCustomer());
    $wishlist->setCustomerId($this->user->id());
    $this->assertEquals($this->user, $wishlist->getCustomer());
    $this->assertEquals($this->user->id(), $wishlist->getCustomerId());

    $wishlist->setShippingProfile($profile);
    $this->assertEquals($profile, $wishlist->getShippingProfile());

    $wishlist->setItems([$wishlist_item, $another_wishlist_item]);
    $this->assertEquals([$wishlist_item, $another_wishlist_item], $wishlist->getItems());
    $this->assertTrue($wishlist->hasItems());
    $wishlist->removeItem($another_wishlist_item);
    $this->assertEquals([$wishlist_item], $wishlist->getItems());
    $this->assertTrue($wishlist->hasItem($wishlist_item));
    $this->assertFalse($wishlist->hasItem($another_wishlist_item));
    $wishlist->addItem($another_wishlist_item);
    $this->assertEquals([$wishlist_item, $another_wishlist_item], $wishlist->getItems());
    $this->assertTrue($wishlist->hasItem($another_wishlist_item));

    $wishlist->setCreatedTime(635879700);
    $this->assertEquals(635879700, $wishlist->getCreatedTime());
  }

}
