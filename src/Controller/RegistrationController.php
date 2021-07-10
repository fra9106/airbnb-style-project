<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileFormType;
use App\Entity\PasswordUpdate;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use App\Form\PasswordUpdateFormType;
use Symfony\Component\Form\FormError;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="app_register")
     * 
     * @return Response
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator
    ): Response {
        $user = new User();
        $user->setCreatedAt(new \Datetime());
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('contact@monpersoweb.fr', '"AirBnB Admin"'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     * 
     * @return Response
     */
    public function verifyUserEmail(
        Request $request,
        UserRepository $userRepository
    ): Response {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('message', 'Your email address has been verified.');

        return $this->redirectToRoute('app_homepage');
    }

    /**
     * @Route("/profile/edit", name="app_profile_edit")
     * 
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function profileEdit(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $user = $this->getUser();

        $form = $this->createForm(ProfileFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('message', "Vos modifications ont été pris en compte 😊 ");
        }

        return $this->render('user/profile_edit.html.twig', [
            'profileForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/password-update", name="app_password_update")
     * 
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function passwordUpdate(
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response {

        $user = $this->getUser();

        $passwordUpdate = new PasswordUpdate();

        $form = $this->createForm(PasswordUpdateFormType::class, $passwordUpdate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!password_verify($passwordUpdate->getOldPassword(), $user->getPassword())) {
                $form->get('oldPassword')->addError(new FormError("Ce mot de passe n'est pas votre mot de passe actuel 😕"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $encoded = $passwordEncoder->encodePassword($user, $newPassword);

                $user->setPassword($encoded);
                $manager->persist($user);
                $manager->flush();


                $this->addFlash('message', "Votre nouveau mot de passe à bien été pris en compte 😊 ");

                return $this->redirectToRoute('app_homepage');
            }
        }

        return $this->render('security/new_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * account of the user by his property 
     *
     * @Route("/profile/{slug}/", name="app_profile_display")
     *
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function profileDisplay(User $user): Response
    {
        return $this->render('user/profile_display.html.twig', [
            'user' => $user
        ]);
    }

}
