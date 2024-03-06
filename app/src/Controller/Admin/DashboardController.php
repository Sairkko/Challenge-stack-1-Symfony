<?php

namespace App\Controller\Admin;

use App\Entity\AskTeacherAccount;
use App\Entity\Event;
use App\Entity\Lesson;
use App\Entity\LessonPermission;
use App\Entity\Module;
use App\Entity\Question;
use App\Entity\QuestionReponse;
use App\Entity\School;
use App\Entity\Student;
use App\Entity\StudentGroup;
use App\Entity\StudentReponse;
use App\Entity\Teacher;
use App\Entity\Test;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]

    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
        ]);
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addWebpackEncoreEntry('app');
    }


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Challenge Stack')
            ->setFaviconPath('build/favicon.ico');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Dashboard')->setPermission('');
        yield MenuItem::linkToDashboard('Accueil', 'fa fa-home');
        yield MenuItem::linkToCrud('Demande de compte formatteur', 'fas fa-id-card', AskTeacherAccount::class)->setPermission('');

        yield MenuItem::section('Gestion des formations')->setPermission('');

        yield MenuItem::linkToCrud('Ecole', 'fas fa-id-card', School::class)->setPermission('');
        yield MenuItem::linkToCrud('Evenement', 'fas fa-id-card', Event::class)->setPermission('');
        yield MenuItem::linkToCrud('Cours', 'fas fa-id-card', Lesson::class)->setPermission('');
        yield MenuItem::linkToCrud('Cours Permission', 'fas fa-id-card', LessonPermission::class)->setPermission('');
        yield MenuItem::linkToCrud('Matière', 'fas fa-id-card', Module::class)->setPermission('');
        yield MenuItem::linkToCrud('Question', 'fas fa-id-card', Question::class)->setPermission('');
        yield MenuItem::linkToCrud('Question Reponse', 'fas fa-id-card', QuestionReponse::class)->setPermission('');

        yield MenuItem::linkToCrud('Eval me', 'fas fa-id-card', Test::class)->setPermission('');

        yield MenuItem::section('Gestion des étudiants')->setPermission('');

        yield MenuItem::linkToCrud('Elève', 'fas fa-id-card', Student::class)->setPermission('');
        yield MenuItem::linkToCrud('Classe', 'fas fa-id-card', StudentGroup::class)->setPermission('');
        yield MenuItem::linkToCrud('Elève Reponse', 'fas fa-id-card', StudentReponse::class)->setPermission('');

        yield MenuItem::section('Gestion des Organismes de formation')->setPermission('');

        yield MenuItem::section('Mise à disposition des cours')->setPermission('');

        yield MenuItem::section('Passation d\'examens en ligne')->setPermission('');
    }
}
