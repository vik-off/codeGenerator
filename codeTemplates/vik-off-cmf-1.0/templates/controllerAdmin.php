%%%	USE PLACEHOLDERS:
%%%		__MODULE__
%%%		__CONTROLLERNAME__
%%%		__MODELNAME__
<?php

class __CONTROLLERNAME__ extends Controller {
	
	/** имя модуля */
	const MODULE = '__MODULE__';
	
	/** элемент, отображаемый во view по умолчанию */
	const DEFAULT_VIEW = 1;
	
	/** путь к шаблонам (относительно FS_ROOT) */
	const TPL_PATH = 'modules/__MODULE_DIR__/templates/';
	
	/** метод, отображаемый по умолачанию */
	protected $_displayIndex = 'list';
	
	/** ассоциация методов контроллера с ресурсами */
	public $methodResources = array(
		'display_list'     => 'admin_edit',
		'display_new'      => 'admin_edit',
		'display_edit'     => 'admin_edit',
		'display_copy'     => 'admin_edit',
		'display_delete'   => 'admin_edit',

		'action_save'      => 'admin_edit',
		'action_delete'    => 'admin_edit',
%% BLOCK BEGIN : PUBLISH %%
		'action_publish'   => 'edit',
		'action_unpublish' => 'edit',
%% BLOCK END : PUBLISH %%
	);
	
	
	/** ПРОВЕРКА ПРАВ НА ВЫПОЛНЕНИЕ РЕСУРСА */
	public function checkResourcePermission($resource){
		
		return User_Acl::get()->isResourceAllowed(self::MODULE, $resource);
	}
	
	/** ПОЛУЧИТЬ ИМЯ КЛАССА */
	public function getClass(){
		return __CLASS__;
	}
	
	
	/////////////////////
	////// DISPLAY //////
	/////////////////////
	
	/** DISPLAY LIST */
	public function display_list(){
		
		$collection = new __COLLECTION_CLASS__();
		$variables = array(
			'collection' => $collection->getPaginated(),
			'pagination' => $collection->getPagination(),
			'sorters' => $collection->getSortableLinks(),
		);
		
		BackendLayout::get()
			->prependTitle('Список элементов')
			->setLinkTags($collection->getLinkTags())
			->setContentPhpFile(self::TPL_PATH.'admin_list.php', $variables)
			->render();
	}
	
	/** DISPLAY NEW */
	public function display_new(){
		
		$pageTitle = 'Создание новой страницы';
		
		$variables = array_merge($_POST, array(
			'instanceId' => 0,
			'pageTitle'  => $pageTitle,
			'validation' => __MODELNAME__::create()->getValidator()->getJsRules(),
		));
		
		BackendLayout::get()
			->prependTitle($pageTitle)
			->addBreadcrumb($pageTitle)
			->setContentPhpFile(self::TPL_PATH.'admin_edit.php', $variables)
			->render();
	}
	
	/** DISPLAY EDIT */
	public function display_edit($instanceId = null){
		
		$instanceId = (int)$instanceId;
		$instance = __MODELNAME__::load($instanceId);
		
		$pageTitle = '<span style="font-size: 14px;">Редактирование элемента</span> #'.$instance->getField('id');
	
		$variables = array_merge($instance->getAllFieldsPrepared(), array(
			'instanceId' => $instanceId,
			'pageTitle'  => $pageTitle,
			'validation' => $instance->getValidator()->getJsRules(),
		));
		
		BackendLayout::get()
			->prependTitle('Редактирование записи')
			->addBreadcrumb('Редактирование записи')
			->setContentPhpFile(self::TPL_PATH.'admin_edit.php', $variables)
			->render();
	}
	
	/** DISPLAY COPY */
	public function display_copy($instanceId = null){
		
		$instanceId = (int)$instanceId;
		$instance = __MODELNAME__::load($instanceId);
		
		$pageTitle = 'Копирование записи';
	
		$variables = array_merge($instance->getAllFieldsPrepared(), array(
			'instanceId' => 0,
			'pageTitle'  => $pageTitle,
			'validation' => $instance->getValidator()->getJsRules(),
		));
		
		BackendLayout::get()
			->prependTitle($pageTitle)
			->addBreadcrumb($pageTitle)
			->setContentPhpFile(self::TPL_PATH.'admin_edit.php', $variables)
			->render();
	}
	
	/** DISPLAY DELETE */
	public function display_delete($instanceId = null){
		
		$instanceId = (int)$instanceId;
		$instance = __MODELNAME__::load($instanceId);

		$variables = array_merge($instance->getAllFieldsPrepared(), array(
			'instanceId' => $instanceId,
		));
		
		BackendLayout::get()
			->prependTitle('Удаление записи')
			->addBreadcrumb('Удаление записи')
			->setContentPhpFile(self::TPL_PATH.'admin_delete.php', $variables)
			->render();
	}
	

	////////////////////
	////// ACTION //////
	////////////////////
	
	/** ACTION SAVE */
	public function action_save(){
		
		$instanceId = getVar($_POST['id'], 0, 'int');
		$instance = new __MODELNAME__($instanceId);
		$saveMode = $instance->isNewObj ? __MODELNAME__::SAVE_CREATE : __MODELNAME__::SAVE_EDIT;
		
		if ($instance->save($_POST, $saveMode)) {
			Messenger::get()->addSuccess('Запись сохранена');
			$this->_redirectUrl = !empty($this->_redirectUrl)
				? preg_replace('/\(%([\w\-]+)%\)/e', '$instance->getField("$1")', $this->_redirectUrl)
				: null;
			return TRUE;
		} else {
			Messenger::get()->addError('Не удалось сохранить запись:', $instance->getError());
			return FALSE;
		}
	}
	
	/** ACTION DELETE */
	public function action_delete(){
		
		$instanceId = getVar($_POST['id'], 0, 'int');
		$instance = __MODELNAME__::load($instanceId);
	
		if ($instance->destroy()) {
			Messenger::get()->addSuccess('Запись удалена');
			return TRUE;
		} else {
			Messenger::get()->addError('Не удалось удалить запись:', $instance->getError());
			return FALSE;
		}

	}
%% BLOCK BEGIN : PUBLISH %%
	
	/** ACTION PUBLISH */
	public function action_publish(){
		
		$instance = __MODELNAME__::Load(getVar($_POST['id'], 0, 'int'));
		$instance->publish();
		Messenger::get()->addSuccess('Запись "'.$instance->title.'" опубликована');
		return TRUE;
	}
	
	/** ACTION UNPUBLISH */
	public function action_unpublish(){
		
		$instance = __MODELNAME__::Load(getVar($_POST['id'], 0, 'int'));
		$instance->unpublish();
		Messenger::get()->addSuccess('Запись "'.$instance->title.'" скрыта');
		return TRUE;
	}
%% BLOCK END : PUBLISH %%
	
}
