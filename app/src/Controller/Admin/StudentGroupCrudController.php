<?php

namespace App\Controller\Admin;

use App\Entity\StudentGroup;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class StudentGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StudentGroup::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Prénom'),
            AssociationField::new('students', 'Etudiants')
                ->setFormTypeOption('by_reference', false)
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Groupes d\'élèves')
            ->setPageTitle(Crud::PAGE_NEW, 'Créer');
    }

    public function configureAction(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Créer');
            });
    }
    
}
