<?php

namespace Biznes\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Incomes
 * @ORM\Entity(repositoryClass="Biznes\DatabaseBundle\Repository\IncomesRepository")
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
     * @var string
     */
    private $state;

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
     * @var \Biznes\DatabaseBundle\Entity\Users
     */
    private $idUser;


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
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
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
     * Set idUser
     *
     * @param \Biznes\DatabaseBundle\Entity\Users $idUser
     *
     * @return Incomes
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

