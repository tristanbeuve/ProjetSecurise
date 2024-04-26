<?php

namespace App\Controller;


use App\DTO\EmailDTO;
use App\DTO\Forgot_PasswordDTO;
use App\Form\EmailDTOType;
use App\Form\ForgotPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ForgotPasswordController extends AbstractController
{
    #[Route('/password', name: 'app_password')]
    public function index(Request $request, UserRepository $ur,EntityManagerInterface $entityManager): Response
    {
        $emailDTO = new EmailDTO();
        $form = $this->createForm(EmailDTOType::class, $emailDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $ur->findByEmail($emailDTO->getEmail());

            $token = strval(rand(1000000000,9999999999));
            if ($user) {
                $user->setToken($token);

                $entityManager->persist($user);
                $entityManager->flush();
            }
            return $this->redirectToRoute('app_password_change',array('token' => $token));
        }

        return $this->render('forgot_password/email_request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/password/{token}', name: 'app_password_change')]
    public function change(UserPasswordHasherInterface $userPasswordHasher,EntityManagerInterface $entityManager,UserRepository $ur,Request $request,string $token): Response
    {
        $form = $this->createForm(ForgotPasswordType::class, $password = new
        Forgot_PasswordDTO());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() &&
            $password->password==$password->confirmPassword) {
            $user= $ur->findByToken($token);
            if ($user) {
                $user->setPassword($userPasswordHasher->hashPassword($user,
                    $password->password));
                $user->setToken(null);

                $entityManager->persist($user);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_home');
        }
        return $this->render('forgot_password/index.html.twig', [
            'form'=>$form
        ]);
    }
}