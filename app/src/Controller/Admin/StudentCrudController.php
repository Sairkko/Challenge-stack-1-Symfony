<?php

namespace App\Controller\Admin;

use App\Entity\Student;
use App\Entity\StudentGroup;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Prénom');
        yield TextField::new('lastname', 'Nom');
        yield AssociationField::new('student_groupe', 'Classe')
            ->setCrudController(StudentGroup::class)
            ->formatValue(function ($value, $entity) {
                $classe = $entity->getStudentGroupe();
                return implode(', ', $classe->map(function ($classe) {
                    return $classe->getName();
                })->toArray());
            });
    }

    private function getCustomCreateUrl(): string
    {
        return $this->generateUrl('app_registration_student');
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        // Supposons que vous avez un moyen d'obtenir l'ID de l'enseignant actuel,
        // par exemple à partir de l'utilisateur actuellement connecté
        $teacherId = $this->getUser()->getTeacher(); // Remplacez ceci par le code approprié pour obtenir l'ID de l'enseignant

        // Filtrer les étudiants pour n'inclure que ceux associés à l'enseignant spécifique
        $qb->andWhere('entity.id_teacher = :teacherId')
            ->setParameter('teacherId', $teacherId);

        return $qb;
    }
}
