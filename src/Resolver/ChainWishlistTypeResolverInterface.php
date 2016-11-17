<?php

namespace Drupal\commerce_wishlist\Resolver;

/**
 * Runs the added resolvers one by one until one of them returns the wishlist type.
 *
 * Each resolver in the chain can be another chain, which is why this interface
 * extends the wishlist type resolver one.
 */
interface ChainWishlistTypeResolverInterface extends WishlistTypeResolverInterface {

  /**
   * Adds a resolver.
   *
   * @param \Drupal\commerce_wishlist\Resolver\WishlistTypeResolverInterface $resolver
   *   The resolver.
   */
  public function addResolver(WishlistTypeResolverInterface $resolver);

  /**
   * Gets all added resolvers.
   *
   * @return \Drupal\commerce_wishlist\Resolver\WishlistTypeResolverInterface[]
   *   The resolvers.
   */
  public function getResolvers();

}
