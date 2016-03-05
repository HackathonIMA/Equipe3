<?php


class App
{

	protected $http_method 				= null;
	protected $http_type 				= null;
	protected $controller_classname 	= null;
	protected $controller_filename 		= null;
	protected $controller_instance 		= null;
	protected $method 					= null;
	protected $params 					= array();


	public function __construct()
	{
		// Default
		//Setting the 1st page:
		// $this->controller_classname = 'PagesController';
		// $this->controller_filename 	= 'pages_controller';
		$this->controller_instance 	= null;
		$this->method 				= 'index';


		// Http
		$this->http_method 	= strtolower($_SERVER['REQUEST_METHOD']);
		$this->http_type 	= (strpos('-'.$_SERVER['HTTP_ACCEPT'], 'json')) ? 'json' : null;


		// Url
		$url = $this->parseUrl();


		// $class_name
		$class_name	= str_replace('-', ' ', $url[0]);
		$class_name	= ucwords($class_name);
		$class_name	= str_replace(' ', '', $class_name).'Controller';


		// $file_name
		$file_name = str_replace('-', '_', $url[0]);


		// Controller
		if (file_exists('_controller/'.$file_name.'_controller.php'))
		{
			$this->controller_classname	= $class_name;
			$this->controller_filename 	= $file_name.'_controller';
			unset($url[0]);
		}


		require_once '_controller/'.$this->controller_filename.'.php';
		$this->controller_instance = new $this->controller_classname;


		// Method_rest
		if ($this->http_type == 'json')
		{
			// $url[0]
			switch ($this->http_method)
			{
				case 'post'   : $rest_method = 'rest_create'; break;
				case 'put'    : $rest_method = 'rest_update'; break;
				case 'get'    : $rest_method = 'rest_select'; break;
				case 'delete' : $rest_method = 'rest_delete'; break;
			}
		}


		// Method_validate
		if (isset($rest_method))
		{
			if (method_exists($this->controller_instance, $rest_method))
			{
				$this->method = $rest_method;
				unset($rest_method);
			}
		}
		elseif (isset($url[0]))
		{
			if (method_exists($this->controller_instance, $url[0]))
			{
				$this->method = $url[0];
				unset($url[0]);
			}
		}
		elseif (isset($url[1]))
		{
			if (method_exists($this->controller_instance, $url[1]))
			{
				$this->method = $url[1];
				unset($url[1]);
			}
		}


		// Params
		$this->params = array();

		if ($this->http_type == 'json' && is_array($url))
		{
			$output = array();

			for($i = 0; $i < count($url); $i = $i + 2)
			{
				$key 			= ($url[$i]) ? $url[$i] : 0;
				$output[$key] 	= $url[$i + 1];
			}

			if ($this->http_method == 'get')
			{
				$this->params = array($output);
			}
			else
			{
				$this->params = $output;
			}
		}
		else if($url)
		{
			$this->params = $url;
		}


		// Call
		try
		{
			$call_output = call_user_func_array(array($this->controller_instance, $this->method), $this->params);
		}
		catch (Exception $e)
		{
			$call_output = $e->GetMessage();
		}


		// Output
		if ($this->http_type == 'json')
		{
			echo json_encode($call_output);
		}
		else
		{
			echo $call_output;
		}
	}


	public function parseUrl()
	{
		$url = $_GET['url'];

		if (isset($url))
		{
			$url = rtrim($url, '/');
			$url = filter_var($url, FILTER_SANITIZE_URL);
			$url = explode('/', $url);

			unset($_GET['url']);

			return $url;
		}
	}

}
