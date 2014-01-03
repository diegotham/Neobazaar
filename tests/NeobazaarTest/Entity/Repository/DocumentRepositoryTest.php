<?php
 
namespace NeobazaarTest\Entity\Repository;
 
use PHPUnit_Framework_TestCase;
use Neobazaar\Entity\Repository\DocumentRepository;
use NeobazaarTest\Bootstrap;
use NeobazaarTest\Fixture\DocumentSample;

class DocumentRepositoryTest 
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
        // http://stackoverflow.com/questions/14752930/best-way-to-create-a-test-database-and-load-fixtures-on-symfony-2-webtestcase
        self::runCommand('doctrine:fixtures:load --purge-with-truncate');
        
        $sm = Bootstrap::getServiceManager();
        $this->repository = $sm->get('Neobazaar\Entity\Repository\DocumentRepository');
        $this->fixtureExectutor = $sm->get('Doctrine\Common\DataFixtures\Executor\AbstractExecutor');
        $this->assertInstanceOf('Neobazaar\Entity\Repository\DocumentRepository', $this->repository);
    }

    /**
     * @cover Neobazaar\Entity\Repository\DocumentRepository::getPaginator()
     */
    public function testGetPaginator()
    {
        $documentSample = new DocumentSample();
        $this->fixtureExectutor->execute(array($documentSample));
        
        $paginator = $this->repository->getPaginator(array(
            'limit' => 10,
            'offset' => 0,
            'returnSelect' => true
        ));

        $this->assertTrue(1 ===$paginator->count());
    }
}
 