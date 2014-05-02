<?php
 
namespace NeobazaarTest\Entity\Repository;
 
use PHPUnit_Framework_TestCase;
use Neobazaar\Entity\Repository\UserRepository;

use NeobazaarTest\Bootstrap,
    NeobazaarTest\Fixture\DocumentSample,
    NeobazaarTest\Fixture\UserSample;

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
        $this->repository->setServiceLocator($sm);
        
        $userSample = new UserSample();
        $this->fixtureExectutor->execute(array($userSample));
    }

    /**
     * @cover Neobazaar\Entity\Repository\DocumentRepository::get()
     */
    public function testGet()
    {
        $sm = Bootstrap::getServiceManager();
        $users = $this->repository->findAll();
        $user = reset($users);
        $userId = $user->getUserId();
        
//         // Mock 'neobazaar.service.hashid' service
//         $mock = $this->getMock('Neobazaar\Service\HashId', array('encrypt'));
//         $mock->expects($this->any())
//         ->method('encrypt')
//         ->will($this->returnValue('xyzabc'));
//         $sm->setService('neobazaar.service.hashid', $mock);
        
        $mock = $this->getMock('Zend\Mvc\Controller\Plugin\PluginInterface', array('setController', 'getController', 'hasIdentity', 'getIdentity'));
        $mock->expects($this->any())
        ->method('setController')
        ->will($this->returnSelf());
        $mock->expects($this->any())
        ->method('getController')
        ->will($this->returnSelf());
        $mock->expects($this->any())
        ->method('hasIdentity')
        ->will($this->returnValue(true));
        $mock->expects($this->any())
        ->method('getIdentity')
        ->will($this->returnValue(new \stdClass()));
        $sm->get('ControllerPluginManager')->setService('zfcUserAuthentication', $mock);
        
        // Mock 'User\Model\User' service
        $mock = $this->getMock('User\Model\User', array('setServiceManager', 'setUserEntity', 'init'));
        $mock->expects($this->any())
            ->method('setServiceManager')
            ->will($this->returnSelf());
        $mock->expects($this->any())
            ->method('setUserEntity')
            ->will($this->returnSelf());
        $mock->expects($this->any())
            ->method('init')
            ->will($this->returnSelf());
        $sm->setService('user.model.user', $mock);
        
        // code is changed, refactory needed

//         // Passing the user param as user entity
//         $userModel = $this->repository->get($user, $sm);
//         // Passing the user param as int (id #1 created by fixture)
//         $userModel2 = $this->repository->get($userId, $sm);
//         // Passing the user param as CURRENT_KEYWORD constant (trying to get currently connected user) 
//         // that is actually nobody, so must return null
//         $userModel3 = $this->repository->get(UserRepository::CURRENT_KEYWORD, $sm);
//         // Passing an hash 
//         $hash = $this->repository->getEncryptedId($userId);
//         $userModel4 = $this->repository->get($hash, $sm);

//         $this->assertEquals($userModel, $userModel2);
//         $this->assertEquals($userModel, $userModel4);
//         $this->assertNull($userModel3);
//         $this->assertEquals(1, count($users));
//         $this->assertEquals('John', $user->getName());
//         $this->assertEquals('guest', $user->getRole());
//         $this->assertEquals('it_IT', $user->getLocale());
//         $this->assertInstanceOf('User\Model\User', $userModel);
    }

    /**
     * @cover Neobazaar\Entity\Repository\DocumentRepository::getPaginator()
     */
    public function testGetPaginator() 
    {
        $paginator = $this->repository->getPaginator();
        
        $this->assertEquals(1, $paginator->count());
    }

    /**
     * @cover Neobazaar\Entity\Repository\DocumentRepository::getList()
     */
    public function testGetList() 
    {
        $sm = Bootstrap::getServiceManager();
        $list = $this->repository->getList(array(), $sm);
        $data = $list['data'];
        $paginationData = $list['paginationData'];
        
        $this->assertEquals(1, count($data));
        $this->assertEquals('In visualizzazione record 1/1 di 1', $paginationData->message);
        $this->assertEquals('disabled', $paginationData->next->class);
        $this->assertEquals('disabled', $paginationData->previous->class);
        $this->assertEquals(1, count($paginationData->pages));
        
        $list2 = $this->repository->getList(array('email' => 'not@exists.whatever'), $sm);
        $this->assertEquals(0, count($list2['data']));
    }
}
 