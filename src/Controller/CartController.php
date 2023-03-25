<?php

declare(strict_types=1);

namespace App\Controller;

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
}
