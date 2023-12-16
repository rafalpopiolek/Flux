<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PostRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly PostRepository $postRepository,
    ) {
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        $currentPage = $request->query->getInt('page', 1);
        $maxPerPage = $request->query->getInt('size', 7);

        $adapter = new QueryAdapter($this->postRepository->createPostListQueryBuilder());
        $pagerFanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            adapter: $adapter,
            currentPage: $currentPage,
            maxPerPage: $maxPerPage
        );

        return $this->render('home/index.html.twig', [
            'pager' => $pagerFanta,
        ]);
    }
}
