<?php

namespace Biznes\DatabaseBundle\Entity;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

use Doctrine\ORM\Mapping as ORM;

/**
 * Carts
 */
class Carts
{
    /**
     * @var integer
     */
    private $idCart = null;

    /**
     * @var \Biznes\DatabaseBundle\Entity\RealizationMethods
     */
    private $idRealizationMethod = null;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Products
     */
    private $products = array();

    /**
     * @var \Biznes\DatabaseBundle\Entity\Orders
     */
    private $idOrder = null;

    /**
     * @var integer
     */
    private $price_overall = 0;
    
    /**
     * @var integer
     */
    private $count = 0;

    
    public function __construct() {
        
    }
    
    /**
     * Get idCart
     *
     * @return integer 
     */
    public function getIdCart()
    {
        return $this->idCart;
    }

    /**
     * Set idRealizationMethod
     *
     * @param \Biznes\DatabaseBundle\Entity\RealizationMethods $idRealizationMethod
     * @return Carts
     */
    public function setIdRealizationMethod(\Biznes\DatabaseBundle\Entity\RealizationMethods $idRealizationMethod = null)
    {
        $this->idRealizationMethod = $idRealizationMethod;

        return $this;
    }

    /**
     * Get idRealizationMethod
     *
     * @return \Biznes\DatabaseBundle\Entity\RealizationMethods 
     */
    public function getIdRealizationMethod()
    {
        return $this->idRealizationMethod;
    }
 
    
    
    
    
    
    

    /**
     * Get Products
     *
     * @return \Biznes\DatabaseBundle\Entity\Products 
     */
    

    /**
     * Set idOrder
     *
     * @param \Biznes\DatabaseBundle\Entity\Orders $idOrder
     * @return Carts
     */
    public function setIdOrder(\Biznes\DatabaseBundle\Entity\Orders $idOrder = null)
    {
        $this->idOrder = $idOrder;

        return $this;
    }

    /**
     * Get idOrder
     *
     * @return \Biznes\DatabaseBundle\Entity\Orders 
     */
    public function getIdOrder()
    {
        return $this->idOrder;
    }
}
