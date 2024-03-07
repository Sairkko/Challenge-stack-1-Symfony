<?php

namespace App\Controller;


use App\Entity\StudentReponse;
use App\Repository\TestRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(int $id, TestRepository $testRepository, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser(); // Assurez-vous que cela retourne l'utilisateur actuellement connecté

        $test = $testRepository->find($id);

        if (!$test) {
            throw $this->createNotFoundException('Le Test demandé n\'existe pas');
        }

        $title = $test->getTitle();
        $description = $test->getDescription();
        $questions = $test->getQuestions();

        // Préparer une structure pour stocker l'état de réponse pour chaque question
        $responsesStatus = [];

        foreach ($questions as $question) {
            // Remplacez $user->getId() par l'ID de l'étudiant, si $user représente un utilisateur et non un étudiant
            $responseExists = $entityManager->getRepository(StudentReponse::class)->studentResponseExists($user->getStudent()->getId(), $question->getId());
            $responsesStatus[$question->getId()] = $responseExists;
        }


        return $this->render('EvalMe/test.html.twig', [
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'questions' => $questions,
            'responsesStatus' => $responsesStatus,
            'role' => $user->getRoles()
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
