<?php

namespace Drupal\commerce_wishlist\Resolver;

use Drupal\commerce_wishlist\Entity\WishlistItemInterface;

/**
 * Default implementation of the chain wishlist type resolver.
 */
class ChainWishlistTypeResolver implements ChainWishlistTypeResolverInterface {

  /**
   * The resolvers.
   *
   * @var \Drupal\commerce_wishlist\Resolver\WishlistTypeResolverInterface[]
   */
  protected $resolvers = [];

  /**
   * Constructs a new ChainWishlistTypeResolver object.
   *
   * @param \Drupal\commerce_wishlist\Resolver\WishlistTypeResolverInterface[] $resolvers
   *   The resolvers.
   */
  public function __construct(array $resolvers = []) {
    $this->resolvers = $resolvers;
  }

  /**
   * {@inheritdoc}
   */
  public function addResolver(WishlistTypeResolverInterface $resolver) {
    $this->resolvers[] = $resolver;
  }

  /**
   * {@inheritdoc}
   */
  public function getResolvers() {
    return $this->resolvers;
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(WishlistItemInterface $wishlist_item) {
    foreach ($this->resolvers as $resolver) {
      $result = $resolver->resolve($wishlist_item);
      if ($result) {
        return $result;
      }
    }
    return NULL;
  }

}
