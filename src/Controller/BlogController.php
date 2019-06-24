<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;



class BlogController extends AbstractController
{
    /**
     * @Route("/blog/{page<\d+>?1}", name="blog")
     */
    public function index($page)
    {
        $repository = $this->getDoctrine()->getRepository(Post::class);
        $posts = $repository->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'page' => $page,
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/blog/new-post", name="create_post")
     */
    public function createPost()
    {
        return $this->render('blog/create.html.twig');
    }

    /**
     * @Route("/blog/store-post", name="store_post")
     */
    public function storePost(EntityManagerInterface $entityManager): Response
    {
        $post = new Post;
        $post->setTitle('Post Title');
        $post->setContent('This is the content of the post');
        $post->setAuthor('Fernando Liévano');

        $entityManager->persist($post);
        $entityManager->flush();

        return new Response('Saved new post with id '.$post->getId());
    }

    /**
     * @Route("/blog/post/{id}", name="blog_show")
     */
    public function blog($id)
    {
        $post = $this->getDoctrine()
                        ->getRepository(Post::class)
                        ->find($id);

        if (!$post) {
            throw $this->createNotFoundException(
                'No post found for id '.$id
            );
        }

        return new Response('Check out this great post! : '.$post->getTitle());
    }
}
