<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\ProfileType;
use App\Form\Type\ResetPasswordType;
use App\Model\ChangePassword;
use App\Repository\UserRepository;
use App\Service\Account;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, UserRepository $repository): Response
    {
        // get the current User.
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($user, true);
            $this->addFlash('success', 'Your profile updated successfully.');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/index.html.twig', [
            'profile' => $form->createView(),
        ]);
    }

    #[Route(path: '/reset-password', name: 'app_reset_password')]
    public function resetPassword(Request $request, Account $manager): Response
    {
        $changePassword = new ChangePassword();
        $form = $this->createForm(ResetPasswordType::class, $changePassword);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->updateAccountPassword($changePassword);
            $this->addFlash('success', 'Your profile updated successfully.');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/reset_password.html.twig', ['form' => $form->createView()]);
    }
}
