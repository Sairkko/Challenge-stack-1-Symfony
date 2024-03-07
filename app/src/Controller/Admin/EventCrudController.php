<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Core\Security;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

class EventCrudController extends AbstractCrudController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Event::class;
    }



    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Évenement')
            ->setPageTitle(Crud::PAGE_NEW, 'Créer');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Créer');
            });
    }

    
    public function configureFields(string $pageName): iterable
    {
        $groupsField = AssociationField::new('groups', 'Classes')
        ->setFormTypeOption('by_reference', false)
        ->setRequired(true);

        if (Crud::PAGE_INDEX === $pageName) {
            $groupsField->formatValue(function ($value, $entity) {
                $groupNames = [];
                foreach ($entity->getGroups() as $group) {
                    $groupNames[] = (string) $group;
                }
                return join(', ', $groupNames);
            });
        }

        return [
            TextField::new('title', 'Titre')
                ->setRequired(true),
            TextField::new('description'),
            $groupsField,
            DateTimeField::new('start_datetime', 'Date début')
                ->setRequired(true),
            DateTimeField::new('end_datetime', 'Date fin')
                ->setRequired(true),
            TextField::new('color', 'Couleur')
            ->onlyOnForms(),
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $event = new Event;

        $user = $this->security->getUser()->getTeacher();

        if ($user) {
            $event->setIdTeacher($user);
        }

        return $event; // TODO: Change the autogenerated stub
    }
}
