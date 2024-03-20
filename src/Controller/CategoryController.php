<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{

    private $em;
    private $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository,EntityManagerInterface $em)
    {
      $this->em = $em;
      $this->categoryRepository = $categoryRepository; 
    }


    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        $categories = $this->categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/create', name: 'category_create')]
    public function create(Request $request): Response
    {

        $product = new Category();
        $form = $this->createForm(CategoryFormType::class,$product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $newCategory = $form->getData();
            $this->em->persist($newCategory);
            $this->em->flush();

            $this->addFlash('success', 'category saved successfully');

            return $this->redirectToRoute('app_category');

            
        }



        return $this->render('category/create.html.twig', [
            'form' => $form,
        ]);
    }
}
