<?php

namespace Biznes\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 */
class Orders
{
    /**
     * @var integer
     */
    private $idOrder;
    
    /**
     * @var \DateTime
     */
    private $dateOrder;

    /**
     * @var string
     */
    private $priceOverall;

    /**
     * @var integer
     */
    private $idSponsor;

    /**
     * @var \Biznes\DatabaseBundle\Entity\PaymentMethods
     */
    private $idPaymentMethod;

    /**
     * @var \Biznes\DatabaseBundle\Entity\States
     */
    private $idState;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Users
     */
    private $idUser;


    /**
     * Set dateOrder
     *
     * @param \DateTime $dateOrder
     * @return Orders
     */
    public function setDateOrder($dateOrder)
    {
        $this->dateOrder = $dateOrder;

        return $this;
    }

    /**
     * Get dateOrder
     *
     * @return \DateTime 
     */
    public function getDateOrder()
    {
        return $this->dateOrder;
    }

    /**
     * Set priceOverall
     *
     * @param string $priceOverall
     * @return Orders
     */
    public function setPriceOverall($priceOverall)
    {
        $this->priceOverall = $priceOverall;

        return $this;
    }

    /**
     * Get priceOverall
     *
     * @return string 
     */
    public function getPriceOverall()
    {
        return $this->priceOverall;
    }

    /**
     * Set idSponsor
     *
     * @param integer $idSponsor
     * @return Orders
     */
    public function setIdSponsor($idSponsor)
    {
        $this->idSponsor = $idSponsor;

        return $this;
    }

    /**
     * Get idSponsor
     *
     * @return integer 
     */
    public function getIdSponsor()
    {
        return $this->idSponsor;
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
     * Set idPaymentMethod
     *
     * @param \Biznes\DatabaseBundle\Entity\PaymentMethods $idPaymentMethod
     * @return Orders
     */
    public function setIdPaymentMethod(\Biznes\DatabaseBundle\Entity\PaymentMethods $idPaymentMethod = null)
    {
        $this->idPaymentMethod = $idPaymentMethod;

        return $this;
    }

    /**
     * Get idPaymentMethod
     *
     * @return \Biznes\DatabaseBundle\Entity\PaymentMethods 
     */
    public function getIdPaymentMethod()
    {
        return $this->idPaymentMethod;
    }

    /**
     * Set idState
     *
     * @param \Biznes\DatabaseBundle\Entity\States $idState
     * @return Orders
     */
    public function setIdState(\Biznes\DatabaseBundle\Entity\States $idState = null)
    {
        $this->idState = $idState;

        return $this;
    }

    /**
     * Get idState
     *
     * @return \Biznes\DatabaseBundle\Entity\States 
     */
    public function getIdState()
    {
        return $this->idState;
    }

    /**
     * Set idUser
     *
     * @param \Biznes\DatabaseBundle\Entity\Users $idUser
     * @return Orders
     */
    public function setIdUser(\Biznes\DatabaseBundle\Entity\Users $idUser = null)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return \Biznes\DatabaseBundle\Entity\Users 
     */
    public function getIdUser()
    {
        return $this->idUser;
    }
}
