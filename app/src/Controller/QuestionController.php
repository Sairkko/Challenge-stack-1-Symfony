<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\QuestionReponse;
use App\Entity\Test;
use App\Enum\QuestionType;
use App\Repository\TestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\Translation\t;

class QuestionController extends AbstractController {
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
        return $this->redirectToRoute('some_route', ['id' => $testId]);
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
        return $this->redirectToRoute('some_route', ['id' => $testId]);
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
        return $this->redirectToRoute('some_route', ['id' => $testId]);
    }

}
