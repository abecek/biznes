<?php

namespace Biznes\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categories
 */
class Categories
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var boolean
     */
    private $idCategory;


    /**
     * Set name
     *
     * @param string $name
     * @return Categories
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
     * Get idCategory
     *
     * @return boolean 
     */
    public function getIdCategory()
    {
        return $this->idCategory;
    }
}
