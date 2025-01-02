<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\Authorization;
use Symfony\Component\Mercure\Discovery;
use Symfony\Component\Routing\Attribute\Route;

class MercureController extends AbstractController
{
    #[Route('/discover', name: 'app_discover')]
    public function __invoke(
        Request $request,
        Discovery $discovery,
        Authorization $authorization,
    ): JsonResponse {
        /** @var User $user */
        $user = $this->getUser();

        $discovery->addLink($request);

        $authorization->setCookie(
            request: $request,
            subscribe: [
                "https://example.com/notifications/{$user->getId()}",
            ]
        );

        return new JsonResponse();
    }
}
