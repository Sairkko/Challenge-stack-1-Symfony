<?php

namespace App\Controller\Admin;

use App\Entity\School;
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

        yield MenuItem::section('Gestion des formations')->setPermission('');
        yield MenuItem::linkToCrud('School', 'fas fa-id-card', School::class)->setPermission('ROLE_FORMATEUR');

        yield MenuItem::section('Gestion des étudiants')->setPermission('');

        yield MenuItem::section('Gestion des Organismes de formation')->setPermission('');

        yield MenuItem::section('Mise à disposition des cours')->setPermission('');

        yield MenuItem::section('Passation d\'examens en ligne')->setPermission('');
    }
}
