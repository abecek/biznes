<?php

namespace Biznes\DatabaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

use Biznes\DatabaseBundle\Entity\Users;
use Biznes\DatabaseBundle\Form\UsersType;

use Biznes\DatabaseBundle\Entity\UsersData;
use Biznes\DatabaseBundle\Form\UsersDataType;

use Biznes\DatabaseBundle\Entity\UsersAddresses;
use Biznes\DatabaseBundle\Form\UsersAddressType;

use Biznes\DatabaseBundle\Form\RemindPassType;
use Biznes\DatabaseBundle\Form\ResendActivLinkType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class DefaultController extends Controller {

    /**
     * @Route("/login", name="login")
     * @Route("/shop/login", name="loginShop")
     */
    public function loginAction(Request $request) {
        //Check if user is already logged
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You are already a user.');
        }

        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        $formResendActivLink = $this->createForm(ResendActivLinkType::class, null, array(
                    'action' => $this->generateUrl('resendActivLink'),
                    'method' => 'POST',
                    'attr' => array('class' => 'form'),
                ));
        
        $formRemindPassForm = $this->createForm(RemindPassType::class, null, array(
                    'action' => $this->generateUrl('remindPassword'),
                    'method' => 'POST',
                    'attr' => array('class' => 'form'),
                ));
        

        $uri = $request->getRequestUri();
        $from = explode('/', $uri);

        if (in_array('shop', $from)) {
            $cart = $this->get('cartManager');
            $cart->loadFromSession();

            return $this->render('BiznesShopBundle:Default:login.html.twig', array(
                        'last_username' => $lastUsername,
                        'error' => $error,
                        'cart' => $cart,
                        'formResendActivLink' => $formResendActivLink->createView(),
                        'formRemindPassForm' => $formRemindPassForm->createView(),
            ));
        } 
        else{
            return $this->render('BiznesServiceBundle:Default:login.html.twig', array(
                        'last_username' => $lastUsername,
                        'error' => $error,
                        'formResendActivLink' => $formResendActivLink->createView(),
                        'formRemindPassForm' => $formRemindPassForm->createView(),
            ));
        }
    }

    /**
     * @Route("/activate/{userId}/{hash}", name="activationLink")
     */
    public function activateAction($userId = null, $hash = null) {
        if ($hash != null && is_numeric($userId)) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('BiznesDatabaseBundle:Users')
                    ->findOneByIdUser($userId);

            $uniqueHashForUser = $user->getEmail() . 'activation';
            $uniqueHashForUser = sha1($uniqueHashForUser);

            if ($user->getIsActive() == 1) {
                throw $this->createNotFoundException('Your account has been already activated.');
            } else {
                if ($hash == $uniqueHashForUser) {
                    $user->setIsActive(1);
                    $em->persist($user);
                    $em->flush();
                    return $this->render('BiznesDatabaseBundle:Default:activate.html.twig');
                } else {
                    throw $this->createNotFoundException('Your activation link is not valid.');
                }
            }
        } else {
            throw $this->createNotFoundException('Your activation link is not correct.');
        }
    }

    private function register(Users $user, $form, $referer, $source = null) {
        if ($form->isSubmitted() && $form->isValid()) {
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setDateRegister(new \DateTime());

            $em = $this->getDoctrine()->getManager();

            //SETTING REFERER
            $session = new \Symfony\Component\HttpFoundation\Session\Session();
            $refererFromSession = $session->get('referer');
            $sponsor = null;

            if (is_numeric($refererFromSession)) {
                $sponsor = $em->getRepository('BiznesDatabaseBundle:Users')->findOneByIdUser($refererFromSession);
            } elseif (is_numeric($referer)) {
                $sponsor = $em->getRepository('BiznesDatabaseBundle:Users')->findOneByIdUser($referer);
            }

            if (!empty($sponsor)) {
                $user->setIdSponsor($sponsor);
            }

            //save the User
            //try{
            $em->persist($user);
            $em->flush();

            $uniqueHashForUser = $user->getEmail() . 'activation';
            $uniqueHashForUser = sha1($uniqueHashForUser);

            $userId = $user->getIdUser();

            $message = \Swift_Message::newInstance()
                    ->setSubject('Aktywuj swoje konto w AffiliationTOOLS!')
                    ->setFrom('michal.blaszcz@gmail.com')
                    ->setTo($form['email']->getData())
                    ->setBody(
                    $this->renderView(
                            //app/Resources/views/Emails/registration.html.twig
                            'Emails/registration.html.twig', array(
                        'name' => $form['username']->getData(),
                        'userId' => $userId,
                        'uniqueHashForUser' => $uniqueHashForUser,
                            )
                    ), 'text/html'
            );
            $this->get('mailer')->send($message);

            return true;
            /*
              }
              catch(Exception $e){
              throw $e;
              }
             * 
             */
        } else {
            return false;
        }
    }

    /**
     * @Route("/register/{referer}/{source}", name="register", requirements={"referer": "\d+"})
     */
    public function registerAction(Request $request, $referer = null, $source = null) {
        //Check if user is already logged
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You are already a user.');
        }
        
        $formResendActivLink = $this->createForm(ResendActivLinkType::class, null, array(
                    'action' => $this->generateUrl('resendActivLink'),
                    'method' => 'POST',
                    'attr' => array('class' => 'form'),
                ));
        
        $formRemindPassForm = $this->createForm(RemindPassType::class, null, array(
                    'action' => $this->generateUrl('remindPassword'),
                    'method' => 'POST',
                    'attr' => array('class' => 'form'),
                ));

        $user = new Users();
        $form = $this->createForm(UsersType::class, $user, array(
            'attr' => array('class' => 'form'),
        ));
        $form->handleRequest($request);

        if ($this->register($user, $form, $referer)) {
            //if ($source == null) {
            return $this->redirectToRoute('accountCreatedService');
            //}
        }

        return $this->render('BiznesServiceBundle:Default:register.html.twig', array(
                    'registerForm' => $form->createView(),
                    'formResendActivLink' => $formResendActivLink->createView(),
                    'formRemindPassForm' => $formRemindPassForm->createView(),
        ));
    }

    /**
     * @Route("/shop/register/{referer}/{source}", name="registerShop", requirements={"referer": "\d+"})
     */
    public function shopRegisterAction(Request $request, $referer = null, $source = null) {
        //Check if user is already logged
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You are already a user.');
        }

        $cart = $this->get('cartManager');
        $cart->loadFromSession();

        $user = new Users();
        $form = $this->createForm(UsersType::class, $user, array(
            'attr' => array('class' => 'form'),
        ));

        $form->handleRequest($request);
        if ($this->register($user, $form, $referer)) {
            if ($source == null) {
                return $this->redirectToRoute('accountCreatedShop');
            } elseif ($source == "checkout") {
                return $this->redirectToRoute('checkout');
            }
        }
        
        $formResendActivLink = $this->createForm(ResendActivLinkType::class, null, array(
                    'action' => $this->generateUrl('resendActivLink'),
                    'method' => 'POST',
                    'attr' => array('class' => 'form'),
                ));
        
        $formRemindPassForm = $this->createForm(RemindPassType::class, null, array(
                    'action' => $this->generateUrl('remindPassword'),
                    'method' => 'POST',
                    'attr' => array('class' => 'form'),
                ));

        return $this->render('BiznesShopBundle:Default:register.html.twig', array(
                    'registerForm' => $form->createView(),
                    'cart' => $cart,
                    'formResendActivLink' => $formResendActivLink->createView(),
                    'formRemindPassForm' => $formRemindPassForm->createView(),
        ));
    }

    private function addPersonalData(UsersData $userData, $form) {
        if ($form->isSubmitted() && $form->isValid()) {
            $userData->setIdUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($userData);
            $em->flush();
            return true;
        } else {
            return false;
        }
    }

    private function addPersonalAddress(UsersAddresses $userAddress, $form) {
        if ($form->isSubmitted() && $form->isValid()) {
            $userAddress->setIdUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($userAddress);
            $em->flush();
            return true;
        } else {
            return false;
        }
    }

    /**
     * @Route("/personalData", name="personalData")
     * Adding and Editing PersonalData and PersonalAddress
     */
    public function personalDataAction(Request $request) {
        //Check if user is fully authenticated
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }

        // 1) build the form
        $user = $this->getUser();
        $um = $this->get('userManager');
        $um->loadDataFromUser($user);

        $userData = $um->get('userData');
        $userAddress = $um->get('userAddress');

        $form1 = $this->createForm(UsersDataType::class, $userData, array(
            'userData' => $userData,
            'attr' => array('class' => 'form'),
        ));
        // 2) handle the submit (will only happen on POST)
        $form1->handleRequest($request);

        if ($this->addPersonalData($userData, $form1)) {
            return $this->redirectToRoute('personalDataInfo');
        }

        $form2 = $this->createForm(UsersAddressType::class, $userAddress, array(
            'userAddress' => $userAddress,
        ));
        $form2->handleRequest($request);

        if ($this->addPersonalAddress($userAddress, $form2)) {
            return $this->redirectToRoute('personalDataInfo');
        }
        
        return $this->render('BiznesServiceBundle:Default:personalData.html.twig', array(
                'dataForm' => $form1->createView(),
                'addressForm' => $form2->createView(),


        ));
    }

    /**
     * @Route("/shop/personalData/{source}/{dataToEdit}", name="personalDataShop")
     * Adding and Editing PersonalData and PersonalAddress
     */
    public function shopPersonalDataAction(Request $request, $source = null, $dataToEdit = null) {
        //Check if user is fully authenticated
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }

        $cart = $this->get('cartManager');
        $cart->loadFromSession();
        // 1) build the form
        $user = $this->getUser();
        $um = $this->get('userManager');
        $um->loadDataFromUser($user);

        $userData = $um->get('userData');
        $userAddress = $um->get('userAddress');

        $form1 = null;

        //if ($dataToEdit == 'personal') {
        $form1 = $this->createForm(UsersDataType::class, $userData, array(
            'userData' => $userData,
            'attr' => array(
                'class' => 'form',
                'id' => 'userDataForm',
            ),
        ));
        // 2) handle the submit (will only happen on POST)
        $form1->handleRequest($request);
        if ($this->addPersonalData($userData, $form1)) {
            if ($source == null) {
                return $this->redirectToRoute('shopPersonalDataInfo');
            } elseif ($source == "checkout") {
                return $this->redirectToRoute('checkout');
            }
        }
        //}

        $form2 = null;

        //if ($dataToEdit == 'address') {
        $form2 = $this->createForm(UsersAddressType::class, $userAddress, array(
            'userAddress' => $userAddress,
            'attr' => array(
                'class' => 'form',
                'id' => 'userAddressForm',
            ),
        ));
        $form2->handleRequest($request);

        if ($this->addPersonalAddress($userAddress, $form2)) {
            if ($source == null) {
                return $this->redirectToRoute('shopPersonalDataInfo');
            } elseif ($source == "checkout") {
                return $this->redirectToRoute('checkout');
            }
        }
        //}

        $form1 !== null ? $dataFormView = $form1->createView() : $dataFormView = null;
        $form2 !== null ? $addressFormView = $form2->createView() : $addressFormView = null;


        return $this->render('BiznesShopBundle:Default:personalData.html.twig', array(
                    'cart' => $cart,
                    'dataForm' => $dataFormView,
                    'addressForm' => $addressFormView,
        ));
    }

    /**
     * @Route("/account", name="accountData")
     */
    public function accountAction() {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }

        return $this->render('BiznesServiceBundle:Default:accountData.html.twig', array(
        ));
    }

    /**
     * @Route("/account/requestpass", name="requestNewPassword")
     * @Method({"POST"})
     */
    public function requestNewPasswordAction(Request $request) {
        //Check if user is fully authenticated
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }

        $user = $this->getUser();
        if ($request->request->get('type') == 'password') {
            $uniqueHashForUser = $user->getEmail() . 'passwordChange';
            $uniqueHashForUser = sha1($uniqueHashForUser);

            $user->setDateLastPassRequest(new \DateTime);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $message = \Swift_Message::newInstance()
                    ->setSubject('Potwierdzenie prośby o nowe hasło w AffiliationsTOOLS')
                    ->setFrom('michal.blaszcz@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                    $this->renderView(
                            //app/Resources/views/Emails/newPassword.html.twig
                            'Emails/newPassword.html.twig', array(
                        'name' => $user->getUsername(),
                        'userId' => $user->getIdUser(),
                        'uniqueHashForUser' => $uniqueHashForUser,
                            )
                    ), 'text/html'
            );
            $this->get('mailer')->send($message);

            return $this->redirectToRoute('homepage');
        } else {
            exit;
        }

        return $this->render('BiznesServiceBundle:Default:accountData.html.twig', array(
        ));
    }

    /**
     * @Route("/account/changepassword/{userId}/{hash}", name="changePassword")
     */
    public function changePasswordAction(Request $request, $userId = null, $hash = null) {
        if (is_numeric($userId) && $hash !== null) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('BiznesDatabaseBundle:Users')
                    ->findOneByIdUser($userId);

            $uniqueHashForUser = $user->getEmail() . 'passwordChange';
            $uniqueHashForUser = sha1($uniqueHashForUser);

            if ($hash == $uniqueHashForUser) {
                $date = new \DateTime;
                if ($user->getDateLastPassRequest() !== null) {
                    $lastPassRequest = $user->getDateLastPassRequest()->modify('+1 day');

                    if ($lastPassRequest < $date) {
                        //1 day has passed, link is dead 
                        return $this->redirectToRoute('homepage');
                    } 
                    else{
                        $form = $this->createFormBuilder(null, array(
                                ))->add('plainNewPassword', RepeatedType::class, array(
                                    'type' => PasswordType::class,
                                    'first_options' => array(
                                        'label' => 'Hasło:',
                                        'attr' => array('class' => 'form-control')
                                    ),
                                    'second_options' => array(
                                        'label' => 'Powtórz hasło:',
                                        'attr' => array('class' => 'form-control')
                                    ),
                                ))->add('createAccount', SubmitType::class, array(
                                    'label' => 'Zmień hasło!',
                                    'attr' => array(
                                        'class' => 'btn btn-primary btn-block',
                                    ),
                                ))->getForm();

                        $form->handleRequest($request);
                        if ($form->isSubmitted() && $form->isValid()) {
                                $encoderService = $this->get('security.password_encoder');
                                $plainNewPass = $form['plainNewPassword']->getData();

                                //$encoder = $this->get('security.password_encoder');
                                $user->setPassword($encoderService->encodePassword($user, $plainNewPass));
                                $user->setPlainPassword($plainNewPass);
                                $user->setDateLastPassRequest(null);
                                $em->persist($user);
                                $em->flush();

                                return $this->redirectToRoute('homepage');
                                // TO DO VIEW WITH MSG
                        }

                        return $this->render('BiznesServiceBundle:Default:changePassword.html.twig', array(
                                    'changePassForm' => $form->createView(),
                        ));
                    }
                }
                else{
                    throw new \Exception('You have to send request for changing password before.');
                }
            }
        }
        throw new \Exception('Your URL address is wrong');
    }

    /**
     * @Route("/remindpassword/{$hash}", name="remindPassword")
     * @Method({"POST", "GET"})
     */
    public function remindPassAction(Request $request, $hash = null){
            $form = $request->request->get('remind_pass');

            if($form['email'] !== null){
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository('BiznesDatabaseBundle:Users')
                        ->findOneBy(array(
                            'email' => $form['email'],
                        ));
                if($user !== null){
                    $uniqueHashForUser = $user->getEmail() . 'passwordChange';
                    $uniqueHashForUser = sha1($uniqueHashForUser);

                    $user->setDateLastPassRequest(new \DateTime);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();

                    $message = \Swift_Message::newInstance()
                            ->setSubject('Potwierdzenie prośby o nowe hasło w AffiliationsTOOLS')
                            ->setFrom('michal.blaszcz@gmail.com')
                            ->setTo($user->getEmail())
                            ->setBody(
                            $this->renderView(
                                    //app/Resources/views/Emails/newPassword.html.twig
                                    'Emails/newPassword.html.twig', array(
                                'name' => $user->getUsername(),
                                'userId' => $user->getIdUser(),
                                'uniqueHashForUser' => $uniqueHashForUser,
                                    )
                            ), 'text/html'
                    );
                    $this->get('mailer')->send($message);

                    return $this->redirectToRoute('login');           
                }
                else{
                    throw new \Exception('User with that username or email does not exists.');
                }
            }
            else{
                throw new \Exception('Something very bad happened.');
            }
    }
    
    /**
     * @Route("/resendactivlink", name="resendActivLink")
     */
    public function resendActivLinkAction(Request $request){
        $form = $request->request->get('resend_activ_link');
        
        if($form['emailOrUsername'] !== null){
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('BiznesDatabaseBundle:Users');
            $user = $repo->findOneBy(array(
                        'email' => $form['emailOrUsername'],
                    ));

            if($user === null){
                $user = $repo->findOneBy(array(
                        'username' => $form['emailOrUsername'],
                    ));
            }
            
            if($user !== null){
                if(!$user->isActive()){
                    $uniqueHashForUser = $user->getEmail() . 'activation';
                    $uniqueHashForUser = sha1($uniqueHashForUser);
                    
                    $message = \Swift_Message::newInstance()
                            ->setSubject('Aktywuj swoje konto w AffiliationTOOLS!')
                            ->setFrom('michal.blaszcz@gmail.com')
                            ->setTo($user->getEmail())
                            ->setBody(
                            $this->renderView(
                                    //app/Resources/views/Emails/registration.html.twig
                                    'Emails/registration.html.twig', array(
                                'name' => $user->getUsername(),
                                'userId' => $user->getIdUser(),
                                'uniqueHashForUser' => $uniqueHashForUser,
                                    )
                            ), 'text/html');
                    
                    $this->get('mailer')->send($message);
                    
                    return $this->redirectToRoute('login');
                }
                else{
                    throw new \Exception('You account has been already activated.');
                }
            }
            else{
                throw new \Exception('User with that username or email does not exists.');
            }
        }
        else{
            throw new \Exception('Something very bad happened.');
        }
    }
    
    /**
     * @Route("/account/requestemail", name="requestNewEmail")
     * @Method({"POST"})
     */
    public function requestNewEmailAction(Request $request) {
        //Check if user is fully authenticated
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }

        $user = $this->getUser();
        if ($request->request->get('type') == 'email') {
            if($user->getCanChangeEmail()){
                //$uniqueHashForUser = $user->getEmail() . 'emailChange';
                //$uniqueHashForUser = sha1($uniqueHashForUser);

                $user->setCanChangeEmail(1);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                /*
                $message = \Swift_Message::newInstance()
                        ->setSubject('Potwierdzenie prośby o nowe hasło w AffiliationsTOOLS')
                        ->setFrom('michal.blaszcz@gmail.com')
                        ->setTo($user->getEmail())
                        ->setBody(
                        $this->renderView(
                                //app/Resources/views/Emails/newEmail.html.twig
                                'Emails/newEmail.html.twig', array(
                            'name' => $user->getUsername(),
                            'userId' => $user->getIdUser(),
                            'uniqueHashForUser' => $uniqueHashForUser,
                                )
                        ), 'text/html'
                );
                $this->get('mailer')->send($message);
                */
            }
            return $this->redirectToRoute('homepage');
        } else {
            exit;
        }

        return $this->render('BiznesServiceBundle:Default:accountData.html.twig', array(
        ));
    }
    
}
