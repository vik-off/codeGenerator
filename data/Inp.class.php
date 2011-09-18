<?

class Inp {
	
	public static $tplVarInputTypes = array(
		'input-text' => 'text-line',
		'input-password' => 'password',
		'textarea' => 'textarea',
		'wysiwyg' => 'wysiwyg',
		'checkbox' => 'checkbox',
		'select' => 'select'
	);
	
	private static $_checkboxPK = 0;
	
	public static function checkbox($name, $checked, $title = ''){
		
		$cssId = 'checkbox-'.self::_getCheckboxPK();
		$output = '
			<input type="hidden" name="'.$name.'" value="0" />
			<input id="'.$cssId.'" type="checkbox" name="'.$name.'" value="1" '.($checked ? 'checked="checked"' : '').' />
		';
		if(strlen($title))
			$output .= '<label for="'.$cssId.'">'.$title.'</label>';
			
		return $output;
	}
	
	private static function _getCheckboxPK(){
		
		return ++self::$_checkboxPK;
	}
	
	public static function select($name, $options, $selected = ''){
		
		$output = '<select name="'.$name.'">';
		foreach($options as $k => $v)
			$output .= '<option value="'.$k.'" '.($k == $selected ? 'selected="selected"' : '').'>'.$v.'</option>';
		$output .= '</selected>';
		
		return $output;
	}
	
	public static function getEditTplInput($type, $name){
		switch($type){
			case 'input-text': 		return '<input type="text" name="'.$name.'" value="{$'.$name.'}" />';
			case 'input-password': 	return '<input type="password" name="'.$name.'" value="{$'.$name.'}" />';
			case 'checkbox': 		return '<input type="checkbox" name="'.$name.'" value="1" {if $'.$name.'}checked="checked"{/if} />';
			case 'textarea': 		return '<textarea name="'.$name.'">{$'.$name.'}</textarea>';
			case 'wysiwyg': 		return '<textarea class="wysiwyg" name="'.$name.'">{$'.$name.'}</textarea>';
			case 'select': 			return '<select name="'.$name.'"><option value="">Выберите...</option></select>';
			default: trigger_error('Неизвестный тип поля ввода <b>'.$type.'</b>', E_USER_ERROR);
		}
	}
}

?>