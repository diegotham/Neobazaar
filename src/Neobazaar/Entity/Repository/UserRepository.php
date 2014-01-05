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

use Neobazaar\Entity\User as UserEntity,
	Neobazaar\Doctrine\ORM\EntityRepository;

/**
 * @author Sergio
 */
class UserRepository 
    extends EntityRepository
{
	const CURRENT_KEYWORD = 'current';
	
	/**
	 * Return a normalized representation of a document
	 * 
	 * @param int|\Neobazaar\Entity\Document $id
	 * @return \stdClass
	 */
	public function get($user, ServiceManager $sm) 
	{
		if(!$user instanceof UserEntity) {
			if(is_numeric($user)) {
				$user = $this->find($user);
			} else {
				if(self::CURRENT_KEYWORD == $user) {
					$zfcUserAuth = $sm->get('ControllerPluginManager')->get('zfcUserAuthentication');
					$user = $zfcUserAuth->hasIdentity() ? $zfcUserAuth->getIdentity() : null;
				} else {
					$user = $this->findByEncryptedId($user, 'userId');
				}
			}
		}
		
		if(!$user instanceof UserEntity) {
			return null;
		}
	
		$userModel = $sm->get('user.model.user');
		$userModel->setServiceManager($sm);
		$userModel->setUserEntity($user);
		$userModel->init();
		
		return $userModel;
	}
	
	/**
	 * User list
	 * 
	 * @param unknown $params
	 * @param ServiceLocatorAwareInterface $sm
	 * @throws \Exception
	 * @return array
	 */
	public function getList($params = array(), ServiceManager $sm) 
	{
		$page = isset($params['page']) ? (int)$params['page'] : 1;
		unset($params['page']);
		
		$params['limit'] = isset($params['limit']) ? (int) $params['limit'] : 30;
		// The eventual presence of 'offset' key has priority over calculated 'page' value
		$params['offset'] = isset($params['offset']) ? (int) $params['offset'] : ($page * $params['limit'] - $params['limit']);
		
		$routeName = 'UserUser/page';
		$routeParams = array('page' => $page);
		
		$paginator = $this->getPaginator($params);
		
		$data = array();
		foreach($paginator as $user) {
			$data[] = $this->get($user, $sm);
		}
		
		unset($params['limit']);
		unset($params['offset']);
		$queryParams = $params;

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
	 * Return the paginator
	 * 
	 * @param array $params
	 * @return \Zend\Paginator\Paginator
	 */
	public function getPaginator($params = array()) 
	{
		$page = isset($params['page']) ? (int)$params['page'] : 1;
		$email = isset($params['email']) ? $params['email'] : null;
		$limit = isset($params['limit']) ? (int) $params['limit'] : 10;
		$offset = isset($params['offset']) ? (int) $params['offset'] : 0;
		
		
		$qb = $this->_em->createQueryBuilder();
		$qb->select(array('a'));
		$qb->from($this->getEntityName(), 'a');
		$qb->addOrderBy('a.dateInsert', 'DESC');
		
		if(null !== $email) {
			$qb->andWhere($qb->expr()->eq('a.email', ':paramEmail'));
			$qb->setParameter('paramEmail', $email);
		}
		
		$query = $qb->getQuery();
		$query->setFirstResult($offset);
		$query->setMaxResults($limit);
// 		$paginator = new DoctrinePaginator($query, $fetchJoinCollection = true);
// 		$result = array();
// 		foreach($paginator as $p) {
// 			$result[] = $p->getUserId();
// 		}
// 		$result = $query->getResult();
// 		$paginatorAdapter =  new RazorPaginator(
// 			$result,
// 			count($paginator)
// 		);
		$paginatorAdapter = new DoctrinePaginatorAdapter(new DoctrinePaginator($query));
		$paginator = new Paginator($paginatorAdapter);
		$paginator->setCurrentPageNumber($page);
		$paginator->setDefaultItemCountPerPage($limit);
		
		return $paginator;
	}
}