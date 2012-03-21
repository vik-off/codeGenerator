<?

class CodeGenerator extends CodeGeneratorCommon{
	
	// ГЕНЕРАЦИЯ МОДЕЛИ
	public function generateModel($className, $tableName, $strValidatCommonRules, $strValidatIndividRules, $fieldTitles, $sortableFields){
		
		$placeholders = array(
			'__CLASSNAME__' 			=> $className,
			'__TABLENAME__' 			=> $tableName,
			'__VALIDATION_COMMON__' 	=> $strValidatCommonRules,
			'__VALIDATION_INDIVIDUAL__'	=> $strValidatIndividRules,
			'__FIELD_TITLES__' 			=> $fieldTitles,
			'__SORTABLE_FIELDS__'		=> $sortableFields,
		);
		$content = $this->parsePhpTemplate('templates/'.$this->_template.'/model.php', $placeholders);
		$this->createFile('output/models/', $className.'.model.php', $content);
	}

	// ГЕНЕРАЦИЯ КОНТРОЛЛЕРА
	public function generateController($controllerName, $modelName){
	
		$placeholders = array(
			'__CONTROLLERNAME__' => $controllerName,
			'__MODELNAME__' 	 => $modelName,
			'__MODEL_NAME_LOW__' => $this->getModelUrlPart($modelName),
		);
		$content = $this->parsePhpTemplate('templates/'.$this->_template.'/controller.php', $placeholders);
		$this->createFile('output/controllers/', str_replace('Controller', '', $controllerName).'.controller.php', $content);
	}
	
	// ГЕНЕРАЦИЯ ШАБЛОНА ADMIN-LIST
	public function generateTplAdminList($modelName, $fieldtitles, $sortableFields, $allowedFields){
		
		// echo '<pre>'; var_dump($sortableFields); die;
		$content = $this->parseHtmlTemplate('templates/templates/'.$this->_template.'/admin_list.tpl', array(
			'MODEL_NAME_LOW' => $this->getModelUrlPart($modelName),
			'FIELDS_TITLES' => $fieldtitles,
			'SORTABLE_FIELDS' => $sortableFields,
			'ALLOWED_FIELDS' => $allowedFields,
		));
		$this->createFile('output/templates/'.$modelName.'/', 'admin_list.tpl', $content);
	}
	
	// ГЕНЕРАЦИЯ ШАБЛОНА LIST
	public function generateTplList($modelName, $fieldtitles, $type = TYPE_TABLE, $allowedFields){
		
		$tpl = $type == TYPE_TABLE ? 'list_table.tpl' : 'list_div.tpl';
		
		$content = $this->parseHtmlTemplate('templates/templates/'.$this->_template.'/'.$tpl, array(
			'MODEL_NAME_LOW' => $this->getModelUrlPart($modelName),
			'FIELDS_TITLES' => $fieldtitles,
			'ALLOWED_FIELDS' => $allowedFields,
		));
		$this->createFile('output/templates/'.$modelName.'/', 'list.tpl', $content);
	}
	
	// ГЕНЕРАЦИЯ ШАБЛОНА VIEW
	public function generateTplView($modelName, $fieldtitles, $type = TYPE_TABLE, $allowedFields){
	
		$tpl = $type == TYPE_TABLE ? 'view_table.tpl' : 'view_div.tpl';
		
		$content = $this->parseHtmlTemplate('templates/templates/'.$this->_template.'/'.$tpl, array(
			'MODEL_NAME_LOW' => $this->getModelUrlPart($modelName),
			'FIELDS_TITLES' => $fieldtitles,
			'ALLOWED_FIELDS' => $allowedFields,
		));
		$this->createFile('output/templates/'.$modelName.'/', 'view.tpl', $content);
	}
	
	// ГЕНЕРАЦИЯ ШАБЛОНА EDIT
	public function generateTplEdit($modelName, $fieldtitles, $type = TYPE_TABLE, $allowedFields, $inputTypes){
	
		$tpl = $type == TYPE_TABLE ? 'edit_table.tpl' : 'edit_div.tpl';
		
		$content = $this->parseHtmlTemplate('templates/templates/'.$this->_template.'/'.$tpl, array(
			'MODEL_NAME_LOW' => $this->getModelUrlPart($modelName),
			'FIELDS_TITLES' => $fieldtitles,
			'ALLOWED_FIELDS' => $allowedFields,
			'INPUT_TYPES' => $inputTypes,
		));
		$this->createFile('output/templates/'.$modelName.'/', 'edit.tpl', $content);
	}
	
	// ГЕНЕРАЦИЯ ШАБЛОНА DELETE
	public function generateTplDelete($modelName, $fieldtitles){
		
		$content = $this->parseHtmlTemplate('templates/templates/'.$this->_template.'/delete.tpl', array(
			'MODEL_NAME_LOW' => $this->getModelUrlPart($modelName),
			'FIELDS_TITLES' => $fieldtitles,
		));
		$this->createFile('output/templates/'.$modelName.'/', 'delete.tpl', $content);
	}
	
}

?>