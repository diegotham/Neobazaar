<?php

namespace Neobazaar\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\Collection,
	Doctrine\Common\Collections\ArrayCollection,
	Doctrine\Common\Util\Debug as DDebug;

/**
 * Document
 *
 * @ORM\Table(name="document")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Neobazaar\Entity\Repository\DocumentRepository")
 */
class Document
   // extends MappedSuperclassBase
{
	// DocumentType enum values
	const DOCUMENT_TYPE_PAGE = 'page';
	const DOCUMENT_TYPE_CLASSIFIED = 'ads';
	const DOCUMENT_TYPE_IMAGE = 'image';
	
	// State enum values
	const DOCUMENT_STATE_ACTIVE = 1;
	const DOCUMENT_STATE_DEACTIVE = 2;
	const DOCUMENT_STATE_DELETED = 3;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="document_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $documentId;

    /**
     * @var string
     *
     * @ORM\Column(name="document_type", type="string", length=19, nullable=false)
     */
    protected $documentType;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=200, nullable=true)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    protected $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="smallint", nullable=false)
     */
    protected $state;

    /**
     * @var integer
     *
     * @ORM\Column(name="visited", type="integer", nullable=false)
     */
    protected $visited;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_insert", type="string", length=15, nullable=true)
     */
    protected $ipInsert;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_insert", type="datetime", nullable=true)
     */
    protected $dateInsert;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_edit", type="datetime", nullable=true)
     */
    protected $dateEdit;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Neobazaar\Entity\TermTaxonomy", inversedBy="document", cascade={"persist"})
     * @ORM\JoinTable(name="term_relationship", 
     *   joinColumns={
     *     @ORM\JoinColumn(name="document_id", referencedColumnName="document_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="term_taxonomy_id", referencedColumnName="term_taxonomy_id")
     *   }
     * )
     */
    protected $termTaxonomy;

    /**
     * @var \Neobazaar\Entity\Document
     *
     * @ORM\ManyToOne(targetEntity="Neobazaar\Entity\Document", inversedBy="children", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="document_id")
     * })
     */
    protected $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Neobazaar\Entity\Document", mappedBy="parent", cascade={"remove"})
     */
    protected $children;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Neobazaar\Entity\DocumentMeta", mappedBy="document", cascade={"persist", "remove"})
     */
    protected $metadata;

    /**
     * @var \Neobazaar\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Neobazaar\Entity\User", inversedBy="document", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    protected $user;

    /**
     * @var \Neobazaar\Entity\Geonames
     *
     * @ORM\ManyToOne(targetEntity="Neobazaar\Entity\Geonames")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="geoname_id", referencedColumnName="geoname_id")
     * })
     */
    protected $geoname;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->termTaxonomy = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->metadata = new ArrayCollection();
    }

    /**
     * Get documentId
     *
     * @return integer 
     */
    public function getDocumentId()
    {
        return $this->documentId;
    }
    
    
    static private $documentTypeValues = null;
    static public function getDocumentTypeValues()
    {
    	if (self::$documentTypeValues == null) {
    		self::$documentTypeValues = array();
    		$oClass = new \ReflectionClass(__NAMESPACE__ . '\Document');
    		$classConstants = $oClass->getConstants();
    		$constantPrefix = "DOCUMENT_TYPE_";
    		foreach ($classConstants as $key => $val) {
    			if (substr($key, 0, strlen($constantPrefix)) === $constantPrefix) {
    				self::$documentTypeValues[$val] = $val;
    			}
    		}
    	}
    	return self::$documentTypeValues;
    }

    /**
     * Set documentType
     *
     * @param string $documentType
     * @return Document
     */
    public function setDocumentType($documentType)
    {
    	if (!in_array($documentType, self::getDocumentTypeValues())) {
    		throw new \InvalidArgumentException(
				sprintf('Invalid value for document.documentType : %s.', $documentType)
    		);
    	}
    	
        $this->documentType = $documentType;
    
        return $this;
    }

    /**
     * Get documentType
     *
     * @return string 
     */
    public function getDocumentType()
    {
        return $this->documentType;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Document
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
     * Set content
     *
     * @param string $content
     * @return Document
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set state
     *
     * @param integer $state
     * @return Document
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
     * Set visited
     *
     * @param integer $visited
     * @return Document
     */
    public function setVisited($visited)
    {
        $this->visited = $visited;
    
        return $this;
    }

    /**
     * Get visited
     *
     * @return integer 
     */
    public function getVisited()
    {
        return $this->visited;
    }

    /**
     * Set ipInsert
     *
     * @param string $ipInsert
     * @return Document
     */
    public function setIpInsert($ipInsert)
    {
        $this->ipInsert = $ipInsert;
    
        return $this;
    }

    /**
     * Get ipInsert
     *
     * @return string 
     */
    public function getIpInsert()
    {
        return $this->ipInsert;
    }

    /**
     * Set dateInsert
     *
     * @param \DateTime $dateInsert
     * @return Document
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
     * @return Document
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

//     /**
//      * Add termTaxonomy
//      *
//      * @param \Neobazaar\Entity\TermTaxonomy $termTaxonomy
//      * @return Document
//      */
//     public function addTermTaxonomy(\Neobazaar\Entity\TermTaxonomy $termTaxonomy)
//     {
//         $this->termTaxonomy[] = $termTaxonomy;
    
//         return $this;
//     }

//     /**
//      * Remove termTaxonomy
//      *
//      * @param \Neobazaar\Entity\TermTaxonomy $termTaxonomy
//      */
//     public function removeTermTaxonomy(\Neobazaar\Entity\TermTaxonomy $termTaxonomy)
//     {
//         $this->termTaxonomy->removeElement($termTaxonomy);
//     }
    

    public function addTermTaxonomy(Collection $termTaxonomies)
    {
    	foreach ($termTaxonomies as $termTaxonomy) {
    		$this->termTaxonomy->add($termTaxonomy);
    	}
    }
    
    public function removeTermTaxonomy(Collection $termTaxonomies)
    {
    	foreach ($termTaxonomies as $termTaxonomy) {
			$this->termTaxonomy->removeElement($termTaxonomy);
    	}
    }

    /**
     * Get termTaxonomy
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTermTaxonomy()
    {
        return $this->termTaxonomy;
    }

    /**
     * Set parent
     *
     * @param \Neobazaar\Entity\Document $parent
     * @return Document
     */
    public function setParent(\Neobazaar\Entity\Document $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \Neobazaar\Entity\Document 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    public function addChildren(Collection $children)
    {
    	foreach ($children as $child) {
    		$child->setParent($this);
    		$this->children->add($child);
    	}
    }
    
    public function removeChildren(Collection $children)
    {
    	foreach ($children as $child) {
    		$child->setParent(null);
    		$this->children->removeElement($child);
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
    		$meta->setDocument($this);
    		$this->metadata->add($meta);
    	}
    }
    
    public function removeMetadata(Collection $metadata)
    {
    	foreach ($metadata as $meta) {
    		$meta->setDocument(null);
    		$this->metadata->removeElement($meta);
    	}
    }

    /**
     * Set user
     *
     * @param \Neobazaar\Entity\User $user
     * @return Document
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
     * Set geoname
     *
     * @param \Neobazaar\Entity\Geonames $geoname
     * @return Document
     */
    public function setGeoname(\Neobazaar\Entity\Geonames $geoname = null)
    {
        $this->geoname = $geoname;
    
        return $this;
    }

    /**
     * Get geoname
     *
     * @return \Neobazaar\Entity\Geonames 
     */
    public function getGeoname()
    {
        return $this->geoname;
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
    	if(null === $this->getVisited()) {
    		$this->setDateEdit(new \Datetime());
    	}
    }
    
    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
		$remote = new \Zend\Http\PhpEnvironment\RemoteAddress;
    	$this->setDateInsert(new \Datetime());
    	$this->setDateEdit(new \Datetime());
    	$this->setVisited(0);
    	$this->setIpInsert($remote->getIpAddress());
    }
}