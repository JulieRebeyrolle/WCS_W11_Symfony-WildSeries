<?php

namespace App\Controller;

use App\Entity\Program;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     * @param ProgramRepository $programRepository
     * @return Response
     */

    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findBy([], ['id' => 'DESC'], 3);

        return $this->render('index.html.twig', [
            'website' => 'Wild Séries',
            'programs' => $programs,
        ]);
    }

    public function navbarTop(CategoryRepository $categoryRepository): Response
    {
        return $this->render('_includes/_navbartop.html.twig', [
            'categories' => $categoryRepository->findBy([], ['name' => 'ASC'])
        ]);

    }
}