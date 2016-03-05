<?php	

/**
*  
* 	Classe genérica para as seguintes ações:
* 		- Select ($key, $value)
* 		- Create ($properties = null)
* 		- Update ($properties = null)
* 		- Delete ($key, $value)
* 
* 
* 	# Abstract: 
* 		- nome da tabela no banco;
* 		- nome das propriedades(colunas) no banco.
* 
* 	# Methods: 
* 		Os métodos específicos do objeto devem ser implementados na classe inferior.
* 
*/
abstract class Model
{

	public $table_name 		= null;
	public $properties 		= array();
	public $return_apagado 	= false;

	
	/**
	 * Valida se classe possui "nome de tabela" e "propriedade" definidas.
	 */
	public function __construct($pReturn_apagado = false)
	{
		try 
		{
			if(empty($this->table_name))
				throw new Exception('Defina a propriedade $table_name na classe '.get_class($this).'.');

			if(empty($this->properties))
				throw new Exception('Defina a propriedade $properties na classe '.get_class($this).'.');

			$this->return_apagado = $pReturn_apagado;
		}
		
		catch(PDOException $ex)
		{
			$dao->rollBack();
			$dao = NULL;            
			
			$message = (debug_mode) ? $ex->GetMessage() : 'Ops... Ocorreu um erro!';
			throw new Exception('ERRO: '.$message);
		}
	}


	/**
	 * Executa uma stored procedure.
	 * @param string $sp_name 	Ex: 'sp_busca_cliente('1001')'
	 * TODO: receber parametros em array e usar bindParam.
	 */
	public function call($sp_name)
	{
		try
		{
			//valida
			if(empty($sp_name)) 
				throw new Exception("O primeiro parametro é obrigatório.");
			elseif(!empty($params) && !is_array($params))
				throw new Exception("O segundo parametro deve ser um array no padrão: param => valor.");

			//instance
			$dao = DAO::getInstance();
			$stmt = $dao->prepare("CALL {$sp_name}");

			//exec
			$dao->beginTransaction();
			// echo $stmt->queryString;
			$stmt->execute();
			$dao->commit();
			$output = $stmt->fetchAll(PDO::FETCH_ASSOC);

			//output
			return array('type'=>true, 'result'=>$output);
		}

		catch(PDOException $ex)
		{
			$dao->rollBack();
			$dao = NULL;            
			
			$message = (debug_mode) ? $ex->GetMessage() : 'Ops... Ocorreu um erro!';
			throw new Exception('ERRO: '.$message);
		}
	}


	/**
	 * Insere registro na tabela.
	 * @param Array $pData (Array deve ser formado por colunas e valores).
	 * @return Array('type'=>bool, ['message'=>string], ['cliente_id'=>intLastInsertId]).
	 */
	public function create($pData)
	{
		try
		{
			//valida
			if(empty($pData) || !is_array($pData)) 
				throw new Exception("O parametro deve ser um array no padrão: coluna => valor.");

			//query
			$tmp_properties = array_keys($pData);
			$columns 		= implode(', ', $tmp_properties);
			$values 		= implode(', :', $tmp_properties);
			$query			= " INSERT INTO {$this->getTableName()} ({$columns})
									 VALUES (:{$values})";
			//instance
			$dao = DAO::getInstance();
			$stmt = $dao->prepare($query);


			//params
			foreach($pData as $column => $value)
			{
				if(is_int($value))
					$paramType = PDO::PARAM_INT;
				else
					$paramType = PDO::PARAM_STR;

				$stmt->bindValue(":{$column}", $value, $paramType);
			}


			//exec
			$dao->beginTransaction();
			// echo $stmt->queryString;
			$stmt->execute();
			$output = $dao->lastInsertId();
			$dao->commit();


			//return
			return array('type'=>true, "{$this->properties[0]}" => $output, 'message' => 'Registro cadastrado com sucesso.');
		}

		catch(PDOException $ex)
		{
			$dao->rollBack();
			$dao = NULL;            
			
			$message = (debug_mode) ? $ex->GetMessage() : 'Ops... Ocorreu um erro!';
			throw new Exception('ERRO: '.$message);
		}
	}


	/**
	 * Remove registro da tabela. Forever!
	 * @param String $pKey   (Ex: cliente_id)
	 * @param String $pValue (Ex: 101)
	 * @return Array('type'=>bool, 'message'=>string).
	 */
	public function delete($pKey, $pValue, $pEver = false)
	{
		try
		{
			//valida
			if(empty($pKey) || empty($pValue)) 
				throw new Exception("Devem ser informados 2 parametros: chave_primaria, valor.");
				

			//query
			if ($pEver)
			{
				$query		= "DELETE FROM 
									{$this->getTableName()}
								WHERE 
									$pKey = :$pKey
								LIMIT 1";
			}
			else
			{
				$query		= "UPDATE 
									{$this->getTableName()}
								  SET
								  	apagado = '1'
								WHERE 
									$pKey = :$pKey
								LIMIT 1";
			}


			//instance    
			$dao = DAO::getInstance();
			$stmt = $dao->prepare($query);


			//params
			if(is_int($pValue))
				$paramType = PDO::PARAM_INT;
			else
				$paramType = PDO::PARAM_STR;

			$stmt->bindValue(":$pKey", $pValue, $paramType);


			//exec
			$dao->beginTransaction();
			// echo $stmt->queryString;
			$output = $stmt->execute();
			$dao->commit();


			//return
			return array('type'=>true, 'message'=> 'Registro removido com sucesso.');
		}

		catch(PDOException $ex)
		{
			$dao->rollBack();
			$dao = NULL;            
			
			$message = (debug_mode) ? $ex->GetMessage() : 'Ops... Ocorreu um erro!';
			throw new Exception('ERRO: '.$message);
		}
	}


	/**
	 * Busca registros na "view da tabela" caso não tenha busca na tabela.
	 * @access public
	 * @param Array $params (Parâmetros para pesquisa Ex: $param['LIKE']['nome'] = 'nome' ou $param['>']['idade'] = 18.) 
	 *                      (Também pode conter a chave where. Ex: $param['where'] = 'nome like = joao%' )
	 * @return Array('type'=>bool, 'result'=>objscts);
	 */
	public function select($params = null, $order = false, $limit = false)
	{
		try
		{
			// where
			if ($params['where'])
			{
				$where = 'WHERE '.$params['where'];
			}
			else if(count($params))
			{
				$where  = 'WHERE ';

				foreach($params as $tipoWhere => $param)
				{
					foreach($param as $key => $value)
					{
						if ($i++ > 0)
							$where .= " AND ";

						$where .= "{$key} {$tipoWhere} :{$key}";
					}
				}
			}
			else
			{
				$where = '';
			}

			// order
			if($order && !empty($order))
			{
				$order = "ORDER BY {$order}";
			}
			else
			{
				$order = 'ORDER BY '.$this->properties[0].' ASC';
			}

			// limit
			if($limit && !empty($limit))
			{
				$limit = "LIMIT {$limit}";
			}

			//query
			$apagado 	= ($this->return_apagado ? "" : (empty($where) ? "WHERE" : 'AND')." apagado = '0'");
			$columns 	= implode(', ', $this->properties);
			$query		= "SELECT 
								{$columns} 
							 FROM 
							 	{$this->getTableName()} 
								{$where} 
								{$apagado} 
								{$order} 
								{$limit}";

			// instance
			$dao    = DAO::getInstance();
			$stmt   = $dao->prepare($query);

			// params
			if(count($params) && empty($params['where']))
			{
				foreach($params as $tipoWhere => $param)
				{
					foreach($param as $key => $value)
					{
						if(is_int($value))
							$paramType = PDO::PARAM_INT;
						else
							$paramType = PDO::PARAM_STR;

						$stmt->bindValue(":{$key}", $value, $paramType);
					}
				}
			}

			$dao->beginTransaction();
			// echo $stmt->queryString;
			$stmt->execute();
			$dao->commit();
			$output = $stmt->fetchAll(PDO::FETCH_ASSOC);

			// output
			return array('type'=>true, 'result'=>$output);
		}

		catch(PDOException $ex)
		{
			$dao->rollBack();
			$dao = NULL;
			
			
			$message = (debug_mode) ? $ex->GetMessage() : 'Ops... Ocorreu um erro!';
			throw new Exception('ERRO: '.$message);
		}
	}


	/**
	 * Retorna um unico registro da tebela.
	 * @param String $pKey   (Ex: cliente_id or $array[cliente_id, nome])
	 * @param String $pValue (Ex: 101 or $array[101, 'Rodrigo'])
	 * @return Array(type=>bool, result=>values).
	 */
	public function selectOne($pKey, $pValue)
	{
		try
		{
			//valida
			if(empty($pKey) || empty($pValue)) 
				throw new Exception("Devem ser informados 2 parametros: chave_primaria, valor.");
			
			$keyValues = array();
			if( is_array($pKey) && is_array($pValue) )
			{
				$clauseWhere = '';
				foreach($pKey as $index => $actualKey){
					
					if( !empty($clauseWhere) )
						$clauseWhere .= ' AND ';
					
					$clauseWhere .= "{$actualKey} = :{$actualKey}";
					$keyValues[$actualKey] = $pValue[$index];
					
				}
			}
			else
			{
				$clauseWhere = "{$pKey} = :{$pKey} {$apagado}";
			}
				
			//query
			$apagado 	= ($this->return_apagado) ? "" : " AND apagado = '0'";
			$columns 	= implode(', ', $this->properties);
			$query		= "SELECT {$columns} 
							 FROM {$this->getTableName()} 
							WHERE {$clauseWhere} {$apagado}
							LIMIT 1";
							
			//instance    
			$dao = DAO::getInstance();
			$stmt = $dao->prepare($query);
			
			if( !empty($keyValues) ) 
			{
				foreach( $keyValues as $keyName => $keyValue )
				{
					$stmt->bindValue(":{$keyName}", $keyValue, $this->getParamType($keyValue));	
				}
			}
			else
			{
				$stmt->bindValue(":{$pKey}", $pValue, $this->getParamType($pValue));
			}

			//exec
			$dao->beginTransaction();
			//echo $stmt->queryString;
			$stmt->execute();
			$dao->commit();

			//return
			$output = $stmt->fetch(PDO::FETCH_ASSOC);

			return array('type'=>true, 'result'=>$output);
		}

		catch(PDOException $ex)
		{
			$dao->rollBack();
			$dao = NULL;            
			
			$message = (debug_mode) ? $ex->GetMessage() : 'Ops... Ocorreu um erro!';
			throw new Exception('ERRO: '.$message);
		}
	}
	
	
	private function getParamType($value)
	{
		//params
		if(is_int($value))	
			$paramType = PDO::PARAM_INT;
		else
			$paramType = PDO::PARAM_STR;
		
		return $paramType;	
	}


	public function getTableName()
	{
		return $this->table_name;
	}


	/**
	 * Atualiza registro na tabela.
	 * @param Array $pData (Array deve ser formado por colunas e valores.)
	 * @return Array('type'=>bool, 'message'=>string).
	 */
	public function update($pData)
	{
		try
		{
			//valida
			if(empty($pData) || !is_array($pData)) 
				throw new Exception("O parametro deve ser um array no padrão: coluna => valor.");

			//query
			$tmp_properties = array_keys($pData);
			$primaryKey 	= $this->properties[0];
			unset($tmp_properties[array_search($primaryKey, $tmp_properties)]);
			$sets 			= null;

			foreach ($tmp_properties as $property)
			{
				if(array_search($property, $this->properties) != false)
					$sets .= ''.$property.' = :'.$property.', ';
				else
					throw new Exception('Propriedade "'.$property.'" não encontrada no objeto: '.get_class($this).' (Model).');
			}

			$sets = substr($sets, 0, -2);
			$query			= " UPDATE {$this->getTableName()} 
								   SET $sets
								 WHERE {$primaryKey} = :{$primaryKey}";


			//instance    
			$dao = DAO::getInstance();
			$stmt = $dao->prepare($query);


			//params
			foreach($pData as $column => $value)
			{
				if(is_int($value) || $column == 'ativo' || $column == 'apagado')
				{
					$paramType = PDO::PARAM_INT;
					$value = (int)$value;
				}
				else
					$paramType = PDO::PARAM_STR;

				$stmt->bindValue(":{$column}", $value, $paramType);
			}


			//exec
			$dao->beginTransaction();
			// echo $stmt->queryString;
			$output = $stmt->execute();
			$dao->commit();


			//return
			return array('type'=>true, 'message'=>'Registro atualizado com sucesso.');
		}

		catch(PDOException $ex)
		{
			$dao->rollBack();
			$dao = NULL;            
			
			$message = (debug_mode) ? $ex->GetMessage() : 'Ops... Ocorreu um erro!';
			throw new Exception('ERRO: '.$message);
		}
	}


	public function querySelect($comando){
		try{
			// instance
			$dao    = DAO::getInstance();
			$stmt   = $dao->prepare($comando);
			$dao->beginTransaction();
			$stmt->execute();
			$dao->commit();
			$output = $stmt->fetchAll(PDO::FETCH_ASSOC);

			//return
			return array('type'=>true, 'result'=>$output);
		}

		catch(PDOException $ex)
		{
			$dao->rollBack();
			$dao = NULL;

			$message = (debug_mode) ? $ex->GetMessage() : 'Ops... Ocorreu um erro!';
			throw new Exception('ERRO: '.$message);
		}
	}


	/**
	 * Retorna valor de alguma propriedade.
	 * @param  String $property (Ex: nome)
	 * @return String.
	 */
	public function __get($property)
	{
		if($this->properties[$property])
		{
			return $this->properties[$property];
		}
		return 'Propriedade não definida.';
	}


	/**
	 * Atribui valor para alguma propriedade.
	 * @param String $property (Ex: nome)
	 * @param String $value    (Ex: joao)
	 */
	public function __set($property, $value)
	{
		$this->properties[$property] = $value;
	}

}



// EXEMPLOS
	// class ClienteModel extends BaseModel
	// {
	// 	public $properties = array('cliente_id', 'nome_fantasia', 'razao_social', 'cnpj', 'inscricao_estadual', 'telefone', 'site', 'representante_responsavel', 'data_criacao');
		
	// 	// no methods
	// }



// SELECT ONE
	// $objCliente = new ClienteModel();
	// $out = $objCliente->select('cliente_id', 1);
	// print_r($out);



// CREATE
	// $data['cliente_id']                 = '';
	// $data['nome_fantasia']              = 'Nome Fantasia';
	// $data['razao_social']               = 'Razao Social Ltda.';
	// $data['cnpj']                       = '111.222.333/0001-44';
	// $data['inscricao_estadual']         = 'isento';
	// $data['telefone']                   = '(00) 1234 5678';
	// $data['site']                       = 'www.site.com.br';
	// $data['representante_responsavel']  = 'Representante Responsavel';
	// $data['data_criacao']  				= date('Y-m-d H:i:s');

	// $obj = new ClienteModel();
	// $out = $obj->create($data);
	// print_r($out);


// UPDATE
	// $data = array('cliente_id' => '2', 'nome_fantasia' => 'Willianson Araújo');
	// $data['cliente_id']                 = '2';
	// $data['nome_fantasia']              = 'Nome Fantasia Atualizado';
	// $data['razao_social']               = 'Razao Social Atualizada Ltda.';
	// $data['cnpj']                       = '100.200.300/0001-40';
	// $data['inscricao_estadual']         = '';
	// $data['telefone']                   = '(19) 3232 1010';
	// $data['site']                       = 'www.cleans.com.br';
	// $data['representante_responsavel']  = 'Representante Responsavel Atualizado';
	// $obj = new ClienteModel();
	// $out = $obj->update($data);
	// print_r($out);


// DELETE
	// $obj 	= new ClienteModel();
	// $output = $obj->delete('cliente_id', 4);
	// print_r($output);


// SELECT
	// $params['LIKE']['nome_fantasia'] = '%empresa%';
	// $obj 	= new ClienteModel();
	// $output = $obj->find(null, 'nome_fantasia ASC');
	// print_r($output);
	
	// $params['where'] = 'nome_fantasia like "'.$pesquisa.'%" OR razao_social like "'.$pesquisa.'%" OR site like "%'.$pesquisa.'%"';
	// $obj 	= new ClienteModel();
	// $output = $obj->find(null, 'nome_fantasia ASC');
	// print_r($output);