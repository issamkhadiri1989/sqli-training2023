<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Service\CartHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    public function __construct(private readonly CartHandler $cartHandler)
    {
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
    public function addToCart(Product $product): Response
    {
        // @TODO This function should let the user to add a product to the cart.
    }
}
