<?php

namespace Biznes\DatabaseBundle\Entity;

/**
 * UsersAddresses
 */
class UsersAddresses
{
    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $nrHouse;

    /**
     * @var string
     */
    private $nrFlat;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $postCode;

    /**
     * @var string
     */
    private $country;

    /**
     * @var integer
     */
    private $idUserAddress;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Users
     */
    private $idUser;


    /**
     * Set street
     *
     * @param string $street
     *
     * @return UsersAddresses
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set nrHouse
     *
     * @param string $nrHouse
     *
     * @return UsersAddresses
     */
    public function setNrHouse($nrHouse)
    {
        $this->nrHouse = $nrHouse;

        return $this;
    }

    /**
     * Get nrHouse
     *
     * @return string
     */
    public function getNrHouse()
    {
        return $this->nrHouse;
    }

    /**
     * Set nrFlat
     *
     * @param string $nrFlat
     *
     * @return UsersAddresses
     */
    public function setNrFlat($nrFlat)
    {
        $this->nrFlat = $nrFlat;

        return $this;
    }

    /**
     * Get nrFlat
     *
     * @return string
     */
    public function getNrFlat()
    {
        return $this->nrFlat;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return UsersAddresses
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set postCode
     *
     * @param string $postCode
     *
     * @return UsersAddresses
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;

        return $this;
    }

    /**
     * Get postCode
     *
     * @return string
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return UsersAddresses
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Get idUserAddress
     *
     * @return integer
     */
    public function getIdUserAddress()
    {
        return $this->idUserAddress;
    }

    /**
     * Set idUser
     *
     * @param \Biznes\DatabaseBundle\Entity\Users $idUser
     *
     * @return UsersAddresses
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
