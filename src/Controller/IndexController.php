<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ProductRepository $repository): Response
    {
        /*$products = $repository->findBy(limit: 10, criteria: []);*/
        $products = $repository->findAll();

        return $this->render('index/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route(path: '/contact-us', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('index/contact_us.html.twig');
    }
}
