<?php
namespace Neobazaar\Entity\Event;

use Doctrine\ORM\Events,
	Doctrine\Common\EventSubscriber,
	Doctrine\Common\Persistence\Event\LifecycleEventArgs,
	Doctrine\Common\Util\Debug as DDebug;

class UserUniqueCheck
{
    public function prePersist(PreUpdateEventArgs $args)
    {
		$entity = $args->getObject();
        $entityManager = $args->getObjectManager();

        // perhaps you only want to act on some "Product" entity
        if ($entity instanceof \Neobazaar\Entity\User) {
        	$repository = $entityManager->getRepository('Neobazaar\Entity\User');
        	$result = $repository->findOneBy(array('email' => $entity->getEmail()));
        	if(null !== $result) {
        		//$entity->setUserId($result->getUserId());
        		$args->setNewValue('userId', $result->getUserId());
        	}
        }
    }
}