<?

class CodeGenerator extends CodeGeneratorCommon{
	
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
				$this->_data['tablename'],
				$this->_data['strValidatCommonRules'],
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
				$this->_data['controlclass'],
				$this->_data['modelclass']
			);
			$successMsg .= '<p>Файл контроллера сохранен!</p>';
		}

		// сгенерировать шаблон admin-list
		if(!empty($files['tpl-admin-list'])){

			$this->generateTplAdminList(
				$this->_data['modelclass'],
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
				$this->_data['modelclass'],
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
				$this->_data['modelclass'],
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
				$this->_data['modelclass'],
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
				$this->_data['modelclass'],
				$this->_data['fieldsTitles'],
				$this->_data['admSection']
			);
			$successMsg .= '<p>Файл Шаблона delete сохранен!</p>';
		}
		
		return $successMsg;
	}
	
	// ГЕНЕРАЦИЯ МОДЕЛИ
	public function generateModel($module, $modelName, $tableName, $strValidatCommonRules, $strValidatIndividRules, $fieldTitles, $sortableFields){
		
		$placeholders = array(
			'__MODULE__'				=> $module,
			'__CLASSNAME__' 			=> $modelName,
			'__COLLECTION_CLASS__' 		=> str_replace('_Model', '_Collection', $modelName),
			'__TABLENAME__' 			=> $tableName,
			'__VALIDATION_COMMON__' 	=> $strValidatCommonRules,
			'__VALIDATION_INDIVIDUAL__'	=> $strValidatIndividRules,
			'__FIELD_TITLES__' 			=> $fieldTitles,
			'__SORTABLE_FIELDS__'		=> $sortableFields,
		);
		$content = $this->parsePhpTemplate('templates/'.$this->_template.'/model.php', $placeholders);
		$this->createFile('output/modules/'.ucfirst($this->_module).'/', $modelName.'.php', $content);
	}

	// ГЕНЕРАЦИЯ КОНТРОЛЛЕРА
	public function generateController($module, $controllerName, $modelName, $admSection){
	
		$placeholders = array(
			'__MODULE__'			=> $module,
			'__CONTROLLERNAME__' 	=> $controllerName,
			'__MODELNAME__' 	 	=> $modelName,
			'__COLLECTION_CLASS__' 	=> str_replace('_Model', '_Collection', $modelName),
			'__ADMSECTION__'		=> $admSection,
			'__MODEL_NAME_LOW__' 	=> $this->getModelUrlPart($modelName),
		);
		$content = $this->parsePhpTemplate('templates/'.$this->_template.'/controller.php', $placeholders);
		$this->createFile('output/modules/'.ucfirst($this->_module).'/', $controllerName.'.php', $content);
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