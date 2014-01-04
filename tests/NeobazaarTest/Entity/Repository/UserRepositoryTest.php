<?php
 
namespace NeobazaarTest\Entity\Repository;
 
use PHPUnit_Framework_TestCase;
use Neobazaar\Entity\Repository\UserRepository;
use NeobazaarTest\Bootstrap;
use NeobazaarTest\Fixture\UserSample;

class UserRepositoryTest 
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
        $this->repository = $sm->get('Neobazaar\Entity\Repository\UserRepository');
        $this->fixtureExectutor = $sm->get('Doctrine\Common\DataFixtures\Executor\AbstractExecutor');
        $this->assertInstanceOf('Neobazaar\Entity\Repository\UserRepository', $this->repository);
    }

    /**
     * @cover Neobazaar\Entity\Repository\DocumentRepository::get()
     */
    public function testGet()
    {
        $userSample = new UserSample();
        $this->fixtureExectutor->execute(array($userSample));
        
        $user = $this->repository->find(1);
        
        $this->assertEquals('John', $user->getName());
    }
}
 