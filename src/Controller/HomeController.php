<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\PostRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $size = 5,
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $adapter = new QueryAdapter($this->postRepository->createPostListQueryBuilder($user->getId()));

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
