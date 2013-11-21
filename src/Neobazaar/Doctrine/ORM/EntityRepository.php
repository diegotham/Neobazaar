<?php 
namespace Neobazaar\Doctrine\ORM;

use Razor\Doctrine\ORM\EntityRepository as EntityRepositoryAbstract;

use Doctrine\Common\Util\Debug as DDebug;

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
     * The user used to relate document with no user
     * @var unknown
     */
    protected static $dummyUserId;
	
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
	 * Set $dummyUserId value
	 * 
	 * @param string $dummyUserId
	 * @return void
	 */
	public static function setDummyUserId($dummyUserId) 
	{
		self::$dummyUserId = $dummyUserId;
	}
	
	/**
	 * Get $dummyUserId value
	 * 
	 * @return string
	 */
	public static function getDummyUserId() 
	{
		return self::$dummyUserId;
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
        $options = array(); 
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('a'));
        $qb->from($this->getEntityName(), 'a');
        $qb->where($qb->expr()->eq('SHA1(CONCAT_WS(\'\', \'' . self::getEncryptionKeyLeft() . '\', a.' . $field . ', \'' . self::getEncryptionKeyRight() . '\'))', ':value'));
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
		return sha1(self::getEncryptionKeyLeft() . $id . self::getEncryptionKeyRight());
	}
}