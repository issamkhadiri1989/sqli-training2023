<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\ContactType;
use App\Form\Type\RegisterType;
use App\Model\Contact;
use App\Repository\ProductRepository;
use App\Service\Account;
use App\Service\Mailer\MessageSender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private string $email;

    public function __construct(string $adminEmail)
    {
        $this->email = $adminEmail;
    }

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
    public function contact(Request $request, MessageSender $mailer): Response
    {
        $contact = new Contact();
        // Remember, we can also use an array to hold data not only objects.
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mailSent = $mailer->sendMail(
                $contact->getEmail(),
                $this->email,
                'Message from Contact form',
                'emails/contact.html.twig',
                ['contact' => $contact]
            );

            if (false === $mailSent) {
                $this->addFlash('danger', 'Your email has not been sent due to errors');
            } else {
                $this->addFlash('success', 'Thank you. Your message has been sent to the admin');
            }

            return $this->redirectToRoute('app_index');
        }

        return $this->render('index/contact_us.html.twig', ['contact_form' => $form->createView()]);
    }

    #[Route(path: '/create-account', name: 'app_register')]
    public function register(Request $request, Account $subscription): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $subscription->registerSubscription($user);
            $this->addFlash('success', 'Your registration has been taken saved');

            return $this->redirectToRoute('app_index');
        }

        return $this->render('index/register.html.twig', [
            'registration' => $form->createView(),
        ]);
    }
}
