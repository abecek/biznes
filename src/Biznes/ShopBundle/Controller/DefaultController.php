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
        
        $user = $this->getUser();
        $um = $this->get('userManager');
        $um->loadDataFromUser($user);

        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('BiznesDatabaseBundle:Products')
                ->findAll();
        
        $categories = $em->getRepository('BiznesDatabaseBundle:Categories')
                ->findAll();
                
        $cart = $this->get('cartManager');
        $cart->loadFromSession();
        return $this->render('BiznesShopBundle:Default:index.html.twig',
                array(
                    'products' => $products,
                    'cart' => $cart,
                    'categories' => $categories,
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
     * @Method("GET")
     */
    public function showCartAction(){
        $cart = $this->get('cartManager');
        $cart->loadFromSession();
        
        return $this->render('BiznesShopBundle:Default:cart.html.twig',
                array(
                    'cart' => $cart,
                )); 

    }
    
    /**
     * @Route("/cart/add", name="cartAdd")
     * @Method("POST")
     */
    public function addToCartAction(Request $request){
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
     * @Route("/cart/delete", name="cartDelete")
     * @Method("POST")
     */
    public function removeFromCartAction(Request $request){
        $idProduct = $request->get('idProduct');
        $cart = $this->get('cartManager');
        $cart->loadFromSession();
        $all = $request->get('all');
        if($all == 'true'){
            $cart->clearCart();
            return $this->redirectToRoute('cart');
        }
        $cart->removeProductById($idProduct);
        
        return $this->redirectToRoute('cart');
    }
    
    /**
     * @Route("/shop/checkout", name="checkout")
     * @Method({"GET"})
     */
    public function checkoutAction(){
        $cart = $this->get('cartManager');
        $cart->loadFromSession();
        
        $um = $this->get('userManager');
        $user = $um->loadDataFromUser($this->getUser());
        
        $userData = null;
        if($um->userDataExists()){
            $userData = $um->get('userData');
        }
        $userAddresss = null;
        if($um->userAddressExists()){
            $userAddresss = $um->get('userAddress');
        }
        
        if ($user == null) $roles = array('Brak');
        else $roles = $user->getRoles();        
        
        $em = $this->getDoctrine()->getManager();
        $realizationMethods = $em->getRepository('BiznesDatabaseBundle:RealizationMethods')
                ->findAll();
        
        $paymentMethods = $em->getRepository('BiznesDatabaseBundle:PaymentMethods')
                ->findAll();
        
        return $this->render('BiznesShopBundle:Default:checkout.html.twig',
                array(
                    'roles' => $roles,
                    'cart' => $cart,
                    'userData' => $userData,
                    'userAddress' => $userAddresss,
                    'realizationMethods' => $realizationMethods,
                    'paymentMethods' => $paymentMethods,
                ));   
    }
   
    
    /**
     * @Route("/confirm", name="confirm")
     * @Method({"POST"})
     */
    public function confirmAction(Request $request){
        $cart = $this->get('cartManager');
        $cart->loadFromSession();
        
        return $this->render('BiznesShopBundle:Default:confirm.html.twig',
                array(
                    'cart' => $cart,
                    'req'   => $request,
                ));   
    }
    
    /**
     * @Route("/created", name="account_created_shop")
     */
    public function createdAction(){ 
        $cart = $this->get('cartManager');
        $cart->loadFromSession();

        return $this->render('BiznesShopBundle:Default:register_created.html.twig',
                array(
                    'cart' => $cart,
                ));  
    }
    
    /**
     * @Route("/shop/personalinfo", name="shopPersonalDataInfo")
     */
    public function personalInfoAction(){ 
        $cart = $this->get('cartManager');
        $cart->loadFromSession();
        
        return $this->render('BiznesShopBundle:Default:personalDataInfo.html.twig', array(
            'cart' => $cart,
        ));
    }
    
    
}
