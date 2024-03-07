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
use App\Repository\EventRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DashboardController extends AbstractDashboardController
{
    private $eventRepository;
    private $security;

    public function __construct(EventRepository $monRepository, Security $security)
    {
        $this->eventRepository = $monRepository;
        $this->security = $security;
    }

    #[Route('/admin', name: 'admin')]

    public function index(): Response
    {
        $user = $this->security->getUser();
        if ($user && $user->getTeacher() instanceof Teacher){
            $events = $this->eventRepository->findByTeacher($user->getTeacher());
        }else{
            $events = [];
        }
        // $events = $this->eventRepository->findAll(); Si l'utilisateur est admin
        $evenements = [];
        foreach($events as $event){
            $evenements[] = [
                'id' => $event->getId(),
                'start' => $event->getStartDatetime()->format('Y-m-d H:i:s'),
                'end' => $event->getEndDatetime()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
            ];
        }
        $data = json_encode($evenements);
        
        return $this->render('admin/dashboard.html.twig', compact('data'));
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
        yield MenuItem::linkToCrud('Question Reponse', 'fas fa-id-card', QuestionReponse::class)->setPermission('');

        yield MenuItem::linkToCrud('Eval me', 'fas fa-id-card', Test::class)->setPermission('');

        yield MenuItem::section('Gestion des étudiants')->setPermission('');

        yield MenuItem::linkToCrud('Elève', 'fas fa-id-card', Student::class)->setPermission('');
        yield MenuItem::linkToCrud('Profile', 'fas fa-id-card', User::class)->setPermission('');
        yield MenuItem::linkToCrud('Classe', 'fas fa-id-card', StudentGroup::class)->setPermission('');
        yield MenuItem::linkToCrud('Elève Reponse', 'fas fa-id-card', StudentReponse::class)->setPermission('');

        yield MenuItem::section('Gestion des Organismes de formation')->setPermission('');

        yield MenuItem::section('Mise à disposition des cours')->setPermission('');

        yield MenuItem::section('Passation d\'examens en ligne')->setPermission('');
    }
}
