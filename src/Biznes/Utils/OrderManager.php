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

use Biznes\DatabaseBundle\Entity\Invoices;
use Biznes\DatabaseBundle\Entity\Carts;
use Biznes\DatabaseBundle\Entity\Incomes;

use Biznes\DatabaseBundle\Entity\Orders;

/**
 * Description of OrderManager
 *
 * @author Michal
 */
class OrderManager extends Controller {

    private $order = null;
    private $carts = array();
    private $incomes = array();
    private $invoice = null;
    
    private $realizationMethod = null;
    private $paymentMethod = null;
    private $state;
    
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

    public function prepareOrder(Users $user, $priceNettoOverAll, PaymentMethods $paymentMethod, RealizationMethods $realizationMethod, States $state) {
        $sponsor = $user->getIdSponsor();
        $idSponsor = null;
        if ($sponsor != null) {
            $idSponsor = $sponsor->getIdUser();
        }
        
        $this->paymentMethod = $paymentMethod;
        $this->realizationMethod = $realizationMethod;
        $this->state = $state;

        $date = new \DateTime;
        $this->order = new Orders();
        $this->order->setDateOrder($date)
                ->setIdPaymentMethod($paymentMethod)
                ->setIdRealizationMethod($realizationMethod)
                ->setIdState($state)
                ->setIdUser($user)
                ->setIdSponsor($idSponsor)
                ->setPriceNetto($priceNettoOverAll)
                ->setPriceBrutto(
                        round($priceNettoOverAll * (1 + $this->vatValue), 2)
                )
                ->setVatValue(
                        round($this->order->getPriceBrutto() * $this->vatValue, 2)
        );

        return $this->order;
    }

    public function prepareCarts($productsInCart) {
        foreach ($productsInCart as $product) {
            $cart = new Carts();

            $cart->setIdProduct($product);
            $cart->setIdOrder($this->order);

            $this->carts[] = $cart;
        }
        return $this->carts;
    }

    public function prepareIncomes($productsInCart, Users $sponsor, Users $user) {
        foreach ($productsInCart as $product) {
            if ($sponsor != null) {
                $income = new Incomes(); 
                
                $income->setIdSponsor($sponsor->getIdUser());
                $income->setIdUserfrom($user);

                $income->setIdOrder($this->order);
                $income->setIdProduct($product);

                $income->setDateIncome($this->order->getDateOrder());
                $income->setState("do zaakceptowania");

                $value = (intval($product->getPrice()) * 0.1);
                $value = round($value, 2);
                $value = strval($value);

                $income->setValue($value);

                $this->incomes[] = $income;
            }
        }
        return $this->incomes;
    }

    public function prepareInvoice() {
        $invoice = new Invoices();
        $invoice->setIdOrder($this->order);

        $date = $this->order->getDateOrder();
        $invoice->setDateExposure($date)
                ->setDateSale($date)
                ->setDatePayment(
                        $date
                        ->add(date_interval_create_from_date_string('14 days'))
                )
                ->setType('PROFORMA');

        $this->invoice = $invoice;

    }
    
    
    public function pushIntoDatabase(){
        $this->em->persist($this->order);
        foreach($this->carts as $cart){
            $this->em->merge($cart);
        }
        foreach($this->incomes as $incomes){
            $this->em->merge($incomes);
        }
        $this->em->merge($this->invoice);
        $this->em->flush();
        $this->em->clear();
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

    public function getOrder() {
        return $this->order;
    }

    public function getCarts() {
        return $this->carts;
    }

    public function getIncomes() {
        return $this->incomes;
    }

    public function getInvoice() {
        return $this->invoice;
    }

    public function setOrder($order) {
        $this->order = $order;
    }

    public function setCarts($carts) {
        $this->carts = $carts;
    }

    public function setIncomes($incomes) {
        $this->incomes = $incomes;
    }

    public function setInvoice($invoice) {
        $this->invoice = $invoice;
    }

    public function getState() {
        return $this->state;
    }

    public function setState($state) {
        $this->state = $state;
    }


}
