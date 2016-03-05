<?php


class Escola extends Model
{
    public $table_name = 'escolas';
    public $properties = array(
        'escola_id',
        'escola_ima_id',
        'nome_unidade_escolar',
        'descricao',
        'endereco_completo',
				// API IMA
        'descricao_regiao',
        'nome_unidade_escolar',
        'descricao',
        'endereco_logradouro',
        'endereco_bairro',
        'endereco_municipio',
        'endereco_uf',
        'endereco_cep',
        'demanda',
    );
}
