<?php

namespace Biznes\DatabaseBundle\Entity;

/**
 * Withdraws
 */
class Withdraws
{
    /**
     * @var \DateTime
     */
    private $dateWithdraw;

    /**
     * @var string
     */
    private $value;

    /**
     * @var integer
     * 0 - awaiting / oczekuje
     * 1-  at realization / w realizacji
     * 2 - realized / zrealizowano
     */
    private $state = 0;

    /**
     * @var integer
     */
    private $idWithdraw;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Users
     */
    private $idUser;

    /**
     * @var string
     * Password for confirming new withdraw
     */
    private $password;
    /**
     * @var string
     */
    private $contractNumber;

    /**
     * @return mixed
     */
    public function getContractNumber()
    {
        if($this->contractNumber === null && $this->idWithdraw !== null){
            $this->setContractNumber($this->idWithdraw);
        }
        return $this->contractNumber;
    }

    /**
     * @param integer $idWithdraw
     */
    public function setContractNumber($idWithdraw)
    {
        $contractNumber = $this->dateWithdraw->format('Y/m/d') . '/' . strval($idWithdraw);
        $this->contractNumber = $contractNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Set dateWithdraw
     *
     * @param \DateTime $dateWithdraw
     *
     * @return Withdraws
     */
    public function setDateWithdraw($dateWithdraw)
    {
        $this->dateWithdraw = $dateWithdraw;

        return $this;
    }

    /**
     * Get dateWithdraw
     *
     * @return \DateTime
     */
    public function getDateWithdraw()
    {
        return $this->dateWithdraw;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Withdraws
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
     * @param integer $state
     *
     * @return Withdraws
     */
    public function setState($state)
    {
        if($state == 0 || $state == "awaiting" || $state == "oczekuje"){
            $this->state = 0;
        }
        elseif($state == 1 || $state == "at realization" || $state == "w realizacji"){
            $this->state = 1;
        }
        elseif($state == 2 || $state == "realized" || $state == "zrealizowano"){
            $this->state = 2;
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
            $string = "oczekuje";
        }
        elseif($this->state == 1){
            $string = "w realizacji";
        }
        elseif($this->state == 2){
            $string = "zrealizowano";
        }

        return $string;
    }

    /**
     * Get idWithdraw
     *
     * @return integer
     */
    public function getIdWithdraw()
    {
        return $this->idWithdraw;
    }

    /**
     * Set idUser
     *
     * @param \Biznes\DatabaseBundle\Entity\Users $idUser
     *
     * @return Withdraws
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
