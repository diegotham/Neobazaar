<?php 
namespace Neobazaar\Service;

use Zend\ServiceManager\ServiceLocatorInterface,
	Zend\ServiceManager\FactoryInterface;

use Neobazaar\Options\ModuleOptions;

class ModuleOptionsFactory
	implements FactoryInterface
{
    /**
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \Options\ModuleOptions
     */
    public function createService(ServiceLocatorInterface $sl)
    {
        $config = $sl->get('Config');
        return new ModuleOptions(isset($config['NeobazaarMainModule']) ? $config['NeobazaarMainModule'] : array());
    }
}