# Commerce Wishlist 8.x-3.x

Provides the ability for customers to add products to a list other than a cart.

## Alpha 1 Scope

Architecturally, the third version of Commerce Wishlist for Drupal 7 was converted to using Orders as the entity that is
called a "Wishlist." After discussion with Nick Vahalik and Matt Glaman, it was decided that the Drupal 8 port of the
Wishlist module would make the same architectural decision: Wishlists will be orders.

A special order type will be used to store the order items that track product variationss on the list. The first
iteration will closely model the Add-to-cart workflow with exceptions made to be able to co-exist with the add-to-cart
button.

The architectural decision to use order items allows us the opportunity in the future to allow administrators to add any
kind of customer-facing field to the wishlist item. This would allow for wishlist notes, links to external products, 
prioritization, anything you can imagine saving next to a desired product. **Note**: While fields may be added to order
items, the ability for the customers to create/modify those fields is likely not available quite yet.

### User Stories

> As a _(type of user)_, I can _(some goal)_ so that _(some reason)_.

* **Customers**
  * As a **customer**, I can add a product to a wishlist.
  * As a **customer**, I can remove a product from a wishlist.
  * As a **customer**, I can change the quantity of a product on a wishlist.
  * As a **customer**, I can add a product to my cart from my wislist.
  * As a **customer**, I can move a product from my cart to my wishlist.
  * As a **customer**, I can view my wishlist.
    * A wishlist MAY be accessed by clicking a link from the user's account page.
* **Administrators**
  * As an **administrator**, I can control the position of the "Add to wishlist" button.
    * The wishlist settings page MUST have a configurable weight integer.
    * The weight integer MUST be used _globally_ to position the button above or below other form elements.
  * As an **administrator**, I can choose to display the "Add to wishlist" as a button or an AJAX link.
    * The button MUST reload the page by default.
    * The AJAX link MUST accomplish the same task without a page reload.
  * As an **administrator**, I can add a "Move to wishlist" button to the cart view. 

## Backlogged User Stories

* **Anonymous Users**
  * As an **anonymous user**, I can add a product to a wishlist.
  * As an **anonymous user**, I can remove a product from a wishlist.
  * As an **anonymous user**, I can change the quantity of a product on a wishlist.
  * As an **anonymous user**, I can add a product to my cart from my wislist.
  * As an **anonymous user**, I can move items from my cart to my wishlist.
  * As an **anonymous user**, I can register and my wishlist will be saved to my account.
  * As an **anonymous user**, I can login and my wishlist will be saved to my account.
* **Customers**
  * As a **customer**, I can add or delete information stored in order items on my wishlist.
  * As a **customer**, I can view a list of my wishlists by accessing a menu link.
  * As a **customer**, I can create, update, and delete wishlists.
    * A customer MUST have a "default" wishlist.
    * A wishlist MUST be created if none exists when adding the first product to a wishlist.
    * A customer MAY provide a wishlist title.
  * As a **customer**, I can view a list of wishlists on my account page.
  * As a **customer**, I can select one of many wishlists before I click "Add to wishlist."
  * As a **customer**, I can create a new wishlist while saving a product to it.
  * As a **customer**, I can move products between wishlists.
  * As a **customer**, I can share a wishlist.
    * Sharing MAY be accessed with an unlisted url once a shared status has been set.
    * Limited sharing with a passcode MAY be enabled.
  * As a **customer**, I will see products from my wishlist(s) in a block underneath the cart.
* **Administrators**
  * As an **administrator**, I can mark order item fields to be included as customer-editable on wishlists.
  * As an **administrator**, I can choose which order types are treated as wishlists.
  * As an **administrator**, I can add a link to the menu system that links to a customer's wishlist.
    * The link MUST be used as a menu link token in the form of `<wishlist-dashboard>` 
    * The link MUST disappear if the user is not allowed to have a wishlist.
