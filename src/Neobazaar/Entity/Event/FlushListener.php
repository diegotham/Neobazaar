<?php
namespace Neobazaar\Entity\Event;

class FlushListener
{
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() AS $entity) {

        }

        foreach ($uow->getScheduledEntityUpdates() AS $entity) {

        }

        foreach ($uow->getScheduledEntityDeletions() AS $entity) {

        }

        foreach ($uow->getScheduledCollectionDeletions() AS $col) {

        }

        foreach ($uow->getScheduledCollectionUpdates() AS $col) {

        }
    }
}