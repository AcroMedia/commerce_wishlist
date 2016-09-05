<?php

namespace Drupal\wishlist_template\Event;

use Drupal\wishlist_template\Entity\WishlistTemplateInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Defines the wishlist template event.
 *
 * @see \Drupal\wishlist_template\Event\WishlistTemplateEvents
 */
class WishlistTemplateEvent extends Event {

  /**
   * The wishlist template.
   *
   * @var \Drupal\wishlist_template\Entity\WishlistTemplateInterface
   */
  protected $wishlist_template;

  /**
   * Constructs a new WishlistTemplateEvent.
   *
   * @param \Drupal\wishlist_template\Entity\WishlistTemplateInterface $wishlist_template
   *   The wishlist template.
   */
  public function __construct(WishlistTemplateInterface $wishlist_template) {
    $this->wishlist_template = $wishlist;
  }

  /**
   * Gets the wishlist template.
   *
   * @return \Drupal\wishlist_template\Entity\WishlistTemplateInterface
   *   The wishlist template.
   */
  public function getWishlisttemplate() {
    return $this->wishlist_template;
  }

}
