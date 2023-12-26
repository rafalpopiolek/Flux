<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Notification;
use App\Enum\Notification\Type;
use App\Event\UserHasBeenFollowedEvent;
use App\Exception\UserNotFoundException;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SendNewFollowerNotification
{
    public function __construct(
        private UserRepository $userRepository,
        private NotificationRepository $notificationRepository,
    ) {
    }

    public function __invoke(UserHasBeenFollowedEvent $event): void
    {
        $follower = $this->userRepository->find($event->follower);
        $followee = $this->userRepository->find($event->followee);

        if ($follower === null || $followee === null) {
            throw new UserNotFoundException();
        }

        $notification = new Notification(
            sender: $follower,
            recipient: $followee,
            type: Type::NEW_FOLLOWER,
            data: [
                'description' => $follower->getFullName() . ' is now following you!',
                'occurredAt' => $event->occurredAt->format('d M Y H:i:s'),
            ]
        );

        $this->notificationRepository->save($notification);
    }
}
