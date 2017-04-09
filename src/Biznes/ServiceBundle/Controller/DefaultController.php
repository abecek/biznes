<?php

namespace Biznes\ServiceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Biznes\DatabaseBundle\Entity\Users;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('BiznesServiceBundle:Default:index.html.twig');
    }

    /**
     * @Route("/created", name="account_created")
     */
    public function createdAction(){   
        return $this->render('BiznesServiceBundle:Default:register_create.html.twig');
    }
    
    /**
     * @Route("/logged", name="logged")
     */
    public function loggedAction(){   
        $user = $this->getUser();
        
        if ($user == null) $roles = array('Brak');
        else $roles = $user->getRoles();
        
        return $this->render('BiznesServiceBundle:Default:logged.html.twig', array(
            'role' => $roles
        ));
    }
    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction(){   
        $user = $this->getUser();
        return $this->render('BiznesServiceBundle:Default:logged.html.twig', array(
            'role' => $user->getRoles()
        ));
    }
} 
