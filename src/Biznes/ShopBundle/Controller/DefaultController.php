<?php

namespace Biznes\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DefaultController extends Controller
{
    /**
     * @Route("/shop", name="shop")
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
                    'products' => $products,
                ));
    }
    
    /**
     * @Route("/product/{id}", name="product")
     */
    public function productAction($id){
        if(is_numeric($id)){
            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository('BiznesDatabaseBundle:Products')
                ->findOneByIdProduct($id);
            
            if(!empty($product)){
                $serializer = $this->get('serializer');
                $response = $serializer->serialize($product,'json');
                
                return $this->render('BiznesShopBundle:Default:product.html.twig',
                        array(
                            'product' => $response,
                        ));
            }
            else{
                //TO DO
                return $this->render('BiznesShopBundle:Default:index.html.twig',
                array(
                    'products' => array(),
                ));
            }
            
        }
    }
    
}
