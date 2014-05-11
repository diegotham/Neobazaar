<?php
namespace Neobazaar;

return array( 
    'router' => array(
        'routes' => array(
			'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        		
        	__NAMESPACE__ . 'Expired' => array(
                'type' => 'Literal',
                'priority' => 1000,
                'options' => array(
                    'route' => '/expired',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\Index',
                        'action'     => 'expired',
                    ),
                ),
        	),
        		
        	__NAMESPACE__ . 'Reactivation' => array(
                'type' => 'Literal',
                'priority' => 1000,
                'options' => array(
                    'route' => '/activation-email-resend',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\Index',
                        'action'     => 'activation-email-resend',
                    ),
                ),
        	),
        		
        	'static-data' => array(
                'type' => 'Literal',
                'priority' => 1000,
                'options' => array(
                    'route' => '/data',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\Index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'categories' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/categories',
                            'defaults' => array(
                                'action'     => 'categories',
                            ),
                        ),
                    ),
                    'locations' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/locations',
                            'defaults' => array(
                                'action'     => 'locations',
                            ),
                        ),
                    ),
				),
			),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __NAMESPACE__ => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity',
            ),
 
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_entities'
                ),
            ),
        ),
    ),
    'caches' => array(
    	// User only for classifieds document objects
        'ClassifiedCache' => array(
            'adapter' => array(
                'name' => 'filesystem'
            ),
            'options' => array(
                'cache_dir' => 'data/cache/document/classified/public',
                'ttl' => 3600*12
            ),
			'plugins' => array(
				array(
					'name' => 'serializer',
					'options' => array()
				)
			)
        ),
        
    	// User only for classifieds document objects
        'ClassifiedCacheOwnerAdmin' => array(
            'adapter' => array(
                'name' => 'filesystem'
            ),
            'options' => array(
                'cache_dir' => 'data/cache/document/classified/private',
                'ttl' => 3600*12
            ),
			'plugins' => array(
				array(
					'name' => 'serializer',
					'options' => array()
				)
			)
        ),
    	
    	// add +1 once every 24 hours
        'ClassifiedVisitCache' => array(
            'adapter' => array(
                'name' => 'filesystem'
            ),
            'options' => array(
                'cache_dir' => 'data/cache/document/hits',
                'ttl' => 3600*24
            ),
			'plugins' => array(
				array(
					'name' => 'serializer',
					'options' => array()
				)
			)
        ),
    		
        'ImageCache' => array(
            'adapter' => array(
                'name' => 'filesystem'
            ),
            'options' => array(
                'cache_dir' => 'data/cache/document/image',
                'ttl' => 3600*4
            ),
			'plugins' => array(
				array(
					'name' => 'serializer',
					'options' => array()
				)
			)
        ),
    		
    	// Used for multioptions and similar datasets
        'DatasetCache' => array(
            'adapter' => array(
                'name' => 'filesystem'
            ),
            'options' => array(
                'cache_dir' => 'data/cache/dataset',
                'ttl' => 3600*24
                // other options
            ),
			'plugins' => array(
				array(
					'name' => 'serializer',
					'options' => array()
				)
			)
        ),
    		
        'UserCache' => array(
            'adapter' => array(
                'name' => 'filesystem'
            ),
            'options' => array(
                'cache_dir' => 'data/cache/user',
                'ttl' => 3600*12
            ),
			'plugins' => array(
				array(
					'name' => 'serializer',
					'options' => array()
				)
			)
        ),
        
    	// User only for classifieds document objects
        'PrerenderCache' => array(
            'adapter' => array(
                'name' => 'filesystem'
            ),
            'options' => array(
                'cache_dir' => 'data/cache/prerender',
                'ttl' => 3600*12
            ),
			'plugins' => array(
				array(
					'name' => 'serializer',
					'options' => array()
				)
			)
        ),
    		
        // more cache adapters settings
    ),
);