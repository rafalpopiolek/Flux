<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Post;
use App\Entity\User;
use App\Enum\Post\Status;
use App\Form\CreatePostFormType;
use App\Repository\PostRepository;
use App\Trait\Form\FormResponseStatus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostController extends AbstractController
{
    use FormResponseStatus;

    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly SluggerInterface $slugger,
    ) {
    }

    #[Route(path: '/posts', name: 'app_post_create', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(CreatePostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = $form->get('media')->getData();

            if ($files) {
                foreach ($files as $file) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $this->slugger->slug($originalName);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                    try {
                        $file->move(
                            $this->getParameter('media_storage'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        $this->addFlash('error', 'Error uploading file!');
                    }

                    $media = new Media();
                    $media->setFilename($newFilename);
                    $post->addMedia($media);
                }
            }

            $post->setAuthor($this->getUser());
            $post->setStatus(Status::PUBLISHED);

            $this->postRepository->save($post);

            $this->addFlash('success', 'Post created successfully!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView(),
        ], new Response(null, $this->setResponseStatus($form)));
    }

    #[Route(path: '/posts/{post}', name: 'app_post_remove', methods: ['POST'])]
    public function remove(Post $post): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user->getId() !== $post->getAuthor()->getId()) {
            return $this->redirectToRoute('app_home');
        }

        $this->postRepository->removeReactions($post);

        /** @var Media $media */
        foreach ($post->getMedia() as $media) {
            $path = $this->getParameter('media_storage') . '/' . $media->getFilename();

            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->postRepository->remove($post);

        $this->addFlash('success', 'Post removed successfully!');

        return $this->redirectToRoute('app_home');
    }
}
