<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StructureType
 *
 * @ORM\Table(name="structure_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StructureTypeRepository")
 */
class StructureType
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
     * @ORM\Column(name="typeName", type="string", length=255)
     */
    private $typeName;

    /**
     * @var Structure[]
     *
     * @ORM\OneToMany(targetEntity="Structure", mappedBy="type")
     */
    private $structuresList;


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
     * Set typeName
     *
     * @param string $typeName
     *
     * @return StructureType
     */
    public function setTypeName($typeName)
    {
        $this->typeName = $typeName;

        return $this;
    }

    /**
     * Get typeName
     *
     * @return string
     */
    public function getTypeName()
    {
        return $this->typeName;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->structuresList = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add structuresList
     *
     * @param \AppBundle\Entity\Structure $structuresList
     *
     * @return StructureType
     */
    public function addStructuresList(\AppBundle\Entity\Structure $structuresList)
    {
        $this->structuresList[] = $structuresList;

        return $this;
    }

    /**
     * Remove structuresList
     *
     * @param \AppBundle\Entity\Structure $structuresList
     */
    public function removeStructuresList(\AppBundle\Entity\Structure $structuresList)
    {
        $this->structuresList->removeElement($structuresList);
    }

    /**
     * Get structuresList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStructuresList()
    {
        return $this->structuresList;
    }
}
