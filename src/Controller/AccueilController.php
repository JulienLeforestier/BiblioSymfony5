<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(LivreRepository $livreRepository): Response
    {
        $liste_livres = $livreRepository->findAll();
        $liste_livres_indispos = $livreRepository->findByAvailable();
        return $this->render('accueil/index.html.twig', compact("liste_livres", "liste_livres_indispos"));
    }
}
