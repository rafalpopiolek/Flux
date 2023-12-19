<?php

declare(strict_types=1);

namespace App\CommandHandler;

use App\Command\SetReactionToTargetCommand;
use App\Entity\Reaction;
use App\Entity\User;
use App\Enum\Reaction\Target;
use App\Enum\Reaction\Type;
use App\Repository\ReactionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SetReactionToTargetCommandHandler
{
    public function __construct(
        private ReactionRepository $reactionRepository,
        private UserRepository $userRepository,
    ) {
    }

    /**
     * @throws NonUniqueResultException
     */
    public function __invoke(SetReactionToTargetCommand $command): void
    {
        /** @var User $author */
        $author = $this->userRepository->find($command->authorId);

        $targetEnum = match ($command->target) {
            'posts' => Target::POST,
            'comments' => Target::COMMENT,
        };

        $reactionType = match ($command->reactionType) {
            'like' => Type::LIKE,
            'dislike' => Type::DISLIKE,
            'love' => Type::LOVE,
            'laugh' => Type::LAUGH,
            'sad' => Type::SAD,
        };

        $oldReaction = $this->reactionRepository->findByTargetAndAuthor(
            $targetEnum->value,
            $command->targetId,
            $author
        );

        if ($oldReaction === null) {
            $reaction = new Reaction();
            $reaction->setAuthor($author);
            $reaction->setType($reactionType);
            $reaction->setTargetType($targetEnum);
            $reaction->setTargetId($command->targetId);

            $this->reactionRepository->save($reaction);
        }

        if ($oldReaction && $reactionType->name !== $oldReaction->getType()->name) {
            $oldReaction->setType($reactionType);

            $this->reactionRepository->save($oldReaction);
        }
    }
}
