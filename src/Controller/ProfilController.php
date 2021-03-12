<?php

namespace App\Controller;

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
}
