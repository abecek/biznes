<?php

namespace Biznes\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Products
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="Biznes\DatabaseBundle\Repository\ProductsRepository")
 */
class Products implements \Serializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $price;
    
    
    private $priceBrutto;
    
    private $vatValue;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $rating;

    /**
     * @var integer
     */
    private $idProduct;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Categories
     */
    private $idCategory;

    /**
     * @var \Biznes\DatabaseBundle\Entity\Programs
     */
    private $idProgram;


    /**
     * Set name
     *
     * @param string $name
     *
     * @return Products
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
     * Set description
     *
     * @param string $description
     *
     * @return Products
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Products
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }
    
    /**
     * Set priceBrutto
     *
     * @param string $price
     *
     * @return Products
     */
    public function setPriceBrutto($price)
    {
        $this->priceBrutto = $price;

        return $this;
    }

    /**
     * Get priceBrutto
     *
     * @return string
     */
    public function getPriceBrutto()
    {
        return $this->priceBrutto;
    }
    
        /**
     * Set vatValue
     *
     * @param string $vatValue
     *
     * @return Products
     */
    public function setVatValue($vatValue)
    {
        $this->vatValue = $vatValue;

        return $this;
    }

    /**
     * Get vatValue
     *
     * @return string
     */
    public function getVatValue()
    {
        return $this->vatValue;
    }

    /**
     * Set version
     *
     * @param string $version
     *
     * @return Products
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set rating
     *
     * @param string $rating
     *
     * @return Products
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Get idProduct
     *
     * @return integer
     */
    public function getIdProduct()
    {
        return $this->idProduct;
    }

    /**
     * Set idCategory
     *
     * @param \Biznes\DatabaseBundle\Entity\Categories $idCategory
     *
     * @return Products
     */
    public function setIdCategory(\Biznes\DatabaseBundle\Entity\Categories $idCategory = null)
    {
        $this->idCategory = $idCategory;

        return $this;
    }

    /**
     * Get idCategory
     *
     * @return \Biznes\DatabaseBundle\Entity\Categories
     */
    public function getIdCategory()
    {
        return $this->idCategory;
    }

    /**
     * Set idProgram
     *
     * @param \Biznes\DatabaseBundle\Entity\Programs $idProgram
     *
     * @return Products
     */
    public function setIdProgram(\Biznes\DatabaseBundle\Entity\Programs $idProgram = null)
    {
        $this->idProgram = $idProgram;

        return $this;
    }

    /**
     * Get idProgram
     *
     * @return \Biznes\DatabaseBundle\Entity\Programs
     */
    public function getIdProgram()
    {
        return $this->idProgram;
    }
    
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->idProduct,
            $this->name,
            $this->description,
            $this->price,
            $this->priceBrutto,
            $this->vatValue,
            $this->version,
            $this->rating,
            $this->idCategory,
            $this->idProduct,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->idProduct,
            $this->name,
            $this->description,
            $this->price,
            $this->priceBrutto,
            $this->vatValue,
            $this->version,
            $this->rating,
            $this->idCategory,
            $this->idProduct,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }
}
