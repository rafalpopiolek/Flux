<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Enum\Post\Status;
use App\Form\CreatePostFormType;
use App\Trait\Form\FormResponseStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    use FormResponseStatus;

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
            $post->setStatus(Status::PUBLISHED);

            $this->entityManager->persist($post);
            $this->entityManager->flush();

            $this->addFlash('success', 'Post created successfully!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('post/index.html.twig', [
            'form' => $form->createView(),
        ], new Response(null, $this->setResponseStatus($form)));
    }
}
