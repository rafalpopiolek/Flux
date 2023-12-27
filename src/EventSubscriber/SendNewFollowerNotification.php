<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Notification;
use App\Enum\Notification\Type;
use App\Event\UserHasBeenFollowedEvent;
use App\Exception\UserNotFoundException;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SendNewFollowerNotification
{
    public function __construct(
        private UserRepository $userRepository,
        private NotificationRepository $notificationRepository,
        private HubInterface $hub,
    ) {
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
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
            ],
        );

        $this->notificationRepository->save($notification);

        $update = new Update(
            topics: sprintf('https://example.com/notifications/%s', $followee->getId()),
            data: json_encode([
                'content' => $follower->getFullName() . ' is now following you!',
                'unreadNotifications' => $this->notificationRepository->countUnreadByUser($followee),
            ]),
            private: true
        );

        $this->hub->publish($update);
    }
}
