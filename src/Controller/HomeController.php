<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly PostRepository $postRepository,
    ) {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $posts = $this->postRepository->getPosts();

        return $this->render('home/index.html.twig', [
            'posts' => $posts,
        ]);
    }
}
