<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Type\DummyAdminType;
use App\Model\Admin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DummyController extends AbstractController
{
    #[Route("/dummy")]
    public function dummy(Request $request): Response
    {
        /*
         * Uncomment this if you don't want to use inheritance while validating form.
         */
        /*$user = new User();*/

        /*
         * Use Admin instance to create the form so that you can see how to validate forms on objects with inheritance
         */
        $user = new Admin();
        $form = $this->createForm(DummyAdminType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('dummy/index.html.twig', ['form' => $form->createView()]);
    }
}
