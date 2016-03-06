<?php

class Usuario extends Model
{

	public $table_name = 'usuarios';
	public $properties = array(
    'usuario_id',
    'escola_id', // opcional
    'nome_completo',
    'email',
    'endereco_completo',
    'data_nascimento',
    'habilidades' // lista separado por ","
	);

}

?>
