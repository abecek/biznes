<?php

namespace Biznes\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * UsersAddresses
 * @ORM\Entity
 * @ORM\Table(name="users_addresses")
 * @ORM\Entity(repositoryClass="Biznes\DatabaseBundle\Repository\UsersAddressesRepository")
 */
class UsersAddresses implements \Serializable
{
    /**
     * @var integer
     */
    private $idUserAddress;
    
    /**
     * @ORM\Column(name="ulica")
     * @Assert\NotBlank()
     */
    private $ulica;

    /**
     * @ORM\Column(name="nr_house")
     * @Assert\NotBlank()
     */
    private $nrHouse;

    /**
     * @ORM\Column(name="nr_flat")
     */
    private $nrFlat;

    /**
     * @ORM\Column(name="city")
     * @Assert\NotBlank()
     */
    private $city;

    /**
     * @ORM\Column(name="post_code")
     */
    private $postCode;

    /**
     * @ORM\Column(name="country")
     * @Assert\NotBlank()
     */
    private $country;


    /**
     * @var \Biznes\DatabaseBundle\Entity\Users
     */
    private $idUser;


    /**
     * Set ulica
     *
     * @param string $ulica
     * @return UsersAddresses
     */
    public function setUlica($ulica)
    {
        $this->ulica = $ulica;

        return $this;
    }

    /**
     * Get steet
     *
     * @return string 
     */
    public function getUlica()
    {
        return $this->ulica;
    }

    /**
     * Set nrHouse
     *
     * @param string $nrHouse
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
    
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->idUser,
            $this->idUserAddress,
            $this->city,
            $this->country,
            $this->ulica,
            $this->nrFlat,
            $this->nrHouse,
            $this->postCode,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->idUser,
            $this->idUserAddress,
            $this->city,
            $this->country,
            $this->ulica,
            $this->nrFlat,
            $this->nrHouse,
            $this->postCode,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }
}
