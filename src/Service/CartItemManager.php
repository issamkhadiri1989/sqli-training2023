<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Repository\CartItemRepository;

/**
 * This service will handle everything related to the CartItem.
 */
class CartItemManager
{
    public function __construct(private readonly CartItemRepository $repository)
    {
    }

    /**
     * Builds a new instance of CartItem.
     *
     * @param Cart $cart
     * @param Product $product
     * @param int $quantity
     *
     * @return CartItem
     */
    public function createCartItemInstance(Cart $cart, Product $product, int $quantity): CartItem
    {
        return (new CartItem())
            ->setProduct($product)
            ->setCart($cart)
            ->setQuantity($quantity);
    }

    /**
     * Saves the item instance into the database.
     *
     * @param CartItem $item
     *
     * @return void
     */
    public function saveNewCartItem(CartItem $item): void
    {
        $cart = $item->getCart();
        $product = $item->getProduct();

        //Check if the product exists already in the cart.
        $existingCartItem = $this->findExistingProductInCart($cart, $product);
        if (null === $existingCartItem) {
            // no product found in the cart
            $this->repository->save($item, true);
        } else {
            // an instance has been found so the quantity must be updated
            $existingCartItem->updateQuantity($item->getQuantity());
            $this->repository->save($existingCartItem);
        }
    }

    /**
     * Search for a CartItem line of the given product in the cart.
     *
     * @param Cart $cart
     * @param Product $product
     *
     * @return ?CartItem
     */
    private function findExistingProductInCart(Cart $cart, Product $product): ?CartItem
    {
        $items = $cart->getCartItems()->filter(function (CartItem $item) use ($product) {
            return $product === $item->getProduct();
        });

        return ($items->isEmpty() === true) ? null : $items->first();
    }
}