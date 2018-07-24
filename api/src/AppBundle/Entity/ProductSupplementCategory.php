<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductSupplementCategory
 *
 * @ORM\Table(name="product_supplement_group")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductSupplementGroupRepository")
 */
class ProductSupplementCategory
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="supplements")
     */
    private $product;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="SupplementCategory")
     */
    private $supGroup;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set product
     *
     * @param string $product
     *
     * @return ProductSupplementCategory
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return string
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set supGroup
     *
     * @param string $supGroup
     *
     * @return ProductSupplementCategory
     */
    public function setSupGroup($supGroup)
    {
        $this->supGroup = $supGroup;

        return $this;
    }

    /**
     * Get supGroup
     *
     * @return string
     */
    public function getSupGroup()
    {
        return $this->supGroup;
    }
}

