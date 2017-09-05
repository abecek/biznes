<?php

namespace Biznes\DatabaseBundle\Entity;

/**
 * Ratings
 */
class Ratings
{
    /**
     * @var \DateTime
     */
    private $dateRating;

    /**
     * @var string
     */
    private $text;

    /**
     * @var boolean
     */
    private $isAccepted = '0';

    /**
     * @var integer
     */
    private $idRating;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Products
     */
    private $idProduct;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Users
     */
    private $idUser;


    /**
     * Set dateRating
     *
     * @param \DateTime $dateRating
     *
     * @return Ratings
     */
    public function setDateRating($dateRating)
    {
        $this->dateRating = $dateRating;

        return $this;
    }

    /**
     * Get dateRating
     *
     * @return \DateTime
     */
    public function getDateRating()
    {
        return $this->dateRating;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Ratings
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
     * Set isAccepted
     *
     * @param boolean $isAccepted
     *
     * @return Ratings
     */
    public function setIsAccepted($isAccepted)
    {
        $this->isAccepted = $isAccepted;

        return $this;
    }

    /**
     * Get isAccepted
     *
     * @return boolean
     */
    public function getIsAccepted()
    {
        return $this->isAccepted;
    }

    /**
     * Get idRating
     *
     * @return integer
     */
    public function getIdRating()
    {
        return $this->idRating;
    }

    /**
     * Set idProduct
     *
     * @param \Biznes\DatabaseBundle\Entity\Products $idProduct
     *
     * @return Ratings
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
     * Set idUser
     *
     * @param \Biznes\DatabaseBundle\Entity\Users $idUser
     *
     * @return Ratings
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
