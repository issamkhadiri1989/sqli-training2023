<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Form\Type\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/{productSlug}', name: 'app_product', priority: 1)]
    #[Entity('product', options: ['mapping' => ['productSlug' => 'slug']])]
    public function index(Product $product): Response
    {
        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/check/{id}', name: 'app_product_check', methods: ['GET'])]
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
    public function add(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // @TODO persist & flush to database
            $var = $form->get('test')->getData();
            dump($var);
        }

        return $this->render(
            'product/add.html.twig',
            ['addForm' => $form->createView()]
        );
    }
}
