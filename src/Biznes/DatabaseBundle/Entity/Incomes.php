<?php

namespace Biznes\DatabaseBundle\Entity;

/**
 * Incomes
 */
class Incomes
{
    /**
     * @var \DateTime
     */
    private $dateIncome;

    /**
     * @var string
     */
    private $value;

    /**
     * @var integer
     * 0 - to accept / do zaakceptowania
     * 1 - accepted / zaakceptowano
     */
    private $state = 0;

    /**
     * @var integer
     */
    private $idIncome;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Orders
     */
    private $idOrder;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Products
     */

    private $idProduct;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Users
     */
    private $idUserfrom;

    /**
     * @var integer
     */
    private $idSponsor;


    /**
     * Set dateIncome
     *
     * @param \DateTime $dateIncome
     *
     * @return Incomes
     */
    public function setDateIncome($dateIncome)
    {
        $this->dateIncome = $dateIncome;

        return $this;
    }

    /**
     * Get dateIncome
     *
     * @return \DateTime
     */
    public function getDateIncome()
    {
        return $this->dateIncome;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Incomes
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Incomes
     */
    public function setState($state)
    {
        if($state == 0 || $state == "do zaakceptowania" || $state == "to accept"){
            $this->state = 0;
        }
        elseif($state == 1 || $state == "zaakceptowano" || $state == "accepted"){
            $this->state = 1;
        }

        return $this;
    }

    /**
     * Get state
     *
     * @return integer
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getStateAsString()
    {
        $string = "";
        if($this->state == 0){
            $string = "do zaakceptowania";
        }
        elseif($this->state == 1){
            $string = "zaakceptowano";
        }
        return $string;
    }

    /**
     * Get idIncome
     *
     * @return integer
     */
    public function getIdIncome()
    {
        return $this->idIncome;
    }

    /**
     * Set idOrder
     *
     * @param \Biznes\DatabaseBundle\Entity\Orders $idOrder
     *
     * @return Incomes
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

    /**
     * Set idUserfrom
     *
     * @param \Biznes\DatabaseBundle\Entity\Users $idUserfrom
     *
     * @return Incomes
     */
    public function setIdUserfrom(\Biznes\DatabaseBundle\Entity\Users $idUserfrom = null)
    {
        $this->idUserfrom = $idUserfrom;

        return $this;
    }

    /**
     * Get idUserfrom
     *
     * @return \Biznes\DatabaseBundle\Entity\Users
     */
    public function getIdUserfrom()
    {
        return $this->idUserfrom;
    }

    /**
     * Set idSponsor
     *
     * @param integer
     *
     * @return Incomes
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
     * Set idProduct
     *
     * @param \Biznes\DatabaseBundle\Entity\Products $idProduct
     *
     * @return Incomes
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
