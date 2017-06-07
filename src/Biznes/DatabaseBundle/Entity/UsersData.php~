<?php

namespace Biznes\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * UsersData
 * @ORM\Entity
 * @ORM\Table(name="users_data")
 * @ORM\Entity(repositoryClass="Biznes\DatabaseBundle\Repository\UsersAddressesRepository")
 * @UniqueEntity(fields="identityNumber", message="Identity number already taken")
 */
class UsersData implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id_user")
     */
    private $idUserData;
    
    /**
     * @ORM\Column(name="name1")
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 25,
     *      minMessage = "Name is to short.",
     *      maxMessage = "Name is to long."
     *      )
     */
    private $name1;

    /**
     * @ORM\Column(name="name2")
     * @Assert\Length(
     *      min = 3,
     *      max = 25,
     *      minMessage = "Second name is to short.",
     *      maxMessage = "Second name is to long."
     *      )
     */
    private $name2;

    /**
     * @ORM\Column(name="surname")
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 35,
     *      minMessage = "Surname is to short.",
     *      maxMessage = "Surname is to long."
     *      )
     */
    private $surname;

    /**
     * @ORM\Column(name="identity_number", type="string", length=11)
     * @Assert\Length(
     *      min = 11,
     *      max = 11
     *      )
     */
    private $identityNumber;

    /**
     * @ORM\Column(name="telephone", type="string", length=9)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 9,
     *      max = 9
     *      )
     */
    private $telephone;

    /**
     * @ORM\Column(name="gender", type="string")
     * @Assert\NotBlank()
     */
    private $language;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Users
     */
    private $idUser;


    /**
     * Set name1
     *
     * @param string $name1
     * @return UsersData
     */
    public function setName1($name1)
    {
        $this->name1 = $name1;

        return $this;
    }

    /**
     * Get name1
     *
     * @return string 
     */
    public function getName1()
    {
        return $this->name1;
    }

    /**
     * Set name2
     *
     * @param string $name2
     * @return UsersData
     */
    public function setName2($name2)
    {
        $this->name2 = $name2;

        return $this;
    }

    /**
     * Get name2
     *
     * @return string 
     */
    public function getName2()
    {
        return $this->name2;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return UsersData
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set identityNumber
     *
     * @param string $identityNumber
     * @return UsersData
     */
    public function setIdentityNumber($identityNumber)
    {
        $this->identityNumber = $identityNumber;

        return $this;
    }

    /**
     * Get identityNumber
     *
     * @return string 
     */
    public function getIdentityNumber()
    {
        return $this->identityNumber;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return UsersData
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return UsersData
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Get idUserData
     *
     * @return integer 
     */
    public function getIdUserData()
    {
        return $this->idUserData;
    }

    /**
     * Set idUser
     *
     * @param \Biznes\DatabaseBundle\Entity\Users $idUser
     * @return UsersData
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
            $this->idUserData,
            $this->identityNumber,
            $this->language,
            $this->name1,
            $this->name2,
            $this->surname,
            $this->telephone,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->idUserData,
            $this->identityNumber,
            $this->language,
            $this->name1,
            $this->name2,
            $this->surname,
            $this->telephone,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }

}
