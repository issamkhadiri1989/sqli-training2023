<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    /**
     * This controller shows a dummy list of categories. This list will be replaced by a database query.
     */
    public function categories(int $limit = 0): Response
    {
        $categories = [
            'Women Clothes' => 10,
            'Technologies' => 30,
            'Spring' => 5,
        ];

        return $this->render('category/categories.html.twig', [
            'categories' => $categories,
        ]);
    }
}
