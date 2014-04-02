<?php 
namespace Neobazaar\Service;

interface EntityManagerAwareInterface 
{
    /**
     * Set Doctrine 2 Entity Manager
     * 
     * @param EntityManagerInterface $entityManager
     */
    function setEntityManager(EntityManagerInterface $entityManager);
    
    /**
     * Get Doctrine 2 Entity Manager
     */
    function getEntityManager();
}