<?php

namespace Biznes\DatabaseBundle\Entity;

/**
 * Notifications
 */
class Notifications
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
    private $dateNotification;

    /**
     * @var integer
     */
    private $idNotification;


    /**
     * Set title
     *
     * @param string $title
     *
     * @return Notifications
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
     * @return Notifications
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
     * Set dateNotification
     *
     * @param \DateTime $dateNotification
     *
     * @return Notifications
     */
    public function setDateNotification($dateNotification)
    {
        $this->dateNotification = $dateNotification;

        return $this;
    }

    /**
     * Get dateNotification
     *
     * @return \DateTime
     */
    public function getDateNotification()
    {
        return $this->dateNotification;
    }

    /**
     * Get idNotification
     *
     * @return integer
     */
    public function getIdNotification()
    {
        return $this->idNotification;
    }
}
