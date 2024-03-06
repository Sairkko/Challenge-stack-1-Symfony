<?php

namespace App\Controller\Admin;

use App\Entity\Student;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class StudentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Student::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $customCreate = Action::new('customCreate', 'Créer', 'fa fa-plus')
            ->linkToUrl($this->getCustomCreateUrl())
            ->createAsGlobalAction();

        return $actions
            ->add(Crud::PAGE_INDEX, $customCreate)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    private function getCustomCreateUrl(): string
    {
        return $this->generateUrl('app_registration_student');
    }
}
