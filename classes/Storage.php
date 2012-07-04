<?

class Storage {
	
	private static $_instances = array();
	
	private $_filePath = null;
	
	public $data = array();

	/**
	 * @static
	 * @singleton
	 * @param $tpl - имя шаблона
	 * @return Storage - экземпляр хранилища для указанного шаблона
	 */
	public static function get($tpl){
		
		if(empty(self::$_instances[$tpl]))
			self::$_instances[$tpl] = new Storage($tpl);
		
		return self::$_instances[$tpl];
	}
	
	private function __construct($tpl){
		
		$this->_filePath = FS_ROOT.'codeTemplates/'.$tpl.'/storage.txt';

		if (!file_exists($this->_filePath))
			trigger_error('storage file '.$this->_filePath.' does not exists!', E_USER_ERROR);

		$this->data = file_get_contents($this->_filePath);
		$this->data = strlen($this->data)
			? unserialize($this->data)
			: array();
	}
	
	public function save(){

		if (!is_writeable($this->_filePath))
			trigger_error('storage file '.$this->_filePath.' is not writeable!', E_USER_ERROR);

		file_put_contents($this->_filePath, serialize($this->data));
	}

	public function clear(){

		$this->data = array();
		$this->save();
	}
	
}