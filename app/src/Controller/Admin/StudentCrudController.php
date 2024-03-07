<?php

namespace App\Controller\Admin;

use App\Entity\Student;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;

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
