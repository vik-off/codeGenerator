<?

class Storage{
	
	private static $_instances = array();
	
	private $_filePath = null;
	
	public $data = array();
	
	
	public static function get($type = 'all'){
		
		if(empty(self::$_instances[$type]))
			self::$_instances[$type] = new Storage($type);
		
		return self::$_instances[$type];
	}
	
	private function __construct($type){
		
		$this->_filePath = dirname(__FILE__).'/storage/'.$type.'.txt';
		
		$this->data = file_get_contents($this->_filePath);
		$this->data = strlen($this->data)
			? unserialize($this->data)
			: array();
	}
	
	public function save(){
		
		file_put_contents($this->_filePath, serialize($this->data));
	}
	
}