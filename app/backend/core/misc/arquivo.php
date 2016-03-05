<?php


class ManipularArquivo
{

    public $dirTmp; //Diretorio de onde sera copiado o arquivo apartir da raiz
    public $dir;    //Diretorio para onde sera enviado o arquivo apartir da raiz
    public $arq;    //Arquivo para apagar
    public $nome;   //Nome do arquivo

    public $tipo_foto   = array('.jpg', '.png', '.gif');                    //Tipos permitidos
    public $tipo_texto  = array('.doc', '.docx', '.pdf', '.txt', '.rtf');   //Tipos permitidos
    public $tipo_flash  = array('.swf');                                    //Tipos permitidos
	public $tipo_all	= '*';
    public $tipos;
    

    public function SetArquivo($array)
    {
        
        $this->dir		= $this->FormarDir($array['dir']);
        $this->dirTmp   = $this->FormarDir($array['dirTmp']);
        $this->arq      = $array['arq'];
        $this->nome     = $array['nome'];
    }
	
	
	public function FormarDir($dir)
	{
		$firstChar	= substr($dir, 0, 1);
		$lastChar	= substr($dir, -1);
		
		$newDir		= $dir;
		$newDir		= ( $lastChar == '/' ) ? substr($newDir, 0, -1) : $newDir;
		$newDir		= ( $firstChar == '/' ) ? $newDir : '/'.$newDir;
		
		return raiz.$newDir;
	}
    
    
    public function MoveArquivo($prefixo = null, $sufixo = null, $tipo = 'foto')
    {
        $tipo = 'tipo_'.$tipo;
        $this->tipos = $this->$tipo;
        
        $this->CriaDiretorios();
        
        if(rename($this->dirTmp.'/'.$prefixo.$this->arq.$sufixo, $this->dir.'/'.$prefixo.$this->arq.$sufixo))
            $retorno = array(true, $this->arq);
        else
            $retorno = array(false, "Não foi possível copiar o arquivo!");

        return $retorno;
    }
    
    
    public function EnviaArquivo($tipo = 'foto')
    {
        $tipo = 'tipo_'.$tipo;
        $this->tipos = $this->$tipo;
        
        $this->CriaDiretorios();
        
        $arqTmpNome  = $this->arq['tmp_name'];  //Caminho completo da imagem
        $arqNome     = $this->arq['name'];      //Nome do arquivo com extensao
        $arqTamanho  = $this->arq['size'];      //Tamanho do arquivo
		
        $extensao    = strtolower(substr($arqNome, strrpos($arqNome, '.')));
		$nomeArquivo = (empty($this->nome) ? time() : $this->nome);
		$arquivo	 = $nomeArquivo.$extensao;
		
		if(is_file($this->dir.'/'.$arquivo))
			$arquivo = $nomeArquivo.'-'.time().$extensao;

        if($arqTamanho > 0)
        {
            if(in_array($extensao, $this->tipos) || $this->tipos = '*')
            {			
                if(move_uploaded_file($arqTmpNome, $this->dir.'/'.$arquivo) )
                    $retorno = array(true, $arquivo);
                else
                    $retorno = array(false, "Não foi possível enviar o arquivo!");
            }
            else
                $retorno = array(false, "Tipo de arquivo inválido!");
        }
        else
            $retorno = array(false, "Selecione um arquivo para enviar");

        return $retorno;
    }
    
    /**
     * Método para criar estrutura de pastas para anuncio
     * @access public
     * @return void
     */
    public function CriaDiretorios()
    {
        
        if(!is_dir($this->dir))
            mkdir($this->dir, 0775);  
    }
    
    public function ExcluiArquivo($pasta, $arquivo)
    {
		$novaPasta = $this->FormarDir($pasta);
		
        if(is_file($novaPasta.'/'.$arquivo))
            $del = @unlink($novaPasta.'/'.$arquivo);
        else
            $del = false;

        return $del;
    }
    
}

?>