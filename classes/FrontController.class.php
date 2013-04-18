<?

class FrontController extends Controller{
	
	private static $_instance = null;
	
	public $requestMethod = null;
	public $requestParams = array();
	
	public $codeTplDir = null;
	
	/** получение экземпляра FrontController */
	public static function get(){
		
		if(is_null(self::$_instance))
			self::$_instance = new FrontController();
		
		return self::$_instance;
	}
	
	/**
	 * Приватный конструктор.
	 * Доступ к объекту осуществляется через статический метод self::get()
	 * Выполняет примитивную авторизацию пользователя.
	 * Парсит полученную query string.
	 * @access private
	 */
	private function __construct(){
		
		// авторизация
		$this->_checkAuth();
		
		// парсинг запроса
		$request = explode('/', getVar($_GET['r']));
		$_rMethod = array_shift($request);
		
		$this->requestMethod = !empty($_rMethod) ? $_rMethod : 'index';
		$this->requestParams = $request;

		$this->codeTplDir = FS_ROOT.'codeTemplates/';
	}
	
	/** запуск приложения */
	public function run(){
		
		$this->_checkAction();
		
		if($this->_checkDisplay())
			exit;
		
		$this->display_404();
	}
	
	/** запуск приложения в ajax-режиме */
	public function run_ajax(){
		
		if($this->_checkAction())
			exit;
			
		if($this->_checkAjax())
			exit;
		
		if($this->_checkDisplay())
			exit;
		
		$this->display_404();
	}
	
	/** проверка авторизации */
	private function _checkAuth(){
		
		if(!empty($_POST['action']) && $_POST['action'] == 'login')
			$this->action_login();
		
		// if(empty($_SESSION['logged']))
			// $this->display_login();
	}
	
	/** проверка необходимости выполнения действия */
	private function _checkAction(){
		
		if(!isset($_POST['action']) || !checkFormDuplication())
			return FALSE;
		
		$action = $_POST['action'];
		
		// если action вида 'controller/action'
		if(strpos($action, '/')){
			
			list($controller, $action) = explode('/', $action);
			$controllerClass = $this->getControllerClassName($controller);
			
			if(empty($controllerClass)){
				$this->display_404('action '.$controllerClass.'/'.$action.' not found');
				exit;
			}
			
			$instance = new $controllerClass();
			$result = $instance->action($action, getVar($_POST['redirect']));
		}
		// если action вида 'action'
		else{
			$result = $this->action($action, getVar($_POST['redirect']));
		}

		if ($result)
			lockFormCode();

		return $result;
	}
	
	/** проверка необходимости выполнения отображения */
	private function _checkDisplay(){
		
		return $this->display($this->requestMethod, $this->requestParams);
	}
	
	/** проверка необходимости выполнения ajax */
	private function _checkAjax(){
		
		return $this->ajax($this->requestMethod, $this->requestParams);
	}

	
	/////////////////////
	////// DISPLAY //////
	/////////////////////
	
	public function display_index(){

		$dirs = array();
		foreach (scandir($this->codeTplDir) as $elm)
			if ($elm != '.' && $elm != '..' && is_dir($this->codeTplDir.$elm))
				$dirs[] = $elm;

		$vars = array('templates' => $dirs);

		Layout::get()
			->setContentPhpFile('index.php', $vars)
			->render();
	}

	public function display_template($template = null){

		if (empty($template) || !is_dir($this->codeTplDir.$template))
			redirect('');

		$tplDir = $this->codeTplDir.$template.'/';
		$vars = array(
			'data' => Storage::get($template)->data,
			'codeTemplate' => $template,
		);

		Layout::get()
			->setTitle($template)
			->setContentPhpFile($tplDir.'form.php', $vars)
			->render();
	}

	public function display_parse_db_struct($template = null){

		if (empty($template) || !is_dir($this->codeTplDir.$template)) {
			echo 'Шаблон не найден';
			return FALSE;
		}

		$vars = array('template' => $template);

		Layout::get()
			->setContentPhpFile('table-structure.php', $vars)
			->render();
	}

	public function display_test() {

		if (!empty($_POST['structure'])) {
			$parser = new DbStructParser($_POST['structure'], DbStructParser::SRC_CREATE);
		}
		$vars = array(
			'structure_str' => !empty($_POST['structure']) ? $_POST['structure'] : '',
			'structure_arr' => !empty($_POST['structure']) ? $parser : '',
		);
		Layout::get()
			->setContentPhpFile('test.php', $vars)
			->render();
	}

	////////////////////
	////// ACTION //////
	////////////////////

	public function action_clear_session(){

		$template = getVar($_POST['template']);

		if (empty($template) || !is_dir($this->codeTplDir.$template)) {
			Messenger::get()->addError('Шаблон не найден');
			return FALSE;
		}

		Storage::get($template)->clear();
		Messenger::get()->addInfo('Все данные очищены');
		return TRUE;
	}

	public function action_parse_db_structure(){

		$template = getVar($_POST['template']);
		$inputType = getVar($_POST['input-type']);

		$inputTypes = array(
			'create' => DbStructParser::SRC_CREATE,
			'describe' => DbStructParser::SRC_DESCRIBE_EVAL,
		);
		$error = '';

		if (empty($template) || !is_dir($this->codeTplDir.$template)) {
			Messenger::get()->addError('Шаблон не найден');
			return FALSE;
		}

		$str = trim(getVar($_POST['structure']));
		if(!strlen($str))
			$error .= 'Получена пустая строка';

		if(get_magic_quotes_gpc() || get_magic_quotes_runtime())
			$str = stripslashes($str);

		if(!$error){
			$storage = Storage::get($template);
			try{
				$parser = new DbStructParser($str, $inputTypes[$inputType]);

				$storage->data['tablename'] = $parser->getTableName();
				$storage->data['tableStruct'] = $parser->getStructure();
				$storage->data['validatIndividRules'] = $parser->getIndividualRules();
				$storage->save();

			}catch(Exception $e){
				$error .= $e->getMessage();
			}
		}

		if($error){
			Messenger::get()->addError($error);
		}else{
			echo '
				<script type="text/javascript">
					window.opener.window.location.reload();
					window.close();
				</script>
			';
		}
	}

	public function action_save_data(){

		$template = getVar($_POST['template']);
		if (empty($template) || !is_dir($this->codeTplDir.$template)) {
			Messenger::get()->addError('Шаблон не найден');
			return FALSE;
		}

		if(get_magic_quotes_gpc() || get_magic_quotes_runtime())
			$_POST = array_stripslashes($_POST);

		$s = Storage::get($template);

		foreach($_POST as $k => $v)
			$s->data[$k] = $v;

		$s->save();

		Messenger::get()->addSuccess('Данные сохранены');
		return TRUE;
	}

	public function action_generate(){

		$template = getVar($_POST['template']);
		if (empty($template) || !is_dir($this->codeTplDir.$template)) {
			Messenger::get()->addError('Шаблон не найден');
			return FALSE;
		}

		$s = & Storage::get($template)->data;

		$s['files'] = array(
			'model' 			=> getVar($_POST['files']['model']),
			'controller' 		=> getVar($_POST['files']['controller']),
			'config' 			=> getVar($_POST['files']['config']),
			'tpl-admin-list'	=> getVar($_POST['files']['tpl-admin-list']),
			'tpl-list'			=> getVar($_POST['files']['tpl-list']),
			'tpl-view'		 	=> getVar($_POST['files']['tpl-view']),
			'tpl-edit'			=> getVar($_POST['files']['tpl-edit']),
			'tpl-delete' 		=> getVar($_POST['files']['tpl-delete']),
		);

		$s['clear-output-dir'] = getVar($_POST['clear-output-dir'], FALSE, 'bool');

		Storage::get($template)->save();

		$classFile = FS_ROOT.'codeTemplates/'.$template.'/CodeGenerator.php';
		if(!file_exists($classFile))
			trigger_error('Класс кодогенерации не найден ['.$classFile.']', E_USER_ERROR);

		require ($classFile);
		$generator = new CodeGenerator($template, $s, $s['clear-output-dir']);
		$successMsg = '';

		try{
			$successMsg = $generator->generateAll($s['files']);
			if($successMsg)
				Messenger::get()->addSuccess($successMsg);
		}
		catch(Exception $e){

			if($successMsg)
				Messenger::get()->addSuccess($successMsg);
			Messenger::get()->addError('При генерации файлов произошли ошибки:<br />'.$e->getMessage());
		}

		return TRUE;
	}

	////////////////////
	//////  AJAX  //////
	////////////////////

	////////////////////
	//////  MODEL  /////
	////////////////////
	
}

?>