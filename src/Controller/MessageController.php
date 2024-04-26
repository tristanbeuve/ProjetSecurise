<?php

namespace App\Controller;

use App\DTO\MessageDTO;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use function Symfony\Component\Clock\now;

class MessageController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/messages', name: 'app_messages')]
    public function index(MessageRepository $mr, Request $request,
                          EntityManagerInterface $em, UserRepository $ur):
    Response
    {
        $id = $this->getUser()->getId();
        $user = $ur->findOneBy(['id' => $id]);

        $messages = $mr->findAllByUser($id);


        $messageDTO = new MessageDTO();
        $form = $this->createForm(MessageType::class, $messageDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = new Message();
            $message->setContent($messageDTO->content);
            $message->setAuthor($user);
            $message->setDate(new \DateTime('now'));

            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('app_messages');
        }

        return $this->render('message/index.html.twig', [
            'messages' => $messages,
            'form' => $form->createView(),
        ]);
    }
}