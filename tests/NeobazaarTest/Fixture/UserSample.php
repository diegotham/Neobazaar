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
        $this->user->setName('John');
        $this->user->setRole('guest');
        $this->user->setLocale('it_IT');
        $manager->persist($this->user);
        $manager->flush();
    }
    
    public function getUser()
    {
        return $this->user;
    }
}