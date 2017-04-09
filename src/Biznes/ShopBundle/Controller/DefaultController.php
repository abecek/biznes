<?php

namespace Biznes\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DefaultController extends Controller
{
    /**
     * @Route("/shop")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('BiznesDatabaseBundle:Products')
                ->findAll();
        
        $serializer = $this->get('serializer');
        $response = $serializer->serialize($products,'json');
        
        return $this->render('BiznesShopBundle:Default:index.html.twig',
                array(
                    'products' => $response,
                ));
    }
    
}
