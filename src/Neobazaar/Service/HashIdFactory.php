<?php 
namespace Neobazaar\Service;

use Zend\ServiceManager\ServiceLocatorInterface,
	Zend\ServiceManager\FactoryInterface;

use Neobazaar\Options\ModuleOptions;

use Hashids\Hashids;

class HashIdFactory
	implements FactoryInterface
{
    /**
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \Options\ModuleOptions
     */
    public function createService(ServiceLocatorInterface $sl)
    {
	    $config = $sl->get('Neobazaar\Options\ModuleOptions');
	    $hashids = new Hashids($config->getHashSalt(), $config->getHashLength());
	    
	    return $hashids;
    }
}