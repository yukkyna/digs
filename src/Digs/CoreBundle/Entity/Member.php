<?php

namespace Digs\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Member
 */
class Member implements AdvancedUserInterface, \Serializable
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $salt;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

	public function __construct()
    {
//        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
		$this->roles = new ArrayCollection();
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
     * Set email
     *
     * @param string $email
     * @return Member
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
     * @return Member
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
     * Set salt
     *
     * @param string $salt
     * @return Member
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Member
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Member
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }
	
	public function getRoles()
    {
        return $this->roles->toArray();
    }
	
	public function eraseCredentials()
    {
    }
	
	public function serialize() {
		return serialize(array(
            $this->id,
        ));
	}

	public function unserialize($serialized) {
		list (
            $this->id,
        ) = unserialize($serialized);
	}

	public function getUsername() {
		return $this->getEmail();
	}

	public function isAccountNonExpired() {
		return true;
	}

	public function isAccountNonLocked() {
		return true;
		
	}

	public function isCredentialsNonExpired() {
		return true;
		
	}

	public function isEnabled() {
		return true;
		
	}
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $roles;


    /**
     * Add roles
     *
     * @param \Digs\CoreBundle\Entity\Role $roles
     * @return Member
     */
    public function addRole(\Digs\CoreBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;
    
        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Digs\CoreBundle\Entity\Role $roles
     */
    public function removeRole(\Digs\CoreBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }
    /**
     * @var boolean
     */
    private $active;


    /**
     * Set active
     *
     * @param boolean $active
     * @return Member
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }
    /**
     * @var \Digs\CoreBundle\Entity\Profile
     */
    private $profile;


    /**
     * Set profile
     *
     * @param \Digs\CoreBundle\Entity\Profile $profile
     * @return Member
     */
    public function setProfile(\Digs\CoreBundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;
    
        return $this;
    }

    /**
     * Get profile
     *
     * @return \Digs\CoreBundle\Entity\Profile 
     */
    public function getProfile()
    {
        return $this->profile;
    }
}