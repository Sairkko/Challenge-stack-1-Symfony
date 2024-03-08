<?php

namespace App\Controller;


use App\Entity\StudentReponse;
use App\Repository\StudentRepository;
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
        $isStudent = in_array('ROLE_STUDENT', $user->getRoles());

        $responsesStatus = [];

        if ($isStudent) {
            foreach ($questions as $question) {
                // Remplacez $user->getStudent()->getId() par l'ID de l'étudiant, si $user représente un utilisateur et non un étudiant
                // Assurez-vous que la méthode getStudent existe et est appropriée pour récupérer l'entité étudiant associée
                $responseExists = $entityManager->getRepository(StudentReponse::class)->studentResponseExists($user->getStudent()->getId(), $question->getId());
                $responsesStatus[$question->getId()] = $responseExists;
            }
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

    #[Route('/end-quizz', name: 'end.quizz')]
    public function endQuizz()
    {
        return $this->render('EvalMe/endQuizz.html.twig', [
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
        $questionIds = [];
        foreach($questions as $question){
            $questionIds[] = $question->getId();
        }
        $groups = $test->getGroups();
        $students = [];
        foreach($groups as $group){
            foreach($group->getStudents() as $student){
                $allStudentsReponses = $student->getStudentResponses();

                $thisTestReponses = $allStudentsReponses->filter(function($reponse) use ($questionIds) {
                    return in_array($reponse->getIdQuestion()->getId(), $questionIds);
                });

                $correctionPercentage = 0;
                if($thisTestReponses->count() != 0){
                    $totalResponsesCount = $thisTestReponses->count();
                    if ($totalResponsesCount > 0) {
                        $notTotoResponsesCount = $thisTestReponses->filter(function($response) {
                        return $response->getIsCorrectByTeacher() !== 'null';
                    })->count();

                $correctionPercentage = round($notTotoResponsesCount / $totalResponsesCount * 100);
            } else {
                $correctionPercentage = 0; 
            }

                }
                $students[] = [
                    'id' => $student->getId(),
                    'nom' => $student->getName(),
                    'prenom' => $student->getLastName(),
                    'picture' => $student->getIdUser()->getProfilPicture(),
                    'reponses' => count($thisTestReponses),
                    'correctionPercentage' => $correctionPercentage,
                    'totalPoints' => 0
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

    #[Route('/test/answers/{testId}/student/{studentId}', name: 'student_answers_page')]
    public function studentAnswers(int $testId, int $studentId, TestRepository $testRepository, StudentRepository $studentRepository): Response
    {
        $test = $testRepository->find($testId);
        $student = $studentRepository->find($studentId);

        if (!$test) {
            throw $this->createNotFoundException('Le Test demandée n\'existe pas');
        }
        
        $title = $test->getTitle();
        $description = $test->getDescription();
        $allStudentsReponses = $student->getStudentResponses();
        $questions = $test->getQuestions();
        $data = [
            'id' => $test->getId(),
            'title' => $title,
            'description' => $description,
            'questions' => []
        ];
        foreach($questions as $question){
            $studentReponse = $allStudentsReponses->filter(function($reponse) use ($question) {return $reponse->getIdQuestion() === $question;})[0];
            $studentReponseValue = null;
            if($studentReponse !== null){
                $studentReponseValue = ['value' => $studentReponse->getValue()];
            }
            
            $el = [];
            $el[] = $studentReponseValue;
            $data['questions'][] = [
                'id'=> $question->getId(),
                'question' => $question,
                'questionReponses' => $question->getQuestionReponses(),
                'studentReponse' => $studentReponseValue,
                'is_validated' => true
            ];
        }
        dd($el);
        
        return $this->render('EvalMe/studentAnswers.html.twig', $data);
    }
}
