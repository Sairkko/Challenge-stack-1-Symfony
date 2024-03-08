<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\User;
use App\Form\RegistrationStudentFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RegistrationStudentController extends AbstractController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
//    todo bloquer cette route pour seulement les teacher et comment recuperer son id pour le set
    #[Route('/registration/student', name: 'app_registration_student')]
    #[IsGranted('ROLE_TEACHER')]
    public function index(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $form = $this->createForm(RegistrationStudentFormType::class);
        $form->handleRequest($request);

        $user = new User();
        $token = bin2hex(random_bytes(10));

        $student = new Student();

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setEmail($form->get('email')->getData());
            $user->setRoles(["ROLE_STUDENT"]);
            $user->setToken($token);

            $student->setIdUser($user);
            $studentGroups = $form->get('studentGroup')->getData();
            foreach ($studentGroups as $studentGroup) {
                $student->addStudentGroupe($studentGroup);
            }

            $teacher = $security->getUser()->getTeacher();
            $student->setIdTeacher($teacher);

            $entityManager->persist($user);
            $entityManager->persist($student);
            $entityManager->flush();

            $email = (new Email())
                ->from('noreply@example.com')
                ->to($form->get('email')->getData())
                ->subject('Confirmation de compte enseignant')
                ->html('Votre demande de compte enseignant a été validée. Veuillez créer votre compte en suivant <a href="http://localhost:8000/register/' . $token . '">ce lien</a>.');

            $this->mailer->send($email);

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('registration_student/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
