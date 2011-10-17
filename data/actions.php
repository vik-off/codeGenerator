<?

$action = isset($_POST['action']) ? $_POST['action'] : '';

$messenger = Messenger::get();

// СОХРАНЕНИЕ ДАННЫХ
if($action == 'saveData'){
	
	if(get_magic_quotes_gpc() || get_magic_quotes_runtime())
		$_POST = array_stripslashes($_POST);
	
	// echo '<pre>'; print_r($_POST); die;
	$s['template'] = trim($_POST['template']);
	
	$s['tablename'] 	= trim($_POST['tablename']);
	$s['modulename'] 	= trim($_POST['modulename']);
	$s['modelclass'] 	= trim($_POST['modelclass']);
	$s['controlclass'] 	= trim($_POST['controlclass']);
	
	$s['admSection']    = trim($_POST['admSection']);
	
	$s['strValidatCommonRules']  = trim($_POST['validatCommonRules']);
	$s['strValidatIndividRules'] = trim($_POST['validatIndividRules']);
	
	$s['fieldsTitles'] 	 = (array)$_POST['fieldsTitles'];
	$s['sortableFields'] = (array)$_POST['sortableFields'];
	$s['tplFields'] 	 = (array)$_POST['tplFields'];
	$s['inputTypes'] 	 = (array)$_POST['inputTypes'];
	
	
	Storage::get(GEN_TYPE)->save();
	
	$messenger->addSuccess('Данные сохранены');
	reload();
}

// СГЕНЕРИРОВАТЬ ФАЙЛЫ
elseif($action == 'generate'){
	
	$s['files']['model'] 			= getVar($_POST['files']['model']);
	$s['files']['controller'] 		= getVar($_POST['files']['controller']);
	$s['files']['tpl-admin-list']	= getVar($_POST['files']['tpl-admin-list']);
	$s['files']['tpl-list'] 		= getVar($_POST['files']['tpl-list']);
	$s['files']['tpl-view']		 	= getVar($_POST['files']['tpl-view']);
	$s['files']['tpl-edit'] 		= getVar($_POST['files']['tpl-edit']);
	$s['files']['tpl-delete'] 		= getVar($_POST['files']['tpl-delete']);
	
	$s['clear-output-dir']			= getVar($_POST['clear-output-dir'], FALSE, 'bool');
	
	Storage::get(GEN_TYPE)->save();
	
	$classFile = FS_ROOT.'templates/'.$s['template'].'/classes/CodeGenerator.php';
	if(!file_exists($classFile))
		trigger_error('Класс кодогенерации не найден ['.$classFile.']', E_USER_ERROR);
	
	require($classFile);
	$generator = new CodeGenerator(getVar($s['template']), $s, $s['clear-output-dir']);
	$successMsg = '';
	
	try{
		$successMsg = $generator->generateAll($s['files']);
		if($successMsg)
			$messenger->addSuccess($successMsg);
	}
	catch(Exception $e){
		
		if($successMsg)
			$messenger->addSuccess($successMsg);
		$messenger->addError('При генерации файлов произошли ошибки:<br />'.$e->getMessage());
	}
		
	reload();
	
}

// ПАРСИНГ CREATE TABLE СТРОКИ
elseif($action == 'db-parse-create'){
	
	$error = '';
	
	$str = trim($_POST['create-table-str']);
	if(!strlen($str))
		$error .= 'Получена пустая строка';
		
	if(get_magic_quotes_gpc() || get_magic_quotes_runtime())
		$str = stripslashes($str);
	
	if(!$error){
		try{
			$parser = new DbStructParser($str, DbStructParser::SRC_CREATE);
			
			$s['tableStruct'] = $parser->getStructure();
			$s['validatCommonRules'] = $parser->getCommonRules();
			$s['validatIndividRules'] = $parser->getIndividualRules();
			
			if(!getVar($s['tablename']) && !is_null($parser->getTableName()))
				$s['tablename'] = $parser->getTableName();

			Storage::get(GEN_TYPE)->save();
			
		}catch(Exception $e){
			$error .= $e->getMessage();
		}
	}
	
	if($error){
		$messenger->addError($error);
	}else{
		?>
		<script type="text/javascript">
			window.opener.window.location.reload();
			window.location.href='?action=db-eval-describe';
		</script>	
		<?
	}
}

// ПАРСИНГ DESCRIBE СТРОКИ
elseif($action == 'db-eval-describe'){
	
	$error = '';
	
	$str = trim($_POST['describe-str']);
	if(!strlen($str))
		$error .= 'Получена пустая строка';
		
	if(get_magic_quotes_gpc() || get_magic_quotes_runtime())
		$str = stripslashes($str);
	
	if(!$error){
		try{
			$parser = new DbStructParser($str, DbStructParser::SRC_DESCRIBE_EVAL);
			$s['tableStruct'] = $parser->getStructure();
			$s['validatCommonRules'] = $parser->getCommonRules();
			$s['validatIndividRules'] = $parser->getIndividualRules();

			Storage::get(GEN_TYPE)->save();
			
		}catch(Exception $e){
			$error .= $e->getMessage();
		}
	}
	
	if($error){
		$messenger->addError($error);
	}else{
		?>
		<script type="text/javascript">
			window.opener.window.location.reload();
			window.close();
		</script>	
		<?
	}
}

// ОЧИСТКА СЕССИИ
elseif($action == 'clearSession'){

	$s = array();
	Storage::get(GEN_TYPE)->save();
	
	$messenger->addInfo('Все данные очищены');
	reload();
}



?>