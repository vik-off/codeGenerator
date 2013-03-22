%%%	USE PLACEHOLDERS:
%%%		__MODULE__
%%%		__MODELNAME__
%%%		__CONTROLLERNAME__
%%%		__FORMNAME__
%%%		__ROUTENAME__
%%%		__ADMVIEW_PATH__
<?php

namespace Pages\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use \Zend\View\Model\ViewModel;
use \__MODULE__\Form\__FORMNAME__;

class __CONTROLLERNAME__ extends AbstractActionController
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
				$this->redirect()->toRoute('zfcadmin/__ROUTENAME__');
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
				$model->populateRow($record)->save();
				$this->flashMessenger()->addSuccessMessage('record saved');
				$this->redirect()->toRoute('zfcadmin/__ROUTENAME__');
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
		$page = $this->_getModel()->loadById($this->params('id'));
		if ($this->getRequest()->isPost()) {
			if ($this->params()->fromPost('remove')) {
				$page->delete();
				$this->flashMessenger()->addSuccessMessage('record deleted');
				$this->redirect()->toRoute('zfcadmin/__ROUTENAME__');
			}
		}

		return array(
			'page' => $page,
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
