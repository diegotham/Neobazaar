<?php
 
namespace NeobazaarTest\Service;
 
use PHPUnit_Framework_TestCase;
use Neobazaar\Service\MainModuleService;
use NeobazaarTest\Bootstrap;

class MainModuleServiceTest 
    extends PHPUnit_Framework_TestCase
{
    protected $obj;

    /**
     * @var DocumentRepository
     */
    protected $repository;

    public function setUp()
    {
        $sm = Bootstrap::getServiceManager();
        $this->obj = new MainModuleService();
        $em = $sm->get('Doctrine\ORM\EntityManager');
        $this->obj->setEntityManager($em);
        $this->obj->setServiceManager($sm);
    }

    /**
     * @cover Neobazaar\Service\MainModuleService::setServiceManager()
     * @cover Neobazaar\Service\MainModuleService::getServiceManager()
     */
    public function testSetGetServiceManager()
    {
        $class = get_class($this->obj->getServiceManager());
        $this->assertEquals('Zend\ServiceManager\ServiceManager', $class);
    }

    /**
     * @cover Neobazaar\Service\MainModuleService::setGetEntityManager()
     * @cover Neobazaar\Service\MainModuleService::getGetEntityManager()
     */
    public function testSetGetEntityManager()
    {
        $this->assertEquals('Doctrine\ORM\EntityManager', get_class($this->obj->getEntityManager()));
    }
    
    /**
     * @cover Neobazaar\Service\MainModuleService::getDocumentEntityRepository()
     */
    public function testGetDocumentEntityRepository() 
    {
        $sm = Bootstrap::getServiceManager();
        $sphinxMock = $this->getMock('Sphinx\Client');
        $sm->setService('sphinxsearch.client.default', $sphinxMock);
        $repository = $this->obj->getDocumentEntityRepository();
        $this->assertInstanceOf('Neobazaar\Entity\Repository\DocumentRepository', $repository);
        // Second call early return previous instance
        $repository = $this->obj->getDocumentEntityRepository();
        $this->assertInstanceOf('Neobazaar\Entity\Repository\DocumentRepository', $repository);
    }
    
    /**
     * @cover Neobazaar\Service\MainModuleService::getTermTaxonomyEntityRepository()
     */
    public function testGetTermTaxonomyEntityRepository() 
    {
        $repository = $this->obj->getTermTaxonomyEntityRepository();
        $this->assertInstanceOf('Neobazaar\Entity\Repository\TermTaxonomyRepository', $repository);
        // Second call early return previous instance
        $repository = $this->obj->getTermTaxonomyEntityRepository();
        $this->assertInstanceOf('Neobazaar\Entity\Repository\TermTaxonomyRepository', $repository);
    }
    
    /**
     * @cover Neobazaar\Service\MainModuleService::getGeonamesEntityRepository()
     */
    public function testGetGeonamesEntityRepository() 
    {
        $repository = $this->obj->getGeonamesEntityRepository();
        $this->assertInstanceOf('Neobazaar\Entity\Repository\GeonamesRepository', $repository);
        // Second call early return previous instance
        $repository = $this->obj->getGeonamesEntityRepository();
        $this->assertInstanceOf('Neobazaar\Entity\Repository\GeonamesRepository', $repository);
    }
    
    /**
     * @cover Neobazaar\Service\MainModuleService::getUserEntityRepository()
     */
    public function testGetUserEntityRepository() 
    {
        $repository = $this->obj->getUserEntityRepository();
        $this->assertInstanceOf('Neobazaar\Entity\Repository\UserRepository', $repository);
        // Second call early return previous instance
        $repository = $this->obj->getUserEntityRepository();
        $this->assertInstanceOf('Neobazaar\Entity\Repository\UserRepository', $repository);
    }
}
 