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
     * @param bool $useCriteria
     *
     * @return void
     */
    public function saveCartItem(CartItem $item, bool $useCriteria = false): void
    {
        $cart = $item->getCart();
        $product = $item->getProduct();
        $requestedQuantity = $item->getQuantity();

        //Check if the product exists already in the cart.
        $existingCartItem = $this->getExistingEntity($cart, $product, $useCriteria);

        // Saving data to database.
        $this->doSaveItem($existingCartItem ?? $item, $requestedQuantity);
    }

    /**
     * Performs writing to database.
     *
     * @param CartItem $entity
     *
     * @return void
     */
    private function doSaveItem(CartItem $entity, int $quantity): void
    {
        $isNewEntry = false;
        if ($entity->getId() === null) {
            $isNewEntry = true;
        } else {
            // an instance has been found so the quantity must be updated
            $entity->updateQuantity($quantity);
        }

        $this->repository->save($entity, $isNewEntry);
    }

    /**
     * Search for a CartItem line of the given product in the cart.
     *
     * @see Cart::filterByProduct()
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

    /**
     * This functions finds a Product in 2 ways: using the filter() function or the Criteria clause.
     *
     * @param Cart $cart
     * @param Product $product
     * @param bool $usingCriteria If true, we will use the Criteria in the entity class. False, using the ordinary filter()
     *
     * @return CartItem|null
     */
    private function getExistingEntity(Cart $cart, Product $product, bool $usingCriteria = false): ?CartItem
    {
        if (false === $usingCriteria) {
            return $cart->filterByProduct($product);
        }

        // the new function
        return $cart->findCartItemFor($product);
    }
}