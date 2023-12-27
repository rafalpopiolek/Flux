<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Event\UserHasBeenFollowedEvent;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class FollowerController extends AbstractController
{
    public function __construct(
        private readonly ManagerRegistry $managerRegistry,
        private readonly MessageBusInterface $eventBus,
    ) {
    }

    #[Route('/follow/{userToFollow}', name: 'app_follow_user')]
    public function follow(Request $request, User $userToFollow): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($currentUser->getId() === $userToFollow->getId()) {
            throw new LogicException('You cannot follow yourself');
        }

        $currentUser->follow($userToFollow);

        $this->eventBus->dispatch(
            new UserHasBeenFollowedEvent(
                follower: $currentUser->getId(),
                followee: $userToFollow->getId(),
            )
        );

        $this->managerRegistry->getManager()->flush();

        $this->addFlash('success', 'You are now following ' . $userToFollow->getFullName());

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/unfollow/{userToUnfollow}', name: 'app_unfollow_user')]
    public function unfollow(Request $request, User $userToUnfollow): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($currentUser->getId() === $userToUnfollow->getId()) {
            throw new LogicException('You cannot unfollow yourself');
        }

        $currentUser->unfollow($userToUnfollow);

        $this->managerRegistry->getManager()->flush();

        $this->addFlash('success', 'You are no longer following ' . $userToUnfollow->getFullName());

        return $this->redirect($request->headers->get('referer'));
    }
}
