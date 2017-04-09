<?php

namespace Biznes\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Carts
 */
class Carts
{
    /**
     * @var integer
     */
    private $idCart;

    /**
     * @var \Biznes\DatabaseBundle\Entity\RealizationMethods
     */
    private $idRealizationMethod;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Products
     */
    private $idProduct;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Orders
     */
    private $idOrder;


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
     * Set idProduct
     *
     * @param \Biznes\DatabaseBundle\Entity\Products $idProduct
     * @return Carts
     */
    public function setIdProduct(\Biznes\DatabaseBundle\Entity\Products $idProduct = null)
    {
        $this->idProduct = $idProduct;

        return $this;
    }

    /**
     * Get idProduct
     *
     * @return \Biznes\DatabaseBundle\Entity\Products 
     */
    public function getIdProduct()
    {
        return $this->idProduct;
    }

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
