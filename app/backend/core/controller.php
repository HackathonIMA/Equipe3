<?php

class Controller
{
	
	public $body_class_name = null;
	public $class_name 		= null;
	public $urla_class_name = null;
	public $data 			= array();


	public function __construct()
	{
		$class_name = get_class($this);
		$class_name = str_replace('Controller', '', $class_name);
		
		$this->class_name      = $class_name;
		$this->urla_class_name = strtolower($class_name);
	}


	public function index()
	{
		$class_name = strtolower($this->class_name);
		$this->view($class_name.'/index');
	}


	public function model($model)
	{
		$required_file = '_model/'.$model.'.php';
		
		if (!file_exists($required_file))
		{
			echo 'MODEL: "'.$required_file.'" n&atilde;o encontrado!';
		}
		
		require_once $required_file;
		return new $model();
	}


	public function template($filename)
	{
		$required_file = '_view/_templates/'.$filename.'.phtml';

		if (!file_exists($required_file))
		{
			echo 'TEMPLATE: "'.$required_file.'" nao encontrado.';
		}

		require_once $required_file;
	}


	public function view($view, $data = array())
	{
		// self 
		$this->body_class_name = str_replace('/', '-', $view);
		$this->data = $data;

		// view
		$required_file = '_view/'.$view.'.phtml';

		if (!file_exists($required_file))
		{
			echo 'VIEW: "'.$required_file.'" nao encontrado.';
		}

		require_once $required_file;
	}


	public function Security()
	{
		if ($_SESSION['yc_logged'] != true)
		{
			header('Location: /signin');
			exit;
		}
	}



	/* RESTful
	-------------------------------------------------------------------------------------*/

	// TODO: 
	// 		The class that extendes must be implement Security ad more...
	// 		move rest methods to another class file. And create a readme file. (WILL)


	// POST
	protected function rest_create()
	{
		// $this->Security();

		$obj 		= $this->model($this->class_name);
		$output 	= $obj->create($_POST);

		return $output;
	}


	// DELETE
	protected function rest_delete($id)
	{
		$this->Security();
		
		$obj 		= $this->model($this->class_name);
		$output 	= $obj->delete($obj->properties[0], $id);

		return $output;
	}


	// GET
	protected function rest_select($params = array())
	{
		$this->Security();
		
		$query 	= null;
		$order 	= null;
		$obj 	= $this->model($this->class_name);

		// single
		if (count($params) == 1)
		{
			$query['='][$obj->properties[0]] = $params[0];
		}

		// multiple
		else
		{
			if ($_GET['order'])
			{
				$order = $_GET['order'];
				unset($_GET['order']);
			}

			foreach ($_GET as $column => $value)
			{
				$query['LIKE'][$column] = '%'.$value.'%';
			}
		}

		$output = $obj->select($query, $order);

		return $output;
	}


	// PUT
	protected function rest_update($id)
	{
		$this->Security();
		
		// MAYBE: Can be work across POST, if primary_key isset.
		
		// instance
		$obj = $this->model($this->class_name);
		
		// data
		parse_str(file_get_contents("php://input"), $data);
		$data[$obj->properties[0]] = $id;
		
		// output
		$output = $obj->update($data);
		
		return $output;
	}

}