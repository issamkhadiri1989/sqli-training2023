<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Product;

interface CartInterface
{
    public function addToCart(Product $product, int $quantity): void;

    public function removeFromCart(Product $product): void;

    public function getCartInstance(): Cart;

    public function getProduct(int $id): Product;

    public function clearCart(): void;
}
