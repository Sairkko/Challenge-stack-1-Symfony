<?php

namespace App\Controller;


use App\Repository\TestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomRedirectController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/quizz/{id}', name: 'quizz')]
    public function index(int $id, TestRepository $testRepository): Response
    {
        $user = $this->security->getUser()->getRoles();

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
            'questions' => $questions,
            'role' => $user
        ]);
    }
}
