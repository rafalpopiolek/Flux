<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Notification;
use App\Enum\Notification\Type;
use App\Event\CommentHasBeenCreatedEvent;
use App\Repository\CommentRepository;
use App\Repository\NotificationRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SendNewCommentNotification
{
    public function __construct(
        private PostRepository $postRepository,
        private CommentRepository $commentRepository,
        private NotificationRepository $notificationRepository,
        private HubInterface $hub,
    ) {
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function __invoke(CommentHasBeenCreatedEvent $event): void
    {
        $post = $this->postRepository->find($event->postId);
        $comment = $this->commentRepository->find($event->commentId);

        $notification = new Notification(
            sender: $comment->getAuthor(),
            recipient: $post->getAuthor(),
            type: Type::NEW_COMMENT,
            data: [
                'description' => $comment->getAuthor()->getFullName() . ' commented on your post!',
            ],
        );

        $this->notificationRepository->save($notification);

        $update = new Update(
            topics: sprintf('https://example.com/notifications/%s', $post->getAuthor()->getId()),
            data: json_encode([
                'content' => $comment->getAuthor()->getFullName() . ' commented on your post!',
                'unreadNotifications' => $this->notificationRepository->countUnreadByUser($post->getAuthor()),
            ]),
            private: true
        );

        $this->hub->publish($update);
    }
}
