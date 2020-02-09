<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\type\UserType;
use App\Security\LoginFormAuthenticator;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    private $guardHandler;
    private $loginFormAuthenticator;
    private $logger;

    /**
     * RegistrationController constructor.
     * @param GuardAuthenticatorHandler $guardHandler
     * @param LoginFormAuthenticator $loginFormAuthenticator
     */
    public function __construct(GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $loginFormAuthenticator, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->guardHandler = $guardHandler;
        $this->loginFormAuthenticator = $loginFormAuthenticator;
    }

    /**
     * @Route("/register", name="user_registration")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse|Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPasswordHash($password);

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_login');

        }

        return $this->render(
            'registration/index.html.twig',
            array('form' => $form->createView())
        );
    }
}