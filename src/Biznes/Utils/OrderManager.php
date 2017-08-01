<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Biznes\Utils;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\ORM\EntityManager;

use Biznes\DatabaseBundle\Entity\Users;
use Biznes\DatabaseBundle\Entity\PaymentMethods;
use Biznes\DatabaseBundle\Entity\RealizationMethods;
use Biznes\DatabaseBundle\Entity\States;
/**
 * Description of OrderManager
 *
 * @author Michal
 */
class OrderManager extends Controller{
    private $realizationMethod = null;
    private $paymentMethod = null;
    protected $em;
    
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    public function createOrder(Users $user, PaymentMethods $paymentMethod, RealizationMethods $realizationMethod, States $state, $productsInCart){
        $sponsor = $user->getIdSponsor();
        $idSponsor = $sponsor->getIdUser();
        
        $cartManager = new CartManager();
        $cartManager->loadFromSession();
        
        $order = new \Biznes\DatabaseBundle\Entity\Orders();
        $order->setDateOrder(new \DateTime)
                ->setIdPaymentMethod($paymentMethod)
                ->setIdRealizationMethod($realizationMethod)
                ->setIdState($state)
                ->setIdUser($user)
                ->setIdSponsor($idSponsor)
                ->setPriceOverall($cartManager->getPriceOverall());
        
        $this->em->persist($order);
        $this->em->flush();
        
        foreach($productsInCart as $product){
           $cart = new \Biznes\DatabaseBundle\Entity\Carts();

           $cart->setIdProduct($product); 
           $cart->setIdOrder($order);
           
           $this->em->merge($cart);
           $this->em->flush();
           $this->em->clear();
        }
        
        return $order;
    }
    
    public function getRealizationMethod() {
        return $this->realizationMethod;
    }

    public function getPaymentMethod() {
        return $this->paymentMethod;
    }

    public function setRealizationMethod($realizationMethod) {
        $this->realizationMethod = $realizationMethod;
    }

    public function setPaymentMethod($paymentMethod) {
        $this->paymentMethod = $paymentMethod;
    }


}
