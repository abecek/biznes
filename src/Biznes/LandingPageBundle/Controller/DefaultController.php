<?php

namespace Biznes\LandingPageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/landing/")
     */
    public function indexAction()
    {
        return $this->render('BiznesLandingPageBundle:Default:index.html.twig');
    }
}
