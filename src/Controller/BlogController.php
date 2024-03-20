<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogFromType;
use App\Form\BlogFormUpdateType;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlogController extends AbstractController
{

    private $em;
    private $blogRepository;
    public function __construct(BlogRepository $blogRepository,EntityManagerInterface $em) 
    {
        $this->em = $em;
        $this->blogRepository = $blogRepository;
    }

    // private $blogRepository;
    // public function __construct(BlogRepository $blogRepository) 
    // {
    //     $this->blogRepository = $blogRepository;
    // }

    #[Route('/blog', name: 'app_blog')]
    public function index(): Response
    {
           $blogs = $this->blogRepository->findAll();
        return $this->render('blog/index.html.twig', [
            'blogs' => $blogs,
        ]);
    }


    #[Route('/blog/create', name: 'app_blog_create')]
    public function createBlog(Request $request): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogFromType::class,$blog);
        $form->handleRequest($request);


        // if ($form->isSubmitted()) {
        //     // If the form is submitted, but not valid, you can retrieve and display errors
        //     if (!$form->isValid()) {
        //         // Fetch errors
        //         $errors = $form->getErrors(true, false);
        
        //        dd( $errors);
        //     }
        // }
        
        if($form->isSubmitted() && $form->isValid()){
            $newBlog = $form->getData();

            // dd($request);

            $imagePath = $form->get('imagePath')->getData();
            if($imagePath){
                $newFileName = uniqid().'.'.$imagePath->guessExtension();

                try{
                    $imagePath->move(
                            $this->getParameter('kernel.project_dir').'/public/uploads',
                            $newFileName
                    );
                }catch(FileException $e){
                    return new Response($e->getMessage());
                }

                $blog->setImagePath('/uploads/'.$newFileName);
            }
            $this->em->persist($blog);
            $this->em->flush();

            $this->addFlash('success', 'blog saved successfully');

            return $this->redirectToRoute('app_blog_create');
        }
        
        return $this->render('blog/store.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/blog/edit/{id}', name: 'app_blog_edit')]
    public function editBlog(Request $request,int $id): Response
    {
        $blog = $this->blogRepository->find($id);
        
        if(!$blog){
            throw $this->createNotFoundException('Blog not found');
        }
        $form = $this->createForm(BlogFormUpdateType::class,$blog);        
        $form->handleRequest($request);
        $imagePath = $form->get('imagePath')->getData();
        
        if($form->isSubmitted() && $form->isValid()){
            if($imagePath){

                if($blog->getImagePath() !== null){
                    if(file_exists(
                    $this->getParameter('kernel.project_dir').$blog->getImagePath()                        
                    )){
                        $this->getParameter('kernel.project_dir').$blog->getImagePath();
                    }
                    $newFileName = uniqid().'.'.$imagePath->guessExtension();

                    try{
                        $imagePath->move(
                                $this->getParameter('kernel.project_dir').'/public/uploads',
                                $newFileName
                        );
                    }catch(FileException $e){
                        return new Response($e->getMessage());
                    }

                    $blog->setImagePath('uploads/'.$newFileName);
                    $blog->setTitle($form->get('title')->getData());
                    $blog->setDescription($form->get('description')->getData());

                    $this->em->flush();
                    return $this->redirectToRoute('app_blog');
                }

            }else{
                $blog->setTitle($form->get('title')->getData());
                $blog->setDescription($form->get('description')->getData());

                $this->em->flush();

                return $this->redirectToRoute('app_blog');
            }
           
        }
        
        return $this->render('blog/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/blog/delete/{id}', name: 'app_blog_delete',methods:['GET','DELETE'])]
    public function deletBlog(Request $request,int $id): Response
    {
        $blog = $this->blogRepository->find($id);
        
        if(!$blog){
            throw $this->createNotFoundException('Blog not found');
        }

        $this->em->remove($blog);
        $this->em->flush();

        return $this->redirectToRoute('app_blog');
        
        
    }


}
