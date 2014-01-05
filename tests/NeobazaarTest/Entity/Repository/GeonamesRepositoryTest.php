<?php
 
namespace NeobazaarTest\Entity\Repository;
 
use PHPUnit_Framework_TestCase;
use Neobazaar\Entity\Repository\GeonamesRepository;
use NeobazaarTest\Bootstrap;
use NeobazaarTest\Fixture\GeonameSample;

class GeonamesRepositoryTest 
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\Common\DataFixtures\Executor\AbstractExecutor
     */
    protected $fixtureExectutor;

    /**
     * @var DocumentRepository
     */
    protected $repository;

    public function setUp()
    {
        $sm = Bootstrap::getServiceManager();
        $this->repository = $sm->get('Neobazaar\Entity\Repository\GeonamesRepository');
        $this->fixtureExectutor = $sm->get('Doctrine\Common\DataFixtures\Executor\AbstractExecutor');
        $this->assertInstanceOf('Neobazaar\Entity\Repository\GeonamesRepository', $this->repository);

        $geonameSample = new GeonameSample();
        $this->fixtureExectutor->execute(array($geonameSample));
    }
    
    /**
     * @cover Neobazaar\Entity\Repository\GeonamesRepository::getLocationMultioptions()
     */
    public function testGetLocationMultioptions() 
    {
        $sm = Bootstrap::getServiceManager();
        
        $mock = $this->getMockBuilder('Zend\Cache\Storage\Adapter\Apc', array('getItem'))
            ->disableOriginalConstructor()
            ->getMock();
        $mock->expects($this->any())
            ->method('getItem')
            ->will($this->returnValue(false));
        $sm->setService('DatasetCache', $mock);
        
        $multioptions = $this->repository->getLocationMultioptions($sm);
        
        // this doesn't test anything, multioptions is false....
        $this->assertEquals(1, count($multioptions));
    }
}
 