<?php

namespace App\Controller;


use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{slug}', name: 'category_show')]
    public function show(?Category $category, CategoryRepository $categoryRepo): Response
    {

        if(!$category){
            return $this->redirectToRoute('app_home');
        }

        return $this->render('category/show.html.twig', [
            'category'=>$category,
            'categories' => $categoryRepo->findAll()
        ]);
    }
}