<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Product;
use App\Enum\CartStatus;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * This service will persist data into a text file.
 * It is juste an example of how to inject services via Abstraction @see config/services.yaml
 */
class CartFile implements CartInterface
{
    private SessionInterface $session;

    /**
     * @param string $dataPath The directory path where the carts will be stored
     */
    public function __construct(private readonly string $dataPath, private readonly RequestStack $requestStack)
    {

    }

    public function addToCart(Product $product, int $quantity): void
    {
        // TODO: Implement addToCart() method for example write lines in the given file
    }

    public function removeFromCart(Product $product): void
    {
        // TODO: Implement removeFromCart() method for example remove lines from file
    }

    /**
     * Must create a file where the cart will be stored.
     *
     * @return Cart
     *
     * @throws \Exception
     */
    public function getCartInstance(): Cart
    {
        if ($this->requestStack->getSession()->has('file_cart_id')) {
            // get the content from the file
            $cart = $this->getCartInfoFromFile();
        } else {
            $cart = new Cart();
            $cart->setStatus(CartStatus::OPENED)
                ->setCreatedAt(new \DateTimeImmutable());

            $this->createCartFile($cart);
        }


        return $cart;
    }

    /**
     * Reads Products from the Cart file.
     *
     * @param int $id
     *
     * @return Product
     */
    public function getProduct(int $id): Product
    {
        // TODO: Implement getProduct() method so that can get a Product instance from the file
        return new Product();
    }

    /**
     * Truncates the content of the Cart file.
     *
     * @return void
     */
    public function clearCart(): void
    {
        // TODO: Implement clearCart() method: truncate the content of the file
    }

    /**
     * Creates a dummy file that will contain the cart content.
     *
     * @param Cart $cart
     *
     * @return void
     */
    private function createCartFile(Cart $cart): void
    {
        $uid = \uniqid();
        $this->requestStack->getSession()->set('file_cart_id', $uid);
        $filePattern = \sprintf('%s/cart-%s.txt', $this->dataPath, $uid);
        $fid = \fopen($filePattern, 'a');
        \fprintf($fid, "ID=%s\tCREATE=%s\tSTATUS=%s\n", $uid, $cart->getCreatedAt()->format('Y-m-d\Th:i:s'), $cart->getStatus()->value);
        \fclose($fid);
    }

    /**
     * A simple implementation of getting cart info from file.
     *
     * @return Cart
     *
     * @throws \Exception
     */
    private function getCartInfoFromFile(): Cart
    {
        $uid = $this->requestStack->getSession()->get('file_cart_id');
        $filePattern = \sprintf('%s/cart-%s.txt', $this->dataPath, $uid);
        if (\file_exists($filePattern)) {
            // the cart file has been found and the cart instance can be created
            $cart = new Cart();
            $this->parseFileToCart($cart, $filePattern);

            return $cart;
        }

        throw new \Exception();
    }

    /**
     * A simple implementation of parsing file to get cart info.
     *
     * @param Cart $cart
     * @param string $fileName
     *
     * @return void
     *
     * @throws \Exception
     */
    private function parseFileToCart(Cart $cart, string $fileName): void
    {
        $fid = \fopen($fileName, 'r');
        \fscanf($fid, "ID=%s\tCREATE=%s\tSTATUS=%s\n", $uid, $createAt, $status);
        \fclose($fid);

        $cart->setCreatedAt(new \DateTimeImmutable($createAt))
            ->setStatus(CartStatus::from($status));

        // set cart id to 1 using the ReflexionClass API
        $reflexion = new \ReflectionClass($cart);
        $reflexion->getProperty('id')
            ->setValue($cart, 1);
    }

    public function updateCart(Cart $cart): void
    {
        // TODO: Implement updateCart() method.
    }
}