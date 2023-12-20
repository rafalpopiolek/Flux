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

    #[Route('/profile/{user}', name: 'app_profile', requirements: [
        'user' => '\d+',
    ])]
    public function show(User $user): Response
    {
        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile/update', name: 'app_profile_update')]
    public function updateProfile(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $profile = $user->getProfile();

        $form = $this->createForm(AddProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->profileRepository->save($profile);

            $this->addFlash('success', 'Profile updated!');

            return $this->redirectToRoute('app_profile', [
                'user' => $user->getId(),
            ]);
        }

        return $this->render('profile/createProfileForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
