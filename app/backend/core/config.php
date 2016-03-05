<?php


/* Header
-----------------------------------------------------------------------------------------*/
ob_start();
session_start();
header('Content-type:text/html; charset=UTF-8');
date_default_timezone_set('America/Sao_Paulo');




/* Debug (local, online)
-----------------------------------------------------------------------------------------*/
$debug_mode = 'local';




/* Mode
-----------------------------------------------------------------------------------------*/
switch ($debug_mode)
{
 	// Local
 	case 'local':
		$app_version = time();
		error_reporting(E_ALL ^ E_NOTICE);

		define('HOSTNAME', 'localhost');
		define('DATABASE', 'aluno_ativo');
		define('USERNAME', 'root');
		define('PASSWORD',  'teste123');
 		break;

 	// Online
 	case 'online':
		$app_version = 1.0;
		error_reporting(0);

		define('HOSTNAME', 'localhost');
		define('DATABASE', '_db');
		define('USERNAME', '_user');
		define('PASSWORD', '');
 		break;

 	default:
 		die('ERRO: Debug mode desconhecido no arquivo "config.php".');
}




/* Constantes
-----------------------------------------------------------------------------------------*/
define('APP_VERSION' 		, $app_version);
define('APP_HOST' 			, "http://{$_SERVER['SERVER_NAME']}");
define('CURRENT_URL' 		, "http://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}");
define('DOCUMENT_ROOT'  	, "{$_SERVER['DOCUMENT_ROOT']}");
define('VIEW_TEMPLATES' 	, DOCUMENT_ROOT.'_views/_templates/');
define('IMA_API_BASE_URL', 'http://api.ima.sp.gov.br/v1');


?>
