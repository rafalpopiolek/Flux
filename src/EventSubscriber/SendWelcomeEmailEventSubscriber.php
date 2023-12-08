<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\UserHasBeenCreatedEvent;
use App\Exception\UserNotFoundException;
use App\Repository\UserRepository;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
final class SendWelcomeEmailEventSubscriber
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly MailerInterface $mailer,
    ) {
    }

    #[NoReturn]
    public function __invoke(UserHasBeenCreatedEvent $event): void
    {
        $user = $this->userRepository->find($event->getId());

        if ($user === null) {
            throw new UserNotFoundException(
                message: sprintf('User with id "%d" not found', $event->getId()),
                code: Response::HTTP_BAD_REQUEST
            );
        }

        $email = (new Email())
            ->from($_ENV['APP_EMAIL'])
            ->to($user->getEmail())
            ->subject('Welcome to the Flux Application!')
            ->text('We are glad you are here!');

        $this->mailer->send($email);
    }
}
