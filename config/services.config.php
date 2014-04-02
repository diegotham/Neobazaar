<?php 
namespace Neobazaar;

use Neobazaar\Entity\Event\UserUniqueCheck,
    Neobazaar\Service\Initializer\EntityManagerAwareInitializer;

return array(
	'factories' => array(
		'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
		'neobazaar.doctrine.em' => function (\Zend\ServiceManager\ServiceLocatorInterface $sl) {
			$em = $sl->get('doctrine.entitymanager.orm_default');
			$em->getConfiguration()->addCustomStringFunction("SHA1", "DoctrineExtensions\Query\Mysql\Sha1");
			$em->getConfiguration()->addCustomStringFunction("CONCAT_WS", "DoctrineExtensions\Query\Mysql\ConcatWs");
			$em->getConfiguration()->addCustomStringFunction("FIELD", "DoctrineExtensions\Query\Mysql\Field");
			$em->getConfiguration()->addCustomStringFunction("COALESCE", "Razor\Doctrine\Query\Mysql\Coalesce");

			return $em;
		},
		'neobazaar.service.main' => new Service\NeobazaarMainServiceFactory(),
		'Neobazaar\Options\ModuleOptions' => new Service\ModuleOptionsFactory(),
		'Neobazaar\Service\HashId' => new Service\HashIdFactory()
	),
	'initializers' => array(
	    'Neobazaar\Service\Initializer\EntityManagerAwareInitializer' => new EntityManagerAwareInitializer()
	),
	'aliases' => array(
		'neobazaar.service.hashid' => 'Neobazaar\Service\HashId'
	),
);