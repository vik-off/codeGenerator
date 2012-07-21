<?

class CodeGenerator extends CodeGeneratorCommon {

	/** включаемые в шаблоны блоки */
	protected $_blocks = array();

	protected function _init(){

		if (!empty($this->_data['implementPublished']))
			$this->_blocks['PUBLISH'] = TRUE;
	}

	/** СГЕНЕРИРОВАТЬ ВСЕ НЕОБХОДИМЫЕ ФАЙЛЫ */
	public function generateAll($files){
		
		$successMsg = '';
		
		// сгенерировать модель
		if(!empty($files['model'])){
			
			$strFieldsTitles = "\n";
			foreach($this->_data['fieldsTitles'] as $field => $title)
				$strFieldsTitles .= "\t\t\t'".$field."' => '".$title."',\n";
				
			$sortableFields = "\n";
			foreach($this->_data['sortableFields'] as $f => $true)
				$sortableFields .= "\t\t'".$f."' => '".(isset($this->_data['fieldsTitles'][$f]) ? $this->_data['fieldsTitles'][$f] : $f)."',\n";

			$this->generateModel(
				$this->_data['modulename'],
				$this->_data['modelclass'],
				$this->_data['moduledir'],
				$this->_data['tablename'],
				$this->_data['strValidatIndividRules'],
				$strFieldsTitles,
				$sortableFields
			);
			$successMsg .= '<p>Файл модели сохранен!</p>';
		}

		// сгенерировать контроллер
		if(!empty($files['controller'])){
		
			$this->generateController(
				$this->_data['modulename'],
				$this->_data['moduledir'],
				$this->_data['controlclass'],
				$this->_data['admcontrolclass'],
				$this->_data['modelclass'],
				$this->_data['admSection']
			);
			$successMsg .= '<p>Файл контроллера сохранен!</p>';
		}

		// сгенерировать конфиг
		if(!empty($files['config'])){
		
			$this->generateConfig(
				$this->_data['modulename'],
				$this->_data['moduledir'],
				$this->_data['moduletitle'],
				$this->_data['controlclass'],
				$this->_data['admcontrolclass']
			);
			$successMsg .= '<p>Файл config сохранен!</p>';
		}

		// сгенерировать шаблон admin-list
		if(!empty($files['tpl-admin-list'])){

			$this->generateTplAdminList(
				$this->_data['modulename'],
				$this->_data['moduledir'],
				$this->_data['fieldsTitles'],
				$this->_data['sortableFields'],
				$this->_data['tplFields']['admin-list'],
				$this->_data['admSection']
 			);
			$successMsg .= '<p>Файл Шаблона admin-list сохранен!</p>';
		}

		// сгенерировать шаблон list
		if(!empty($files['tpl-list'])){

			$this->generateTplList(
				$this->_data['modulename'],
				$this->_data['moduledir'],
				$this->_data['fieldsTitles'],
				$files['tpl-list'],
				$this->_data['tplFields']['list'],
				$this->_data['admSection']
			);
			$successMsg .= '<p>Файл Шаблона list сохранен!</p>';
		}

		// сгенерировать шаблон view
		if(!empty($files['tpl-view'])){

			$this->generateTplView(
				$this->_data['modulename'],
				$this->_data['moduledir'],
				$this->_data['fieldsTitles'],
				$files['tpl-view'],
				$this->_data['tplFields']['view'],
				$this->_data['admSection']
			);
			$successMsg .= '<p>Файл Шаблона view сохранен!</p>';
		}

		// сгенерировать шаблон edit
		if(!empty($files['tpl-edit'])){

			$this->generateTplEdit(
				$this->_data['modulename'],
				$this->_data['moduledir'],
				$this->_data['fieldsTitles'],
				$files['tpl-edit'],
				$this->_data['tplFields']['edit'],
				$this->_data['inputTypes'],
				$this->_data['admSection']
			);
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
	
	// ГЕНЕРАЦИЯ МОДЕЛИ
	public function generateModel($module, $modelName, $moduledir, $tableName, $strValidatIndividRules, $fieldTitles, $sortableFields){
		
		$validationFields = eval('return array_keys('.$strValidatIndividRules.');');
		foreach($validationFields as &$f)
			$f = "'".$f."'";
		$validationFieldsStr = implode(", ", $validationFields);
		$placeholders = array(
			'__MODULE__'				=> $module,
			'__CLASSNAME__' 			=> $modelName,
			'__COLLECTION_CLASS__' 		=> preg_replace('/Model$/', 'Collection', $modelName),
			'__TABLENAME__' 			=> $tableName,
			'__VALIDATION_FIELDS__'		=> $validationFieldsStr,
			'__VALIDATION_INDIVIDUAL__'	=> $strValidatIndividRules,
			'__FIELD_TITLES__' 			=> $fieldTitles,
			'__SORTABLE_FIELDS__'		=> $sortableFields,
		);
		$tpl = 'codeTemplates/'.$this->_template.'/templates/model.php';
		$content = $this->parsePhpTemplate($tpl, $placeholders, $this->_blocks);
		$this->createFile('output/modules/'.$moduledir.'/', $modelName.'.php', $content);
	}

	// ГЕНЕРАЦИЯ КОНТРОЛЛЕРА
	public function generateController($module, $moduledir, $controllerName, $adminControllerName, $modelName, $admSection){
		
		$placeholders = array(
			'__MODULE__'			=> $module,
			'__MODULE_DIR__'		=> $moduledir,
			'__MODULE_URL__'		=> $this->getModuleUrl($module),
			'__CONTROLLERNAME__' 	=> $controllerName,
			'__MODELNAME__' 	 	=> $modelName,
			'__COLLECTION_CLASS__' 	=> preg_replace('/Model$/', 'Collection', $modelName),
			'__ADMSECTION__'		=> $admSection,
		);
		$tpl1 = 'codeTemplates/'.$this->_template.'/templates/controller.php';
		$content = $this->parsePhpTemplate($tpl1, $placeholders, $this->_blocks);
		$this->createFile('output/modules/'.$moduledir.'/', $controllerName.'.php', $content);
		
		$placeholders['__CONTROLLERNAME__'] = $adminControllerName;
		$tpl2 = 'codeTemplates/'.$this->_template.'/templates/controllerAdmin.php';
		$contentAdmin = $this->parsePhpTemplate($tpl2, $placeholders, $this->_blocks);
		$this->createFile('output/modules/'.$moduledir.'/', $adminControllerName.'.php', $contentAdmin);
	}
	
	
	// ГЕНЕРАЦИЯ КОНФИГА
	public function generateConfig($module, $moduledir, $moduleTitle, $controllerName, $adminControllerName){
		
		$placeholders = array(
			'__MODULE__'				=> $module,
			'__MODULE_TITLE__'			=> $moduleTitle,
			'__CONTROLLERNAME__' 		=> $controllerName,
			'__ADM_CONTROLLERNAME__' 	=> $adminControllerName,
		);
		
		$tpl = 'codeTemplates/'.$this->_template.'/templates/config.php';
		$content = $this->parsePhpTemplate($tpl, $placeholders, $this->_blocks);
		$this->createFile('output/modules/'.$moduledir.'/', 'config.php', $content);
	}
	
	// ГЕНЕРАЦИЯ ШАБЛОНА ADMIN-LIST
	public function generateTplAdminList($module, $moduledir, $fieldtitles, $sortableFields, $allowedFields, $admSection){
		
		$content = $this->parseHtmlTemplate('codeTemplates/'.$this->_template.'/templates/views/admin_list.php', array(
			'MODULE'  		  => $this->getModuleUrl($module),
			'FIELDS_TITLES'   => $fieldtitles,
			'SORTABLE_FIELDS' => $sortableFields,
			'ALLOWED_FIELDS'  => $allowedFields,
			'ADMIN_SECTION'   => $admSection,
			'BLOCKS'          => $this->_blocks,
		));
		$this->createFile('output/modules/'.$moduledir.'/templates/', 'admin_list.php', $content);
	}
	
	// ГЕНЕРАЦИЯ ШАБЛОНА LIST
	public function generateTplList($module, $moduledir, $fieldtitles, $type = TYPE_TABLE, $allowedFields, $admSection){
		
		$tpl = $type == TYPE_TABLE ? 'list_table.php' : 'list_div.php';
		
		$content = $this->parseHtmlTemplate('codeTemplates/'.$this->_template.'/templates/views/'.$tpl, array(
			'MODULE'  		  => $this->getModuleUrl($module),
			'FIELDS_TITLES'   => $fieldtitles,
			'ALLOWED_FIELDS'  => $allowedFields,
			'ADMIN_SECTION'   => $admSection,
			'BLOCKS'          => $this->_blocks,
		));
		$this->createFile('output/modules/'.$moduledir.'/templates/', 'list.php', $content);
	}
	
	// ГЕНЕРАЦИЯ ШАБЛОНА VIEW
	public function generateTplView($module, $moduledir, $fieldtitles, $type = TYPE_TABLE, $allowedFields, $admSection){
	
		$tpl = $type == TYPE_TABLE ? 'view_table.php' : 'view_div.php';
		
		$content = $this->parseHtmlTemplate('codeTemplates/'.$this->_template.'/templates/views/'.$tpl, array(
			'MODULE'  		  => $this->getModuleUrl($module),
			'FIELDS_TITLES'   => $fieldtitles,
			'ALLOWED_FIELDS'  => $allowedFields,
			'ADMIN_SECTION'   => $admSection,
			'BLOCKS'          => $this->_blocks,
		));
		$this->createFile('output/modules/'.$moduledir.'/templates/', 'view.php', $content);
	}
	
	// ГЕНЕРАЦИЯ ШАБЛОНА EDIT
	public function generateTplEdit($module, $moduledir, $fieldtitles, $type = TYPE_TABLE, $allowedFields, $inputTypes, $admSection){
	
		$tpl = $type == TYPE_TABLE ? 'admin_edit_table.php' : 'admin_edit_div.php';
		
		$content = $this->parseHtmlTemplate('codeTemplates/'.$this->_template.'/templates/views/'.$tpl, array(
			'MODULE'  		  => $this->getModuleUrl($module),
			'FIELDS_TITLES' => $fieldtitles,
			'ALLOWED_FIELDS' => $allowedFields,
			'INPUT_TYPES' => $inputTypes,
			'ADMIN_SECTION'   => $admSection,
			'BLOCKS'          => $this->_blocks,
		));
		$this->createFile('output/modules/'.$moduledir.'/templates/', 'admin_edit.php', $content);
	}
	
	// ГЕНЕРАЦИЯ ШАБЛОНА DELETE
	public function generateTplDelete($module, $moduledir, $fieldtitles, $admSection){
		
		$content = $this->parseHtmlTemplate('codeTemplates/'.$this->_template.'/templates/views/admin_delete.php', array(
			'MODULE'  		  => $this->getModuleUrl($module),
			'FIELDS_TITLES'   => $fieldtitles,
			'ADMIN_SECTION'   => $admSection,
			'BLOCKS'          => $this->_blocks,
		));
		$this->createFile('output/modules/'.$moduledir.'/templates/', 'admin_delete.php', $content);
	}
	
	/** ПОЛУЧИТЬ HTML-INPUT УКАЗАННОГО ТИПА */
	public function getEditTplInput($type, $name){
		
		$value = '$this->'.$name;
		
		if (!empty($this->_data['useHtmlForm'])) {
			switch($type){
				case 'input-text': 		return "<?= Html_Form::inputText(array('name' => '$name', 'value' => $value)); ?>";
				case 'input-password': 	return "<?= Html_Form::input(array('type' => 'password', 'name' => '$name', 'value' => $value)); ?>";
				case 'checkbox': 		return "<label><?= Html_Form::checkbox(array('name' => '$name', 'value' => '1', 'checked' => $value)); ?></label>";
				case 'textarea': 		return "<?= Html_Form::textarea(array('name' => '$name', 'value' => $value)); ?>";
				case 'wysiwyg': 		return "<?= Html_Form::textarea(array('class' => 'editor', 'name' => '$name', 'value' => $value)); ?>";
				case 'select': 			return "<?= Html_Form::select(array('name' => '$name'), array('' => 'Выберите...'), $value); ?>";
				default: trigger_error('Неизвестный тип поля ввода <b>'.$type.'</b>', E_USER_ERROR);
				
			}
		
		} else {
			switch($type){
				case 'input-text': 		return '<input type="text" name="'.$name.'" value="<?= '.$value.'; ?>" />';
				case 'input-password': 	return '<input type="password" name="'.$name.'" value="<?= '.$value.'; ?>" />';
				case 'checkbox': 		return '<input type="checkbox" name="'.$name.'" value="1" <? if('.$value.'): ?>checked="checked"<? endif; ?> />';
				case 'textarea': 		return '<textarea name="'.$name.'"><?= '.$value.'; ?></textarea>';
				case 'wysiwyg': 		return '<textarea class="wysiwyg" name="'.$name.'"><?= '.$value.'; ?></textarea>';
				case 'select': 			return '<select name="'.$name.'"><option value="">Выберите...</option></select>';
				default: trigger_error('Неизвестный тип поля ввода <b>'.$type.'</b>', E_USER_ERROR);
			}
		}
		
	}
	
	public function getModuleUrl($module){
	
		return strtolower(preg_replace('/([^\s])([A-Z])/', '\1-\2', $module));
	}

}

?>