<?php

namespace Biznes\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Biznes\DatabaseBundle\Repository\RMRepository")
 */
class RealizationMethods
{
    /**
     * @var integer
     */
    private $idRealizationMethod;
    
    /**
     * @var string
     */
    private $name;

    /**
     * Set name
     *
     * @param string $name
     * @return RealizationMethods
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get idRealizationMethod
     *
     * @return integer 
     */
    public function getIdRealizationMethod()
    {
        return $this->idRealizationMethod;
    }
}
