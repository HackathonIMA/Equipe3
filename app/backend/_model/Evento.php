<?php

class Evento extends Model
{

	public $table_name = 'escolas';
	public $properties = array(
		'evento_id',
    'usuario_id',
    'titulo',
    'datahora',
    'descricao'
	);

}

?>
