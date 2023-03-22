<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{slug}', name: 'app_category')]
    public function index(Category $category): Response
    {
        return $this->render('category/index.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * This controller shows a dummy list of categories. This list will be replaced by a database query.
     */
    public function categories(CategoryRepository $repository): Response
    {
        $categories = $repository->findAll();

        return $this->render('category/categories.html.twig', [
            'categories' => $categories,
        ]);
    }
}
