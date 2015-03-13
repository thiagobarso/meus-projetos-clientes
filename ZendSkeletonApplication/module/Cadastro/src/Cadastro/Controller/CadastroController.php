<?php

namespace Cadastro\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Cadastro\Model\Cadastro; // <-- Add this import
use Cadastro\Form\CadastroForm;

class CadastroController extends AbstractActionController {
	protected $cadastroTable;
	public function indexAction() {
		return new ViewModel ( array (
				'cadastros' => $this->getCadastroTable ()->fetchAll () 
		) );
	}
	public function addAction() {
		$form = new CadastroForm ();
		$form->get ( 'submit' )->setValue ( 'Add' );
		
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$cadastro = new Cadastro ();
			$form->setInputFilter ( $cadastro->getInputFilter () );
			$form->setData ( $request->getPost () );
			
			if ($form->isValid ()) {
				$cadastro->exchangeArray ( $form->getData () );
				$this->getCadastroTable ()->saveCadastro ( $cadastro );
				
				// Redirect to list of cadastros
				return $this->redirect ()->toRoute ( 'cadastro' );
			}
		}
		return array (
				'form' => $form 
		);
	}
	public function editAction() {
		$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
		if (! $id) {
			return $this->redirect ()->toRoute ( 'cadastro', array (
					'action' => 'add' 
			) );
		}
		
		// Get the Cadastro with the specified id. An exception is thrown
		// if it cannot be found, in which case go to the index page.
		try {
			$cadastro = $this->getCadastroTable ()->getCadastro ( $id );
		} catch ( \Exception $ex ) {
			return $this->redirect ()->toRoute ( 'cadastro', array (
					'action' => 'index' 
			) );
		}
		
		$form = new CadastroForm ();
		$form->bind ( $cadastro );
		$form->get ( 'submit' )->setAttribute ( 'value', 'Edit' );
		
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$form->setInputFilter ( $cadastro->getInputFilter () );
			$form->setData ( $request->getPost () );
			
			if ($form->isValid ()) {
				$this->getCadastroTable ()->saveCadastro ( $cadastro );
				
				// Redirect to list of cadastros
				return $this->redirect ()->toRoute ( 'cadastro' );
			}
		}
		
		return array (
				'id' => $id,
				'form' => $form 
		);
	}
	public function deleteAction() {
		$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
		if (! $id) {
			return $this->redirect ()->toRoute ( 'cadastro' );
		}
		
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$del = $request->getPost ( 'del', 'No' );
			
			if ($del == 'Yes') {
				$id = ( int ) $request->getPost ( 'id' );
				$this->getCadastroTable ()->deleteCadastro ( $id );
			}
			
			// Redirect to list of cadastros
			return $this->redirect ()->toRoute ( 'cadastro' );
		}
		
		return array (
				'id' => $id,
				'cadastro' => $this->getCadastroTable ()->getCadastro ( $id ) 
		);
	}
	public function getCadastroTable() {
		if (! $this->cadastroTable) {
			$sm = $this->getServiceLocator ();
			$this->cadastroTable = $sm->get ( 'Cadastro\Model\CadastroTable' );
		}
		return $this->cadastroTable;
	}
}
