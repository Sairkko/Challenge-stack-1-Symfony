<?php

namespace App\Controller;

use App\Repository\LessonRepository;
use App\Repository\ModuleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\throwException;

class ShowMatiereController extends AbstractController
{
    #[Route('/show/matiere/{id}', name: 'app_show_matiere')]
    public function index(int $id,ModuleRepository $moduleRepository): Response
    {
        $matiere= $moduleRepository->find($id);

        if (!$matiere) {
            throw new \RuntimeException('Aucune Matière trouver.');
        }

        $name = $matiere->getName();
        $lessons = $matiere->getLessons();
        $teacherName = $matiere->getIdTeacher()->getName();


        return $this->render('show_matiere/index.html.twig', [
            'lessons' => $lessons,
            'name' => $name,
            'teacherName' => $teacherName,
        ]);
    }

    #[Route('/show/lesson/{id}', name: 'app_show_lesson')]
    public function lesson(int $id, LessonRepository $lessonRepository): Response
    {
        $lesson = $lessonRepository->find($id);

        if (!$lesson) {
            throw new \RuntimeException('Aucune Leçon trouvé.');
        }

        $title = $lesson->getTitle();
        $teacher = $lesson->getIdTeacher()->getName();
        $description = $lesson->getDescription();
        $content = $lesson->getContent();
        $files = $lesson->getFile();

        return $this->render('show_matiere/lesson.html.twig', [
            'title' => $title,
            'teacher' => $teacher,
            'description' => $description,
            'content' => $content,
            'files' => $files,
        ]);
    }
}
