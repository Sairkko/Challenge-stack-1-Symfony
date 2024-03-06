<?php

namespace App\Controller\Admin;

use App\Entity\AskTeacherAccount;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class AskTeacherAccountCrudController extends AbstractCrudController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    public static function getEntityFqcn(): string
    {
        return AskTeacherAccount::class;
    }


    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('email')
            ->setColumns("col-lg-6 col-12")
            ->setLabel('Email');
        yield BooleanField::new('isValid')
            ->setColumns("col-lg-6 col-12")
            ->setLabel('Validé ?');
    }


    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(BooleanFilter::new('isValid')
                ->setLabel('Recherche par validation')
            );
    }


    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $token = bin2hex(random_bytes(10));
        $originalData = $entityManager->getUnitOfWork()->getOriginalEntityData($entityInstance);

        parent::updateEntity($entityManager, $entityInstance);

        if ($entityInstance instanceof AskTeacherAccount && $entityInstance->isIsValid() && !$originalData['isValid']) {
            $this->sendValidationEmail($entityInstance->getEmail(), $token);
            $this->createTeacherUser($entityInstance, $entityManager, $token);
        }
    }

    private function sendValidationEmail(string $emailAddress, string $token): void
    {
        $email = (new Email())
            ->from('noreply@example.com')
            ->to($emailAddress)
            ->subject('Confirmation de compte enseignant')
            ->html('Votre demande de compte enseignant a été validée. Veuillez créer votre compte en suivant <a href="http://localhost:8000/register/' . $token . '">ce lien</a>.');

        $this->mailer->send($email);
    }


    private function createTeacherUser(AskTeacherAccount $askTeacherAccount, EntityManagerInterface $entityManager, string $token): void
    {
        $user = new User();
        $user->setEmail($askTeacherAccount->getEmail());
        $user->setRoles(['ROLE_TEACHER']);
        $user->setToken($token);

        $entityManager->persist($user);
        $entityManager->flush();
    }

}
