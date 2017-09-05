<?php

namespace Biznes\DatabaseBundle\Entity;

/**
 * Tickets
 */
class Tickets
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $text;

    /**
     * @var \DateTime
     */
    private $dateOpen;

    /**
     * @var \DateTime
     */
    private $dateClose;

    /**
     * @var integer
     */
    private $idTicket;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Users
     */
    private $idUser;


    /**
     * Set title
     *
     * @param string $title
     *
     * @return Tickets
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Tickets
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set dateOpen
     *
     * @param \DateTime $dateOpen
     *
     * @return Tickets
     */
    public function setDateOpen($dateOpen)
    {
        $this->dateOpen = $dateOpen;

        return $this;
    }

    /**
     * Get dateOpen
     *
     * @return \DateTime
     */
    public function getDateOpen()
    {
        return $this->dateOpen;
    }

    /**
     * Set dateClose
     *
     * @param \DateTime $dateClose
     *
     * @return Tickets
     */
    public function setDateClose($dateClose)
    {
        $this->dateClose = $dateClose;

        return $this;
    }

    /**
     * Get dateClose
     *
     * @return \DateTime
     */
    public function getDateClose()
    {
        return $this->dateClose;
    }

    /**
     * Get idTicket
     *
     * @return integer
     */
    public function getIdTicket()
    {
        return $this->idTicket;
    }

    /**
     * Set idUser
     *
     * @param \Biznes\DatabaseBundle\Entity\Users $idUser
     *
     * @return Tickets
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
