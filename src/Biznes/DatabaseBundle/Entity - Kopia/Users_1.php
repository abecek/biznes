<?php

namespace Biznes\DatabaseBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @UniqueEntity(fields="username", message="Login already taken")
 * @UniqueEntity(fields="email", message="Email already taken")
 */
class Users implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id_user")
     */
    protected $idUser;
    
    /**
     * @ORM\Column(name="username")
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 20,
     *      minMessage = "Login is to short.",
     *      maxMessage = "Login is to long."
     *      )
     */
    protected $username;

    /**
     * @ORM\Column(name="email", type="string", length=35)
     * @Assert\NotBlank()
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     */
    protected $email;
    
    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 6,
     *      max = 20,
     *      minMessage = "",
     *      maxMessage = ""
     *      )
     */
    protected $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(name="password", length=64, type="string")
     */
    protected $password;

    /**
     * @ORM\Column(name="date_register", type="datetime")
     */
    protected $dateRegister;

    /**
     * @ORM\Column(name="gender", type="string")
     * @Assert\NotBlank()
     */
    protected $gender;

    /**
     * @ORM\Column(name="rank", type="integer")
     */
    protected $rank = 0;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Users
     */
    protected $idSponsor = null;
    
    
    /**
     * Set username
     *
     * @param string $username
     *
     * @return Users
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }
    
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }


    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        // The bcrypt algorithm doesn't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    /**
     * Set dateRegister
     *
     * @param \DateTime $dateRegister
     *
     * @return Users
     */
    public function setDateRegister($dateRegister)
    {
        $this->dateRegister = $dateRegister;

        return $this;
    }

    /**
     * Get dateRegister
     *
     * @return \DateTime
     */
    public function getDateRegister()
    {
        return $this->dateRegister;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Users
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     *
     * @return Users
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return boolean
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Get idUser
     *
     * @return integer
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set idSponsor
     *
     * @param integer $id
     *
     * @return Users
     */
    public function setIdSponsor($id)
    {
        $this->idSponsor = $id;

        return $this;
    }

    /**
     * Get idSponsor
     *
     * @return \Biznes\DatabaseBundle\Entity\Users
     */
    public function getIdSponsor()
    {
        return $this->idSponsor;
    }

    
    
    
    public function isEnabled()
    {
        return $this->isActive;
    }
    
    public function getRoles()
    {   
        $roles = array();
        
        if($this->rank == 0){
            array_push($roles, 'ROLE_USER');
        }
        if($this->rank == 1) {
            array_push($roles, 'ROLE_ADMIN'); 
        }
        
        return $roles;
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->idUser,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->idUser,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }

}

