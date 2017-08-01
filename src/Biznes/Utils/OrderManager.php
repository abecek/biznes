<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Biznes\Utils;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Biznes\Utils\CartManager;
use Biznes\Utils\UserManager;
/**
 * Description of OrderManager
 *
 * @author Michal
 */
class OrderManager extends Controller{
    private $realizationMethod = null;
    private $paymentMethod = null;
    
    
    function getRealizationMethod() {
        return $this->realizationMethod;
    }

    function getPaymentMethod() {
        return $this->paymentMethod;
    }

    function setRealizationMethod($realizationMethod) {
        $this->realizationMethod = $realizationMethod;
    }

    function setPaymentMethod($paymentMethod) {
        $this->paymentMethod = $paymentMethod;
    }


}
