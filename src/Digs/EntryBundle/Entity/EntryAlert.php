<?php

namespace Digs\EntryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntryAlert
 */
class EntryAlert
{
    /**
     * @var integer
     */
    private $id;


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
     * @var \Digs\CoreBundle\Entity\Member
     */
    private $member;

    /**
     * @var \Digs\EntryBundle\Entity\Entry
     */
    private $entry;


    /**
     * Set member
     *
     * @param \Digs\CoreBundle\Entity\Member $member
     * @return EntryAlert
     */
    public function setMember(\Digs\CoreBundle\Entity\Member $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \Digs\CoreBundle\Entity\Member 
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set entry
     *
     * @param \Digs\EntryBundle\Entity\Entry $entry
     * @return EntryAlert
     */
    public function setEntry(\Digs\EntryBundle\Entity\Entry $entry = null)
    {
        $this->entry = $entry;

        return $this;
    }

    /**
     * Get entry
     *
     * @return \Digs\EntryBundle\Entity\Entry 
     */
    public function getEntry()
    {
        return $this->entry;
    }
}
