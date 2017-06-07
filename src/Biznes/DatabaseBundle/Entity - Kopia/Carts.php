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
    private $idOrder;

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
     * Set idRealizationMethod
     *
     * @param \Biznes\DatabaseBundle\Entity\RealizationMethods $idRealizationMethod
     *
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
