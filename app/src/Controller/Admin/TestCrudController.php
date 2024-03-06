<?php

namespace App\Controller\Admin;

use App\Entity\Test;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Security\Core\Security;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Component\HttpFoundation\Response;

class TestCrudController extends AbstractCrudController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Test::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */

    public function createEntity(string $entityFqcn)
    {
        $test = new Test();

        $user = $this->security->getUser()->getTeacher();

        if ($user) {
            $test->setIdTeacher($user);
        }

        return $test; // TODO: Change the autogenerated stub
    }


    public function configureActions(Actions $actions): Actions
    {
        $customAction = Action::new('customAction', 'Affichage Personnalisé', 'fa fa-eye')
            ->linkToCrudAction('myCustomAction'); // Nom de la méthode dans ce contrôleur

        return $actions
            ->add(Crud::PAGE_INDEX, $customAction);
    }

    public function myCustomAction(AdminContext $context)
    {
        $testId = $context->getEntity()->getInstance()->getId();
        // Assurez-vous que 'some_route' est le nom de la route de votre nouveau contrôleur Symfony
        return $this->redirect($this->generateUrl('some_route', ['id' => $testId]));
    }

}
