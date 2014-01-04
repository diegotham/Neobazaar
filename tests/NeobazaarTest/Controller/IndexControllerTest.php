<?php
namespace NeobazaarTest\Controller;

use NeobazaarTest\Bootstrap;
use Neobazaar\Controller\IndexController;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter,
    Zend\Http\Request,
    Zend\Http\Response,
    Zend\Mvc\MvcEvent,
    Zend\Mvc\Router\RouteMatch,
	Zend\View\Renderer\PhpRenderer,
	Zend\View\Resolver,
	Zend\Navigation\Service\DefaultNavigationFactory;
use PHPUnit_Framework_TestCase;

class IndexControllerTest 
    extends \PHPUnit_Framework_TestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new IndexController();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'index'));
        $this->event      = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);
        
//         $application = $serviceManager->get('Application');
//         $application->bootstrap();
//         $this->setUpPhpRenderer();
//         $this->setUpNavigation();

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
    }
    
    /**
     * @return void
     */
    protected function setUpNavigation()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $n = new DefaultNavigationFactory();
        $serviceManager->setService('navigation', $n->createService($serviceManager));
    }
    
    /**
     * @return void
     */
    protected function setUpPhpRenderer()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $modulePath = dirname(getcwd());
        $renderer = new PhpRenderer();
        $resolver = new Resolver\AggregateResolver();
        $renderer->setResolver($resolver);
        $map = new Resolver\TemplateMapResolver(array(
            'layout'      => $modulePath . '/view/layout.phtml',
            'index/index' => $modulePath . '/view/index/index.phtml',
        ));
        $stack = new Resolver\TemplatePathStack(array(
            'script_paths' => array(
                $modulePath . '/view'
            )
        ));
        $resolver->attach($map)    // this will be consulted first
        ->attach($stack);
        
        $renderer->getHelperPluginManager()->setFactory('url', function () {
            return new \ClassifiedsTest\View\Helper\Url();
        });
        
        try {
            $serviceManager->setService('Zend\View\Renderer\PhpRenderer', $renderer);
        } catch(\Exception $e) {}
    }
    
    /**
     * @cover Neobazaar\Controller\Repository\IndexController::getEntityManager()
     */
    public function testGetEntityManager() 
    {
        $em = $this->controller->getEntityManager();
        
        $this->assertTrue($em instanceof \Doctrine\ORM\EntityManager);
    }
    
    /**
     * @cover Neobazaar\Controller\Repository\IndexController::indexAction()
     */
    public function testIndexActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'index');
    
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
    
        $this->assertEquals(200, $response->getStatusCode());
    }
    
//     /**
//      * @cover Neobazaar\Controller\Repository\IndexController::breadcrumbsAction()
//      */
//     public function testBreadcrumbsAction()
//     {
//         $this->routeMatch->setParam('action', 'breadcrumbs');
    
//         $result   = $this->controller->dispatch($this->request);
//         $response = $this->controller->getResponse();
        
//         $this->assertTrue($response instanceof \Zend\View\Model\ViewModel);
    
//         $this->assertEquals(200, $response->getStatusCode());
//     }
}