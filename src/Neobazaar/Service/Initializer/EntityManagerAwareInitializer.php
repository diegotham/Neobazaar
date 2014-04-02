<?php 
namespace Neobazaar\Service\Initializer;

use Zend\ServiceManager\InitializerInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    Zend\Debug\Debug;

use Neobazaar\Service\EntityManagerAwareInterface;

class EntityManagerAwareInitializer 
    implements InitializerInterface
{
    public function initialize($instance, ServiceLocatorInterface $serviceLocator) 
    {
        if($instance instanceof EntityManagerAwareInterface) {
            $instance->setEntityManager($serviceLocator->get('neobazaar.doctrine.em'));
        }
    }
}