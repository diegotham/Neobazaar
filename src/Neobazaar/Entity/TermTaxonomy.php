<?php

namespace Neobazaar\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\Collection,
	Doctrine\Common\Collections\ArrayCollection,
	Doctrine\Common\Util\Debug as DDebug;

/**
 * TermTaxonomy
 *
 * @ORM\Table(name="term_taxonomy")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Neobazaar\Entity\Repository\TermTaxonomyRepository")
 */
class TermTaxonomy
{
    /**
     * @var integer
     *
     * @ORM\Column(name="term_taxonomy_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $termTaxonomyId;

    /**
     * @var string
     *
     * @ORM\Column(name="taxonomy", type="string", length=30, nullable=false)
     */
    private $taxonomy;

    /**
     * @var string
     *
     * @ORM\Column(name="term_id", type="integer", length=11, nullable=false)
     */
    private $termId;

    /**
     * @var string
     *
     * @ORM\Column(name="parent", type="integer", length=11, nullable=false)
     */
    private $parentId;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=99, nullable=false)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer", nullable=false)
     */
    private $count;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Neobazaar\Entity\Document", mappedBy="termTaxonomy")
     */
    private $document;

    /**
     * @var \Neobazaar\Entity\TermTaxonomy
     *
     * @ORM\ManyToOne(targetEntity="Neobazaar\Entity\TermTaxonomy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent", referencedColumnName="term_taxonomy_id")
     * })
     */
    private $parent;

    /**
     * @var \Neobazaar\Entity\Term
     *
     * @ORM\ManyToOne(targetEntity="Neobazaar\Entity\Term", inversedBy="termTaxonomy", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="term_id", referencedColumnName="term_id")
     * })
     */
    private $term;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->document = new \Doctrine\Common\Collections\ArrayCollection();
    }
    

    /**
     * Get termTaxonomyId
     *
     * @return integer 
     */
    public function getTermTaxonomyId()
    {
        return $this->termTaxonomyId;
    }
    
    /**
     * Get termId
     *
     * @return integer 
     */
    public function getTermId()
    {
        return $this->termId;
    }
    
    /**
     * Get parentId
     *
     * @return integer 
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set taxonomy
     *
     * @param string $taxonomy
     * @return TermTaxonomy
     */
    public function setTaxonomy($taxonomy)
    {
        $this->taxonomy = $taxonomy;
    
        return $this;
    }

    /**
     * Get taxonomy
     *
     * @return string 
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return TermTaxonomy
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return TermTaxonomy
     */
    public function setCount($count)
    {
        $this->count = $count;
    
        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Add document
     *
     * @param \Neobazaar\Entity\Document $document
     * @return TermTaxonomy
     */
    public function addDocument(\Neobazaar\Entity\Document $document)
    {
        $this->document[] = $document;
    
        return $this;
    }

    /**
     * Remove document
     *
     * @param \Neobazaar\Entity\Document $document
     */
    public function removeDocument(\Neobazaar\Entity\Document $document)
    {
        $this->document->removeElement($document);
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

    /**
     * Set parent
     *
     * @param \Neobazaar\Entity\TermTaxonomy $parent
     * @return TermTaxonomy
     */
    public function setParent(\Neobazaar\Entity\TermTaxonomy $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \Neobazaar\Entity\TermTaxonomy 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set term
     *
     * @param \Neobazaar\Entity\Term $term
     * @return TermTaxonomy
     */
    public function setTerm(\Neobazaar\Entity\Term $term = null)
    {
        $this->term = $term;
    
        return $this;
    }

    /**
     * Get term
     *
     * @return \Neobazaar\Entity\Term 
     */
    public function getTerm()
    {
        return $this->term;
    }
    
    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
    	
    }
}