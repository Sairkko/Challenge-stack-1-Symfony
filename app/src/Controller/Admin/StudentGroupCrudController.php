<?php

namespace App\Controller\Admin;

use App\Entity\StudentGroup;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StudentGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StudentGroup::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            AssociationField::new('students', 'Etudiants')
                ->setFormTypeOption('by_reference', false)
        ];
    }
}
