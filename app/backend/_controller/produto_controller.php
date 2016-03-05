<?php 


class ProdutoController extends Controller
{

	public function atualizar()
	{
		// data
		$_POST['valor'] = Common::convertValueBrToUs($_POST['valor']);

		// update
		$objProduto = $this->model('Produto');
		$output = $objProduto->update($_POST);

		// view
		$this->lista();
	}


	public function confirma_exclusao($id)
	{
		// select
		$objProduto = $this->model('Produto');
		$result 	= $objProduto->selectOne('produto_id',$id);

		// data
		$data = $result['result'];

		// view
		$this->view('produto/excluir',$data);
	}


	public function excluir($id)
	{
		// delete
		$objProduto = $this->model('Produto');
		$objProduto->delete('produto_id',$id);

		// view
		$this->lista();
	}


	public function form($produto_id)
	{
		// data
		$data['form_titulo'] = 'Adicionar produto';
		$data['form_method'] = 'POST';
		$data['form_action'] = '/produto/novo';

		// edit
		if (isset($produto_id))
		{
			$objProduto 			= $this->model('Produto');
			$output 				= $objProduto->selectOne('produto_id', $produto_id);
			$data 					= $output['result'];
			$data['valor']          =  Common::covertValueUstoBr($data['valor']);
			$data['form_titulo'] 	= 'Editar produto';
			$data['form_method'] 	= 'POST';
			$data['form_action'] 	= '/produto/atualizar';

			if (empty($output['result']))
			{
				$data['aviso'] 		= 'Produto não encontrado.';
				$data['form_class'] = 'none';
			}
		}

		// categoria
		$objProdutoCategoria                = $this->model('ProdutoCategoria');
		$output 		                    = $objProdutoCategoria->select();
		$data['produto_categoria_options']  = Common::convertArrayToOptions($output['result'], 'produto_categoria_id', 'nome', $data['produto_categoria_id']);
		unset($output);

		// view
		$this->view('produto/form', $data);
	}
	

	public function index()
	{
		$this->lista();
	}


	public function lista()
	{
		// select
		$order 		= 'nome ASC';
		$objProduto = $this->model('Produto');
		$output 	= $objProduto->selectFull();

		// html
		$data['lista'] = '';
		
		foreach ($output['result'] as $produto)
		{
			$template = '<article>
							<strong>'.$produto['produto_categoria_nome'].'</strong><br/>
							<h3>'.$produto['nome'].'</h3>
							<p>'.Common::convertValueUstoBr($produto['valor']).'</p>
							<p>'.$produto['detalhes'].'</p>
							<a href="/produto/form/'.$produto['produto_id'].'">Editar</a><span> - </span>
							<a href="/produto/confirma_exclusao/'.$produto['produto_id'].'">Excluir</a>
						</article>';

			$data['lista'] .= $template;
		}

		// view
		$this->view('produto/lista', $data);
	}


	public function novo()
	{
		// create
		$objProduto = $this->model('Produto');
		$output = $objProduto->create($_POST);

		// view
		$this->lista();
	}

}