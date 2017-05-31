<?php

namespace Biznes\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

use Biznes\ShopBundle\Utils\Cart;


class DefaultController extends Controller
{
    /**
     * @Route("/shop/{referer}", name="shop", requirements={"referer": "\d+"})
     */
    public function indexAction($referer = null)
    {
        $session = new Session();

        if ($referer != null) {
            $session->set('referer', $referer);
        }

        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('BiznesDatabaseBundle:Products')
                ->findAll();
        
        $serializer = $this->get('serializer');
        $response = $serializer->serialize($products,'json');
        /*
        $cart = Cart::getInstance();
        $cart->loadFromSession();
        */
        $cart = $this->get('cartManager');
        $cart->loadFromSession();
        return $this->render('BiznesShopBundle:Default:index.html.twig',
                array(
                    'products' => $products,
                    'cart' => $cart
                
                ));
    }
    
    /**
     * @Route("/product/{id}/{referer}", name="product", requirements={"id": "\d+", "referer": "\d+"})
     */
    public function productAction($id, $referer = null){
        $session = new Session();
        if ($referer != null) {
            $session->set('referer', $referer);
        }
        
        if(is_numeric($id)){
            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository('BiznesDatabaseBundle:Products')
                ->findOneByIdProduct($id);
            /*
            $cart = Cart::getInstance();
            $cart->loadFromSession();
            */
            $cart = $this->get('cartManager');
            $cart->loadFromSession();
            
            if(!empty($product)){
                $serializer = $this->get('serializer');
                $response = $serializer->serialize($product,'json');
                
                return $this->render('BiznesShopBundle:Default:product.html.twig',
                        array(
                            'product' => $product,
                            'cart' => $cart,
                        ));
            }
            else{
                //TO DO
                return $this->render('BiznesShopBundle:Default:index.html.twig',
                array(
                    'products' => array(),
                    'cart' => $cart,
                ));
            }
            
        }
    }
    
    /**
     * @Route("/cart", name="cart")
     * @Method({"GET","HEAD"})
     */
    public function showCartAction(){
        $cart = $this->get('cartManager');
        $cart->loadFromSession();
        /*
        $cart = Cart::getInstance();
        $cart->loadFromSession();
        */
        return $this->render('BiznesShopBundle:Default:cart.html.twig',
                array(
                    'cart' => $cart
                )); 

    }
    
    /**
     * @Route("/cart")
     * @Method("POST")
     */
    public function addToCartAction(Request $request){
        $data['idProduct'] = $request->get('idProduct');
        $data['desc'] = $request->get('desc');
        
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('BiznesDatabaseBundle:Products')
            ->findOneByIdProduct($data['idProduct']);
        //$product2 = $em->getRepository('BiznesDatabaseBundle:Products')
        //    ->findOneByIdProduct($data['idProduct']+1);
        
        $cart = $this->get('cartManager');
        $cart->loadFromSession();
        /*
        $cart = Cart::getInstance();
        $cart->loadFromSession();
         */
        $cart->addProduct($product, $data['desc']);
        
        //$cart->addProduct($product2, $data['desc']);
        //$cart->addProductByIdProduct($data['idProduct'], $data['desc']);
        //$cart->saveToSession();
        
        //$serializer = $this->get('serializer');
        //$response = $serializer->serialize($cart,'json');
        
        return $this->render('BiznesShopBundle:Default:cart.html.twig',
                array(
                    'cart' => $cart
                ));   
        
    }
    
    /**
     * @Route("/cart")
     * @Method("DELETE")
     */
    public function removeFromCartAction(Request $request){
        $data['idProduct'] = $request->get('idProduct');
        /*
        $cart = Cart::getInstance();
        $cart->loadFromSession();
        */
        $cart = $this->get('cartManager');
        $cart->loadFromSession();
        $cart->removeProductById($data['idProduct']);
        
        return $this->render('BiznesShopBundle:Default:cart.html.twig',
                array(
                    'cart' => $cart
                )); 
    }
    
    /**
     * @Route("/shop/checkout", name="checkout")
     */
    public function checkoutAction(){
        /*
        $cart = Cart::getInstance();
        $cart->loadFromSession();
        */
        $cart = $this->get('cartManager');
        $cart->loadFromSession();
        
        $user = $this->getUser();
        
        if ($user == null) $roles = array('Brak');
        else $roles = $user->getRoles();
        
        //if(in_array('ROLE_USER', $roles)){
            $response = '';
        //}
        
        return $this->render('BiznesShopBundle:Default:checkout.html.twig',
                array(
                    'roles' => $roles,
                    'response' => $response,
                    'cart' => $cart,
                ));   
    }
    
    /**
     * @Route("/confirm", name="confirm")
     */
    public function confirmAction(){
        /*
        $cart = Cart::getInstance();
        $cart->loadFromSession();
         */
        
        return $this->render('BiznesShopBundle:Default:confirm.html.twig',
                array(

                ));   
    }
    
    /**
     * @Route("/created", name="account_created_shop")
     */
    public function createdAction(){ 
        $cart = $this->get('cartManager');
        $cart->loadFromSession();
        /*
        $cart = Cart::getInstance();
        $cart->loadFromSession();
         */
        return $this->render('BiznesShopBundle:Default:register_created.html.twig',
                array(
                    'cart' => $cart,
                ));  
    }
    
    
}
