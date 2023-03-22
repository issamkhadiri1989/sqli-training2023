<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Product;
use App\Enum\CartStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * This service will handle all operations that occur on the cart.
 */
class CartHandler
{
    private SessionInterface $session;

    public function __construct(
        private readonly RequestStack $request,
        private readonly EntityManagerInterface $manager
    ) {
        $this->session = $this->request->getSession();
    }

    /**
     * This function will add the given Product to the cart with the specific quantity.
     * This function does not know how the process is going to be because it is handled by the CartItemManager.
     *
     * @param Product $product
     *
     * @param int $quantity
     *
     * @return void
     */
    public function addToCart(Product $product, int $quantity)
    {
        // @TODO call the service CartItemManager to add products to the  current cart instance.
    }

    /**
     * Gets an instance of the Cart.
     * It will create and get the newly created instance when no id found in the session.
     *
     * @return Cart
     *
     * @throws \Exception
     */
    public function getCartInstance(): Cart
    {
        if ($this->session->has('cart_id')) {
            $cartId = $this->session->get('cart_id');

            return $this->doRetrieveRefreshedCartInstance($cartId);
        }

        return $this->createCartInstance();
    }

    /**
     * Saves the cart instance into the database and its id to session.
     *
     * @return Cart
     */
    private function createCartInstance(): Cart
    {
        $cart = $this->doCreateNewCart();

        $this->session->set('cart_id', $cart->getId());

        return $cart;
    }

    /**
     * Creates and saves an instance of Cart.
     *
     * @return Cart
     */
    private function doCreateNewCart(): Cart
    {
        $cart = new Cart();
        $cart->setCreatedAt(new \DateTimeImmutable())
            ->setStatus(CartStatus::OPENED);
        // Persist to database
        $this->manager->persist($cart);
        $this->manager->flush();

        return $cart;
    }

    /**
     * Gets a refreshed instance of the Cart by its id.
     *
     * @param int $id
     *
     * @return Cart
     *
     * @throws \Exception
     */
    private function doRetrieveRefreshedCartInstance(int $id): Cart
    {
        $repository = $this->manager->getRepository(Cart::class);
        /** @var ?Cart $cart */
        $cart = $repository->find($id);
        if (null === $cart) {
            throw new \Exception();
        }

        return $cart;
    }
}
