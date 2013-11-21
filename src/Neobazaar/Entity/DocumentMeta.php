<?php

namespace Neobazaar\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DocumentMeta
 *
 * @ORM\Table(name="document_meta")
 * @ORM\Entity
 */
class DocumentMeta
    //extends MappedSuperclassBase
{
    /**
     * @var integer
     *
     * @ORM\Column(name="document_meta_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $documentMetaId;

    /**
     * @var string
     *
     * @ORM\Column(name="`key`", type="string", length=30, nullable=false)
     */
    protected $key;

    /**
     * @var string
     *
     * @ORM\Column(name="`value`", type="text", nullable=false)
     */
    protected $value;

    /**
     * @var \Neobazaar\Entity\Document
     *
     * @ORM\ManyToOne(targetEntity="Neobazaar\Entity\Document", inversedBy="metadata")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="document_id", referencedColumnName="document_id")
     * })
     */
    protected $document;

    /**
     * Get documentMetaId
     *
     * @return integer 
     */
    public function getDocumentMetaId()
    {
        return $this->documentMetaId;
    }

    /**
     * Set key
     *
     * @param string $key
     * @return DocumentMeta
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
     * @return DocumentMeta
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
     * Set document
     *
     * @param \Neobazaar\Entity\Document $document
     * @return DocumentMeta
     */
    public function setDocument(\Neobazaar\Entity\Document $document = null)
    {
        $this->document = $document;
    
        return $this;
    }

    /**
     * Get document
     *
     * @return \Neobazaar\Entity\Document 
     */
    public function getDocument()
    {
        return $this->document;
    }
}