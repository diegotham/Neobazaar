<?php
namespace NeobazaarTest\Fixture;
 
use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\Persistence\ObjectManager;

use Neobazaar\Entity\Document,
    Neobazaar\Entity\User;

class DocumentSample 
    extends AbstractFixture
{
    protected $document;
    
    public function load(ObjectManager $manager)
    {
        $this->document = new Document();
        $this->document->setDocumentType(Document::DOCUMENT_TYPE_CLASSIFIED);
        $this->document->setState(Document::DOCUMENT_STATE_ACTIVE);
        $manager->persist($this->document);
        $manager->flush();
    }
    
    public function getDocument()
    {
        return $this->document;
    }
}