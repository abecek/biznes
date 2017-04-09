<?php

namespace Biznes\DatabaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Biznes\DatabaseBundle\Entity\Users;
use Biznes\DatabaseBundle\Form\UsersType;

class DefaultController extends Controller {

    /**
     * @Route("/database/")
     */
    public function indexAction(Request $req) {
        
    } 

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('BiznesServiceBundle:Default:login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
        //return $this->render('BiznesServiceBundle:Default:login.html.twig');
    }

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {   
        // 1) build the form
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setDateRegister(new \DateTime());

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user
            $message = \Swift_Message::newInstance()
                ->setSubject('Hello Email')
                ->setFrom('michal.blaszcz@gmail.com')
                ->setTo($form['email']->getData())
                ->setBody(
                    $this->renderView(
                        // app/Resources/views/Emails/registration.html.twig
                        'Emails/registration.html.twig',
                        array('name' => $form['username']->getData())
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);

            return $this->redirectToRoute('account_created');
        }

        
        return $this->render('BiznesServiceBundle:Default:register.html.twig', array(
            'register_form' => $form->createView()
        ));
    }

}
