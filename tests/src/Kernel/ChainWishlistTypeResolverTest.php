<?php

namespace Drupal\Tests\commerce_wishlist\Kernel;

use Drupal\commerce_wishlist\Entity\WishlistItem;
use Drupal\commerce_wishlist\Entity\WishlistItemType;
use Drupal\KernelTests\KernelTestBase;

/**
 * Tests the chain wishlist type resolver.
 *
 * @group commerce_wishlist
 */
class ChainWishlistTypeResolverTest extends KernelTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'system',
    'field',
    'user',
    'path',
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
    $this->installSchema('system', 'router');
    $this->installEntitySchema('user');
    $this->installEntitySchema('profile');
    $this->installEntitySchema('commerce_wishlist');
    $this->installEntitySchema('commerce_wishlist_item');
    $this->installConfig('commerce_wishlist');

    WishlistItemType::create([
      'id' => 'test',
      'label' => 'Test',
      'wishlistType' => 'default',
    ])->save();
  }

  /**
   * Tests resolving the wishlist type.
   */
  public function testWishlistTypeResolution() {
    $wishlist_item = WishlistItem::create([
      'type' => 'test',
    ]);
    $wishlist_item->save();

    $resolver = $this->container->get('commerce_wishlist.chain_wishlist_type_resolver');

    $wishlist_type = $resolver->resolve($wishlist_item);

    $this->assertEquals('default', $wishlist_type);
  }

}
