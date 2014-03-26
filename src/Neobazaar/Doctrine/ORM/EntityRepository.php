<?php 
namespace Neobazaar\Doctrine\ORM;

use Razor\Doctrine\ORM\EntityRepository as EntityRepositoryAbstract;

use Doctrine\Common\Util\Debug as DDebug;

use Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceManager\ServiceLocatorInterface;

use Hashids\Hashids;

/**
 * @ORM\MappedSuperclass
 */
class EntityRepository 
    extends EntityRepositoryAbstract
{
	/**
	 * @var string
	 */
    protected static $encryptionKeyLeft = '';
    protected static $encryptionKeyRight = '';
    
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
	 * Set $encryptionKeyLeft value
	 * 
	 * @param string $encryptionKeyLeft
	 * @return void
	 */
	public static function setEncryptionKeyLeft($encryptionKeyLeft) 
	{
		self::$encryptionKeyLeft = $encryptionKeyLeft;
	}
	
	/**
	 * Get $encryptionKeyLeft value
	 * 
	 * @return string
	 */
	public static function getEncryptionKeyLeft() 
	{
		return self::$encryptionKeyLeft;
	}
	
	/**
	 * Set $encryptionKeyRight value
	 * 
	 * @param string $encryptionKeyRight
	 * @return void
	 */
	public static function setEncryptionKeyRight($encryptionKeyRight) 
	{
		self::$encryptionKeyRight = $encryptionKeyRight;
	}
	
	/**
	 * Get $encryptionKeyRight value
	 * 
	 * @return string
	 */
	public static function getEncryptionKeyRight() 
	{
		return self::$encryptionKeyRight;
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
	    $config = $this->getServiceLocator()->get('Neobazaar\Options\ModuleOptions');
	    $hashids = new Hashids($config->getHashSalt());
	    $id = $hashids->decrypt($id);
	    
        $options = array(); 
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('a'));
        $qb->from($this->getEntityName(), 'a');
        //$qb->where($qb->expr()->eq('SHA1(CONCAT_WS(\'\', \'' . self::getEncryptionKeyLeft() . '\', a.' . $field . ', \'' . self::getEncryptionKeyRight() . '\'))', ':value'));
        $qb->where($qb->expr()->eq($field, ':value'));
        $qb->setParameter('value', $id);
        $result = $qb->getQuery()->getResult();
        
        return reset($result);
	}
	
	/**
	 * Return the encrypted id
	 * 
	 * @param int $id
	 * @return string
	 */
	public function getEncryptedId($id) 
	{
	    $config = $this->getServiceLocator()->get('Neobazaar\Options\ModuleOptions');
	    $hashids = new Hashids($config->getHashSalt());
	    $hash = $hashids->encrypt($id);
	    
	    return $hash;
		//return sha1(self::getEncryptionKeyLeft() . $id . self::getEncryptionKeyRight());
	}
}