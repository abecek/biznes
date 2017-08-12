<?php

namespace Biznes\ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;

use Biznes\Utils\WalletManager;

class DefaultController extends Controller {

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction() {
        $user = $this->getUser();
        $um = $this->get('userManager');
        $um->loadDataFromUser($user);
        return $this->render('BiznesServiceBundle:Default:index.html.twig', array('um' => $um,));
    }

    /**
     * @Route("/created", name="account_created_service")
     */
    public function createdAction() {
        return $this->render('BiznesServiceBundle:Default:register_created.html.twig');
    }

    /**
     * @Route("/personalinfo", name="personalDataInfo")
     */
    public function personalInfoAction() {
        return $this->render('BiznesServiceBundle:Default:personalDataInfo.html.twig');
    }

    /**
     * @Route("/logged", name="logged")
     */
    public function loggedAction() {
        $user = $this->getUser();

        if ($user == null)
            $roles = array('Brak');
        else
            $roles = $user->getRoles();

        return $this->render('BiznesServiceBundle:Default:logged.html.twig', array(
                    'role' => $roles
        ));
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction() {
        $user = $this->getUser();
        return $this->render('BiznesServiceBundle:Default:logged.html.twig', array(
                    'role' => $user->getRoles()
        ));
    }
    
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction(){
        $user = $this->getUser();
        if($user != null){
            $em = $this->getDoctrine()->getManager();
            $wm = new WalletManager($em);

            $wm->countMoneyInWallet($user);
            $moneyInWallet = $wm->getMoneyInWallet();

            return $this->render('BiznesServiceBundle:Default:dashboard.html.twig', array(
                    'moneyInWallet' => $moneyInWallet,
            ));
        }
        else{
            throw $this->createAccessDeniedException('You must be logged in to see this page.');
        }
        
    }

}
