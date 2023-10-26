<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Service\CategoryManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private CategoryManager $categoryManager;

    public function __construct(categoryManager $categoryManager){
        $this->categoryManager= $categoryManager;
    }

    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepo, CategoryRepository $categoryRepo, Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $mediaFilePath = null;

        $mediaFile = $request->files->get('Article')['media'];

        if ($mediaFile) {
            $mediaFilename = md5(uniqid()) . '.' . $mediaFile->guessExtension();
            $mediaFile->move(
                $this->getParameter('media_directory'),
                $mediaFilename
            );
            $article->setMediaName($mediaFilename);

            $mediaFilePath = $this->getParameter('media_directory') . '/' . $mediaFilename;
        }
        $entityManager->flush();

        $this->categoryManager->createDefaultCategories();

        return $this->render('home/index.html.twig',[
            'articles' => $articleRepo->findAll(),
            'categories' => $categoryRepo->findAll(),
            'mediaFilePath' => $mediaFilePath,
        ]);
    }

}
