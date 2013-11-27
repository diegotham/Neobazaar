<?php

namespace Neobazaar\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * UserMeta
 *
 * @ORM\Table(name="user_meta", 
 * 	indexes={
 * 		@ORM\Index(name="user_meta_type_id", columns={"key"})
 * }))
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class UserMeta
{
    /**
     * @var integer
     *
     * @ORM\Column(name="user_meta_id", type="integer", length=10, nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userMetaId;

    /**
     * @var string
     *
     * @ORM\Column(name="`key`", type="string", length=30, nullable=false)
     */
    private $key;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    private $value;

    /**
     * @var \Neobazaar\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Neobazaar\Entity\User", inversedBy="metadata")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", nullable=false, referencedColumnName="user_id")
     * })
     */
    private $user;

    /**
     * Get userMetaId
     *
     * @return integer 
     */
    public function getUserMetaId()
    {
        return $this->userMetaId;
    }

    /**
     * Set key
     *
     * @param string $key
     * @return UserMeta
     */
    public function setKey($key)
    {
        $this->key = $key;
    
        return $this;
    }

    /**
     * Get key
     *
     * @return string 
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return UserMeta
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set user
     *
     * @param \Neobazaar\Entity\User $user
     * @return UserMeta
     */
    public function setUser(\Neobazaar\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Neobazaar\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
    	if(null === $this->getUser()) {
    		$this->setUser(0);
    	}
    }
    
    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
    	if(null === $this->getUser()) {
    		$this->setUser(0);
    	}
    }
}