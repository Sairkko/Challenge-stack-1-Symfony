<?php

namespace App\Controller;

use App\Entity\AskTeacherAccount;
use App\Form\AskTeacherAccountFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class AskAccountController extends AbstractController
{
    #[Route('/ask-teacher-account', name: 'app_ask_account')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $askTeacherAccount = new AskTeacherAccount();
        $form = $this->createForm(AskTeacherAccountFormType::class, $askTeacherAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $askTeacherAccount->setIsValid(false);

            $entityManager->persist($askTeacherAccount);
            $entityManager->flush();

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('ask_account/index.html.twig', [
            'askAccountForm' =>  $form->createView(),
        ]);
    }
}
