<?php

namespace Drupal\commerce_wishlist\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Defines the interface for wishlist types.
 */
interface WishlistTypeInterface extends ConfigEntityInterface {

  /**
   * Gets whether the wishlist item type allows anonymous wishlists.
   *
   * @return bool
   *   TRUE if anonymous wishlists are allowed, FALSE otherwise.
   */
  public function isAllowAnonymous();

  /**
   * Sets whether the wishlist item type allows anonymous wishlists.
   *
   * @param bool $allow_anonymous
   *   Whether the wishlist item type allows anonymous wishlists.
   *
   * @return $this
   */
  public function setAllowAnonymous($allow_anonymous);

  /**
   * Gets whether the wishlist item type allows users to have multiple wishlists
   * of the same type.
   *
   * @return bool
   *   TRUE if multiple wishlists are allowed, FALSE otherwise.
   */
  public function isAllowMultiple();

  /**
   * Sets whether the wishlist item type allows users to have multiple wishlists
   * of the same type.
   *
   * @param bool $allow_multiple
   *   Whether the wishlist item type allows users to have multiple wishlists of
   *   the same type.
   *
   * @return $this
   */
  public function setAllowMultiple($allow_multiple);

  /**
   * Gets whether the wishlist item type allows public wishlists.
   *
   * @return bool
   *   TRUE if public wishlists are allowed, FALSE otherwise.
   */
  public function isAllowPublic();

  /**
   * Sets whether the wishlist item type allows public wishlists.
   *
   * @param bool $allow_public
   *   Whether the wishlist item type allows public wishlists.
   *
   * @return $this
   */
  public function setAllowPublic($allow_public);

  /**
   * Gets the Views ID of the wishlist form view to use.
   *
   * @return string
   *   The Views ID of the wishlist form view to use.
   */
  public function getWishlistFormView();

  /**
   * Sets the Views ID of the wishlist form view to use.
   *
   * @param string $wishlist_form_view
   *   The Views ID of the wishlist form view to use.
   *
   * @return $this
   */
  public function setWishlistFormView($wishlist_form_view);

}
