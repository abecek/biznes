<?php

namespace Biznes\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentMethods
 */
class PaymentMethods
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var boolean
     */
    private $idPaymentMethod;


    /**
     * Set name
     *
     * @param string $name
     * @return PaymentMethods
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
     * Get idPaymentMethod
     *
     * @return boolean 
     */
    public function getIdPaymentMethod()
    {
        return $this->idPaymentMethod;
    }
}
