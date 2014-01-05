<?php
namespace NeobazaarTest; //Change this namespace for your test

use Zend\Loader\AutoloaderFactory;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils,
    Zend\View\Renderer\PhpRenderer,
	Zend\View\Resolver,
	Zend\Navigation\Service\DefaultNavigationFactory;
	
use RuntimeException;

use Neobazaar\Service\MainModuleService;

use User\Model\User as UserModel;

use Doctrine\Common\DataFixtures\Purger\ORMPurger as FixturePurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor as FixtureExecutor;

use Doctrine\ORM\Tools\SchemaTool;

error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

class Bootstrap
{
    protected static $serviceManager;
    protected static $config;
    protected static $bootstrap;

    public static function init()
    {
        // Load the user-defined test configuration file, if it exists; otherwise, load
        if (is_readable(__DIR__ . '/TestConfig.php')) {
            $testConfig = include __DIR__ . '/TestConfig.php';
        } else {
            $testConfig = include __DIR__ . '/TestConfig.php.dist';
        }

        $zf2ModulePaths = array();

        if (isset($testConfig['module_listener_options']['module_paths'])) {
            $modulePaths = $testConfig['module_listener_options']['module_paths'];
            foreach ($modulePaths as $modulePath) {
                if (($path = static::findParentPath($modulePath)) ) {
                    $zf2ModulePaths[] = $path;
                }
            }
        }

        $zf2ModulePaths  = implode(PATH_SEPARATOR, $zf2ModulePaths) . PATH_SEPARATOR;
        $zf2ModulePaths .= getenv('ZF2_MODULES_TEST_PATHS') ?: (defined('ZF2_MODULES_TEST_PATHS') ? ZF2_MODULES_TEST_PATHS : '');

        static::initAutoloader();

        // use ModuleManager to load this module and it's dependencies
        $baseConfig = array(
            'module_listener_options' => array(
                'module_paths' => explode(PATH_SEPARATOR, $zf2ModulePaths),
            ),
        );

        $config = ArrayUtils::merge($baseConfig, $testConfig);

        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();
        
        // @todo move to own factory class/add to merged configuration? Create a test module?
        $serviceManager->setFactory(
            'neobazaar.doctrine.em',
            function(ServiceLocatorInterface $sl)
            {
                $em = $sl->get('doctrine.entitymanager.orm_default');
                $em->getConfiguration()->addCustomStringFunction("SHA1", "DoctrineExtensions\Query\Mysql\Sha1");
                $em->getConfiguration()->addCustomStringFunction("CONCAT_WS", "DoctrineExtensions\Query\Mysql\ConcatWs");
                $em->getConfiguration()->addCustomStringFunction("FIELD", "DoctrineExtensions\Query\Mysql\Field");
                $em->getConfiguration()->addCustomStringFunction("COALESCE", "Razor\Doctrine\Query\Mysql\Coalesce");
            
                return $em;
            }
        );
        
        $serviceManager->setFactory(
            'Doctrine\Common\DataFixtures\Executor\AbstractExecutor',
            function(ServiceLocatorInterface $sl)
            {
                /* @var $em \Doctrine\ORM\EntityManager */
                $em = $sl->get('neobazaar.doctrine.em');
                $schemaTool = new SchemaTool($em);
                $schemaTool->dropDatabase(); // drop previous created tables
                $schemaTool->createSchema($em->getMetadataFactory()->getAllMetadata());
                return new FixtureExecutor($em, new FixturePurger($em));
            }
        );
        
        $serviceManager->setFactory(
            'Neobazaar\Entity\Repository\DocumentRepository',
            function(ServiceLocatorInterface $sl)
            {
                /* @var $em \Doctrine\ORM\EntityManager */
                $em = $sl->get('Doctrine\ORM\EntityManager');
                return $em->getRepository('Neobazaar\Entity\Document');
            }
        );
        
        $serviceManager->setFactory(
            'Neobazaar\Entity\Repository\UserRepository',
            function(ServiceLocatorInterface $sl)
            {
                /* @var $em \Doctrine\ORM\EntityManager */
                $em = $sl->get('Doctrine\ORM\EntityManager');
                return $em->getRepository('Neobazaar\Entity\User');
            }
        );
        
        $serviceManager->setFactory(
            'Neobazaar\Entity\Repository\GeonamesRepository',
            function(ServiceLocatorInterface $sl)
            {
                /* @var $em \Doctrine\ORM\EntityManager */
                $em = $sl->get('Doctrine\ORM\EntityManager');
                return $em->getRepository('Neobazaar\Entity\Geonames');
            }
        );
        
        // This will setup phprenderer service
        static::setUpPhpRenderer($serviceManager);
        
        $serviceManager->setFactory(
            'neobazaar.service.main',
            function(ServiceLocatorInterface $sl)
            {
                $service = new MainModuleService;
                $service->setEntityManager($sl->get('neobazaar.doctrine.em'));
                $service->setView($sl->get('Zend\View\Renderer\PhpRenderer'));
                	
                return $service;
            }
        );
        
        //'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',

        static::$serviceManager = $serviceManager;
        static::$config = $config;
    }
    
    /**
     * This will setup phprenderer using this module partials.
     * It also contains neede helpers like 'url'
     * 
     * @param $serviceManager
     * @return void
     */
    protected static function setUpPhpRenderer($serviceManager)
    {
        $modulePath = dirname(getcwd());
        $renderer = new PhpRenderer();
        $resolver = new Resolver\AggregateResolver();
        $renderer->setResolver($resolver);
        $map = new Resolver\TemplateMapResolver(array(
            'layout'      => $modulePath . '/view/layout.phtml',
            'index/index' => $modulePath . '/view/index/index.phtml',
            'index/index' => $modulePath . '/view/index/index.phtml',
        ));
        $stack = new Resolver\TemplatePathStack(array(
            'script_paths' => array(
                $modulePath . '/view',
                $modulePath . '/tests/data/view',
            )
        ));
        $resolver->attach($map) // this will be consulted first
            ->attach($stack);
    
        // Helper plugins, add here if more needed
        $renderer->getHelperPluginManager()->setFactory('url', function () {
            return new \NeobazaarTest\View\Helper\Url();
        });
        
        $serviceManager->setService('Zend\View\Renderer\PhpRenderer', $renderer);
    }

    public static function getServiceManager()
    {
        return static::$serviceManager;
    }

    public static function getConfig()
    {
        return static::$config;
    }

    protected static function initAutoloader()
    {
        $vendorPath = static::findParentPath('vendor');

        if (is_readable($vendorPath . '/autoload.php')) {
            $loader = include $vendorPath . '/autoload.php';
        } else {
            $zf2Path = getenv('ZF2_PATH') ?: (defined('ZF2_PATH') ? ZF2_PATH : (is_dir($vendorPath . '/ZF2/library') ? $vendorPath . '/ZF2/library' : false));

            if (!$zf2Path) {
                throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.');
            }

            include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';

        }

        AutoloaderFactory::factory(array(
            'Zend\Loader\StandardAutoloader' => array(
                'autoregister_zf' => true,
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
                ),
            ),
        ));
    }

    protected static function findParentPath($path)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) return false;
            $previousDir = $dir;
        }
        return $dir . '/' . $path;
    }
}

Bootstrap::init();