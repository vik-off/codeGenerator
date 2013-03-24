%%%	USE PLACEHOLDERS:
%%%		__MODULE__
%%%		__MODELNAME__
<?php

namespace __MODULE__;

use \Zend\Db\ResultSet\ResultSet;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

	public function getServiceConfig()
	{
        return array(
            'factories' => array(
				'__MODULE__\Model\__MODELNAME__' =>  function($sm) {
					$table = new Model\__MODELNAME__($sm);
					return $table;
				},
			),

		);
	}


}