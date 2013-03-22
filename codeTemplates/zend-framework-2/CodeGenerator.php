<?

class CodeGenerator extends CodeGeneratorCommon {

	/** включаемые в шаблоны блоки */
	protected $_blocks = array();

	protected $_module = null;
	protected $_paths = array();

	protected function _init(){

		if (!empty($this->_data['implementPublished']))
			$this->_blocks['PUBLISH'] = TRUE;
	}

	/** СГЕНЕРИРОВАТЬ ВСЕ НЕОБХОДИМЫЕ ФАЙЛЫ */
	public function generateAll($files)
	{
		$successMsg = '';

		if (empty($this->_data['modulename']))
			throw new Exception('Имя модуля не должно быть пустым');

		$this->_module = ucfirst(strtolower($this->_data['modulename']));

		$this->_generateSceleton();

		// сгенерировать модель
		if(!empty($files['model'])){
			$this->_generateModel();
			$successMsg .= '<p>Файл модели сохранен!</p>';
		}

		// сгенерировать контроллер
		if(!empty($files['controller'])){
			$this->generateController();
			$successMsg .= '<p>Файл контроллера сохранен!</p>';
		}

		// сгенерировать конфиг
		if(!empty($files['config'])){
			$this->generateConfig();
			$successMsg .= '<p>Файл config сохранен!</p>';
		}

		// сгенерировать шаблон admin-list
		if(!empty($files['tpl-admin-list'])){
			$this->generateTplAdminList();
			$successMsg .= '<p>Файл Шаблона admin-list сохранен!</p>';
		}

		// сгенерировать шаблон list
		if(!empty($files['tpl-list'])){
			$this->generateTplList();
			$successMsg .= '<p>Файл Шаблона list сохранен!</p>';
		}

		// сгенерировать шаблон edit
		if(!empty($files['tpl-edit'])){
			$this->generateTplEdit();
			$successMsg .= '<p>Файл Шаблона edit сохранен!</p>';
		}

		// сгенерировать шаблон delete
		if(!empty($files['tpl-delete'])){

			$this->generateTplDelete(
				$this->_data['modulename'],
				$this->_data['moduledir'],
				$this->_data['fieldsTitles'],
				$this->_data['admSection']
			);
			$successMsg .= '<p>Файл Шаблона delete сохранен!</p>';
		}
		
		return $successMsg;
	}

	protected function _getPaths($path = null)
	{
		$moduleLower = strtolower($this->_module);

		if (!$this->_paths) {
			$pathSrc = "$this->_module/src/$this->_module";
			$pathView = "$this->_module/view/$moduleLower";
			$pathRelView = "$this->_module";
			$this->_paths = array(
				"root"       => "$this->_module/",
				'config'     => "$this->_module/config/",
				'src'        => "$pathSrc/",
				'controller' => "$pathSrc/Controller/",
				'model'      => "$pathSrc/Model/",
				'form'       => "$pathSrc/Form/",

				'view'       => "$pathView/",
				'view-admin' => "$pathView/admin/",
				'rel-view'   => "$pathRelView/",
				'rel-view-admin' => "$pathRelView/admin/"
			);
		}

		return $path ? $this->_paths[$path] : $this->_paths;
	}


	protected function _generateSceleton()
	{
		$paths = $this->_getPaths();

		$this->createDir($paths['module']);
		$this->createDir($paths['src']);
		$this->createDir($paths['view']);

		$placeholders = array(
			'__MODULE__'     => $this->_module,
			'__MODELNAME__'  => $this->_data['modelclass'],
		);
		$tpl = 'codeTemplates/'.$this->_template.'/templates/Module/Module.php';
		$content = $this->parsePhpTemplate($tpl, $placeholders, $this->_blocks);
		$this->createFile($this->_getPaths('root'), 'Module.php', $content);
	}

	protected function _generateModel()
	{
		$placeholders = array(
			'__MODULE__'				=> $this->_module,
			'__CLASSNAME__' 			=> $this->_data['modelclass'],
			'__TABLENAME__' 			=> $this->_data['tablename'],
		);
		$tpl = 'codeTemplates/'.$this->_template.'/templates/Module/src/Module/Model/model.php';
		$content = $this->parsePhpTemplate($tpl, $placeholders, $this->_blocks);

		$this->createFile($this->_getPaths('model'), $this->_data['modelclass'].'.php', $content);
	}

	public function generateController()
	{
		$placeholders = array(
			'__MODULE__'			=> $this->_module,
			'__MODELNAME__' 	 	=> $this->_data['modelclass'],
			'__CONTROLLERNAME__' 	=> $this->_data['controlclass'],
			'__FORMNAME__'          => "Edit{$this->_module}Form",
			'__ROUTENAME__'         => $this->_data['routename'],
			'__ADMVIEW_PATH__'      => $this->_getPaths('rel-view-admin'),
		);
		$tpl1 = 'codeTemplates/'.$this->_template.'/templates/Module/src/Module/Controller/controller.php';
		$content = $this->parsePhpTemplate($tpl1, $placeholders, $this->_blocks);
		$this->createFile($this->_getPaths('controller'), $this->_data['controlclass'].'.php', $content);

		$placeholders['__CONTROLLERNAME__'] = $this->_data['admcontrolclass'];
		$tpl2 = 'codeTemplates/'.$this->_template.'/templates/Module/src/Module/Controller/adminController.php';
		$contentAdmin = $this->parsePhpTemplate($tpl2, $placeholders, $this->_blocks);
		$this->createFile($this->_getPaths('controller'), $this->_data['admcontrolclass'].'.php', $contentAdmin);
	}
	
	
	public function generateConfig()
	{
		$placeholders = array(
			'__MODULE__'			=> $this->_module,
			'__MODELNAME__' 	 	=> $this->_data['modelclass'],
			'__CONTROLLERNAME__' 	=> $this->_data['controlclass'],
			'__ADMCONTROLLERNAME__' => $this->_data['admcontrolclass'],
			'__ROUTENAME__'         => $this->_data['routename'],
		);
		
		$tpl = 'codeTemplates/'.$this->_template.'/Module/config/module.config.php';
		$content = $this->parsePhpTemplate($tpl, $placeholders, $this->_blocks);
		$this->createFile($this->_getPaths('config'), 'config.php', $content);
	}
	
	public function generateTplAdminList()
	{
		$tpl = 'codeTemplates/'.$this->_template.'/Module/view/module/admin/index.php';
		$content = $this->parseHtmlTemplate($tpl, array(
			'ROUTENAME'         => $this->_data['routename'],
			'FIELDSTITLES'      => $this->_data['fieldsTitles'],
		));
		$this->createFile($this->_getPaths('view-admin'), 'index.php', $content);
	}
	
	public function generateTplList()
	{
		$tpl = 'codeTemplates/'.$this->_template.'/Module/view/module/index.php';
		$content = $this->parseHtmlTemplate($tpl, array(
			'ROUTENAME'         => $this->_data['routename'],
			'FIELDSTITLES'      => $this->_data['fieldsTitles'],
		));
		$this->createFile($this->_getPaths('view'), 'index.php', $content);
	}

	public function generateTplEdit()
	{
		$tpl = 'codeTemplates/'.$this->_template.'/Module/view/module/admin/edit.php';
		$content = $this->parseHtmlTemplate($tpl, array(
			'MODULE'			=> $this->_module,
			'FORMCLASS'         => "Edit{$this->_module}Form",
			'ROUTENAME'         => $this->_data['routename'],
			'FIELDS'            => array_keys($this->_data['fieldsTitles']),
		));
		$this->createFile($this->_getPaths('view-admin'), 'edit.php', $content);
	}
	
	public function generateTplDelete()
	{
		$tpl = 'codeTemplates/'.$this->_template.'/Module/view/module/admin/remove.php';
		$content = $this->parseHtmlTemplate($tpl, array(
			'ROUTENAME' => $this->_data['routename'],
		));
		$this->createFile($this->_getPaths('view-admin'), 'remove.php', $content);
	}

}

?>