<?php

namespace Digs\InformationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Information
 */
class Information
{
    public function __construct()
    {
        $this->setStatus(1);
    }

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
     * @return Information
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
     * @return Information
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
     * @return Information
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
     * @return Information
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
     * @return Information
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
     * @return Information
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
}
