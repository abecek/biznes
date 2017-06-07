<?php

namespace Biznes\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Messages
 */
class Messages
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var \DateTime
     */
    private $dateMessage;

    /**
     * @var integer
     */
    private $idMessage;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Tickets
     */
    private $idTicket;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Users
     */
    private $idUser;


    /**
     * Set text
     *
     * @param string $text
     * @return Messages
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
     * Set dateMessage
     *
     * @param \DateTime $dateMessage
     * @return Messages
     */
    public function setDateMessage($dateMessage)
    {
        $this->dateMessage = $dateMessage;

        return $this;
    }

    /**
     * Get dateMessage
     *
     * @return \DateTime 
     */
    public function getDateMessage()
    {
        return $this->dateMessage;
    }

    /**
     * Get idMessage
     *
     * @return integer 
     */
    public function getIdMessage()
    {
        return $this->idMessage;
    }

    /**
     * Set idTicket
     *
     * @param \Biznes\DatabaseBundle\Entity\Tickets $idTicket
     * @return Messages
     */
    public function setIdTicket(\Biznes\DatabaseBundle\Entity\Tickets $idTicket = null)
    {
        $this->idTicket = $idTicket;

        return $this;
    }

    /**
     * Get idTicket
     *
     * @return \Biznes\DatabaseBundle\Entity\Tickets 
     */
    public function getIdTicket()
    {
        return $this->idTicket;
    }

    /**
     * Set idUser
     *
     * @param \Biznes\DatabaseBundle\Entity\Users $idUser
     * @return Messages
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
