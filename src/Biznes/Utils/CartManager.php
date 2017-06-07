<?php

/**
 * Description of cart
 *
 * @author Michal
 */

namespace Biznes\Utils;

use Biznes\DatabaseBundle\Entity\Products;
use Biznes\DatabaseBundle\Repository\ProductsRepository;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;



class CartManager {
    private $priceOverall = 0;
    private $products = array();
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
            unset($this->products[$id]);
            $this->countPriceOverall();
            $this->count -= 1;
            $this->saveToSession();
            
            return true;
        }
        else{
            return false;
        }
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
