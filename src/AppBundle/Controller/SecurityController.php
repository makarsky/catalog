<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17.01.17
 * Time: 19:17
 */

namespace AppBundle\Controller;

use AppBundle\Entity\ConfirmEmail;
use AppBundle\Entity\ResetPassword;
use AppBundle\Entity\User;
use AppBundle\Form\User\RecoveryPasswordType;
use AppBundle\Form\User\ResetPasswordType;
use AppBundle\Form\User\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return $this->render('auth/index.html.twig');
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
    }

    /**
     * @param Request $request
     * @Route("/register", name="register")
     * @return Response
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $encoder = $this->get('security.password_encoder');
            $user->setPassword($encoder->encodePassword(
                $user,
                $form->get('password')->getData()
            ));
            $user->setIsActive(false);
            $user->setRole('ROLE_USER');
            $em->persist($user);

            $hash = md5(uniqid(null, true));
            $confirmEmail = new ConfirmEmail();
            $confirmEmail->setEmail($form->get('email')->getData());
            $confirmEmail->setHashKey($hash);
            $message = \Swift_Message::newInstance()
                ->setSubject('Please verify your email address')
                ->setFrom('catalog@gmail.com')
                ->setTo($form->get('email')->getData())
                ->setBody('Hi ' . $user->getUsername() . ',
                Please verify your email address so we know that it\'s really you!
                http://localhost:8000/confirm_email/' . $hash);
            $this->get('mailer')->send($message);
            $em->persist($confirmEmail);
            $em->flush();

            $this->addFlash('notice', 'Activation instructions were sent to your email!');
            return $this->redirectToRoute('login');
        }
        return $this->render('auth/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @Route("/reset_password", name="reset_password")
     * @return Response
     */
    public function resetPasswordAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userRep = $em->getRepository('AppBundle:User');
        $form = $this->createForm(ResetPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $email = $form->get('email')->getData();
            $user = $userRep->findOneByEmail($email);
            if (!is_null($user)) {
                $resetPassword = new ResetPassword();
                $resetPassword->setEmail($email);
                $hash = md5(uniqid(null, true));
                $resetPassword->setHashKey($hash);

                $message = \Swift_Message::newInstance()
                    ->setSubject('Password recovery')
                    ->setFrom('catalog@gmail.com')
                    ->setTo($email)
                    ->setBody('To reset you password please follow this link:
                    http://localhost:8000/password_recovery/' . $hash);
                $this->get('mailer')->send($message);
                $em->persist($resetPassword);
                $em->flush();
                $this->addFlash('notice', 'Instructions were sent to you email!');
                return $this->redirectToRoute('login');
            } else {
                $this->addFlash('notice', 'User with that email not found!');
                return $this->redirectToRoute('reset_password');
            }
        }
        return $this->render('auth/reset_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @Route("/password_recovery/{hash}", name="password_recovery")
     * @return Response
     */
    public function passwordRecoveryAction(Request $request, $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $userRep = $em->getRepository('AppBundle:User');
        $resetRep = $em->getRepository('AppBundle:ResetPassword');

        $forgetter = $resetRep->findOneByHashKey($hash);

        if (!is_null($forgetter)) {
            $form = $this->createForm(RecoveryPasswordType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $user = $userRep->findOneByEmail($forgetter->getEmail());
                $encoder = $this->get('security.password_encoder');
                $user->setPassword($encoder->encodePassword(
                    $user,
                    $form->get('new_password')->getData()
                ));
                $em->remove($forgetter);
                $em->persist($user);
                $em->flush();
                $this->addFlash('notice', 'Your password has been reset successfully!');
                return $this->redirectToRoute('login');
            }
            return $this->render('auth/reset_password.html.twig', [
                'form' => $form->createView()
            ]);
        } else {
            return $this->redirectToRoute('index');
        }
    }

    /**
     * @param Request $request
     * @Route("/confirm_email/{hash}", name="confirm_email")
     * @return Response
     */
    public function confirmEmailAction(Request $request, $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $userRep = $em->getRepository('AppBundle:User');
        $confirmRep = $em->getRepository('AppBundle:ConfirmEmail');

        $confirm = $confirmRep->findOneByHashKey($hash);

        if (!is_null($confirm)) {
            $user = $userRep->findOneByEmail($confirm->getEmail());
            $user->setIsActive(true);
            $em->remove($confirm);
            $em->persist($user);
            $em->flush();
            $this->addFlash('notice', 'Your account has been activated successfully!');
            return $this->redirectToRoute('login');
        } else {
            return $this->redirectToRoute('index');
        }
    }
}