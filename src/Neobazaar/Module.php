<?php
namespace Neobazaar;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface,
	Zend\ModuleManager\Feature\ControllerProviderInterface,
	Zend\ModuleManager\Feature\ServiceProviderInterface,
	Zend\ModuleManager\Feature\ConfigProviderInterface,
	Zend\ModuleManager\Feature\BootstrapListenerInterface,
	Zend\EventManager\EventInterface,
	Zend\Mvc\MvcEvent,
	Zend\Mvc\Router\RouteMatch;

class Module 
	implements 
		AutoloaderProviderInterface,
		BootstrapListenerInterface,
		ControllerProviderInterface,
		ServiceProviderInterface,
		ConfigProviderInterface
{
    public function onBootstrap(EventInterface $e)
    {
        $app = $e->getApplication();
        $sm  = $app->getServiceManager();
        
        // @todo make this dinamic depending on the domain or user preferences
        $translator = $sm->get('translator');
		$translator->addTranslationFile(
			'phpArray',
			'./vendor/zendframework/zendframework/resources/languages/it/Zend_Validate.php',
			'form'
		);
		$translator->addTranslationFile(
			'phpArray',
			'./data/languages/it/Zend_Validate.php',
			'form'
		);
        \Zend\Validator\AbstractValidator::setDefaultTranslator($translator, 'form');
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getControllerConfig()
    {
        return include __DIR__ . '/config/controllers.config.php';
    }

    public function getServiceConfig()
    {
        return include __DIR__ . '/config/services.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
	        'invokables' => array(
	            'formDropdown' => 'Neobazaar\Form\View\Helper\FormDropdown'
	        )
        );
    }
}
