<?php

namespace Biznes\DatabaseBundle\Entity;

/**
 * Expanses
 */
class Expanses
{
    /**
     * @var \DateTime
     */
    private $dateExpanse;

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
    private $idExpanse;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Users
     */
    private $idUser;


    /**
     * Set dateExpanse
     *
     * @param \DateTime $dateExpanse
     *
     * @return Expanses
     */
    public function setDateExpanse($dateExpanse)
    {
        $this->dateExpanse = $dateExpanse;

        return $this;
    }

    /**
     * Get dateExpanse
     *
     * @return \DateTime
     */
    public function getDateExpanse()
    {
        return $this->dateExpanse;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Expanses
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
     * @return Expanses
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
     * Get idExpanse
     *
     * @return integer
     */
    public function getIdExpanse()
    {
        return $this->idExpanse;
    }

    /**
     * Set idUser
     *
     * @param \Biznes\DatabaseBundle\Entity\Users $idUser
     *
     * @return Expanses
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

