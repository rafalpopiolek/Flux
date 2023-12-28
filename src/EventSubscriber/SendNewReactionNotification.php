<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Notification;
use App\Enum\Notification\Type;
use App\Enum\Reaction\Target;
use App\Event\ReactionHasBeenSetEvent;
use App\Repository\CommentRepository;
use App\Repository\NotificationRepository;
use App\Repository\PostRepository;
use App\Repository\ReactionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SendNewReactionNotification
{
    public function __construct(
        private ReactionRepository $reactionRepository,
        private NotificationRepository $notificationRepository,
        private UserRepository $userRepository,
        private PostRepository $postRepository,
        private CommentRepository $commentRepository,
        private HubInterface $hub,
    ) {
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function __invoke(ReactionHasBeenSetEvent $event): void
    {
        $reactionAuthor = $this->userRepository->find($event->authorId);

        $reaction = $this->reactionRepository->findByTargetAndAuthor(
            $event->target,
            $event->targetId,
            $reactionAuthor
        );

        $recipient = match ($reaction->getTargetType()) {
            Target::POST => $this->postRepository->find($event->targetId),
            Target::COMMENT => $this->commentRepository->find($event->targetId)
        };

        $notification = new Notification(
            sender: $reaction->getAuthor(),
            recipient: $recipient->getAuthor(),
            type: Type::NEW_REACTION,
            data: [
                'description' => $reaction->getAuthor()->getFullName() . ' reacted to your post!',
            ],
        );

        $this->notificationRepository->save($notification);

        $update = new Update(
            topics: sprintf('https://example.com/notifications/%s', $recipient->getAuthor()->getId()),
            data: json_encode([
                'content' => $reaction->getAuthor()->getFullName() . ' reacted to your post!',
                'unreadNotifications' => $this->notificationRepository->countUnreadByUser($recipient->getAuthor()),
            ]),
            private: true
        );

        $this->hub->publish($update);
    }
}
