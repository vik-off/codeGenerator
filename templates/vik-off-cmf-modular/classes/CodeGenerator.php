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
	public function generateTplAdminList($modelName, $fieldtitles, $sortableFields, $allowedFields, $admSection){
		
		$content = $this->parseHtmlTemplate('templates/'.$this->_template.'/templates/admin_list.php', array(
			'MODEL_NAME_LOW'  => $this->getModelUrlPart($modelName),
			'FIELDS_TITLES'   => $fieldtitles,
			'SORTABLE_FIELDS' => $sortableFields,
			'ALLOWED_FIELDS'  => $allowedFields,
			'ADMIN_SECTION'   => $admSection,
		));
		$this->createFile('output/templates/'.$modelName.'/', 'admin_list.php', $content);
	}
	
	// ГЕНЕРАЦИЯ ШАБЛОНА LIST
	public function generateTplList($modelName, $fieldtitles, $type = TYPE_TABLE, $allowedFields, $admSection){
		
		$tpl = $type == TYPE_TABLE ? 'list_table.php' : 'list_div.php';
		
		$content = $this->parseHtmlTemplate('templates/'.$this->_template.'/templates/'.$tpl, array(
			'MODEL_NAME_LOW'  => $this->getModelUrlPart($modelName),
			'FIELDS_TITLES'   => $fieldtitles,
			'ALLOWED_FIELDS'  => $allowedFields,
			'ADMIN_SECTION'   => $admSection,
		));
		$this->createFile('output/templates/'.$modelName.'/', 'list.php', $content);
	}
	
	// ГЕНЕРАЦИЯ ШАБЛОНА VIEW
	public function generateTplView($modelName, $fieldtitles, $type = TYPE_TABLE, $allowedFields, $admSection){
	
		$tpl = $type == TYPE_TABLE ? 'view_table.php' : 'view_div.php';
		
		$content = $this->parseHtmlTemplate('templates/'.$this->_template.'/templates/'.$tpl, array(
			'MODEL_NAME_LOW'  => $this->getModelUrlPart($modelName),
			'FIELDS_TITLES'   => $fieldtitles,
			'ALLOWED_FIELDS'  => $allowedFields,
			'ADMIN_SECTION'   => $admSection,
		));
		$this->createFile('output/templates/'.$modelName.'/', 'view.php', $content);
	}
	
	// ГЕНЕРАЦИЯ ШАБЛОНА EDIT
	public function generateTplEdit($modelName, $fieldtitles, $type = TYPE_TABLE, $allowedFields, $inputTypes, $admSection){
	
		$tpl = $type == TYPE_TABLE ? 'edit_table.php' : 'edit_div.php';
		
		$content = $this->parseHtmlTemplate('templates/'.$this->_template.'/templates/'.$tpl, array(
			'MODEL_NAME_LOW' => $this->getModelUrlPart($modelName),
			'FIELDS_TITLES' => $fieldtitles,
			'ALLOWED_FIELDS' => $allowedFields,
			'INPUT_TYPES' => $inputTypes,
			'ADMIN_SECTION'   => $admSection,
		));
		$this->createFile('output/templates/'.$modelName.'/', 'edit.php', $content);
	}
	
	// ГЕНЕРАЦИЯ ШАБЛОНА DELETE
	public function generateTplDelete($modelName, $fieldtitles, $admSection){
		
		$content = $this->parseHtmlTemplate('templates/'.$this->_template.'/templates/delete.php', array(
			'MODEL_NAME_LOW' => $this->getModelUrlPart($modelName),
			'FIELDS_TITLES' => $fieldtitles,
			'ADMIN_SECTION'   => $admSection,
		));
		$this->createFile('output/templates/'.$modelName.'/', 'delete.php', $content);
	}
	
	/** ПОЛУЧИТЬ HTML-INPUT УКАЗАННОГО ТИПА */
	public function getEditTplInput($type, $name){
		
		$value = '$this->'.$name;
		
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

?>