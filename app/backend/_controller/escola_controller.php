<?php


class EscolaController extends Controller
{

	public function index()
	{
		// $dados['id'] = 'asdasd';
		// echo 'teste';
		// $this->view('pages/index', $data);
		// $obj = $this->model("Escola");
		// $obj->create($dados);
		header('Content-Type: application/json');
		$api = new ImaApiClient('O4FK6qtxiu4m');
		return $api->list_escolas();
	}
}
