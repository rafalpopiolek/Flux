<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Event\CommentHasBeenCreatedEvent;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Trait\Form\FormResponseStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    use FormResponseStatus;

    public function __construct(
        private readonly CommentRepository $commentRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly PostRepository $postRepository,
        private readonly MessageBusInterface $eventBus,
    ) {
    }

    #[Route('/comments/{postId}', name: 'app_comment', methods: ['GET', 'POST'])]
    public function index(Request $request, int $postId): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setPost($this->postRepository->find($postId));
            $comment->setContent($form->get('content')->getData());

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            $this->eventBus->dispatch(
                new CommentHasBeenCreatedEvent(
                    commentId: $comment->getId(),
                    postId: $postId,
                )
            );

            $this->addFlash('success', 'Comment created successfully!');

            return $this->redirectToRoute('app_comment', [
                'postId' => $postId,
            ]);
        }

        /** @var User $user */
        $user = $this->getUser();
        $comments = $this->commentRepository->getPostComments($postId, $user->getId());

        return $this->render('comment/index.html.twig', [
            'form' => $form->createView(),
            'comments' => $comments,
            'post' => $this->postRepository->find($postId),
        ], new Response(null, $this->setResponseStatus($form)));
    }
}
