<?php

namespace Digs\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Profile
 */
class Profile
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nickname;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $entryLead;

    /**
     * @var integer
     */
    private $entryNum;

    /**
     * @var \DateTime
     */
    private $updatedAt;


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
     * Set nickname
     *
     * @param string $nickname
     * @return Profile
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    
        return $this;
    }

    /**
     * Get nickname
     *
     * @return string 
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Profile
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
     * Set entryLead
     *
     * @param string $entryLead
     * @return Profile
     */
    public function setEntryLead($entryLead)
    {
        $this->entryLead = $entryLead;
    
        return $this;
    }

    /**
     * Get entryLead
     *
     * @return string 
     */
    public function getEntryLead()
    {
        return $this->entryLead;
    }

    /**
     * Set entryNum
     *
     * @param integer $entryNum
     * @return Profile
     */
    public function setEntryNum($entryNum)
    {
        $this->entryNum = $entryNum;
    
        return $this;
    }

    /**
     * Get entryNum
     *
     * @return integer 
     */
    public function getEntryNum()
    {
        return $this->entryNum;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Profile
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}