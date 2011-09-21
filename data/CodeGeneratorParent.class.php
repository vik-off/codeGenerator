<?

class CodeGeneratorCommon{
	
	
	protected $_template = null;
	
	// КОНСТРУКТОР
	public function __construct($template, $clearDir = FALSE){
		
		$this->_template = $template;
		
		if(!$this->_template)
			throw new Exception('Неверное имя шаблона');
		
		if($clearDir)
			$this->clearOutputDir();
	}
	
	// ФУНКЦИЯ СОЗДАНИЯ ФАЙЛА
	public function createFile($path, $file, $content){
	
		if(!is_dir($path))
			mkdir($path, 0777, true);
			
		$f = fopen($path.$file, 'w') or die('Невозможно открыть файл для сохранения модели');
		fwrite($f, $content) or die('Невозможно произвести запись в файл для сохранения модели');
		fclose($f);
	}
	
	// ФУНКЦИЯ ОЧИСТКИ ДИРЕКТОРИИ
	public function clearOutputDir(){
		
		if(!defined('FS_ROOT'))
			die('FS_ROOT not defined');
			
		$MODELS = FS_ROOT.'output/models';
		$CONTROLLERS = FS_ROOT.'output/controllers';
		$TEMPLATES = FS_ROOT.'output/templates';
		
		if(is_dir($MODELS)){
			self::_removeRecursive($MODELS);
		}
		
		if(is_dir($CONTROLLERS)){
			self::_removeRecursive($CONTROLLERS);
		}
		
		if(is_dir($TEMPLATES)){
			self::_removeRecursive($TEMPLATES);
		}
		
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
	public function parsePhpTemplate($tpl, $placeholders){
		
		$output = '';
		foreach(file($tpl) as $row)
			if(substr($row, 0, 3) != '%%%')
				$output .= strtr($row, $placeholders);
		
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
	
}

?>