<?php

declare(strict_types=1);

namespace App\CommandHandler;

use App\Command\RemoveReactionFromTargetCommand;
use App\Entity\User;
use App\Enum\Reaction\Target;
use App\Repository\ReactionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RemoveReactionFromTargetCommandHandler
{
    public function __construct(
        private ReactionRepository $reactionRepository,
        private UserRepository $userRepository,
    ) {
    }

    /**
     * @throws NonUniqueResultException
     */
    public function __invoke(RemoveReactionFromTargetCommand $command): void
    {
        /** @var User $author */
        $author = $this->userRepository->find($command->authorId);

        $targetEnum = match ($command->target) {
            'posts' => Target::POST,
            'comments' => Target::COMMENT,
        };

        $reaction = $this->reactionRepository->findByTargetAndAuthor(
            $targetEnum->value,
            $command->targetId,
            $author
        );

        if ($reaction) {
            $this->reactionRepository->remove($reaction);
        }
    }
}
