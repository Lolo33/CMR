<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Structure
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StructureRepository")
 */
class Structure
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var StructureType
     *
     * @ORM\ManyToOne(targetEntity="StructureType", inversedBy="structuresList")
     */
    private $type;

    /**
     * @var Interlocutor[]
     *
     * @ORM\OneToMany(targetEntity="Interlocutor", mappedBy="structure")
     */
    private $interlocutorsList;


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
     * Set type
     *
     * @param string $type
     *
     * @return Structure
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
     * Constructor
     */
    public function __construct()
    {
        $this->interlocutorsList = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add interlocutorsList
     *
     * @param \AppBundle\Entity\Interlocutor $interlocutorsList
     *
     * @return Structure
     */
    public function addInterlocutorsList(\AppBundle\Entity\Interlocutor $interlocutorsList)
    {
        $this->interlocutorsList[] = $interlocutorsList;

        return $this;
    }

    /**
     * Remove interlocutorsList
     *
     * @param \AppBundle\Entity\Interlocutor $interlocutorsList
     */
    public function removeInterlocutorsList(\AppBundle\Entity\Interlocutor $interlocutorsList)
    {
        $this->interlocutorsList->removeElement($interlocutorsList);
    }

    /**
     * Get interlocutorsList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInterlocutorsList()
    {
        return $this->interlocutorsList;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Structure
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
}
