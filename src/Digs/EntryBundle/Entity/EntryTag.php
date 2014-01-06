<?php

namespace Digs\EntryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntryTag
 */
class EntryTag
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return EntryTag
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $entries;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->entries = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add entries
     *
     * @param \Digs\EntryBundle\Entity\Entry $entries
     * @return EntryTag
     */
    public function addEntry(\Digs\EntryBundle\Entity\Entry $entries)
    {
        $this->entries[] = $entries;

        return $this;
    }

    /**
     * Remove entries
     *
     * @param \Digs\EntryBundle\Entity\Entry $entries
     */
    public function removeEntry(\Digs\EntryBundle\Entity\Entry $entries)
    {
        $this->entries->removeElement($entries);
    }

    /**
     * Get entries
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEntries()
    {
        return $this->entries;
    }
}
