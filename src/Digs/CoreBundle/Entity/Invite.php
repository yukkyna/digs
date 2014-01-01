<?php

namespace Digs\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Invite
 */
class Invite
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $ticket;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var utcdatetime
     */
    private $createdAt;


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
     * Set ticket
     *
     * @param string $ticket
     * @return Invite
     */
    public function setTicket($ticket)
    {
        $this->ticket = $ticket;

        return $this;
    }

    /**
     * Get ticket
     *
     * @return string 
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Invite
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Invite
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set createdAt
     *
     * @param utcdatetime $createdAt
     * @return Invite
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
     * @var string
     */
    private $category_ids;


    /**
     * Set category_ids
     *
     * @param string $categoryIds
     * @return Invite
     */
    public function setCategoryIds($categoryIds)
    {
        $this->category_ids = $categoryIds;

        return $this;
    }

    /**
     * Get category_ids
     *
     * @return string 
     */
    public function getCategoryIds()
    {
        return $this->category_ids;
    }
}
