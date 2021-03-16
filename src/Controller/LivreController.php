<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/biblio/livre')]
class LivreController extends AbstractController
{
    #[Route('/', name: 'livre')]
    public function index(LivreRepository $livreRepository): Response
    {
        $liste_livres = $livreRepository->findAll();
        $liste_livres_indispos = $livreRepository->findByAvailable();
        return $this->render('livre/index.html.twig', compact("liste_livres", "liste_livres_indispos"));
    }

    #[Route('/{id}', name: 'livre_afficher', requirements: ['id' => '\d+'])]
    public function afficher(Livre $livre, LivreRepository $livreRepository)
    {
        $liste_livres_indispos = $livreRepository->findByAvailable();
        return $this->render('livre/afficher.html.twig', ["livre" => $livre, "liste_livres_indispos" => $liste_livres_indispos]);
    }

    #[Route('/ajouter', name: 'livre_ajouter')]
    public function ajouter(Request $request, EntityManagerInterface $em)
    {
        $livre = new Livre;
        // Je crée un objet $formLivre avec la méthode createForm() qui va représenter le formulaire généré grâce à la classe LivreType
        // Ce formulaire est lié à l'objet $livre
        $formLivre = $this->createForm(LivreType::class, $livre);
        // Avec la méthode handleRequest(), le $formLivre va gérer les données qui viennent du formulaire
        // On va aussi pouvoir savoir si le formulaire a été soumis et si il est valide
        $formLivre->handleRequest($request);
        if ($formLivre->isSubmitted()) {
            if ($formLivre->isValid()) {
                $fichier = $formLivre->get("couverture")->getData();
                if ($fichier) {
                    // La méthode pathinfo() permet de récupérer des informations sur un fichier, par exemple le nom du fichier sans le chemin complet ni l'extension
                    // La méthode getClientOriginalName() récupère le nom du fichier uploadé 
                    // (la méthode est exécutée à partir de lo'bjet instancié par getData() que l'on a exécuté sur l'objet formulaire)
                    $nomFichier = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                    $nomFichier .= "_" . time();
                    $nomFichier .= "." . $fichier->guessExtension(); // La méthode guessExtension() permet de récupérer l'extension d'un fichier
                    $nomFichier = str_replace(' ', '_', $nomFichier);
                    $destination = $this->getParameter("dossier_images") . "livres";
                    $fichier->move($destination, $nomFichier);
                    $livre->setCouverture($nomFichier);
                }
                $em->persist($livre); // La méthode persist() prépare la requête INSERT INTO à partir de l'objet entity passé en paramètre
                $em->flush(); // La méthode flush() exécute les requêtes en attente
                $this->addFlash("success", "Le nouveau livre a bien été enregistré"); // La méthode addFlash() permet de stocker un message en SESSION
                return $this->redirectToRoute("livre");
            } else {
                $this->addFlash("danger", "Le formulaire n'est pas valide");
            }
        }
        return $this->render('livre/ajouter.html.twig', ["formLivre" => $formLivre->createView()]);
    }

    #[Route('/modifier/{id}', name: 'livre_modifier', requirements: ['id' => '\d+'])]
    public function modifier(Request $request, EntityManagerInterface $em, LivreRepository $livreRepository, $id)
    {
        $livre = $livreRepository->find($id);
        $formLivre = $this->createForm(LivreType::class, $livre);
        $formLivre->handleRequest($request);
        if ($formLivre->isSubmitted() && $formLivre->isValid()) {
            if ($fichier = $formLivre->get("couverture")->getData()) { // Affectation puis test d'existence dans le if
                $nomFichier = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME) . "_" . time() . "." . $fichier->guessExtension();
                $nomFichier = str_replace(' ', '_', $nomFichier);
                $destination = $this->getParameter("dossier_images") . "livres";
                $fichier->move($destination, $nomFichier);
                $ancienFichier = $destination . "/" . $livre->getCouverture();
                if (file_exists($ancienFichier) && $livre->getCouverture()) unlink($ancienFichier);
                $livre->setCouverture($nomFichier);
            }
            // $em->persist($livre); 
            // Dès qu'un objet entity a un id non null, 
            // EntityManager va mettre la bdd à jour avec les informations de cet objet quand la méthode flush() sera exécutée
            $em->flush();
            $this->addFlash("success", "Le livre a bien été modifié");
            return $this->redirectToRoute("livre");
        }
        return $this->render('livre/ajouter.html.twig', ["formLivre" => $formLivre->createView()]);
    }

    #[Route('/supprimer/{id}', name: 'livre_supprimer', requirements: ['id' => '\d+'])]
    public function supprimer(Request $request, EntityManagerInterface $em, Livre $livre)
    {
        // On peut faire un autowire (injection de dépendence) de $livre grâce à l'id
        if ($request->isMethod("POST")) {
            $nomFichier = $livre->getCouverture();
            $ancienFichier = $this->getParameter("dossier_images") . "livres" . "/" . $nomFichier;
            $em->remove($livre);
            $em->flush();
            if (file_exists($ancienFichier) && $nomFichier) unlink($ancienFichier);
            $this->addFlash("success", "Le livre a bien été supprimé");
            return $this->redirectToRoute("livre");
        }
        return $this->render("livre/supprimer.html.twig", compact("livre"));
    }
}
