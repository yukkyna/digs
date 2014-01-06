<?php

namespace Digs\EntryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entry
 */
class Entry
{
	public function setCreatedAtValue()
	{
		$this->setCreatedAt(new \DateTime());
	}
	
	public function setEscapedMessageValue()
	{
		$config = \HTMLPurifier_Config::createDefault();
		$config->set('HTML.AllowedElements', array());
		$purifier = new \HTMLPurifier($config);
		$msg = $purifier->purify($this->getMessage());
		$this->setEscapedMessage($msg);
	}
	
	public function setUpdatedAtValue()
	{
		$this->setUpdatedAt(new \DateTime());
	}
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $escapedMessage;

    /**
     * @var utcdatetime
     */
    private $createdAt;

    /**
     * @var utcdatetime
     */
    private $updatedAt;

    /**
     * @var integer
     */
    private $status;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $comments;

    /**
     * @var \Digs\CoreBundle\Entity\Member
     */
    private $member;

    /**
     * @var \Digs\EntryBundle\Entity\EntryTag
     */
    private $tags;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set title
     *
     * @param string $title
     * @return Entry
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Entry
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
     * Set escapedMessage
     *
     * @param string $escapedMessage
     * @return Entry
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

    /**
     * Set createdAt
     *
     * @param utcdatetime $createdAt
     * @return Entry
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
     * Set updatedAt
     *
     * @param utcdatetime $updatedAt
     * @return Entry
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return utcdatetime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Entry
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
     * Add comments
     *
     * @param \Digs\EntryBundle\Entity\EntryComment $comments
     * @return Entry
     */
    public function addComment(\Digs\EntryBundle\Entity\EntryComment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Digs\EntryBundle\Entity\EntryComment $comments
     */
    public function removeComment(\Digs\EntryBundle\Entity\EntryComment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set member
     *
     * @param \Digs\CoreBundle\Entity\Member $member
     * @return Entry
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
     * Set tags
     *
     * @param \Digs\EntryBundle\Entity\EntryTag $tags
     * @return Entry
     */
    public function setTags(\Digs\EntryBundle\Entity\EntryTag $tags = null)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return \Digs\EntryBundle\Entity\EntryTag 
     */
    public function getTags()
    {
        return $this->tags;
    }
}
