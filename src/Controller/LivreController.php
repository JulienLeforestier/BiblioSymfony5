<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LivreController extends AbstractController
{
    #[Route('/livre', name: 'livre')]
    public function index(LivreRepository $livreRepository): Response
    {
        $liste_livres = $livreRepository->findAll();
        return $this->render('livre/index.html.twig', compact("liste_livres"));
    }

    #[Route('livre/ajouter', name: 'livre_ajouter')]
    public function ajouter()
    {
        return $this->render('livre/ajouter.html.twig');
    }
}
