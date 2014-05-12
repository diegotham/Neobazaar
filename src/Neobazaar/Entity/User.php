<?php

namespace Neobazaar\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\Collection,
	Doctrine\Common\Collections\ArrayCollection,
	Doctrine\Common\Util\Debug as DDebug;

use ZfcUser\Entity\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="user", 
 * 	uniqueConstraints={
 * 		@ORM\UniqueConstraint(name="username", columns={"username"}),
 * 		@ORM\UniqueConstraint(name="email", columns={"email"}),
 * 		@ORM\UniqueConstraint(name="nicename", columns={"nicename"})
 * })
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Neobazaar\Entity\Repository\UserRepository")
 */
class User implements UserInterface
{
	// State enum values
	const USER_STATE_ACTIVE = 1;
	const USER_STATE_DEACTIVE = 2;
	const USER_STATE_DELETED = 3;
	const USER_STATE_BANNED = 5;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", length=10, nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="nicename", type="string", length=50, nullable=true)
     */
    private $nicename;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=50, nullable=true)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=128, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=5, nullable=true, options={"default" = "en_GB"})
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=19, nullable=true, options={"default" = "guest"})
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=1, nullable=true)
     */
    private $gender;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="smallint", nullable=true, options={"unsigned"=true})
     */
    private $state;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_born", type="date", nullable=true)
     */
    private $dateBorn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_activated", type="datetime", nullable=true)
     */
    private $dateActivated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_insert", type="datetime", nullable=true)
     */
    private $dateInsert;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_edit", type="datetime", nullable=true)
     */
    private $dateEdit;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Neobazaar\Entity\Document", mappedBy="user", fetch="EXTRA_LAZY")
     */
    private $document;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Neobazaar\Entity\UserMeta", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $metadata;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->document = new ArrayCollection();
        $this->metadata = new ArrayCollection();
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->getUserId();
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
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
     * Set nicename
     *
     * @param string $nicename
     * @return User
     */
    public function setNicename($nicename)
    {
        $this->nicename = $nicename;
    
        return $this;
    }

    /**
     * Get nicename
     *
     * @return string 
     */
    public function getNicename()
    {
        return $this->nicename;
    }

    /**
     * Get nicename
     *
     * @return string 
     */
    public function getDisplayName()
    {
        return $this->getNicename();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
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
     * Set surname
     *
     * @param string $surname
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    
        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
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
     * Set locale
     *
     * @param string $locale
     * @return User
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    
        return $this;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    
        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set state
     *
     * @param integer $state
     * @return User
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return integer 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set dateBorn
     *
     * @param \DateTime $dateBorn
     * @return User
     */
    public function setDateBorn($dateBorn)
    {
        $this->dateBorn = $dateBorn;
    
        return $this;
    }

    /**
     * Get dateBorn
     *
     * @return \DateTime 
     */
    public function getDateBorn()
    {
        return $this->dateBorn;
    }

    /**
     * Set dateActivated
     *
     * @param \DateTime $dateActivated
     * @return User
     */
    public function setDateActivated($dateActivated)
    {
        $this->dateActivated = $dateActivated;
    
        return $this;
    }

    /**
     * Get dateActivated
     *
     * @return \DateTime 
     */
    public function getDateActivated()
    {
        return $this->dateActivated;
    }

    /**
     * Set dateInsert
     *
     * @param \DateTime $dateInsert
     * @return User
     */
    public function setDateInsert($dateInsert)
    {
        $this->dateInsert = $dateInsert;
    
        return $this;
    }

    /**
     * Get dateInsert
     *
     * @return \DateTime 
     */
    public function getDateInsert()
    {
        return $this->dateInsert;
    }

    /**
     * Set dateEdit
     *
     * @param \DateTime $dateEdit
     * @return User
     */
    public function setDateEdit($dateEdit)
    {
        $this->dateEdit = $dateEdit;
    
        return $this;
    }

    /**
     * Get dateEdit
     *
     * @return \DateTime 
     */
    public function getDateEdit()
    {
        return $this->dateEdit;
    }

    /**
     * Get document
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocument()
    {
        return $this->document;
    }
    
    public function addDocument(Collection $documents)
    {
    	foreach ($documents as $document) {
    		$document->setUser($this);
    		$this->document->add($document);
    	}
    }
    
    public function removeDocument(Collection $documents)
    {
    	foreach ($documents as $document) {
    		$document->setUser(null);
    		$this->document->removeElement($document);
    	}
    }

    /**
     * Get metadata
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
    
    public function addMetadata(Collection $metadata)
    {
    	foreach ($metadata as $meta) {
    		$meta->setUser($this);
    		$this->metadata->add($meta);
    	}
    }
    
    public function removeMetadata(Collection $metadata)
    {
    	foreach ($metadata as $meta) {
    		$meta->setUser(null);
    		$this->metadata->removeElement($meta);
    	}
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
    	$this->setDateEdit(new \Datetime());
    }
    
    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
    	$this->setDateInsert(new \Datetime());
    	$this->setDateEdit(new \Datetime());
    	$this->setUsername($this->getEmail());
    }
}