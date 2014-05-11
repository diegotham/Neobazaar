<?php 
namespace Neobazaar\Entity\Repository;

use Zend\ServiceManager\ServiceManager,
	Zend\Debug\Debug,
	Zend\Paginator\Paginator,
	Zend\Json\Json;

use Doctrine\ORM\Mapping as ORM, 
	Doctrine\Common\Util\Debug as DDebug,
	Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrinePaginatorAdapter;

use Neobazaar\Entity\Document,
	Neobazaar\Doctrine\ORM\EntityRepository;

use Document\Model\Image as ImageModel;

use User\Model\User as UserModel;

use Razor\Paginator\Adapter\ArrayAdapter as RazorArrayAdapter;

/**
 * This is used as a model in the rest classifieds controller
 * @author Sergio
 */
class DocumentRepository 
    extends EntityRepository
{
	/**
	 * Create paginator Object
	 * 
	 * @return Zend\Paginator\Paginator
	 */
	public function getPaginator($params) 
	{
        $limit = isset($params['limit']) ? (int) $params['limit'] : 10;
        $offset = isset($params['offset']) ? (int) $params['offset'] : 0;
        
        // @todo (re)setting limit and offset, make it better...
        $params['limit'] = $limit;
        $params['offset'] = $offset;
		
		// must check for limit
		//$page = isset($params['page']) ? (int)$params['page'] : 1;
		
		$fields = isset($params['field']) ? array_values($params['field']) : array();
		$values = isset($params['value']) ? array_values($params['value']) : array();
		
		$page = $params['offset'] / $params['limit'] + 1;
		$isLoggedUserSearch = false;
		$isFullSearch = false;
		$userId = '';
		foreach($fields as $key => $field) {
			switch($field) {
				case 'account':
					$isLoggedUserSearch = true;
					$userId = $values[$key];
					break;
			}
		}
		if($isLoggedUserSearch) {
			    //throw new \Exception('WIP hashid');
			$qb = $this->_em->createQueryBuilder();
			$qb->select(array('a'));
			$qb->from($this->getEntityName(), 'a');
			//$qb->andWhere($qb->expr()->eq('a.user', ':paramUserId'));
			$qb->andWhere($qb->expr()->eq('SHA1(CONCAT_WS(\'\', \'neo\', a.user, \'bazaar\'))', ':paramUserId'));
			$qb->andWhere($qb->expr()->eq('a.documentType', ':paramDocumentType'));
			$qb->andWhere($qb->expr()->neq('a.state', ':paramDocumentState'));
			$qb->setParameter('paramUserId', $userId);
			$qb->setParameter('paramDocumentType', Document::DOCUMENT_TYPE_CLASSIFIED);
			$qb->setParameter('paramDocumentState', Document::DOCUMENT_STATE_DELETED);
			//$qb->addOrderBy('COALESCE(a.dateEdit, a.dateInsert)', 'DESC');
			//$qb->addOrderBy('a.documentId', 'DESC');
			$qb->addOrderBy('a.dateEdit', 'DESC');
			$query = $qb->getQuery();
			$query->setFirstResult($params['offset']);
			$query->setMaxResults($params['limit']);
			$paginatorAdapter = new DoctrinePaginatorAdapter(new DoctrinePaginator($query));
			
			
// 			$paginator = new DoctrinePaginator($query, $fetchJoinCollection = true);
// 			$result = array();
// 			foreach($paginator as $p) {
// 				$result[] = $p->getDocumentId();
// 			}
// 			$result = $query->getResult();
// 			$paginatorAdapter =  new RazorPaginator(
// 				$result,
// 				count($paginator)
// 			);
		} else {
			$query = $this->fetchGridData($params);
			$query->setFirstResult(0);
			$query->setMaxResults($params['limit']);
			$paginatorAdapter =  new RazorArrayAdapter(
				$query->getResult(),
				$this->getSphinxClient()->getLastSearchCount()
			);
			//$paginatorAdapter = new DoctrinePaginatorAdapter(new DoctrinePaginator($query));
		}
		
		
		$paginator = new Paginator($paginatorAdapter);
		$paginator->setCurrentPageNumber($page);
		$paginator->setDefaultItemCountPerPage($params['limit']);
		
		return $paginator;
	}
	
	/**
	 * This return a list of $limit documents
	 * that are expired and elegible to mail send, 
	 * (only if the email was not already sent after they expired)
	 * 
	 * logic behind: only active and expired will be extracted
	 * if the email is sent, the classified will be set as deactive 
	 * so it will not extracted again
	 * 
	 * @param int $limit
	 * @return Doctrine\ORM\Tools\Pagination\Paginator
	 */
	public function getExpiredElegibleToMailSend($limit = 10) 
	{
		$today = new \Datetime();
		$interval = new \DateInterval('P6M'); // THIS IS THE DATE LIMIT MUST BE 6 MONTHS P6M
		$datetimeLimit = $today->sub($interval);
		
		$qb = $this->_em->createQueryBuilder();
		$qb->select(array('a'));
		$qb->from($this->getEntityName(), 'a');
		//$qb->andWhere($qb->expr()->eq('a.user', ':paramUser')); // remove remove remove remove remove remove remove remove remove remove remove
		$qb->andWhere($qb->expr()->eq('a.documentType', ':paramDocumentType'));
		$qb->andWhere($qb->expr()->eq('a.state', ':paramDocumentState'));
		$qb->andWhere($qb->expr()->lte('a.dateEdit', ':paramDateEdit'));
		$qb->setParameter('paramDocumentType', Document::DOCUMENT_TYPE_CLASSIFIED);
		$qb->setParameter('paramDocumentState', Document::DOCUMENT_STATE_ACTIVE);
		$qb->setParameter('paramDateEdit', $datetimeLimit);
		//$qb->setParameter('paramUser', 9); // remove remove remove remove remove remove remove remove remove remove remove remove remove 
		$qb->addOrderBy('a.dateEdit', 'ASC');
		$qb->setFirstResult(0);
		$qb->setMaxResults($limit);
		
		$query = $qb->getQuery();
		$paginator = new DoctrinePaginator($query, $fetchJoinCollection = true);
		
		return $paginator;
	}
	
	/**
	 * This return a list of $limit documents
	 * that needs to have activation email to be resent.
	 * The logic is:
	 * 
	 * state = 2
	 * date edit and date insert are equal
	 * 
	 * After the email is sent, date edit is incremented by 1 sec 
	 * so in the future we know wich document where touched by this method.
	 * 
	 * @param int $limit
	 * @return Doctrine\ORM\Tools\Pagination\Paginator
	 */
	public function getClassifiedsThatNeedActivationEmailToBeResent($limit = 10) 
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select(array('a'));
		$qb->from($this->getEntityName(), 'a');
		$qb->andWhere($qb->expr()->eq('a.dateEdit', 'a.dateInsert'));
		$qb->andWhere($qb->expr()->eq('a.documentType', ':paramDocumentType'));
		$qb->andWhere($qb->expr()->eq('a.state', ':paramDocumentState'));
		$qb->setParameter('paramDocumentType', Document::DOCUMENT_TYPE_CLASSIFIED);
		$qb->setParameter('paramDocumentState', Document::DOCUMENT_STATE_DEACTIVE);
		
		$qb->addOrderBy('a.dateEdit', 'ASC');
		$qb->setFirstResult(0);
		$qb->setMaxResults($limit);
		
		$query = $qb->getQuery();
		$paginator = new DoctrinePaginator($query, $fetchJoinCollection = true);
		
		return $paginator;
	}
	
	/**
	 * Return a normalized representation of a set of document
	 * 
	 * @todo do not pass controller, create an utils service
	 * @param array $params
	 * @param object $controller
	 * @return array
	 */
	public function getList($params = array(), ServiceManager $sm) 
	{
		//var_dump($params); exit();
		$paginator = $this->getPaginator($params);
		
		$data = array();
		foreach($paginator as $post) {
			$data[] = $this->get($post, $sm); // verificare che cazzo fa sto get!!!!!
		}

		// Denormalizzazione parametri per creazione url da route
		$queryParams = array();
		$routeParams = array();
		$fields = isset($params['field']) ? array_values($params['field']) : array();
		$values = isset($params['value']) ? array_values($params['value']) : array();
		foreach($fields as $key => $field) {
			if(in_array($field, array('location', 'purpose', 'category'))) {
				$routeParams[$field] = $values[$key];
				continue;
			}
			$queryParams[$field] = $values[$key];
		}
		
		// remove user key from params if exists and if length 40 
		// (sha1 hash) add current
		$routeName = 'DocumentRegex/type/category/page';
		if(array_key_exists('account', $queryParams) && 40 == strlen($queryParams['account'])) {
			unset($queryParams['account']);
			$queryParams['current'] = true;
			$routeName = 'DocumentAccount/page';
		}
		
		return $this->getPaginationData($sm, $paginator, $data, $routeName, $routeParams, $queryParams);
	}
	
	/**
	 * Get a pagination JSON data.
	 * 
	 * @todo Find how to better manage this
	 */
	public function getPaginationData(ServiceManager $sm, $paginator, $data, $routeName, $routeParams, $queryParams) 
	{
		$mainService = $sm->get('neobazaar.service.main');
		return array(
			'data' => $data,
			'paginationData' => Json::decode($mainService->getView()->paginationControl(
				$paginator,
				'Sliding',
				array('pagination/paginatorjson', 'Document'),
				array(
					'route' => $routeName,
					'params' => $routeParams, 
					'query' => $queryParams
				)
			))
		);
	}
	
	/**
	 * Return an IterableResult to create sitemap entries
	 * 
	 * @return \Doctrine\ORM\Internal\Hydration\IterableResult 
	 */
	public function getSitemapClassifiedsIterableResult() 
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select(array('a'));
		$qb->from($this->getEntityName(), 'a');		
		$qb->andWhere($qb->expr()->eq('a.documentType', ':paramDocumentType'));
		$qb->andWhere($qb->expr()->eq('a.state', ':paramDocumentState'));
		$qb->setParameter('paramDocumentType', Document::DOCUMENT_TYPE_CLASSIFIED);
		$qb->setParameter('paramDocumentState', Document::DOCUMENT_STATE_ACTIVE);
		$qb->addOrderBy('a.dateEdit', 'ASC');
		
		return $qb->getQuery()->iterate();
	}
	
	/**
	 * Return a normalized representation of a document
	 * 
	 * @deprecated Use specific get depending on model type, @too create a model generator service that return cached entry if any
	 * @param int|\Neobazaar\Entity\Document $id
	 * @param ServiceManager 
	 * @param boolean wheter or not chec if user is admin, owner ecc
	 * @return \stdClass
	 */
	public function get($document, ServiceManager $sm, $forceFullData = false) 
	{
		if (!$document instanceof \Neobazaar\Entity\Document) {
			if(is_numeric($document)) {
				$document = $this->find($document);
			} else {
				$document = $this->findByEncryptedId($document, 'documentId');
			}
		}
		
		switch ($document->getDocumentType()) {
			case Document::DOCUMENT_TYPE_IMAGE:
// 				$cache = $controller->getServiceLocator()->get('ImageCache');
// 				$key = $this->getEncryptedId($document->getDocumentId());
// 				if($file = $cache->getItem($key)) {
// 					return $file;
// 				}

				$file = new ImageModel($document, $sm);
				//$cache->setItem($key, $file);
				break;
			default:
				$cache = $sm->get('ClassifiedCache');
				$key = $this->getEncryptedId($document->getDocumentId());
				
				// document model
				if(!$file = $cache->getItem($key)) {
				    // sensible data is not present in this model, 
				    // @todo: inject using param?
            		$file = $sm->get('document.model.classifiedPublicListing');
            		$file->init($document, $sm);
				}

				$cache->setItem($key, $file);
				break;
		}
		
		// do not check if user is admin, owner ecc
		if ($forceFullData) {	
			return $file;
		}
		
		// Before return it, some security work.
		// No owner user should not see some data
		// @todo Do this check in another place or create different models depends on the state of the user
		// Do not add such big dependency only for a single call of a single service
		// W A R  N I N G: do this work!!  
// 		$auth = $sm->get('ControllerPluginManager')->get('zfcUserAuthentication');
// 		$identity = $auth->hasIdentity() ? $auth->getIdentity() : null;
// 		$userModel = null !== $identity ? new UserModel($identity, $sm) : null;
// 		if (
// 			null === $identity || 
// 			null === $userModel || 
// 			(!$userModel->isAdmin && 
// 				($identity && $identity->getEmail() != $file->email)) ) {
// 			unset($file->email);
// 			unset($file->username);
// 		}
				
		return $file;
	}
	
	/**
	 * Restituisce una entità da un id numerico o hash
	 * 
	 * @param string|int $idOrDocument
	 * @throws \Exception
	 * @return \Neobazaar\Entity\Document
	 */
    public function getEntity($idOrDocument) 
    {
    	if(!$idOrDocument instanceof Document) {
    		if(is_numeric($idOrDocument)) {
    			$document = $this->find($idOrDocument);
    		} else {
    			$document = $this->findByEncryptedId($idOrDocument, 'documentId');
    		}
    	} else {
    		$document = $idOrDocument;
    	}
		
		if(!$document instanceof Document) {
			throw new \Exception('The document is not a correct instance');
		}
    	
    	return $document;
    }
}