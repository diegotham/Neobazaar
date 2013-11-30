<?php

namespace Neobazaar\Controller;

use Zend\Mvc\Controller\AbstractActionController,
	Zend\View\Model\ViewModel,
    Zend\View\Model\JsonModel,
    Zend\Http\Response;

class IndexController 
	extends AbstractActionController
{
	/**            
	 * @var Doctrine\ORM\EntityManager
	 */                
	protected $em;
	 
	public function getEntityManager()
	{
	    if (null === $this->em) {
	        $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
	    }
	    return $this->em;
	}

    public function indexAction()
    {
        return new ViewModel();
    }
    
    /**
     * Web service per la restituzione di un breadcrumbs in base ad un id
     * 
     * @return ViewModel
     */
	public function breadcrumbsAction() 
	{
	    $default = 'dashboard';
	    $id = $this->getRequest()->getQuery('id', $default);
	    $pos = strpos($id, '/');
	    $id = false !== $pos ? substr($id, 0, $pos) : $id;
        $view = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
        $navigation = $view->navigation('navigation');
        $container = $navigation->getContainer();
        $page = $container->findOneBy('fragment', $id);
        $page = null === $page ? $container->findOneBy('fragment', $default) : $page;
        $page->setActive(true);
        
        $viewModel = new ViewModel(array(
            'breadcrumbs' => $navigation->breadcrumbs()
        		->setPartial('breadcrumbs')->setRenderInvisible(true)->setMinDepth(0)
        ));
        $viewModel->setTerminal(true);
        
        return $viewModel;
	}
    
    /**
     * Web service per la restituzione delle categorie
     * 
     * @return JsonModel
     */
	public function categoriesAction() 
	{
		$main = $this->getServiceLocator()->get('neobazaar.service.main');
		$categories = $main->getDocumentEntityRepository()
			->getCategoryMultioptionsNoSlug($this->getServiceLocator());
		
		return new JsonModel(array('data' => $categories));
	}
    
    /**
     * Web service per la restituzione delle locations
     * 
     * @return JsonModel
     */
	public function locationsAction() 
	{
		$main = $this->getServiceLocator()->get('neobazaar.service.main');
		$locations = $main->getGeonamesEntityRepository()
        	->getLocationMultioptions($this->getServiceLocator());
		
		return new JsonModel(array('data' => $locations));
	}
	
	/**
	 * This action will:
	 * - call the expired method that will extract all expired and elegible classifieds
	 * - for each of them, trigger the expired event 
	 * 		( the mailer module will will bind this event with mailsend )
	 */
	public function expiredAction() 
	{
		$classifiedService = $this->getServiceLocator()->get('document.service.classified');
		
		try {
			$ids = $classifiedService->expired(5);
			$data = array(
				'status' => 'success',
				'message' => implode(", ", $ids)
			);
		} catch(\Exception $e) {
			$this->getResponse()
				->setStatusCode(Response::STATUS_CODE_500);
			$data = array(
				'status' => 'danger',
				'message' => $e->getMessage()
			);
		}

//         $viewModel = new ViewModel();
//         $viewModel->setTemplate('neobazaar/index/index');
//         return $viewModel;
		return new JsonModel($data);
	}
}
