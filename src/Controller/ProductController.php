<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Form\Type\CartItemType;
use App\Form\Type\ProductType;
use App\Repository\ProductRepository;
use App\Service\CartInterface;
use App\Service\CartItemManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    public function __construct(private readonly CartInterface $cartHandler)
    {
    }

    #[Route('/{productSlug}', name: 'app_product', priority: 1)]
    #[Entity('product', options: ['mapping' => ['productSlug' => 'slug']])]
    public function index(Product $product, Request $request, CartItemManager $builder): Response
    {
        $cartInstance = $this->cartHandler->getCartInstance();
        $cartItem = $builder->createCartItemInstance($cartInstance, $product, 1);
        $form = $this->createForm(CartItemType::class, $cartItem);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Do something with the CartItem instance like persisting it into the database
            // Pass true or false to the second argument of this function so than you can use one of the methods to filter:
            // if true is passed, then you can use the Criteria condition using Doctrine in the Cart entity @see Cart::findCartItemFor
            // if false is passed, then you will use the ordinary filter() method.
            $builder->saveCartItem($cartItem, true/*, true or false*/);
            $this->addFlash('success', 'Product added successfully to the cart');

            return $this->redirectToRoute('app_cart');
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'addToCartForm' => $form->createView(),
        ]);
    }

    #[Route('/check/{id}', name: 'app_product_check', methods: ['GET'], options: ['expose' => true])]
    public function checkAvailability(Product $product, Request $request): Response
    {
        // get a query parameter from the URL
        /*$page = $request->query->get('page');*/

        // get a post parameter from a post request
        /*$page = $request->request->get('page'); */

        if ($request->isXmlHttpRequest()) {
            // ....
            $quantity = \filter_var($request->query->get('qty'), \FILTER_VALIDATE_INT);

            if ($product->getQuantity() < $quantity) {
//                return new JsonResponse(['message' => 'Out of Store']);
                $parameters = [
                    'key' => 'danger',
                    'message' => 'Out of store',
                ];
            } else {
//                return new JsonResponse(['message' => 'In store']);
                $parameters = [
                    'key' => 'success',
                    'message' => 'In of store',
                ];
            }

            return $this->render('partials/_product_availability.html.twig', $parameters);
        }

        throw new BadRequestException('This endpoint must be called with ajax only');
    }

    #[Route('/add', name: 'app_add_product', priority: 2)]
    public function add(Request $request, ProductRepository $repository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $var = $form->get('test')->getData();
            $repository->save($product, true);
            $this->addFlash('success', 'Product saved successfully');

            return $this->redirectToRoute('app_add_product');
        }

        return $this->render(
            'product/add.html.twig',
            ['addForm' => $form->createView()]
        );
    }
}
