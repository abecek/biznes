<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Biznes\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

use Biznes\DatabaseBundle\Entity\Users;
use Biznes\DatabaseBundle\Entity\RealizationMethods;
use Biznes\DatabaseBundle\Entity\PaymentMethods;
use Biznes\DatabaseBundle\Entity\States;

/**
 * @Route("/shop/cart")
 */
class CartController extends Controller{
    
    /**
     * @Route("/", name="cart")
     * @Method("GET")
     */
    public function indexAction() {
        $cart = $this->get('cartManager');
        $cart->loadFromSession();

        return $this->render('BiznesShopBundle:Default:cart.html.twig', array(
                    'cart' => $cart,
        ));
    }

    /**
     * @Route("/add", name="cartAdd")
     * @Method("POST")
     */
    public function addToCartAction(Request $request) {
        $data['idProduct'] = $request->get('idProduct');
        $data['desc'] = $request->get('desc');

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('BiznesDatabaseBundle:Products')
                ->findOneByIdProduct($data['idProduct']);

        $cart = $this->get('cartManager');
        $cart->loadFromSession();

        $cart->addProduct($product, $data['desc']);

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/delete", name="cartDelete")
     * @Method("POST")
     */
    public function removeFromCartAction(Request $request) {
        $idProduct = $request->get('idProduct');
        $cart = $this->get('cartManager');
        $cart->loadFromSession();
        $all = $request->get('all');
        if ($all == 'true') {
            $cart->clearCart();
            return $this->redirectToRoute('cart');
        }
        $cart->removeProductById($idProduct);

        return $this->redirectToRoute('cart');
    }
    
    /**
     * @Route("/checkout", name="checkout")
     * @Method({"GET"})
     */
    public function checkoutAction() {
        $cart = $this->get('cartManager');
        $cart->loadFromSession();

        $um = $this->get('userManager');
        $user = $um->loadDataFromUser($this->getUser());

        $userData = null;
        if ($um->userDataExists()) {
            $userData = $um->get('userData');
        }
        $userAddresss = null;
        if ($um->userAddressExists()) {
            $userAddresss = $um->get('userAddress');
        }

        $em = $this->getDoctrine()->getManager();
        $realizationMethods = $em->getRepository('BiznesDatabaseBundle:RealizationMethods')
                ->findAll();

        $paymentMethods = $em->getRepository('BiznesDatabaseBundle:PaymentMethods')
                ->findAll();

        return $this->render('BiznesShopBundle:Default:checkout.html.twig', array(
                    'error' => null,
                    'cart' => $cart,
                    'toPay' => round(floatval($cart->getPriceOverall()) * 1.23, 2),
                    'userData' => $userData,
                    'userAddress' => $userAddresss,
                    'realizationMethods' => $realizationMethods,
                    'paymentMethods' => $paymentMethods,
        ));
    }

    /**
     * @Route("/confirm", name="order_confirm")
     * @Method({"POST"})
     */
    public function confirmAction(Request $request) {
        $cartManager = $this->get('cartManager');
        $cartManager->loadFromSession();
        $productsInCart = $cartManager->getProducts();
        
        $em = $this->getDoctrine()->getManager();
        $paymentMethod = $em->getRepository('BiznesDatabaseBundle:PaymentMethods')
                ->find($request->get('paymentMethod'));
        $realizationMethod = $em->getRepository('BiznesDatabaseBundle:RealizationMethods')
                ->find($request->get('realizationMethod'));
        $state = $em->getRepository('BiznesDatabaseBundle:States')
                ->find(1);
        
        $user = $this->getUser();
        
        //Pushing some code outside controller
        $orderManager = $this->get('orderManager');
        $order = $orderManager->createOrder($user, $paymentMethod, $realizationMethod, $state, $productsInCart);
        //$cartManager->clearCart();
        
        
        $um = $this->get('userManager');
        $um->loadDataFromUser($user);
        
        if($request->get('action') == 'payment'){
            $dateExposure = new \DateTime;
            $dateSale = new \DateTime;
            $datePayment = new \DateTime;
            $datePayment->add(date_interval_create_from_date_string('14 days'));
            
            return $this->render('BiznesShopBundle:Default:invoice.html.twig', array(
                    'dateExposure' => $dateExposure,
                    'dateSale' => $dateSale,
                    'datePayment' => $datePayment,
                
                    'paymentMethod' => $paymentMethod,
                    'realizationMethod' => $realizationMethod,
                    'cart' => $cartManager,
                    'products' => $productsInCart,
                    'order' => $order,
                    'um' => $um,
                ));
        }

        return $this->render('BiznesShopBundle:Default:confirm.html.twig', array(
                    'paymentMethod' => $paymentMethod->getIdPaymentMethod(),
                    'realizationMethod' => $realizationMethod->getIdRealizationMethod(),
                    'cart' => $cartManager,
                    'products' => $productsInCart,
                    'order' => $order,
                    'um' => $um,
                ));
    }
    
    /**
     * @Route("/payment", name="payment")
     * @Method({"POST", "GET"})
     */
    public function paymentAction(Request $request){
        /*
        $cartManager = $this->get('cartManager');
        $cartManager->loadFromSession();
        $productsInCart = $cartManager->getProducts();
        
        $em = $this->getDoctrine()->getManager();
        $paymentMethod = $em->getRepository('BiznesDatabaseBundle:PaymentMethods')
                ->find($request->get('paymentMethod'));
        $realizationMethod = $em->getRepository('BiznesDatabaseBundle:RealizationMethods')
                ->find($request->get('realizationMethod'));
        $state = $em->getRepository('BiznesDatabaseBundle:States')
                ->find(1);
        
        $user = $this->getUser();
        
        $orderManager = $this->get('orderManager');
        $order = $orderManager->createOrder($user, $paymentMethod, $realizationMethod, $state, $productsInCart);
        $cartManager->clearCart();
        
        
        $um = $this->get('userManager');
        $um->loadDataFromUser($user);
        */
            
            
        return $this->render('BiznesShopBundle:Default:payment.html.twig', array(

        ));
    }
    
    /**
     * @Route("/test", name="test")
     */
    public function testAction(){
        $cart = $this->get('cartManager');
        $cart->loadFromSession();
        
        $boughtProducts = null;
        $um = $this->get('userManager');
        
        $boughtProducts = $um->getBoughtIdProducts($this->getUser()); 
        
        return $this->render('BiznesShopBundle:Default:test.html.twig', array(
            'cart' => $cart,
            'boughtProducts' => $boughtProducts,
        ));
    }
    
}
