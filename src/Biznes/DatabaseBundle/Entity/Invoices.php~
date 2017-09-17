<?php

namespace Biznes\DatabaseBundle\Entity;

/**
 * Invoices
 */
class Invoices
{
    /**
     * @var \DateTime
     */
    private $dateExposure;

    /**
     * @var \DateTime
     */
    private $dateSale;

    /**
     * @var \DateTime
     */
    private $datePayment;

    /**
     * @var string
     */
    private $type;

    /**
     * @var integer
     */
    private $isPaid = 0;

    /**
     * @var integer
     */
    private $idInvoice;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Orders
     */
    private $idOrder;
    
    /**
     * @var string
     */
    private $invoiceNumber;
    
    

    /**
     * Set invoiceNumber
     *
     * @param integer $idOrder
     *
     * @return Invoices
     */
    public function setInvoiceNumber($idOrder)
    {
        $invoiceNumber = substr($this->type, 0, 3) . '/' . $this->dateExposure->format('Y/m/d') . '/' . strval($idOrder);
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }
    
    /**
     * Get invoiceNumber
     *
     * @return string
     */
    public function getInvoiceNumber()
    {
        if($this->invoiceNumber === null && $this->idOrder !== null){
            $this->setInvoiceNumber($this->idOrder->getIdOrder());
        }
        
        return $this->invoiceNumber;
    }

    
    
    /**
     * Set dateExposure
     *
     * @param \DateTime $dateExposure
     *
     * @return Invoices
     */
    public function setDateExposure($dateExposure)
    {
        $this->dateExposure = $dateExposure;

        return $this;
    }

    /**
     * Get dateExposure
     *
     * @return \DateTime
     */
    public function getDateExposure()
    {
        return $this->dateExposure;
    }

    /**
     * Set dateSale
     *
     * @param \DateTime $dateSale
     *
     * @return Invoices
     */
    public function setDateSale($dateSale)
    {
        $this->dateSale = $dateSale;

        return $this;
    }

    /**
     * Get dateSale
     *
     * @return \DateTime
     */
    public function getDateSale()
    {
        return $this->dateSale;
    }

    /**
     * Set datePayment
     *
     * @param \DateTime $datePayment
     *
     * @return Invoices
     */
    public function setDatePayment($datePayment)
    {
        $this->datePayment = $datePayment;

        return $this;
    }

    /**
     * Get datePayment
     *
     * @return \DateTime
     */
    public function getDatePayment()
    {
        return $this->datePayment;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Invoices
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set isPaid
     *
     * @param integer $isPaid
     *
     * @return Invoices
     */
    public function setIsPaid($isPaid)
    {
        $this->isPaid = $isPaid;

        return $this;
    }

    /**
     * Get isPaid
     *
     * @return integer
     */
    public function getIsPaid()
    {
        return $this->isPaid;
    }

    /**
     * Get idInvoice
     *
     * @return integer
     */
    public function getIdInvoice()
    {
        return $this->idInvoice;
    }

    /**
     * Set idOrder
     *
     * @param \Biznes\DatabaseBundle\Entity\Orders $idOrder
     *
     * @return Invoices
     */
    public function setIdOrder(\Biznes\DatabaseBundle\Entity\Orders $idOrder = null)
    {
        $this->idOrder = $idOrder;

        return $this;
    }

    /**
     * Get idOrder
     *
     * @return \Biznes\DatabaseBundle\Entity\Orders
     */
    public function getIdOrder()
    {
        return $this->idOrder;
    }
}
