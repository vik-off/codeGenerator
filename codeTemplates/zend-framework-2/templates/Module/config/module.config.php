%%%	USE PLACEHOLDERS:
%%%		__MODULE__
%%%		__MODELNAME__
%%%		__CONTROLLERNAME__
%%%		__ADMCONTROLLERNAME__
%%%		__ROUTENAME__
<?php

return array(
    'router' => array(
        'routes' => array(

            '__ROUTENAME__' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:action][/:id]',
                    'defaults' => array(
                        'controller' => '__MODULE__\Controller\__MODULE__',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
					'query' => array('type' => 'Query'),
					'wildcard' => array('type' => 'Wildcard')
                )
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            '__MODULE__\Controller\__MODULE__' => '__MODULE__\Controller\__CONTROLLERNAME__',
            '__MODULE__\Controller\Admin__MODULE__' => '__MODULE__\Controller\__ADMCONTROLLERNAME__',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            '__MODULE__' => __DIR__ . '/../view',
        ),
    ),
);
