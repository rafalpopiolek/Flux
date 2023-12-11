<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Form\CreatePostFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route(path: '/posts', name: 'app_post', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(CreatePostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuthor($this->getUser());
            $post->setContent($form->get('content')->getData());
            $post->setType($form->get('type')->getData());
            $post->setStatus($form->get('status')->getData());

            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        $status = match ($form->isSubmitted() && ! $form->isValid()) {
            true => Response::HTTP_UNPROCESSABLE_ENTITY,
            default => Response::HTTP_OK,
        };

        return $this->render('post/index.html.twig', [
            'form' => $form->createView(),
        ], new Response(null, $status));
    }
}
