<?php

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'doctrine_type_mappings' => array('enum' => 'string'),
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => 'localhost',
                    'port' => '3306',
                    'user'     => 'travis',
                    'password' => '',
                    'dbname' => 'neobazaar_fixture',
					'charset' => 'utf8',
					'driverOptions' => array(
						1002 => 'SET NAMES utf8'
					),
                ),
            ),
        ),
        'driver' => array(
            'Neobazaar_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => __DIR__ . '/../src/Neobazaar/Entity',
            ),
        
            'orm_default' => array(
                'drivers' => array(
                    'Neobazaar\Entity' => 'Neobazaar_entities'
                ),
            ),
        ),
    ),
);