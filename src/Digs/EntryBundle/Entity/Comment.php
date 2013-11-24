<?php

namespace Digs\EntryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 */
class Comment
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $message;

    /**
     * @var utcdatetime
     */
    private $createdAt;

    /**
     * @var integer
     */
    private $status;


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
     * Set message
     *
     * @param string $message
     * @return Comment
     */
    public function setMessage($message)
    {
        $this->message = $message;
    
        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set createdAt
     *
     * @param utcdatetime $createdAt
     * @return Comment
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return utcdatetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Comment
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * @var \Digs\EntryBundle\Entity\Entry
     */
    private $entry;


    /**
     * Set entry
     *
     * @param \Digs\EntryBundle\Entity\Entry $entry
     * @return Comment
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
    /**
     * @var \Digs\CoreBundle\Entity\Member
     */
    private $member;


    /**
     * Set member
     *
     * @param \Digs\CoreBundle\Entity\Member $member
     * @return Comment
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
}