<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Entity\Emprunt;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/profil')]
class ProfilController extends AbstractController
{
    #[Route('/', name: 'profil')]
    public function index(): Response
    {
        return $this->render('profil/index.html.twig');
    }

    #[Route('/{id}/emprunter', name: 'profil_emprunter', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function new(Livre $livre): Response
    {
        $emprunt = new Emprunt();
        $emprunt->setDateEmprunt(new \DateTime());
        $emprunt->setAbonne($this->getUser());
        $emprunt->setLivre($livre);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($emprunt);
        $entityManager->flush();
        $this->addFlash("success", "Le nouvel emprunt a bien été enregistré");

        return $this->redirectToRoute('profil');
    }
}
