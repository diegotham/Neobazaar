<?php 

namespace Neobazaar\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface,
	Zend\ServiceManager\ServiceManager;

use ZfcBase\EventManager\EventProvider;

class MainModuleService 
    extends EventProvider 
    	implements ServiceManagerAwareInterface
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;
    
    /**
     * @var array
     */
    protected $er = array();
    
    /**
     * Retrieve doctrine 2 entity manager
     * 
     * @return Doctrine\ORM\EntityManager
     */
    public function getEntityManager() 
    {
        return $this->em;
    }
    
    /**
     * Set doctrine 2 entity manager
     * 
     * @return MainModuleService
     */
    public function setEntityManager(\Doctrine\ORM\EntityManager $em) 
    {
        return $this->em = $em;
        return $this;
    }
    
    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param ServiceManager $serviceManager
     * @return MainModuleService
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }
    
    /**
     * Setta la view da utilizzare
     * 
     * @param mixed|\Zend\View\Renderer\PhpRenderer $view
     * @return \Travelmatic\Grid\Grid
     */
    public function setView($view) 
    {
        $this->view = $view;
        
        return $this;
    }
    
    /**
     * Restituisce istanza del servizio view renderer
     * 
     * @return \Zend\View\Renderer\PhpRenderer
     */
    public function getView() 
    {
        return $this->view;
    }
    
    
    // Entities getters
    
    /**
     * Get Neobazaar\Entity\Document entity repository
     */
    public function getDocumentEntityRepository() 
    {
    	if(isset($this->er['Document'])) {
    		return $this->er['Document'];
    	}
    	$sphinxClient = $this->getServiceManager()->get('sphinxsearch.client.default');
    	$entityRepository = $this->getEntityManager()->getRepository('Neobazaar\Entity\Document');
    	$entityRepository->setSphinxClient($sphinxClient);
    	$entityRepository->setSphinxSearch(true);
    	$this->er['Document'] = $entityRepository;
    	
    	return $entityRepository;
    }
    
    /**
     * Get Neobazaar\Entity\TermTaxonomy entity repository
     */
    public function getTermTaxonomyEntityRepository() 
    {
    	if(isset($this->er['TermTaxonomy'])) {
    		return $this->er['TermTaxonomy'];
    	}
    	$this->er['TermTaxonomy'] = $this->getEntityManager()->getRepository('Neobazaar\Entity\TermTaxonomy');
    	
    	return $this->er['TermTaxonomy'];
    }
    
    /**
     * Get Neobazaar\Entity\Geonames entity repository
     */
    public function getGeonamesEntityRepository() 
    {
    	if(isset($this->er['Geonames'])) {
    		return $this->er['Geonames'];
    	}
    	$this->er['Geonames'] = $this->getEntityManager()->getRepository('Neobazaar\Entity\Geonames');
    	
    	return $this->er['Geonames'];
    }
    
    /**
     * Get Neobazaar\Entity\USer entity repository
     */
    public function getUserEntityRepository() 
    {
    	if(isset($this->er['User'])) {
    		return $this->er['User'];
    	}
    	$this->er['User'] = $this->getEntityManager()->getRepository('Neobazaar\Entity\User');
    	
    	return $this->er['User'];
    }
}