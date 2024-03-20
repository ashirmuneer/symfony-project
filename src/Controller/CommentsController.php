<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentsController extends AbstractController
{
   private $em;
   private $commentRepository;
   public function __construct(CommentRepository $commentRepository,EntityManagerInterface $em)
   {
     $this->em = $em;
     $this->commentRepository = $commentRepository; 
   }


    #[Route('/comments', name: 'comments')]
    public function index(): Response
    {
        $comments = $this->commentRepository->findAllWithBlog();

        // dd($comments);
        return $this->render('comments/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route('/comment/create', name: 'comment_create')]
    public function create(Request $request): Response
    {

         
        

        $comment = new Comment();
        $form = $this->createForm(CommentType::class,$comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // dd($request);
            
            $newComment = $form->getData();
            $this->em->persist($newComment);
            $this->em->flush();

            $this->addFlash('success', 'comment saved successfully');

            return $this->redirectToRoute('comments');
        }


        return $this->render('comments/store.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
