<?php

namespace Digs\EntryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntryComment
 */
class EntryComment
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
     * @var \Digs\CoreBundle\Entity\Member
     */
    private $member;

    /**
     * @var \Digs\EntryBundle\Entity\Entry
     */
    private $entry;


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
     * @return EntryComment
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
     * @return EntryComment
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
     * @return EntryComment
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
     * Set member
     *
     * @param \Digs\CoreBundle\Entity\Member $member
     * @return EntryComment
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
     * @return EntryComment
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

	public function setCreatedAtValue()
	{
		$this->createdAt = new \DateTime();
	}
    /**
     * @var string
     */
    private $escapedMessage;


    /**
     * Set escapedMessage
     *
     * @param string $escapedMessage
     * @return EntryComment
     */
    public function setEscapedMessage($escapedMessage)
    {
        $this->escapedMessage = $escapedMessage;

        return $this;
    }

    /**
     * Get escapedMessage
     *
     * @return string 
     */
    public function getEscapedMessage()
    {
        return $this->escapedMessage;
    }
}
