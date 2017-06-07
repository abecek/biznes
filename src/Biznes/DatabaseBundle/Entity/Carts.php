<?php

namespace Biznes\DatabaseBundle\Entity;

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
     * @var integer
     */
    private $idOrder;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Products
     */
    private $idProduct;


    /**
     * Set idOrder
     *
     * @param integer $idOrder
     *
     * @return Carts
     */
    public function setIdOrder($idOrder)
    {
        $this->idOrder = $idOrder;

        return $this;
    }

    /**
     * Get idOrder
     *
     * @return integer
     */
    public function getIdOrder()
    {
        return $this->idOrder;
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
     * Set idProduct
     *
     * @param \Biznes\DatabaseBundle\Entity\Products $idProduct
     *
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
}

