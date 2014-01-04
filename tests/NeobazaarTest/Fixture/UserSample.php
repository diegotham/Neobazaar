<?php
namespace NeobazaarTest\Fixture;
 
use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\Persistence\ObjectManager;
 
use Neobazaar\Entity\User;

class USerSample 
    extends AbstractFixture
{
    protected $user;
    
    public function load(ObjectManager $manager)
    {
        $this->user = new User();
        $this->user->setState(User::USER_STATE_ACTIVE);
        $this->user->setRole('guest');
        $this->user->setName('John');
        $manager->persist($this->user);
        $manager->flush();
    }
    
    public function getUser()
    {
        return $this->user;
    }
}