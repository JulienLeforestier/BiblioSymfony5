<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class TestController extends AbstractController
{
    #[Route('/test', name: 'test')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TestController.php',
        ]);
    }

    #[Route('/test/calcul/{a}/{b}', requirements: ['b' => '\d+', 'a' => '[0-9]+'])]
    public function calcul($a, $b)
    {
        $resultat = $a + $b;

        // return $this->json([
        //     "calcul" => "$a + $b",
        //     "resultat" => $resultat
        // ]);

        // La méthode render construit l'affichage. Le 1er paramètre est le nom de la vue à utiliser.
        // Le nom de la vue est le chemin du fichier à partir du dossier "templates".
        return $this->render("test/calcul.html.twig", ["a" => $a, "b" => $b, "result" => $resultat]);
    }

    #[Route('/test/salut/{prenom}')]
    public function salut($prenom)
    {
        return $this->render("test/salut.html.twig", ["prenom" => $prenom]);
    }

    #[Route('/test/tableau')]
    public function tableau()
    {
        $tab = ["nom" => "Cérien", "prenom" => "Jean"];
        return $this->render("test/tableau.html.twig", ["tableau" => $tab]);
    }

    #[Route('/test/objet')]
    public function objet()
    {
        $objet = new \stdClass;
        $objet->nom = "Mentor";
        $objet->prenom = "Gérard";
        return $this->render("test/tableau.html.twig", ["tableau" => $objet]);
    }

    #[Route('/test/boucles')]
    public function boucles()
    {
        $tableau = ["Bonjour", "je", "suis", "en", "cours", "de", "Symfony"];
        $chiffres = [];
        for ($i = 0; $i < 10; $i++) $chiffres[] = $i * 12;
        return $this->render("test/boucles.html.twig", ["tableau" => $tableau, "chiffres" => $chiffres]);
    }

    #[Route('/test/conditions')]
    public function conditions()
    {
        $a = 12;
        $b = "blabla";
        return $this->render("test/conditions.html.twig", ["a" => $a, "b" => $b]);
    }

    #[Route('/test/formulaire', name: "test_formulaire")]
    public function formulaire()
    {
        return $this->render("test/formulaire.html.twig");
    }

    #[Route('/test/donnees', name: "test_donnees")]
    public function donnees()
    {
        if ($_POST) {
            extract($_POST);
            // extract -> contrôles des valeurs du POST -> render(compact)
            return $this->render("test/donnees.html.twig", compact("pseudo", "mdp", "email", "civilite", "nom", "prenom"));
        }
    }

    /** 
     * On ne peut pas instancier un objet de la classe Request, donc pour pouvoir l'utiliser, on va utiliser ce qu'on appelle l'injection de dépendance 
     * (vous verrez aussi parfois : autowiring) : en passant par les paramètres d'une méthode d'un contrôleur, 
     * l'objet de la classe est automatiquement instancié et remplit (si besoin)
     * Les classes que l'on peut utiliser avec l'injection de dépendances sont appelées des services (dans Symfony)
     *
     * La classe Request contient toutes les valeurs des variables superglobales de PHP, et quelques infos supplémentaires concernant la requête HTTP
     */
    #[Route('/test/affiche-donnees', name: "test_affiche_donnees")]
    public function afficheDonnees(Request $request)
    {
        // dump($request);
        // dd($request); // Dump and Die
        if ($request->isMethod("POST")) {
            $pseudo = $request->request->get("pseudo");         // L'objet $request a une propriété 'request' qui contient $_POST
            $mdp = $request->request->get("mdp");               // Cette propriété est un objet qui a une méthode get() pour récupérer une valeur
            $email = $request->request->get("email");           // Pour le contenu de $_GET, on utilisera, de la même façon,
            $civilite = $request->request->get("civilite");     // la propriété 'query' de l'objet $request
            $nom = $request->request->get("nom");
            $prenom = $request->request->get("prenom");
            return $this->render("test/donnees.html.twig", compact("pseudo", "mdp", "email", "civilite", "nom", "prenom"));
        }
    }

    #[Route('/test/find', name: "test_find")]
    public function testFind(LivreRepository $livreRepository)
    {
        $livres = $livreRepository->findBy(["titre" => "1984"]);
        dd($livres);
    }
}
