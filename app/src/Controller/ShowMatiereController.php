<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowMatiereController extends AbstractController
{
    #[Route('/show/matiere', name: 'app_show_matiere')]
    public function index(): Response
    {
        return $this->render('show_matiere/index.html.twig', [
            'controller_name' => 'ShowMatiereController',
        ]);
    }
}
