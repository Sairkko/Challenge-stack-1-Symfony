<?php

namespace App\Controller\Admin;

use App\Entity\Lesson;
use App\Entity\Module;
use App\Entity\Test;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Security\Core\Security;

class ModuleCrudController extends AbstractCrudController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Module::class;
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
                })
        ];
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

    public function configureActions(Actions $actions): Actions
    {
        $customActionLesson = Action::new('lesson', 'Voir la page Matière')
            ->linkToCrudAction('MyCustomAction');

        return $actions
            ->add(Crud::PAGE_INDEX, $customActionLesson);
    }

    public function myCustomAction(AdminContext $context)
    {
        $moduleId = $context->getEntity()->getInstance()->getId();
        return $this->redirect($this->generateUrl('app_show_matiere', ['id' => $moduleId]));
    }
}
