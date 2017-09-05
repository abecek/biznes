<?php

namespace Biznes\DatabaseBundle\Entity;

/**
 * Programs
 */
class Programs
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var boolean
     */
    private $idProgram;


    /**
     * Set name
     *
     * @param string $name
     *
     * @return Programs
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
     * Get idProgram
     *
     * @return boolean
     */
    public function getIdProgram()
    {
        return $this->idProgram;
    }
}
