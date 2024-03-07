<?php

namespace App\Controller\Admin;

use App\Entity\QuestionReponse;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class QuestionReponseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return QuestionReponse::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('id_question'),
            TextField::new('text'),
            BooleanField::new('is_correct'),
        ];
    }

}
