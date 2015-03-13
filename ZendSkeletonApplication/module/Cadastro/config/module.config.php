<?php
return array (
		'controllers' => array (
				'invokables' => array (
						'Cadastro\Controller\Cadastro' => 'Cadastro\Controller\CadastroController' 
				) 
		),
		
		// The following section is new and should be added to your file
		'router' => array (
				'routes' => array (
						'cadastro' => array (
								'type' => 'segment',
								'options' => array (
										'route' => '/cadastro[/:action][/:id]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id' => '[0-9]+' 
										),
										'defaults' => array (
												'controller' => 'Cadastro\Controller\Cadastro',
												'action' => 'index' 
										) 
								) 
						) 
				) 
		),
		
		'view_manager' => array (
				'template_path_stack' => array (
						'cadastro' => __DIR__ . '/../view' 
				) 
		) 
);