<?php

namespace App\Controller;

use App\Entity\Photos;
use App\Entity\User;

use App\Form\type\uploadType;
use App\Form\type\UserType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    private $logger;

    /**
     * ProfileController constructor.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;

    }

    /**
     * @Route("/profile/{id}", name="profile")
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function index(User $user, Request $request)
    {
        $photo = new Photos();
        $form = $this->createForm(uploadType::class, $photo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $photo->setUserId($user);

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($photo);
            $entityManager->flush();
        }
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
