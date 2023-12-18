<?php

declare(strict_types=1);

namespace App\Controller;

use App\Command\SetReactionToPostCommand;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ReactionController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $commandBus
    ) {
    }

    #[Route('/reaction/{target}/{targetId}', name: 'app_reaction', methods: ['POST'])]
    public function index(Request $request, string $target, int $targetId): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $this->commandBus->dispatch(
            new SetReactionToPostCommand(
                authorId: $user->getId(),
                target: $target,
                targetId: $targetId,
                reactionType: $request->getPayload()->get('type')
            )
        );

        return new JsonResponse();
    }
}
