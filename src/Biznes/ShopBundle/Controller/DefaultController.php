<?php

namespace Biznes\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/shop")
 */
class DefaultController extends Controller {

    /**
     * @Route("/{referer}", name="shop", requirements={"referer": "\d+"})
     */
    public function indexAction($referer = null) {
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
        return $this->render('BiznesShopBundle:Default:index.html.twig', array(
                    'products' => $products,
                    'cart' => $cart,
                    'categories' => $categories,
        ));
    }

    /**
     * @Route("/product/{id}/{referer}", name="product", requirements={"id": "\d+", "referer": "\d+"})
     */
    public function productAction($id, $referer = null) {
        $session = new Session();
        if ($referer != null) {
            $session->set('referer', $referer);
        }

        if (is_numeric($id)) {
            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository('BiznesDatabaseBundle:Products')
                    ->findOneByIdProduct($id);
            /*
              $cart = Cart::getInstance();
              $cart->loadFromSession();
             */
            $cart = $this->get('cartManager');
            $cart->loadFromSession();

            if (!empty($product)){
                return $this->render('BiznesShopBundle:Default:product.html.twig', array(
                            'product' => $product,
                            'cart' => $cart,
                ));
            } 
            else{
                //TO DO
                $products = $em->getRepository('BiznesDatabaseBundle:Products')
                ->findAll();
                return $this->render('BiznesShopBundle:Default:index.html.twig', array(
                            'products' => $products,
                            'cart' => $cart,
                ));
            }
        }
    }

    /**
     * @Route("/created", name="accountCreatedShop")
     */
    public function createdAction() {
        $cart = $this->get('cartManager');
        $cart->loadFromSession();

        return $this->render('BiznesShopBundle:Default:registerCreated.html.twig', array(
                    'cart' => $cart,
        ));
    }

    /**
     * @Route("/personalinfo", name="shopPersonalDataInfo")
     */
    public function personalInfoAction() {
        $cart = $this->get('cartManager');
        $cart->loadFromSession();

        return $this->render('BiznesShopBundle:Default:personalDataInfo.html.twig', array(
                    'cart' => $cart,
        ));
    }

}
