%%%	USE PLACEHOLDERS:
%%%		__MODULE__
%%%		__CONTROLLERNAME__
%%%		__MODELNAME__
%%%		__COLLECTION_CLASS__
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
		'display_list' 			=> 'view',
		'display_view' 			=> 'view',
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
		
%% BLOCK BEGIN : PUBLISH %%
		$collection = new __COLLECTION_CLASS__(array('published' => TRUE));
%% BLOCK ELSE : PUBLISH %%
		$collection = new __COLLECTION_CLASS__();
%% BLOCK END : PUBLISH %%
		$variables = array(
			'collection' => $collection->getPaginated(),
			'pagination' => $collection->getPagination(),
			'sorters' => $collection->getSortableLinks(),
		);
		
		FrontendLayout::get()
			->setTitle('Коллекция')
			->setLinkTags($collection->getLinkTags())
			->setContentPhpFile(self::TPL_PATH.'list.php', $variables)
			->render();
	}
	
	/** DISPLAY VIEW */
	public function display_view($instanceId = null){
		
		$instanceId = (int)$instanceId;
		$instance = __MODELNAME__::load($instanceId);
%% BLOCK BEGIN : PUBLISH %%
		
		if (!$instance->published)
			throw new Exception404(__MODELNAME__::NOT_FOUND_MESSAGE);
%% BLOCK END : PUBLISH %%
		
		$variables = $instance->getAllFieldsPrepared();
		FrontendLayout::get()
			->setTitle('Детально')
			->setContentPhpFile(self::TPL_PATH.'view.php', $variables)
			->render();
	}
	
	
}

?>