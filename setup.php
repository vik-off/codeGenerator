<?

function getVar(&$varname, $defaultVal = '', $type = ''){

	if(!isset($varname))
		return $defaultVal;
	
	if(strlen($type))
		settype($varname, $type);
	
	return $varname;
}

function getHtmlTempateTypesList($selected, $items = 'dte'){
	return ''
		.(strpos($items, 'd') !== FALSE ? '<option value="div" '.($selected == 'div' ? 'selected="selected"' : '').'>div</option>' : '')
		.(strpos($items, 't') !== FALSE ? '<option value="table" '.($selected == 'table' ? 'selected="selected"' : '').'>table</option>' : '')
		.(strpos($items, 'e') !== FALSE ? '<option value="" '.($selected == '' ? 'selected="selected"' : '').'>disable</option>' : '')
	;
}

function reload(){
	
	$url = Messenger::get()->qsAppendFutureKey('http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	header('location: '.$url);
	exit();
}

function array_stripslashes($arr){
	foreach($arr as &$v)
		$v = is_scalar($v) ? stripslashes($v) : array_stripslashes($v);
	return $arr;
}

define('TYPE_DIV', 'div');
define('TYPE_TABLE', 'table');

require_once('data/CodeGeneratorCommon.php');
require_once('data/DbStuctParser.php');
require_once('data/Messenger.php');
require_once('data/Storage.php');
require_once('data/Inp.php');

$s = & Storage::get(GEN_TYPE)->data;

?>