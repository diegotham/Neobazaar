<?php 
namespace Neobazaar\Doctrine\ORM;

use Razor\Doctrine\ORM\EntityRepository as EntityRepositoryAbstract;

use Doctrine\Common\Util\Debug as DDebug;

use Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @ORM\MappedSuperclass
 */
class EntityRepository 
    extends EntityRepositoryAbstract
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceManager;
    
    /**
     * 
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceManager = $serviceLocator;
    }
    
    /**
     * 
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceManager;
    }
	
	/**
	 * Get an entity using the encrypted ID
	 * 
	 * @param string $id
	 * @param string $field
	 * @return unknown
	 */
	public function findByEncryptedId($id, $field = 'id') 
	{
	    $hashids = $this->getServiceLocator()->get('neobazaar.service.hashid');
	    $id = $hashids->decrypt($id);
	    $id = is_array($id) ? reset($id) : $id;
	    
        $options = array(); 
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('a'));
        $qb->from($this->getEntityName(), 'a');
        $qb->where($qb->expr()->eq('a.' . $field, ':value'));
        $qb->setParameter('value', $id);
        $result = $qb->getQuery()->getResult();
        
        return reset($result);
	}
	
	/**
	 * Return the encrypted id using hash id service
	 * 
	 * @param int $id
	 * @return string
	 */
	public function getEncryptedId($id) 
	{
	    $hashids = $this->getServiceLocator()->get('neobazaar.service.hashid');
	    $hash = $hashids->encrypt($id);
	    
	    return $hash;
	}
}