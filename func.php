<?

if(!defined('WWW_ROOT'))
	die('access denided (setup file)');

/** ФУНКЦИЯ GETVAR */
function getVar(&$varname, $defaultVal = '', $type = ''){

	if(!isset($varname))
		return $defaultVal;
	
	if(strlen($type))
		settype($varname, $type);
	
	return $varname;
}

function href($href){
	$href = str_replace('?', '&', $href);
	return 'index.php'.(!empty($href) ? '?r='.$href : '');
}

/** RELOAD */
function reload(){

	$url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	header('location: '.$url);
	exit();
}

/** REDIRECT */
function redirect($href){
	
	header('location: '.href($href));
	exit();
}

// ПОЛУЧИТЬ HTML INPUT СОДЕРЖАЩИЙ FORMCODE
function getFormCodeInput(){

	if(!isset($_SESSION['su']['userFormChecker']))
		$_SESSION['su']['userFormChecker'] = array('current' => 0, 'used' => array());
	
	$_SESSION['su']['userFormChecker']['current']++;
	return '<input type="hidden" name="formCode" value="'.$_SESSION['su']['userFormChecker']['current'].'" />';
}

// ПРОВЕРКА ВАЛИДНОСТИ ФОРМЫ
function checkFormDuplication(){
	
	if(isset($_POST['allowDuplication']))
		return TRUE;
		
	if(!isset($_POST['formCode'])){
		trigger_error('formCode не передан', E_USER_ERROR);
		return FALSE;
	}
	$formcode = (int)$_POST['formCode'];
	
	if(!CHECK_FORM_DUPLICATION)
		return TRUE;
	
	if(!$formcode)
		return FALSE;
		
	if (isset($_SESSION['su']['userFormChecker']['used'][$formcode])) {
		return FALSE;
	} else {
		return TRUE;
	}
}

// ПОМЕТИТЬ FORMCODE ИСПОЛЬЗОВАННЫМ
function lockFormCode(){

	if(CHECK_FORM_DUPLICATION && !empty($_POST['formCode']))
		$_SESSION['su']['userFormChecker']['used'][ (int)$_POST['formCode'] ] = 1;
}

function __autoload($className){

	$file = FS_ROOT.'classes/'.$className.'.php';
	if (file_exists($file))
		require($file);
	else
		trigger_error("file $file not found (class $className)", E_USER_ERROR);
}

function getHtmlTempateTypesList($selected, $items = 'dte'){
	return ''
		.(strpos($items, 'd') !== FALSE ? '<option value="div" '.($selected == 'div' ? 'selected="selected"' : '').'>div</option>' : '')
		.(strpos($items, 't') !== FALSE ? '<option value="table" '.($selected == 'table' ? 'selected="selected"' : '').'>table</option>' : '')
		.(strpos($items, 'e') !== FALSE ? '<option value="" '.($selected == '' ? 'selected="selected"' : '').'>disable</option>' : '')
		;
}

function array_stripslashes($arr){
	foreach($arr as &$v)
		$v = is_scalar($v) ? stripslashes($v) : array_stripslashes($v);
	return $arr;
}
