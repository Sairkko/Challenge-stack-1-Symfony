<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\QuestionReponse;
use App\Entity\StudentReponse;
use App\Entity\Test;
use App\Enum\QuestionType;
use App\Repository\TestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class QuestionController extends AbstractController {
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route('/submit-question', name: 'submit_question')]
    public function submitQuestion(Request $request, EntityManagerInterface $entityManager, TestRepository $testRepository) {
        $testId = $request->request->get('testId');
        $test = $testRepository->find($testId);

        if (!$test) {
            throw $this->createNotFoundException('Le Test demandé n\'existe pas');
        }

        $question = new Question();
        $question->setIdTest($test);

        // Convertissez la chaîne de type de question en instance de l'enum QuestionType
        $typeString = $request->request->get('questionType');
        $type = QuestionType::tryFrom($typeString);
        if ($type === null) {
            throw new \InvalidArgumentException('Type de question invalide');
        }
        $question->setType($type);

        $question->setQuestionText($request->request->get('questionTitle'));
        $question->setPoint($request->request->get('points'));
        $entityManager->persist($question);
        $entityManager->flush();

        // Maintenant, traitez les réponses en fonction du type de question
        switch ($type) {
            case QuestionType::QCM:
                $this->handleMultipleChoiceResponses($request, $question, $entityManager);
                break;
            case QuestionType::UNIQUE_CHOICE:
                $this->handleUniqueChoiceResponse($request, $question, $entityManager);
                break;
            case QuestionType::OPEN_QUESTION:
                $this->handleOpenQuestionResponse($request, $question, $entityManager);
                break;
        }

        $entityManager->flush();

        // Redirigez vers une page de confirmation ou une autre page appropriée
        return $this->redirectToRoute('quizz', ['id' => $testId]);
    }

    protected function handleMultipleChoiceResponses(Request $request, Question $question, EntityManagerInterface $entityManager) {

        $selectedOption1 = $request->request->get('answers1');
        $selectedOption2 = $request->request->get('answers2');
        $selectedOption3 = $request->request->get('answers3');
        $selectedOption4 = $request->request->get('answers4');

        $response1 = $request->request->get('responseA');
        $response2 = $request->request->get('responseB');
        $response3 = $request->request->get('responseC');
        $response4 = $request->request->get('responseD');

        $questionReponse1 = new QuestionReponse();
        $questionReponse1->setText($response1);
        $questionReponse1->setIdQuestion($question);
        $questionReponse1->setIsCorrect(!is_null($selectedOption1));

        $questionReponse2 = new QuestionReponse();
        $questionReponse2->setText($response2);
        $questionReponse2->setIdQuestion($question);
        $questionReponse2->setIsCorrect(!is_null($selectedOption2));

        $questionReponse3 = new QuestionReponse();
        $questionReponse3->setText($response3);
        $questionReponse3->setIdQuestion($question);
        $questionReponse3->setIsCorrect(!is_null($selectedOption3));

        $questionReponse4 = new QuestionReponse();
        $questionReponse4->setText($response4);
        $questionReponse4->setIdQuestion($question);
        $questionReponse4->setIsCorrect(!is_null($selectedOption4));

        $entityManager->persist($questionReponse1);
        $entityManager->persist($questionReponse2);
        $entityManager->persist($questionReponse3);
        $entityManager->persist($questionReponse4);

        $entityManager->flush();
        // Si vous voulez combiner toutes les options sélectionnées dans une seule réponse
    }

    protected function handleUniqueChoiceResponse(Request $request, Question $question, EntityManagerInterface $entityManager)
    {
        $selectedOption1 = $request->request->get('answer1');
        $selectedOption2 = $request->request->get('answer2');
        $selectedOption3 = $request->request->get('answer3');
        $selectedOption4 = $request->request->get('answer4');

        $response1 = $request->request->get('responseA');
        $response2 = $request->request->get('responseB');
        $response3 = $request->request->get('responseC');
        $response4 = $request->request->get('responseD');

        $questionReponse1 = new QuestionReponse();
        $questionReponse1->setText($response1);
        $questionReponse1->setIdQuestion($question);
        $questionReponse1->setIsCorrect(!is_null($selectedOption1));

        $questionReponse2 = new QuestionReponse();
        $questionReponse2->setText($response2);
        $questionReponse2->setIdQuestion($question);
        $questionReponse2->setIsCorrect(!is_null($selectedOption2));

        $questionReponse3 = new QuestionReponse();
        $questionReponse3->setText($response3);
        $questionReponse3->setIdQuestion($question);
        $questionReponse3->setIsCorrect(!is_null($selectedOption3));

        $questionReponse4 = new QuestionReponse();
        $questionReponse4->setText($response4);
        $questionReponse4->setIdQuestion($question);
        $questionReponse4->setIsCorrect(!is_null($selectedOption4));

        $entityManager->persist($questionReponse1);
        $entityManager->persist($questionReponse2);
        $entityManager->persist($questionReponse3);
        $entityManager->persist($questionReponse4);

        $entityManager->flush();
    }

    protected function handleOpenQuestionResponse(Request $request, Question $question, EntityManagerInterface $entityManager)
    {
        $responseText = $request->request->get('openAnswer');
        $questionReponse = new QuestionReponse();
        $questionReponse->setIdQuestion($question);
        $questionReponse->setText($responseText);
        $questionReponse->setIsCorrect(false);
        $entityManager->persist($questionReponse);
    }

    #[Route('/delete-question/{id}', name: 'delete_question')]
    public function deleteQuestion(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $testId = $request->request->get('testId');
        $question = $entityManager->getRepository(Question::class)->find($id);

        if (!$question) {
            throw $this->createNotFoundException('La question demandée n\'existe pas.');
        }

        // Supprimez d'abord toutes les réponses liées à la question
        foreach ($question->getQuestionReponses() as $reponse) {
            $entityManager->remove($reponse);
        }

        // Après avoir supprimé toutes les réponses, vous pouvez supprimer la question
        $entityManager->remove($question);
        $entityManager->flush();

        // Redirigez vers la page appropriée après la suppression
        return $this->redirectToRoute('quizz', ['id' => $testId]);
    }

    #[Route('/edit-question/{id}', name: 'edit_question', methods: ['PATCH'])]
    public function editQuestion(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $testId = $request->request->get('testId');

        // Récupère la question à partir de la base de données
        $question = $entityManager->getRepository(Question::class)->find($id);

        if (!$question) {
            throw $this->createNotFoundException('La question demandée n\'existe pas.');
        }

        // Récupération des données de la requête
        $content = json_decode($request->getContent(), true);

        // Mise à jour de la question avec les nouvelles données
        if (isset($content['titre'])) {
            $question->setTitre($content['titre']);
        }
        if (isset($content['contenu'])) {
            $question->setContenu($content['contenu']);
        }

        // Persister les modifications dans la base de données
        $entityManager->persist($question);
        $entityManager->flush();

        // Rediriger ou retourner une réponse après la mise à jour
        return $this->redirectToRoute('quizz', ['id' => $testId]);
    }

    #[Route('/student-response/{id}', name: 'submit_student_response')]
    public function submitStudentResponse(int $id, Request $request, EntityManagerInterface $entityManager, TestRepository $testRepository)
    {
        $testId = $request->request->get('testId');
        $test = $testRepository->find($testId);
        $user = $this->getUser()->getStudent(); // Assurez-vous que cette méthode retourne bien l'étudiant connecté

        $question = $entityManager->getRepository(Question::class)->find($id);

        if (!$question) {
            throw $this->createNotFoundException('La question demandée n\'existe pas.');
        }

        // Créer une nouvelle réponse d'étudiant
        $studentResponse = new StudentReponse();
        $studentResponse->setStudent($user);
        $studentResponse->setIdQuestion($question);
        $type = $question->getType();

        // Récupérer la réponse soumise en utilisant l'enum du type de question
        $responseValues = []; // Initialiser la valeur de la réponse comme tableau

        switch ($type) {
            case QuestionType::QCM:
                $selectedResponses = [];
                for ($i = 1; $i <= 4; $i++) { // Assumant toujours 4 réponses possibles
                    $responseValue = $request->request->get('response_student_' . $id . '_' . $i);
                    if (!is_null($responseValue)) {
                        $selectedResponses[] = $responseValue; // Ajoutez la réponse sélectionnée au tableau
                    }
                }
                // Convertissez le tableau des réponses en chaîne JSON pour le stockage
                if (!empty($selectedResponses)) {
                    $responseValues[] = json_encode($selectedResponses);
                }
                break;
            case QuestionType::UNIQUE_CHOICE:
                // Pour UNIQUE_CHOICE, on attend l'id de la réponse choisie
                $responseValue = $request->request->get('response_student_'.$id);
                $responseValues = $responseValue ? [$responseValue] : [];
                break;
            case QuestionType::OPEN_QUESTION:
                // Pour une question ouverte, on attend le texte de la réponse
                $responseValue = $request->request->get('response_student_'.$id);
                $responseValues = $responseValue ? [$responseValue] : [];
                break;
        }

        if (!empty($responseValues)) {
            // Assurez-vous de créer et de persister une seule nouvelle réponse par utilisateur et par question
            $newStudentResponse = new StudentReponse();
            $newStudentResponse->setStudent($user);
            $newStudentResponse->setIdQuestion($question);
            // Ici, comme on peut avoir plus d'une valeur pour les QCM, on prend toujours la première entrée de responseValues
            $newStudentResponse->setValue($responseValues[0]);
            $entityManager->persist($newStudentResponse);
        }

        $entityManager->flush();

        // Rediriger l'utilisateur avec un message de succès
        return $this->redirectToRoute('quizz', ['id' => $testId]); // Assurez-vous de remplacer 'some_other_route' et 'id'
    }

}
