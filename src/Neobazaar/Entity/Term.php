<?php

namespace Neobazaar\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\Collection,
	Doctrine\Common\Collections\ArrayCollection,
	Doctrine\Common\Util\Debug as DDebug;

/**
 * Term
 *
 * @ORM\Table(name="term")
 * @ORM\Entity
 */
class Term
{
    /**
     * @var integer
     *
     * @ORM\Column(name="term_id", type="integer", length=11, nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $termId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=200, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=200, nullable=false)
     */
    private $slug;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Neobazaar\Entity\TermTaxonomy", mappedBy="term")
     */
    private $termTaxonomy;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->termTaxonomy = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Term
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
     * Set slug
     *
     * @param string $slug
     * @return Term
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

//     /**
//      * Set term
//      *
//      * @param \Neobazaar\Entity\TermTaxonomy $term
//      * @return Term
//      */
//     public function setTermTaxonomy(\Neobazaar\Entity\TermTaxonomy $term)
//     {
//         $this->termTaxonomy = $term;
    
//         return $this;
//     }

    /**
     * Get term
     *
     * @return \Neobazaar\Entity\TermTaxonomy 
     */
    public function getTermTaxonomy()
    {
        return $this->termTaxonomy;
    }
    
    public function addTermTaxonomy(Collection $termTaxonomies)
    {
    	foreach ($termTaxonomies as $termTaxonomy) {
    		$termTaxonomy->setTerm($this);
    		$this->termTaxonomy->add($termTaxonomy);
    	}
    }
    
    public function removeTermTaxonomy(Collection $termTaxonomies)
    {
    	foreach ($termTaxonomies as $termTaxonomy) {
    		$termTaxonomy->setTerm(null);
    		$this->termTaxonomy->removeElement($termTaxonomy);
    	}
    }
}