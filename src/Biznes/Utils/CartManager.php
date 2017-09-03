<?php

/**
 * Description of cart
 *
 * @author Michal
 */

namespace Biznes\Utils;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Biznes\DatabaseBundle\Entity\Products;

use Symfony\Component\HttpFoundation\Session\Session;

class CartManager extends Controller{
    protected $em = null;
    private $products = array();
    private $priceOverall = 0;
    private $count = 0;
    
    public function countPriceOverall(){
        $this->priceOverall = 0;
        foreach($this->products as $productInCart){
            $this->priceOverall += $productInCart->getPrice();
        }
        return $this->priceOverall;
    }
    
    public function addProduct(Products $product, $desc = null){   
        $id = $product->getIdProduct();

        $temp_array = array(
            'product' => $product, 
            'desc' => $desc,
        );
        
        $this->products[$id] = $product;
        
        $this->countPriceOverall();
        $this->count = count($this->products);
        
        $this->saveToSession();
        
        return $this;
    }
    
    public function removeProductById($id){
        if(is_numeric($id)){
            //($this->products as $product){
               // if($product->getIdProduct() == $id){
                    unset($this->products[$id]);
              //  }
            //}

            $this->countPriceOverall();
            $this->count -= 1;
            $this->saveToSession();
            
            return true;
        }
        else{
            return false;
        }
    }
    
    public function isInCart($id){
        $temp = array();
        foreach($this->products as $prod){
            $temp[] = $prod->getIdProduct();
        }
        if(in_array($id, $temp)){
            return true;
        }
        return false;
    }
    
    public function setProducts($products){
        $this->products = $products;
        return $this;
    }
    
    public function getProducts()
    {
        return $this->products;
    }
    
    public function saveToSession(){
        $session = new Session(); 
        $session->set('cart', $this);
        
        return true;
    }
    
    public function loadFromSession(){
        $session = new Session();

        if($session->get('cart') != null){
            $object = $session->get('cart');

            $this->priceOverall = $object->priceOverall;
            $this->count = count($object->getProducts());

            $this->setProducts($object->getProducts());
           
            return true;
        }
        else{
            return false;
        }
        
    }
    
    /*
    public function loadFromDatabase($id){
        if(is_numeric($id) && $id != null){
            $em = $this->getDoctrine()->getManager();
            $cart = $em->getRepository('BiznesDatabaseBundle:Carts')
                ->findOneByIdProduct($id);
        }
    }
     */
    
    public function clearCart(){
        $this->count = 0;
        $this->priceOverall = 0;
        $this->products = array();
        $this->saveToSession();
    }
    
    function getPriceOverall() {
        return $this->priceOverall;
    }

    function getCount() {
        return $this->count;
    }


}
