<?php

namespace App\Controller\Admin;

use App\Entity\StudentGroup;
use App\Repository\StudentRepository;
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
        $teacherId = $this->getUser()->getTeacher();
        return [
            TextField::new('name'),
            AssociationField::new('students', 'Étudiants')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    // Personnalisez la requête pour afficher uniquement les étudiants créés par le professeur
                    'query_builder' => function (StudentRepository $studentRepository) use ($teacherId) {
                        return $studentRepository->createQueryBuilder('s')
                            ->where('s.id_teacher = :teacherId')
                            ->setParameter('teacherId', $teacherId);
                    }
                ])
        ];
    }
}
