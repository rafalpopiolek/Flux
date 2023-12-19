<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\AddProfileType;
use App\Repository\ProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    public function __construct(
        private readonly ProfileRepository $profileRepository
    ) {
    }

    #[Route('/profile', name: 'app_profile')]
    public function show(): Response
    {
        return $this->render('profile/index.html.twig');
    }

    #[Route('/profile/create', name: 'app_profile_create')]
    public function createProfile(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $profile = $user->getProfile();

        $form = $this->createForm(AddProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->profileRepository->save($profile);

            $this->addFlash('success', 'Profile updated!');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/createProfileForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
