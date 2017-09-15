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
    private $priceNettoOverall = 0;
    private $count = 0;
    public $vatValue = 0.23;
    
    
    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }
    
    public function countPriceOverall(){
        $this->priceNettoOverall = 0;
        foreach($this->products as $productInCart){
            $this->priceNettoOverall += $productInCart->getPrice();
        }
        return $this->priceNettoOverall;
    }
    
    public function countProducts(){
        foreach($this->products as $productInCart){
            $this->count++;
        }
    }
    
    public function addProduct(Products $product, $desc = null){ 
        $product->setPriceBrutto(
                round(floatval($product->getPrice()) * (1 + $this->vatValue), 2)
                );
        $product->setVatValue(
                round(floatval($product->getPrice()) * $this->vatValue, 2)
                );
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

            $this->priceNettoOverall = $object->priceNettoOverall;
            $this->count = count($object->getProducts());

            $this->setProducts($object->getProducts());
           
            return true;
        }
        else{
            return false;
        }
        
    }
    
    
    public function loadFromDatabase($idOrder){
        if(is_numeric($idOrder) && $idOrder != null){
            if($this->em != null){
                $carts = $this->em->getRepository('BiznesDatabaseBundle:Carts')
                    ->findBy(array(
                        'idOrder' => $idOrder,
                    ));
                
                $temp = array();
                foreach($carts as $productInCart){
                    $product = $this->em->getRepository('BiznesDatabaseBundle:Products')
                        ->findOneBy(array(
                            'idProduct' => $productInCart->getIdProduct(),
                        ));
                    $product->setPriceBrutto(
                            round(($product->getVatValue() + 1) * strval($product->getPrice()), 2)
                            );
                
                    $temp[] = $product;
                }
                
                $this->products = $temp;
                $this->countPriceOverall();
                $this->countProducts();
                $this->vatValue = 0.23;
            }
            else{
                throw new \Exception('EntityManager is null.');
            }
        }
    }
    
    public function clearCart(){
        $this->count = 0;
        $this->priceNettoOverall = 0;
        $this->products = array();
        $this->saveToSession();
    }
    
    function getPriceOverall() {
        return $this->priceNettoOverall;
    }

    function getCount() {
        return $this->count;
    }


}
