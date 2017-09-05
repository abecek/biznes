<?php

namespace Biznes\DatabaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Biznes\DatabaseBundle\Entity\Users;
use Biznes\DatabaseBundle\Form\UsersType;
use Biznes\DatabaseBundle\Entity\UsersData;
use Biznes\DatabaseBundle\Form\UsersDataType;
use Biznes\DatabaseBundle\Entity\UsersAddresses;
use Biznes\DatabaseBundle\Form\UsersAddressType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class DefaultController extends Controller {

    /**
     * @Route("/database/")
     */
    public function indexAction(Request $req) {
        
    }

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

        $uri = $request->getRequestUri();
        $from = explode('/', $uri);

        if (in_array('shop', $from)) {
            //$cart = \Biznes\ShopBundle\Utils\Cart::getInstance();
            $cart = $this->get('cartManager');
            $cart->loadFromSession();

            return $this->render('BiznesShopBundle:Default:login.html.twig', array(
                        'last_username' => $lastUsername,
                        'error' => $error,
                        'cart' => $cart,
            ));
        } else {
            return $this->render('BiznesServiceBundle:Default:login.html.twig', array(
                        'last_username' => $lastUsername,
                        'error' => $error,
            ));
        }
        //return $this->render('BiznesServiceBundle:Default:login.html.twig');
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

            // 4) save the User!
            //try{
            $em->persist($user);
            $em->flush();

            $uniqueHashForUser = $user->getEmail() . 'activation';
            $uniqueHashForUser = sha1($uniqueHashForUser);

            $userId = $user->getIdUser();

            $message = \Swift_Message::newInstance()
                    ->setSubject('Activate your new account in WebTools')
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

        // 1) build the form
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user, array(
            'attr' => array('class' => 'form'),
        ));
        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);

        if ($this->register($user, $form, $referer)) {
            //if ($source == null) {
            return $this->redirectToRoute('accountCreatedService');
            //}
        }

        return $this->render('BiznesServiceBundle:Default:register.html.twig', array(
                    'registerForm' => $form->createView(),
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

        // 1) build the form
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user, array(
            'attr' => array('class' => 'form'),
        ));

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);

        //$this->register($user, $form, $referer, 'shop');

        if ($this->register($user, $form, $referer)) {
            if ($source == null) {
                return $this->redirectToRoute('accountCreatedShop');
            } elseif ($source == "checkout") {
                return $this->redirectToRoute('checkout');
            }
        }

        return $this->render('BiznesShopBundle:Default:register.html.twig', array(
                    'registerForm' => $form->createView(),
                    'cart' => $cart,
        ));
    }

    private function addPersonalData(UsersData $userData, $form) {
        if ($form->isSubmitted() && $form->isValid()) {
            /*
              $userData->setName1($form['name1']->getData());

              if(!empty($form['name2'])){
              $userData->setName2($form['name2']->getData());
              }

              $userData->setSurname($form['surname']->getData())
              ->setIdentityNumber($form['identityNumber']->getData())
              ->setTelephone($form['telephone']->getData())
              ->setLanguage($form['language']->getData());
             */
            //HOW THIS IS WORKING WITHOUT SETTING PROPERTIES?
            // THESE ONE WAS WORKING BEFORE BUT 
            // CODE SIMILAR IN FUNCTION BELOW WASNT
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
            /*
              $userAddress->setCountry($form['country'])
              ->setCity($form['city'])
              ->setPostCode($form['postCode'])
              ->setStreet($form['street'])
              ->setNrHouse($form['nrHouse']);
              if(!empty($form['nrFlat'])){
              $userAddress->setNrFlat($form['nrFlat']);
              }
             */
            //HOW THIS IS WORKING WITHOUT SETTING PROPERTIES?
            //WHEN IN WAS IN THE FORM LIKE AS ABOVE, WASNT WORKING I DONT KNOW WHY
            /*
             * ERROR BEFORE:
             * Catchable Fatal Error: Object of class Symfony\Component\Form\Form could not be converted to string
             */
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
                    'data_form' => $form1->createView(),
                    'address_form' => $form2->createView(),
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
            'attr' => array('class' => 'form'),
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
                    'data_form' => $dataFormView,
                    'address_form' => $addressFormView,
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
     * @Route("/account/request", name="requestNewPassword")
     * @Method({"POST"})
     */
    public function requestAction(Request $request) {
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
                    ->setSubject('Verification of new password request in AffiliationsTOOLS')
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

//                var_dump($lastPassRequest);
//                echo '<Br>';
//                var_dump($date);
//                echo '<Br>';

                    if ($lastPassRequest < $date) {
                        //1 day has passed, link is dead 
                        return $this->redirectToRoute('homepage');
                    } else {

                        $form = $this->createFormBuilder(null, array(
                                ))->add('oldPassword', PasswordType::class, array(
                                    'label' => 'Stare hasło',
                                    'attr' => array('class' => 'form-control'),
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

                            $oldPassPlainForm = $form['oldPassword']->getData();
                            $oldPassUser = $user->getPassword();

                            $match = $encoderService->isPasswordValid($user, $oldPassPlainForm);

                            if ($match) {
                                $plainNewPass = $form['plainNewPassword']->getData();

                                //$encoder = $this->get('security.password_encoder');
                                $user->setPassword($encoderService->encodePassword($user, $plainNewPass));
                                $user->setPlainPassword($plainNewPass);
                                $user->setDateLastPassRequest(null);
                                $em->persist($user);
                                $em->flush();

                                return $this->redirectToRoute('homepage');
                            }
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

}
