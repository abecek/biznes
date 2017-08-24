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
        $userId = null;
        if ($user != null) $userId = $user->getIdUser();
        
        $um = $this->get('userManager');
        $um->loadDataFromUser($user);
        return $this->render('BiznesServiceBundle:Default:index.html.twig', array(
                'um' => $um,
                'userId' => $userId,
            ));
    }

    /**
     * @Route("/created", name="accountCreatedService")
     */
    public function createdAction() {
        return $this->render('BiznesServiceBundle:Default:registerCreated.html.twig');
    }

    /**
     * @Route("/personalInfo", name="personalDataInfo")
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
            $wm = $this->get('walletManager');

            $wm->countMoneyInWallet($user);
            $moneyInWallet = $wm->getMoneyInWallet();

            return $this->render('BiznesServiceBundle:Default:dashboard.html.twig', array(
                    'moneyInWallet' => $moneyInWallet,
                    'wm' => $wm,
            ));
        }
        else{
            throw $this->createAccessDeniedException('You must be logged in to see this page.');
        }    
    }
    
    /**
     * @Route("/incomes", name="incomes")
     */
    public function incomesAction(){
        $wm = $this->get('walletManager');
        $user = $this->getUser();
        $incomes = $wm->loadWalletManagerFromDB($user)->getIncomes();
        
        return $this->render('BiznesServiceBundle:Default:incomes.html.twig', array(
            'incomes' => $incomes,
        ));
    }
    
    /**
     * @Route("/expanses", name="expanses")
     */
    public function expansesAction(){
        $wm = $this->get('walletManager');
        $user = $this->getUser();
        $expanses = $wm->loadWalletManagerFromDB($user)->getExpanses();
        
        return $this->render('BiznesServiceBundle:Default:expanses.html.twig', array(
            'expanses' => $expanses,
        ));
    }

}
