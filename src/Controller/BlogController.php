<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;

/**
* @Route("/blog")
*/
class BlogController extends AbstractController
{
    /**
     * Undocumented function
     *
     * @param Request $request
     *
     * @Route("/add", name="add-post", methods={"POST"})
     */
    public function add(Request $request)
    {
        $serializer = $this->get('serializer');
        $post = $serializer->deserialize($request->getContent(),Post::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        return $this->json($post);
    }
    /**
     * @Route("/{page}", defaults={"page":5}, name="get-all-posts")
     * @return void
     */
    public function index($page,Request $request )
    {
        $repository = $this->getDoctrine()->getRepository(Post::class);
        $posts = $repository->findAll();
        return $this->json([
            'page' => $page,
            'limit' => $request->get('limit',10),
            'data' => array_map(function(Post $post){
                return [
                    'title' => $post->getTitle(),
                    'content' => $post->getContent(),
                    'user' => $post->getAuthor(),
                    'link' => $this->generateUrl('get-one-post-id',['id'=>$post->getId()])
                ];
            
            },$posts)
        ]);
    }
    /**
     * @Route("/post/{id}",requirements={"id": "\d+"}, name="get-one-post-id")
     */
    public function postById($id)
    {        
        $repository = $this->getDoctrine()->getRepository(Post::class);
        $post = $repository->find($id);
        return $this->json($post);
    }
    /**
     * @Route("/post/{slug}" , name="get-one-post-slug")
     */
    public function postBySlug($slug)
    {
        $repository = $this->getDoctrine()->getRepository(Post::class);
        $post = $repository->findOneBy(['slug'=> $slug]);
        return $this->json($post);
    }

}