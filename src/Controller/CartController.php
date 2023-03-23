<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Repository\CartItemRepository;
use App\Service\CartInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private readonly CartInterface $cartHandler;

    /**
     * This will inject the default service CartHandler.
     * If you want to use the CartFile, rename the argument to $cartFile. @see config/services.yaml file.
     *
     * @param CartInterface $handler
     */
    public function __construct(CartInterface $handler)
    {
        $this->cartHandler = $handler;
    }

    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        $cart = $this->cartHandler->getCartInstance();

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route(path: '/checkout', name: 'app_checkout')]
    public function checkout(): Response
    {
        return $this->render('cart/checkout.html.twig');
    }

    #[Route('/cart/add/{id}', name: 'add_to_cart')]
    public function addToCart(Product $product, CartItemRepository $repository): Response
    {
        // @TODO: check if the product exists in the cart

        // @TODO: increment the quantity when the product already exists

        $cart = $this->cartHandler->getCartInstance();
        $quantity = 1;
        $items = $cart->getCartItems();
        $found = false;
        foreach ($items as $cartItem) {
            if ($cartItem->getProduct() === $product) {
                $previousQuantity = $cartItem->getQuantity();
                $newQuantity = $previousQuantity + $quantity;
                $cartItem->setQuantity($newQuantity);
                $found = true;
                break;
            }
        }

        // @TODO: Add the product to the cart if not exist
        if (false === $found) {
            $cartItem = new CartItem();
            $cartItem->setQuantity($quantity)
                ->setCart($cart)
                ->setProduct($product);
        }

        // @TODO: save to database
        $repository->save($cartItem, false === $found);

        // @TODO: redirect the user to the cart page
        $this->addFlash('success', 'Product added to cart');

        return $this->redirectToRoute('app_cart');
    }
}
