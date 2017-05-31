<?php

namespace Biznes\DatabaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Biznes\DatabaseBundle\Entity\Users;
use Biznes\DatabaseBundle\Form\UsersType;

use Biznes\DatabaseBundle\Entity\UsersData;
use Biznes\DatabaseBundle\Form\UsersDataType;

use Biznes\DatabaseBundle\Entity\UsersAddresses;
use Biznes\DatabaseBundle\Form\UsersAddressType;

class DefaultController extends Controller {

    /**
     * @Route("/database/")
     */
    public function indexAction(Request $req) {
        
    } 

    /**
     * @Route("/login", name="login")
     * @Route("/shop/login", name="login_shop")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        $uri = $request->getRequestUri();
        $from = explode('/', $uri);

        if(in_array('shop', $from)){
            //$cart = \Biznes\ShopBundle\Utils\Cart::getInstance();
            $cart = $this->get('cartManager');
            $cart->loadFromSession();

            return $this->render('BiznesShopBundle:Default:login.html.twig', array(
                'last_username' => $lastUsername,
                'error'         => $error,
                'cart'          => $cart,
            ));
        }
        else{
            return $this->render('BiznesServiceBundle:Default:login.html.twig', array(
                'last_username' => $lastUsername,
                'error'         => $error,
            ));
        }
        //return $this->render('BiznesServiceBundle:Default:login.html.twig');
    }

    
    private function register(Users $user, $form, $referer, $source = null){
        if ($form->isSubmitted() && $form->isValid()) {
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setDateRegister(new \DateTime());
            
            $em = $this->getDoctrine()->getManager();
            
            //SETTING REFERER
            if(is_numeric($referer)){
                $sponsor = $em->getRepository('BiznesDatabaseBundle:Users')->findOneByIdUser($referer);
                if(!empty($sponsor)){
                    $user->setIdSponsor($sponsor);
                }
            }

            // 4) save the User!
            //try{
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
                            //app/Resources/views/Emails/registration.html.twig
                            'Emails/registration.html.twig',
                            array('name' => $form['username']->getData())
                        ),
                        'text/html'
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
        }
        else{
            return false;
        }
    }

    /**
     * @Route("/register/{referer}", name="register")
     */
    public function registerAction(Request $request, $referer = null)
    {   
        // 1) build the form
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        
        if($this->register($user, $form, $referer)){
            return $this->redirectToRoute('account_created_service');
        }
        
        return $this->render('BiznesServiceBundle:Default:register.html.twig', array(
                'register_form' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/shop/register/{referer}", name="register_shop")
     */
    public function shopRegisterAction(Request $request, $referer = null)
    {   
        $cart = $this->get('cartManager');
        $cart->loadFromSession();
        
        // 1) build the form
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        
        //$this->register($user, $form, $referer, 'shop');
        
        if($this->register($user, $form, $referer)){
            return $this->redirectToRoute('account_created_shop');
        }
        
        return $this->render('BiznesShopBundle:Default:register.html.twig', array(
                'register_form' => $form->createView(),
                'cart' => $cart,
        ));
    }
    
    private function addPersonalData(UsersData $userData, $form){
        if ($form->isSubmitted() && $form->isValid()) {
            $userData->setName1($form['name1']->getData());
            
            if(!empty($form['name2'])){
                $userData->setName2($form['name2']->getData());
            }
                
            $userData->setSurname($form['surname']->getData())
                    ->setIdentityNumber($form['identityNumber']->getData())
                    ->setTelephone($form['telephone']->getData())
                    ->setLanguage($form['language']->getData());
            
            $userData->setIdUser($this->getUser());
            
            $em = $this->getDoctrine()->getManager(); 
            $em->persist($userData);
            $em->flush();
            return true;
        }
        else{
            return false;
        }
    }
    
    private function addPersonalAddress(UsersAddresses $userAddress, $form){
        if ($form->isSubmitted() && $form->isValid()) {  
            $userAddress->setCountry($form['country'])
                        ->setCity($form['city'])
                        ->setPostCode($form['postCode'])
                        ->setUlica($form['ulica'])
                        ->setNrHouse($form['nrHouse']);
            //if(!empty($form['nrFlat'])){
                $userAddress->setNrFlat($form['nrFlat']);
            //}
            $userAddress->setIdUser($this->getUser());
            
            $em = $this->getDoctrine()->getManager(); 
            $em->persist($userAddress);
            $em->flush();
            return true;
        }
        else{
            return false;
        }
    }
    
    /**
     * @Route("/personalData", name="personalData")
     */
    public function personalDataAction(Request $request){
        // 1) build the form
        $userData = new UsersData();
        $form1 = $this->createForm(UsersDataType::class, $userData);
        // 2) handle the submit (will only happen on POST)
        $form1->handleRequest($request);
        
        if($this->addPersonalData($userData, $form1)){
            return $this->redirectToRoute('personalDataInfo');
        }
        
        $userAddress = new UsersAddresses();
        $form2 = $this->createForm(UsersAddressType::class, $userAddress);
        $form2->handleRequest($request);
        
        if($this->addPersonalAddress($userAddress, $form2)){
            return $this->redirectToRoute('personalDataInfo');
        }
        
        return $this->render('BiznesServiceBundle:Default:personalData.html.twig', array(
                'data_form' => $form1->createView(),
                'address_form' => $form2->createView(),
        ));
    }

}
