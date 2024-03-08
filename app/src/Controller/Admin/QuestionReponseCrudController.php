<?php

namespace App\Controller\Admin;

use App\Entity\QuestionReponse;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class QuestionReponseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return QuestionReponse::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('id_question', 'Question'),
            TextField::new('text'),
            BooleanField::new('is_correct', 'Validé'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Questions réponses')
            ->setPageTitle(Crud::PAGE_NEW, 'Créer');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Créer');
            });
    }

}
