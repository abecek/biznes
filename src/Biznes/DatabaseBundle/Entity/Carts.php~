<?php

namespace Biznes\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Carts
 * @ORM\Table(name="carts")
 * @ORM\Entity(repositoryClass="Biznes\DatabaseBundle\Repository\CartsRepository")
 */
class Carts
{
    /**
     * @var integer
     */
    private $idCart;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Products
     * @ManyToOne(targetEntity="Products")
     */
    private $idProduct = null;

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

    /**
     * Set idOrder
     *
     * @param \Biznes\DatabaseBundle\Entity\Orders $idOrder
     *
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
