<?php

namespace Cadastro;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Cadastro\Model\Cadastro;
use Cadastro\Model\CadastroTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface {
	public function getAutoloaderConfig() {
		return array (
				'Zend\Loader\ClassMapAutoloader' => array (
						__DIR__ . '/autoload_classmap.php' 
				),
				'Zend\Loader\StandardAutoloader' => array (
						'namespaces' => array (
								__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__ 
						) 
				) 
		);
	}
	public function getConfig() {
		return include __DIR__ . '/config/module.config.php';
	}
	public function getServiceConfig() {
		return array (
				'factories' => array (
						'Cadastro\Model\CadastroTable' => function ($sm) {
							$tableGateway = $sm->get ( 'CadastroTableGateway' );
							$table = new CadastroTable ( $tableGateway );
							return $table;
						},
						'CadastroTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet ();
							$resultSetPrototype->setArrayObjectPrototype ( new Cadastro () );
							return new TableGateway ( 'Cadastro', $dbAdapter, null, $resultSetPrototype );
						} 
				) 
		);
	}
}