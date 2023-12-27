<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\NotificationRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NotificationController extends AbstractController
{
    public function __construct(
        private readonly NotificationRepository $notificationRepository
    ) {
    }

    #[Route('/notification', name: 'app_notification')]
    public function show(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('notification/index.html.twig', [
            'notifications' => $this->notificationRepository->getNotifications($user->getId()),
        ]);
    }

    #[Route('/notification/mark-all-as-read', name: 'app_notification_mark_all_as_read', methods: ['POST'])]
    public function markAllAsRead(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $this->notificationRepository->markAllAsRead($user->getId(), new DateTimeImmutable());

        return $this->redirectToRoute('app_notification');
    }

    #[Route('/notification/remove-all', name: 'app_notification_remove_all', methods: ['POST'])]
    public function removeAll(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $this->notificationRepository->removeAll($user->getId());

        return $this->redirectToRoute('app_notification');
    }
}
