%%%	USE PLACEHOLDERS:
%%%		__MODULE__
%%%		__MODELNAME__
%%%		__CONTROLLERNAME__
%%%		__FORMNAME__
%%%		__ROUTENAME__
%%%		__ADMVIEW_PATH__
<?php

namespace __MODULE__\Controller;

use \ZfcAdmin\Controller\AdminController;
use \App\ActiveRecord\ValidationException;
use \Zend\View\Model\ViewModel;
use \__MODULE__\Form\__FORMNAME__;

class __CONTROLLERNAME__ extends AdminController
{
	protected $_viewModel = null;

	public function __construct()
	{
		$this->_viewModel = new ViewModel();
	}

	public function indexAction()
	{
		$items = $this->_getModel()->select();
		return array(
			'items' => $items,
		);
	}

	public function createAction()
	{
		$form = new __FORMNAME__();

		if ($this->getRequest()->isPost()) {
			$form->setData($this->params()->fromPost());
			if ($form->isValid()) {
				$row = $this->_getModel()->createRow($form->getData());
				$row->save();
				$this->flashMessenger()->addSuccessMessage('record created');
				return $this->redirect()->toRoute('admin/__ROUTENAME__');
			} else {
				$this->flashMessenger()->addErrorMessage('unable to create record');
			}
		}

		$view = new ViewModel(array(
			'form' => $form,
		));
		$view->setTemplate('__ADMVIEW_PATH__edit.phtml');

		return $view;
	}

	public function editAction()
	{
		$model = $this->_getModel();
		$record = $model->loadById($this->params('id'), TRUE);
		$form = __FORMNAME__::createExists();
		$form->bind($record);

		if ($this->getRequest()->isPost()) {
			$form->setData($this->params()->fromPost());
			if ($form->isValid()) {
				try {
					$record->save();
					$this->flashMessenger()->addSuccessMessage('record saved');
					return $this->redirect()->toRoute('admin/__ROUTENAME__');
				} catch (ValidationException $e) {
					$this->flashMessenger()->addErrorMessage($e->getMessage());
				}
			} else {
				$this->flashMessenger()->addErrorMessage('unable to save record');
			}
		}

		$view = new ViewModel(array(
			'form' => $form,
		));
		$view->setTemplate('__ADMVIEW_PATH__edit.phtml');

		return $view;
	}

	public function removeAction()
	{
		$record = $this->_getModel()->loadById($this->params('id'));
		if ($this->getRequest()->isPost()) {
			if ($this->params()->fromPost('remove')) {
				$record->delete();
				$this->flashMessenger()->addSuccessMessage('record deleted');
				return $this->redirect()->toRoute('admin/__ROUTENAME__');
			}
		}

		return array(
			'item' => $record,
		);
	}

	/**
	 * @return \__MODULE__\Model\__MODELNAME__
	 */
	protected function _getModel()
	{
		return $this->getServiceLocator()->get('__MODULE__\Model\__MODELNAME__');
	}
}
