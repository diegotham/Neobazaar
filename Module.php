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
        $em  = $app->getEventManager();
        $sm  = $app->getServiceManager();
        $module = $this;
        
        $em->attach(MvcEvent::EVENT_ROUTE, function($e) use ($em, $module) {
            $match = $e->getRouteMatch();
            $route = $match->getMatchedRouteName();
            
            // @todo insert login route heres
            if(!in_array($route, array())) {
                //$module->initView($e);
            }
        });
        
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
    
    public function initView(EventInterface $e) 
    {
        $app = $e->getApplication();
        $em  = $app->getEventManager();
        $sm  = $app->getServiceManager();
        $view = $sm->get('Zend\View\Renderer\PhpRenderer');
        
        $view->headScript()->appendScript("function ready() {};");
        

//         <!-- Google Analytics: change UA-XXXXX-X to be your site's ID -->
//      <script>
//        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
//        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
//        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
//        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        
// 		ga('create', 'UA-XXXXX-X');
//         ga('send', 'pageview');
//         </script>
      
        $javascripts = array(
			"bower_components/jquery/jquery.js",
			"bower_components/angular/angular.min.js",
			"bower_components/bootstrap-sass/js/bootstrap-affix.js",
			"bower_components/bootstrap-sass/js/bootstrap-alert.js",
			"bower_components/bootstrap-sass/js/bootstrap-dropdown.js",
			"bower_components/bootstrap-sass/js/bootstrap-tooltip.js",
			"bower_components/bootstrap-sass/js/bootstrap-modal.js",
			"bower_components/bootstrap-sass/js/bootstrap-transition.js",
			"bower_components/bootstrap-sass/js/bootstrap-button.js",
			"bower_components/bootstrap-sass/js/bootstrap-popover.js",
			"bower_components/bootstrap-sass/js/bootstrap-typeahead.js",
			"bower_components/bootstrap-sass/js/bootstrap-carousel.js",
			"bower_components/bootstrap-sass/js/bootstrap-scrollspy.js",
			"bower_components/bootstrap-sass/js/bootstrap-collapse.js",
			"bower_components/bootstrap-sass/js/bootstrap-tab.js",
			"bower_components/angular-resource/angular-resource.min.js",
			"bower_components/angular-sanitize/angular-sanitize.min.js",
			"scripts/app.js",

			"scripts/directives/searchFormDirective.js",
			"scripts/directives/searchSubFormDirective.js",
			"scripts/directives/breadcrumbsDirective.js",
			"scripts/directives/headerDirective.js",

			"scripts/services/classifiedService.js",
			"scripts/services/classifiedLoaderService.js",
			"scripts/services/classifiedsLoaderService.js",
			"scripts/services/formService.js",
			"scripts/services/formLoaderService.js",

			"scripts/controllers/breadcrumbsController.js",
			"scripts/controllers/detailController.js",
			"scripts/controllers/indexController.js",
			"scripts/controllers/serpController.js",
			"scripts/controllers/searchFormController.js",
			"scripts/controllers/searchSubFormController.js",
			"scripts/controllers/headerController.js",
        );
        
        foreach($javascripts as $js) {
        	$view->inlineScript()->setAllowArbitraryAttributes(true)->appendFile("/app/" . $js);
		}
        
        //$view->inlineScript()->setFile('http://maps.googleapis.com/maps/api/js?sensor=true&libraries=geometry&language=it&callback=ready');
        //$view->inlineScript()->setAllowArbitraryAttributes(true)->appendFile('/app/scripts/vendor/require.min.js', 'text/javascript', array('data-main' => '/app/scripts/main'));

        $view->headLink()->appendStylesheet('/app/styles/bootstrap.min.css');
        $view->headLink()->appendStylesheet('/app/styles/docs.css');
        $view->headLink()->appendStylesheet('/app/styles/style.css');
        
        $view->headTitle('Neobazaar')->setSeparator(' - ')->setAutoEscape(false);
        
        $view->headMeta()->appendName('viewport', 'width=device-width, initial-scale=1.0');
        $view->headMeta()->appendName('charset', 'utf8');
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
        
        $view->headLink(array('rel' => 'shortcut icon', 'type' => 'image/vnd.microsoft.icon', 'href' => '/app/favicon.ico'));
    }
}
