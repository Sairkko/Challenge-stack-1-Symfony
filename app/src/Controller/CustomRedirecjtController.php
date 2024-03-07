<?php

namespace App\Controller;


use App\Repository\TestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomRedirectController extends AbstractController
{
    #[Route('/custom-view/{id}', name: 'some_route')]
    public function index(int $id, TestRepository $testRepository): Response
    {
        // Récupérez des données supplémentaires si nécessaire
        $test = $testRepository->find($id);

        if (!$test) {
            throw $this->createNotFoundException('Le Test demandée n\'existe pas');
        }

        $title = $test->getTitle();
        $description = $test->getDescription();

        $questions = $test->getQuestions();

        return $this->render('EvalMe/test.html.twig', [
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'questions' => $questions
        ]);
    }

    #[Route('/custom-view/answers{id}', name: 'response_page')]
    public function answers(int $id, TestRepository $testRepository): Response
    {
        // Récupérez des données supplémentaires si nécessaire
        $test = $testRepository->find($id);

        if (!$test) {
            throw $this->createNotFoundException('Le Test demandée n\'existe pas');
        }

        $title = $test->getTitle();
        $description = $test->getDescription();

        $questions = $test->getQuestions();

        return $this->render('EvalMe/test.html.twig', [
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'questions' => $questions
        ]);
    }
}
