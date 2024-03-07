<?php

namespace App\Controller\Admin;

use App\Entity\Module;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ModuleCrudController extends AbstractCrudController
{
    private $security;
    private $authorizationChecker;

    public function __construct(Security $security, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->security = $security;
        $this->authorizationChecker = $authorizationChecker;
    }

    public static function getEntityFqcn(): string
    {
        return Module::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Matière')
            ->setPageTitle(Crud::PAGE_NEW, 'Créer');
    }

    public function configureFields(string $pageName): iterable
    {

        return [
            TextField::new('name', 'Nom de la matière'),
            AssociationField::new('lessons', 'Leçons')
                ->setCrudController(LessonCrudController::class)
                ->formatValue(function ($value, $entity) {
                    $lessons = $entity->getLessons();
                    return implode(', ', $lessons->map(function ($lesson) {
                        return $lesson->getTitle();
                    })->toArray());
                }),
            ->onlyOnIndex()
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = parent::configureActions($actions);

        $actions->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
            return $action->setLabel('Créer');
        });

        $customActionLesson = Action::new('lesson', 'Voir la page Matière')
            ->linkToCrudAction('myCustomAction');

        $actions->add(Crud::PAGE_INDEX, $customActionLesson);

        return $actions;
        if (!$this->authorizationChecker->isGranted('ROLE_TEACHER')) {
            $actions
                ->remove(Crud::PAGE_INDEX, Action::NEW)
                ->remove(Crud::PAGE_INDEX, Action::EDIT)
                ->remove(Crud::PAGE_INDEX, Action::DELETE)
                ->add(Crud::PAGE_INDEX, $customActionLesson)
            ;
        }

        return $actions
    }

    public function myCustomAction(AdminContext $context)
    {
        $moduleId = $context->getEntity()->getInstance()->getId();
        return $this->redirect($this->generateUrl('app_show_matiere', ['id' => $moduleId]));
    }

    public function createEntity(string $entityFqcn)
    {
        $module = new Module();

        $user = $this->security->getUser()->getTeacher();

        if ($user) {
            $module->setIdTeacher($user);
        }

        return $module;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($this->getUser()->getRoles() == "ROLE_TEACHER") {
            $teacherId = $this->getUser()->getTeacher();

            $qb->andWhere('entity.id_teacher = :teacherId')
                ->setParameter('teacherId', $teacherId);
        } else {
            $studentGroups = $this->getUser()->getStudent()->getStudentGroupe();
            $studentGroupIds = [];

            foreach ($studentGroups as $group) {
                $studentGroupIds[] = $group->getId();
            }

            $qb->join('entity.studentGroups', 'sg')
                ->andWhere($qb->expr()->in('sg.id', ':studentGroupIds'))
                ->setParameter('studentGroupIds', $studentGroupIds);
        }

        return $qb;
    }

}
