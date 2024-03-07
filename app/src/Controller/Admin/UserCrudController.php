<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Doctrine\ORM\QueryBuilder;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email', 'Email')
                ->onlyOnIndex(),
            ImageField::new('profil_Picture', 'Image de profil')
                ->setUploadDir('public/uploads/profil_Picture')
                ->setBasePath('uploads/profil_Picture')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false)
                ->setFormTypeOption('attr', ['accept' => 'image/*']),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX ,Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        // Supposons que vous avez un moyen d'obtenir l'ID de l'enseignant actuel,
        // par exemple à partir de l'utilisateur actuellement connecté
        $userId = $this->getUser(); // Remplacez ceci par le code approprié pour obtenir l'ID de l'enseignant

        // Filtrer les étudiants pour n'inclure que ceux associés à l'enseignant spécifique
        $qb->andWhere('entity.id = :id')
            ->setParameter('id', $userId);

        return $qb;
    }
}
