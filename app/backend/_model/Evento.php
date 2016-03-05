<?php


class Evento extends Model
{

	public $table_name = 'eventos';
	public $properties = array(
		'evento_id',
		'usuario_id',
		'escola_id',
		'data',
		'titulo',
		'descricao'
	);

}