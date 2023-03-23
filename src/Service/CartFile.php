<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Product;

class CartFile implements CartInterface
{

    public function addToCart(Product $product, int $quantity): void
    {
        // TODO: Implement addToCart() method.
    }

    public function removeFromCart(Product $product): void
    {
        // TODO: Implement removeFromCart() method.
    }

    public function getCartInstance(): Cart
    {
        // TODO: Implement getCartInstance() method.
        return new Cart();
    }

    public function getProduct(int $id): Product
    {
        // TODO: Implement getProduct() method.
        return new Product();
    }

    public function clearCart(): void
    {
        // TODO: Implement clearCart() method.
    }
}