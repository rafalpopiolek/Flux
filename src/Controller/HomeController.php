<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PostRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly PostRepository $postRepository,
    ) {
    }

    #[Route('/', name: 'app_home')]
    public function index(
        Request $request,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $size = 5,
    ): Response {
        $adapter = new QueryAdapter($this->postRepository->createPostListQueryBuilder());
        $pagerFanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            adapter: $adapter,
            currentPage: $page,
            maxPerPage: $size
        );

        return $this->render('home/index.html.twig', [
            'pager' => $pagerFanta,
        ]);
    }
}
