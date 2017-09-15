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
use Symfony\Component\HttpFoundation\Response;
use Biznes\DatabaseBundle\Entity\Users;
use Biznes\DatabaseBundle\Entity\RealizationMethods;
use Biznes\DatabaseBundle\Entity\PaymentMethods;
use Biznes\DatabaseBundle\Entity\States;
use Biznes\DatabaseBundle\Entity\Invoices;
use Biznes\Utils\WalletManager;

/**
 * @Route("/shop/cart")
 */
class CartController extends Controller {

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
        //Check if user is already logged
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }

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
        $um = $this->get('userManager');
        $um->loadDataFromUser($user);

        //Pushing some code outside controller TO REWORK!!!!!!!
        $orderManager = $this->get('orderManager');
        $order = $orderManager->prepareOrder($user, $cartManager->getPriceOverall(), $paymentMethod, $realizationMethod, $state, $productsInCart);

        return $this->render('BiznesShopBundle:Default:confirm.html.twig', array(
                    'products' => $productsInCart,
                    'order' => $order,
                    'cart' => $cartManager,
                    'um' => $um,
        ));
    }

    /**
     * @Route("/payment", name="payment")
     * @Method({"POST"})
     */
    public function paymentAction(Request $request) {
        //Check if user is already logged
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }

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

        $um = $this->get('userManager');
        $um->loadDataFromUser($user);

        //Pushing some code outside controller TO REWORK!!!!!!!
        $orderManager = $this->get('orderManager');
        $order = $orderManager->prepareOrder($user, $cartManager->getPriceOverall(), $paymentMethod, $realizationMethod, $state, $productsInCart);

        if($request->get('action') == 'paid'){
            if(!empty($productsInCart)){
                $orderManager->prepareCarts($productsInCart);
                $sponsor = $user->getIdSponsor();
                if ($sponsor != null) {
                    $orderManager->prepareIncomes($productsInCart, $sponsor, $user);
                }

                $orderManager->prepareInvoice();
                $orderManager->pushIntoDatabase();

                $date = $order->getDateOrder();
                $invoice = $orderManager->getInvoice();

                $datePayment = $invoice->getDatePayment();
                $invoiceNumber = $invoice->getInvoiceNumber();
                
                $this->sendEmailAfterShopping($invoice);

                $snappy = $this->get('knp_snappy.pdf');
                $html = $this->renderView('BiznesShopBundle:Default:invoice.html.twig', array(
                    'type' => $invoice->getType(),
                    'invoiceNumber' => $invoiceNumber,
                    'dateExposure' => $date,
                    'dateSale' => $date,
                    'datePayment' => $datePayment,
                    'cart' => $cartManager,
                    'order' => $order,
                    'um' => $um,
                ));

                $cartManager->clearCart();
                $filename = $invoiceNumber;

                return new Response(
                        $snappy->getOutputFromHtml($html), 200, array(
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="' . $filename . '.pdf"'
                        )
                );
            }
            else{
                return $this->redirectToRoute('shop');
            }
        }

        return $this->render('BiznesShopBundle:Default:payment.html.twig', array(
                    'order' => $order,
                    'cart' => $cartManager,
        ));
    }

    /**
     * @Route("/invoice/{id}", name="invoice")
     */
    public function invoiceAction($id = null) {
        //Check if user is already logged
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }

        if ($id == null || !is_numeric($id)) {
            throw new \Exception('Something goes wrong.');
        } else {
            $em = $this->getDoctrine()->getManager();
            $invoice = $em->getRepository('BiznesDatabaseBundle:Invoices')
                    ->findOneBy(array(
                'idInvoice' => $id,
            ));
            $order = $invoice->getIdOrder();

            if ($this->getUser() != $order->getIdUser()) {
                throw new \Exception('You dont have permission to see not your invoice.');
            } else {
                $um = $this->get('userManager');
                $um->loadDataFromUser($this->getUser());

                $cartManager = new \Biznes\Utils\CartManager();
                $cartManager->setEntityManager($em);
                $cartManager->loadFromDatabase($order->getIdOrder());

                $snappy = $this->get('knp_snappy.pdf');

                $html = $this->renderView('BiznesShopBundle:Default:invoice.html.twig', array(
                    'type' => $invoice->getType(),
                    'invoiceNumber' => $invoice->getInvoiceNumber(),
                    'dateExposure' => $invoice->getDateExposure(),
                    'dateSale' => $invoice->getDateSale(),
                    'datePayment' => $invoice->getDatePayment(),
                    'cart' => $cartManager,
                    'order' => $order,
                    'um' => $um,
                ));

                $filename = $invoice->getInvoiceNumber();

                return new Response(
                        $snappy->getOutputFromHtml($html), 200, array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $filename . '.pdf"'
                        )
                );
            }
        }
    }

    /**
     * @Route("/history", name="history")
     */
    public function historyAction() {
        //Check if user is already logged
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }

        $cartManager = $this->get('cartManager');
        $cartManager->loadFromSession();

        $em = $this->getDoctrine()->getManager();
        $orders = $em->getRepository('BiznesDatabaseBundle:Orders')
                ->findBy(array(
                    'idUser' => $this->getUser(),
                ), array(
                    'dateOrder' => 'DESC',
                ));


        $ordersInvoices = array();
        $ordersCarts = array();
        $ordersProducts = array();
        foreach ($orders as $order) {
            $ordersInvoices[$order->getIdOrder()] = $em->getRepository('BiznesDatabaseBundle:Invoices')
                    ->findBy(array(
                'idOrder' => $order,
            ));

            $ordersCarts = $em->getRepository('BiznesDatabaseBundle:Carts')
                    ->findBy(array(
                'idOrder' => $order,
            ));

            foreach ($ordersCarts as $product) {
                $ordersProducts[$order->getIdOrder()][] = $em->getRepository('BiznesDatabaseBundle:Products')
                        ->findOneBy(array(
                    'idProduct' => $product->getIdProduct(),
                ));
            }
        }

        return $this->render('BiznesShopBundle:Default:history.html.twig', array(
                    'cart' => $cartManager,
                    'orders' => $orders,
                    'invoices' => $ordersInvoices,
                    'ordersProducts' => $ordersProducts,
        ));
    }

    /**
     * @Route("/test", name="test")
     */
    public function textAction() {
        $string = 'Git';
        return $this->render('BiznesShopBundle:Default:test.html.twig', array(
                    'test' => $string,
        ));
    }

    
    private function sendEmailAfterShopping(Invoices $invoice){
        $user = $this->getUser();
        
        $message = \Swift_Message::newInstance()
                    ->setSubject('Twoje udane zakupy w Affiliations TOOLS')
                    ->setFrom('michal.blaszcz@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                    $this->renderView(
                            //app/Resources/views/Emails/userBoughtProduct.html.twig
                            'Emails/userBoughtProduct.html.twig', array(
                            'name' => $user->getUsername(),
                            'invoice' => $invoice,
                            )
                    ), 'text/html'
            );
            $this->get('mailer')->send($message);
            
        return;
    }
}
