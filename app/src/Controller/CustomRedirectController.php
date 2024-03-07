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

    #[Route('/test/answers/{id}', name: 'answers_page')]
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
        $groups = $test->getGroups();
        $students = [];
        foreach($groups as $group){
            foreach($group->getStudents() as $student){
                $allStudentsReponses = $student->getStudentReponses();
                $thisTestReponses = array_filter($allStudentsReponses, function($reponse) use ($questions) {
                    return in_array($reponse->getQuestion(), $questions);
                $students[] = [
                    'id' => $student->getId(),
                    'nom' => $student->getName(),
                    'prenom' => $student->getLastName(),
                    'picture' => $student->getIdUser()->getProfilPicture(),
                    'reponses' => count($thisTestReponses)
                ];
            }
        }
        foreach($questions as $question){
            $question->getQuestionReponses();
        }

        return $this->render('EvalMe/answers.html.twig', [
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'questions' => $questions,
            'students' => $students
        ]);
    }
}
