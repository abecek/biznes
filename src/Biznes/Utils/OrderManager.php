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
    private $vatValue = 0.23;
    protected $em;
    
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    /*
     * param $productsInCart
     * Associative array with the objects of Products class
     * 
     * return object of Orders class
     */
    public function createOrder(Users $user, PaymentMethods $paymentMethod, RealizationMethods $realizationMethod, States $state, $productsInCart){
        $sponsor = $user->getIdSponsor();
        $idSponsor = null;
        if($sponsor != null){
            $idSponsor = $sponsor->getIdUser();
        }
        if(empty($productsInCart)){
           throw new \Exception("Something gone very wrong");
        }
              
        $cartManager = new CartManager();
        $cartManager->loadFromSession();
        
        $date = new \DateTime;
        $order = new \Biznes\DatabaseBundle\Entity\Orders();
        $order->setDateOrder($date)
                ->setIdPaymentMethod($paymentMethod)
                ->setIdRealizationMethod($realizationMethod)
                ->setIdState($state)
                ->setIdUser($user)
                ->setIdSponsor($idSponsor)
                ->setPriceNetto($cartManager->getPriceOverall())
                ->setPriceBrutto(
                        round($cartManager->getPriceOverall() * (1 + $this->vatValue), 2)
                        )
                ->setVatValue(
                        round($order->getPriceBrutto() * $this->vatValue, 2)
                        );
                
        
        /*
        $this->em->persist($order);
        $this->em->flush();
        
        foreach($productsInCart as $product){
           $cart = new \Biznes\DatabaseBundle\Entity\Carts();

           $cart->setIdProduct($product); 
           $cart->setIdOrder($order);
           
           $this->em->merge($cart);
           $this->em->flush();
           $this->em->clear();
           
           if($sponsor != null){
               $wm = new WalletManager($this->em);
               $wm->addIncome($idSponsor, $user, $order, $date, $product);
           }
        }
        */
        
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

    public function getVatValue() {
        return $this->vatValue;
    }

    public function setVatValue($vatValue) {
        $this->vatValue = $vatValue;
    }



}
