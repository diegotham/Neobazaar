<?php 
namespace Neobazaar\Entity\Repository;

use Neobazaar\Doctrine\ORM\EntityRepository;

use Doctrine\ORM\Mapping as ORM;

use Zend\Debug\Debug,
	Zend\ServiceManager\ServiceManager;

class GeonamesRepository 
    extends EntityRepository
{
	/**
	 * SELECT name, admin1_code, admin2_code, admin3_code FROM `geonames` 
	 * where country_code = 'IT' AND feature_code NOT IN('PCLI', 'ADM3') 
	 * ORDER BY COALESCE(admin1_code, admin2_code, admin3_code), admin1_code, 
	 * admin2_code, admin3_code 
	 * 
	 * @param string $country
	 * @return array
	 */
	public function getLocationMultioptions(ServiceManager $sm, $country = 'IT') 
	{
		$cache = $sm->get('DatasetCache');
		$key = 'location-multi-options-no-slug';
		if($options = $cache->getItem($key)) {
			return $options;
		}
		
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('a.url', 'a.asciiname', 'a.admin2Code', 'COALESCE(a.admin1Code, a.admin2Code, a.admin3Code) AS HIDDEN fld'));
        $qb->from('\Neobazaar\Entity\Geonames', 'a');
        $qb->andWhere($qb->expr()->eq('a.countryCode', ':country_code'));
        $qb->andWhere($qb->expr()->notin('a.featureCode', array('PCLI', 'ADM3')));
        $qb->addOrderBy('fld', 'ASC');
        $qb->addOrderBy('a.admin1Code', 'ASC');
        $qb->addOrderBy('a.admin2Code', 'ASC');
        $qb->addOrderBy('a.admin3Code', 'ASC');
        
        $sql = $qb->getQuery()->getSQL();
        
        $stmt = $this->_em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $country);
        $stmt->execute();

        $currentGroup = '';
        while($row = $stmt->fetch(\PDO::FETCH_OBJ)) {
        	$option = array('value' => $row->url0, 'label'=> $row->asciiname1);
        	if(null === $row->admin2_code2) {
        		$option['attributes'] = array('class' => 'input-lg');
        	}
        	
        	if(null === $row->admin2_code2) {
        		$currentGroup = $row->asciiname1;
        	}
        	$option['group'] = $currentGroup;
        	
        	if(null === $row->admin2_code2) continue;
        	
            $options[] = $option;
        }
        $cache->setItem($key, $options);
        
        return $options;
	}
	
	public function getLocationChildrenMultioption($entity) 
	{
		$options = array();

		if(null === $entity) return $options;
			
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('a.url', 'a.asciiname', 'a.admin3Code', 'COALESCE(a.admin1Code, a.admin2Code, a.admin3Code) AS HIDDEN fld'));
        $qb->from('\Neobazaar\Entity\Geonames', 'a');
        $qb->andWhere($qb->expr()->eq('a.countryCode', ':country_code'));
        $qb->addOrderBy('fld', 'ASC');
        $qb->addOrderBy('a.admin1Code', 'ASC');
        $qb->addOrderBy('a.admin2Code', 'ASC');
        $qb->addOrderBy('a.admin3Code', 'ASC');
        
		switch($entity->getFeatureCode()) {
			case "ADM2":
        		$qb->andWhere($qb->expr()->eq('a.featureCode', ':featureCode'));
        		$qb->andWhere($qb->expr()->eq('a.admin1Code', ':admin1Code'));
        		$qb->andWhere($qb->expr()->eq('a.admin2Code', ':admin2Code'));
        		$qb->orWhere($qb->expr()->eq('a.geonameId', ':geonameId'));
				break;
		}
        
        $sql = $qb->getQuery()->getSQL();
        
        $stmt = $this->_em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $entity->getCountryCode());
        $stmt->bindValue(2, 'ADM3');
        $stmt->bindValue(3, $entity->getAdmin1Code());
        $stmt->bindValue(4, $entity->getAdmin2Code());
        $stmt->bindValue(5, $entity->getGeonameId());
        $stmt->execute();
        
        $currentGroup = '';
        while($row = $stmt->fetch(\PDO::FETCH_OBJ)) {
        	$option = array('value' => $row->url0, 'label'=> $row->asciiname1);
        	if(null === $row->admin3_code2) {
        		$option['attributes'] = array('class' => 'input-lg');
        	}
        	 
        	if(null === $row->admin3_code2) {
        		$currentGroup = $row->asciiname1;
        	}
        	$option['group'] = $currentGroup;
        	 
        	if(null === $row->admin3_code2) continue;
        	 
        	$options[] = $option;
        }
        
        return $options;
	}
	
	public function getParent($entity) 
	{
        $qb = $this->_em->createQueryBuilder();
        $qb->select('a');
        $qb->from('\Neobazaar\Entity\Geonames', 'a');
        
		switch($entity->getFeatureCode()) {
			case 'ADM3':
			case 'PPLA3':
			default:
		        $qb->andWhere($qb->expr()->eq('a.featureCode', ':featureCode'));
		        $qb->andWhere($qb->expr()->eq('a.countryCode', ':countrycode'));
		        $qb->andWhere($qb->expr()->eq('a.admin1Code', ':admin1Code'));
		        $qb->andWhere($qb->expr()->eq('a.admin2Code', ':admin2Code'));
		        $qb->andWhere($qb->expr()->isNull('a.admin3Code'));
		        $qb->andWhere($qb->expr()->isNull('a.admin4Code'));
		
		        $qb->setParameter('countrycode', $entity->getCountryCode());
		        $qb->setParameter('admin1Code', $entity->getAdmin1Code());
		        $qb->setParameter('admin2Code', $entity->getAdmin2Code());
		        $qb->setParameter('featureCode', 'ADM2');
				break;
		}
		
		$result = $qb->getQuery()->getResult();
		
        return is_array($result) ? reset($result) : $result;
	}
}