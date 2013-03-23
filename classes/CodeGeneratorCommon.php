<?

abstract class CodeGeneratorCommon {
	
	
	protected $_template = null;
	protected $_data = null;
	
	// КОНСТРУКТОР
	public function __construct($template, $data, $clearDir){
		
		$this->_template = $template;
		$this->_data = $data;
		
		if(!$this->_template)
			throw new Exception('Неверное имя шаблона');
		
		if($clearDir)
			$this->clearOutputDir();

		$this->_init();
	}

	protected function _init(){}
	
	/** СГЕНЕРИРОВАТЬ ВСЕ НЕОБХОДИМЫЕ ФАЙЛЫ */
	abstract public function generateAll($files);

	/**
	 * создание файла
	 * @param $path
	 * @param $name
	 * @param $content
	 */
	public function createFile($path, $name, $content)
	{
		if(!is_dir($path))
			mkdir($path, 0777, true);
			
		$f = fopen($path.$name, 'w') or die('Невозможно открыть файл для сохранения модели');
		fwrite($f, $content) or die('Невозможно произвести запись в файл для сохранения модели');
		fclose($f);
	}

	public function createDir($path)
	{
		if(!is_dir($path))
			mkdir($path, 0777, true);
	}
	
	// ФУНКЦИЯ ОЧИСТКИ ДИРЕКТОРИИ
	public function clearOutputDir(){
		
		if(!defined('FS_ROOT'))
			die('FS_ROOT not defined');
			
		$MODULES = FS_ROOT.'output/modules';
		if(is_dir($MODULES))
			self::_removeRecursive($MODULES);

		Messenger::get()->addInfo('Ранее сгенерированные файлы удалены');
	}
	
	public static function _removeRecursive($fileOrDir){
	
		if(is_dir($fileOrDir)){
		
			foreach(scandir($fileOrDir) as $f){
				if($f != '.' && $f != '..'){
					if(is_dir($fileOrDir.'/'.$f)){
						self::_removeRecursive($fileOrDir.'/'.$f);
						@rmdir($fileOrDir.'/'.$f);
					}else{
						@unlink($fileOrDir.'/'.$f);
					}
				}
			}
			
			@rmdir($fileOrDir);
			
		}else{
			@unlink($fileOrDir);
		}
	}
	
	// ПАРСИТЬ PHP ШАБЛОН
	public function parsePhpTemplate($tpl, $placeholders, $blocks = array()){
		
		$curBlock = null;
		$includeRows = TRUE;

		$output = '';

		foreach(file($tpl) as $row) {
			if(substr($row, 0, 3) === '%%%') // comment
				continue;
			if (substr($row, 0, 2) === '%%') {
				// block begin
				if (preg_match('/%%\s+BLOCK\s+BEGIN\s*:\s*(\w+)\s*%%/', $row, $matches)) {
					$curBlock = $matches[1];
					$includeRows = !empty($blocks[$curBlock]);
					continue;
				}
				// block else
				elseif (preg_match('/%%\s+BLOCK\s+ELSE\s*:\s*(\w+)\s*%%/', $row, $matches)) {
					if ($curBlock !== $matches[1])
						throw new Exception("template parse error: mismatch block tags.
							Open tag: '$curBlock', else tag: '{$matches[1]}'");
					$includeRows = !$includeRows;
					continue;
				}
				// block end
				elseif (preg_match('/%%\s+BLOCK\s+END\s*:\s*(\w+)\s*%%/', $row, $matches)) {
					if ($curBlock !== $matches[1])
						throw new Exception("template parse error: mismatch block tags.
							Open tag: '$curBlock', close tag: '{$matches[1]}'");
					$curBlock = null;
					$includeRows = TRUE;
					continue;
				}
			}

			if ($includeRows)
				$output .= strtr($row, $placeholders);
		}
		
		return $output;
	}
	
	// ПАРСИТЬ HTML ШАБЛОН
	public function parseHtmlTemplate($tpl, $variables){
		
		ob_start();
		extract($variables);
		include($tpl);
		return ob_get_clean();
	}
	
	public function getModelUrlPart($model){
	
		return strtolower(preg_replace('/([^\s])([A-Z])/', '\1-\2', $model));
	}
	
	public function getEditTplInput($type, $name){
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