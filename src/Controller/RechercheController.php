<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RechercheController extends AbstractController
{
    #[Route('/recherche', name: 'recherche')]
    public function index(Request $request, LivreRepository $livreRepository): Response
    {
        $search = $request->query->get("search");
        $liste_livres = $livreRepository->findBySearch($search);
        $liste_livres_indispos = $livreRepository->findByAvailable();
        if (count($liste_livres) < 1) $this->addFlash("danger", "Aucun livre ou auteur ne correspond à votre recherche");
        return $this->render('recherche/index.html.twig', compact('liste_livres', 'liste_livres_indispos', 'search'));
    }
}
