<?php


/**
* Classe de Acesso a dados Singleton (Design Pattern)
* @author Clean - Soluções Web <contato@cleans.com.br>
* @version 0.1
* @copyright Copyright © 2011, Clean - Soluções Web.
* @access public
* @package Model
* @subpackage DAO
*/
class DAO {

    /**
    * Configurações do banco de dados
    * @access private
    * @name $hostname Variável recebe o endereço do Banco de Dados.
    * @name $database Variável recebe o nome do Banco de Dados.
    * @name $username Variável recebe o usuário do Banco de Dados.
    * @name $password Variável recebe a senha do Banco de Dados.
    * @name $instance Variável recebe o Objeto PDO.
    */
    
    private static $instance = null;
    private static $hostname = HOSTNAME;
    private static $database = DATABASE;
    private static $username = USERNAME;
    private static $password = PASSWORD;
       
    
    /**
     * Evita que a classe seja instanciada publicamente
     * @return void
     */
    private function __construct(){  }
    
    
    /**
     * Retorna uma instância já criada ou cria uma nova instância do objeto
     * @return PDO[]
     * @access public
     */
    public static function GetInstance(){
        if(!isset(self::$db)){
            try {
                
                $dsn     = 'mysql:host='.self::$hostname.';dbname='.self::$database;
                $options = Array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
                
                self::$instance = new PDO($dsn, self::$username, self::$password, $options);
                
                self::$instance->query('SET NAMES utf8');
                self::$instance->query('SET character_set_connection = utf8');
                self::$instance->query('SET character_set_client = utf8');
                self::$instance->query('SET character_set_results = utf8');
                
            } catch (PDOException $e) {
                
                die( 'ERRO: '.$e->getMessage() );                
            }
        }
        return self::$instance;
    }
    
    
    /**
     * Evita que a classe seja clonada
     * @return void
     */
    private function __clone(){  }
    
}

?>
