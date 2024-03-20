<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{

    private $em;
   private $productRepository;
   public function __construct(ProductRepository $productRepository,EntityManagerInterface $em)
   {
     $this->em = $em;
     $this->productRepository = $productRepository; 
   }

    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        $products = $this->productRepository->findAll();

        
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/create', name: 'product_create')]
    public function create(Request $request): Response
    {

        $product = new Product();
        $form = $this->createForm(ProductFormType::class,$product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $newProduct = $form->getData();

            $this->em->persist($newProduct);
            $this->em->flush();

            $this->addFlash('success', 'product saved successfully');

            return $this->redirectToRoute('product_create');
        }

        return $this->render('product/create.html.twig', [
            'form' => $form,
        ]);
    }
}
