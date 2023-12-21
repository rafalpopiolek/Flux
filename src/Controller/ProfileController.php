<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\AddProfileType;
use App\Repository\ProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfileController extends AbstractController
{
    public function __construct(
        private readonly ProfileRepository $profileRepository,
        private readonly SluggerInterface $slugger,
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
            /** @var UploadedFile $profilePicture */
            $profilePicture = $form->get('profilePicture')->getData();

            if ($profilePicture) {
                $originalName = pathinfo($profilePicture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalName);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $profilePicture->guessExtension();

                try {
                    $profilePicture->move(
                        directory: $this->getParameter('user_storage'),
                        name: $newFilename
                    );
                } catch (FileException) {
                    $this->addFlash('error', 'Something went wrong while uploading your profile picture!');
                }

                $profile->setProfilePicture($newFilename);
            }

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
