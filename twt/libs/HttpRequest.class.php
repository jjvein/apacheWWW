<?PHP
namespace Twt\Libs;

class HttpRequest {

	private $data = array();
	public function __construct () {
		$module_acton = $this->set_Module_Action ();

		$this->data['domain'] = $_SERVER['SERVER_NAME']; //server name
		$this->data['uri'] = $_SERVER['REQUEST_URI']; //请求uri
		$this->data['protocol'] = $_SERVER['SERVER_PROTOCOL'];//使用协议
		$this->data['request_args'] = $this->get_path (); //请求path数组.
		$this->data['module'] = $module_acton['module'];
		$this->data['action'] = $module_acton['action'];
		$this->data['GET'] = $_GET; //get.
		$this->data['POST'] = $_POST; //post.
		$this->data['REQUEST'] = array_merge( $_GET, $_POST ); //request 组合..
		$this->data['headers'] = Utilities::getHeaders();
	}

	//获取路径数组.
	public function get_path () {

		if ( $uri = $_SERVER['REQUEST_URI'] ) {
			$uri_args = parse_url($uri);
			$path = $uri_args ['path'];
			//$path = ltrim($path, '/'); //因为可能是/twt/goods/info
			$path = substr($path, strlen(ROOT));

			if ($path) {
				$path_args = explode( '/', $path );
				$path_args = array_filter($path_args);
				//print_r($path_args);
				return array_filter($path_args);
			}
		}
		return false;	

	}

	public function set_Module_Action () {
		$path_args = $this->get_path ();
		if ($path_args && is_array($path_args)) {
			$action = array_pop($path_args);
			$module = array_pop($path_args);
		} else {
			$action = 'main';
			$module = 'welcome';
		}

		$arr = array(
			'module' => $module,
			'action' => $action,	
		);	
		return $arr;
	}

	//这里这样做的目的是将数据进行封装...
	public function __get ($item) {
		if (!isset($this->data [$item])) {
			return false;
		}
		return $this->data [$item];
	}

}

?>