<?php

namespace App\Controller;

use App\Entity\Abonne;
use App\Form\AbonneType;
use App\Repository\AbonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AbonneController extends AbstractController
{
    #[Route('/abonne', name: 'abonne')]
    public function index(AbonneRepository $abonneRepository): Response
    {
        $liste_abonnes = $abonneRepository->findAll();
        return $this->render('abonne/index.html.twig', compact("liste_abonnes"));
    }

    #[Route('/abonne/{id}', name: 'abonne_afficher', requirements: ['id' => '\d+'])]
    public function afficher(Abonne $abonne)
    {
        return $this->render('abonne/afficher.html.twig', ["abonne" => $abonne]);
    }

    #[Route('/abonne/ajouter', name: 'abonne_ajouter')]
    public function ajouter(Request $request, EntityManagerInterface $em)
    {
        $abonne = new Abonne;
        $formAbonne = $this->createForm(AbonneType::class, $abonne);
        $formAbonne->handleRequest($request);
        if ($formAbonne->isSubmitted()) {
            if ($formAbonne->isValid()){
                $em->persist($abonne);
                $em->flush();
                $this->addFlash("success", "Le nouvel abonné a bien été enregistré");
                return $this->redirectToRoute("abonne");
            }else{
                $this->addFlash("danger", "Le formulaire n'est pas valide");
            }
        }
        return $this->render('abonne/ajouter.html.twig', ["formAbonne" => $formAbonne->createView()]);
    }

    #[Route('/abonne/modifier/{id}', name: 'abonne_modifier', requirements: ['id' => '\d+'])]
    public function modifier(Request $request, EntityManagerInterface $em, AbonneRepository $abonneRepository, $id)
    {
        $abonne = $abonneRepository->find($id);
        $formAbonne = $this->createForm(AbonneType::class, $abonne);
        $formAbonne->handleRequest($request);
        if ($formAbonne->isSubmitted() && $formAbonne->isValid()) {
            $em->flush();
            return $this->redirectToRoute("abonne");
        }
        return $this->render('abonne/ajouter.html.twig', ["formAbonne" => $formAbonne->createView()]);
    }

    #[Route('/abonne/supprimer/{id}', name: 'abonne_supprimer', requirements: ['id' => '\d+'])]
    public function supprimer(Request $request, EntityManagerInterface $em, Abonne $abonne)
    {
        if ($request->isMethod("POST")) {
            $em->remove($abonne);
            $em->flush();
            return $this->redirectToRoute("abonne");
        }
        return $this->render("abonne/supprimer.html.twig", compact("abonne"));
    }
}
