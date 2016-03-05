<?php

// include_once('../_model/app/Imagem.class.php');

/**
 * Classe Crop
 * @author Clean - Soluções Web <contato@cleans.com.br>
 * @version 1.0
 * @copyright Copyright © 2011, Clean - Soluções Web <contato@cleans.com.br>
 * @access public
 * @package Model
 * @package View
 * @package Controller
 */
class Crop
{
    
    public $fotoCrop;
    public $x;
    public $y;
    public $w;
    public $h;

    public $larguraG = 752;
    public $alturaG  = 177;

    public $larguraP = 320;
    public $alturaP  = 105;

    public $larguraExibicao = 838;
    public $alturaExibicao = 838;
    
    private $dir;
    private $dirTemp;
    
    
    /**
     * Método para setar atributos
     * @access public
     * @param Array[]
     * @return void
     */
    public function SetCrop($dadosCrop)
    {
         $this->dir             = $dadosCrop['dir'];
         $this->dirTemp         = $dadosCrop['dirTemp'];
         $this->larguraG        = $dadosCrop['larguraG'];
         $this->alturaG         = $dadosCrop['alturaG'];
         $this->larguraP        = $dadosCrop['larguraP'];
         $this->alturaP         = $dadosCrop['alturaP'];
         $this->fotoCrop        = $dadosCrop['filename'];
         $this->larguraExibicao = $dadosCrop['larguraExibicao'];
         $this->alturaExibicao  = $dadosCrop['alturaExibicao'];
         $this->x               = $dadosCrop['x'];
         $this->y               = $dadosCrop['y'];
         $this->h               = $dadosCrop['h'];
         $this->w               = $dadosCrop['w'];
    }
    
    /**
     * Método para setar atributos $array[0] = pasta e $array[1] = foto
     * @access public
     * @param Array[]
     * @return void
     */
    private function SetFoto($array)
    {
        $this->foto['tmp_name'] = '../'.$array[0].'/'.$array[1];
        $this->foto['size']     = filesize($this->foto['tmp_name']);
        $this->foto['name']     = $array[1];
    }
    
    
    /**
     * Método para criar estrutura de pastas para anuncio
     * @access public
     * @return void
     */
    public function CriaDiretorios()
    {
        
        if(!is_dir('../'.$this->dir))
            mkdir('../'.$this->dir, 0775);
        if(!is_dir('../'.$this->dirTemp))
            mkdir('../'.$this->dirTemp, 0775);
        
    }
    
    
    /**
     * Método para criar imagem
     * @access public
     * @return Array[]
     */
    public function CriaImagem()
    {
        $this->CriaDiretorios();

        $imagem     = new Imagem();
        $tempNome   = time();
        
        //Imagem grande
        $dadosFoto[0] = $this->dirTemp;
        $dadosFoto[1] = $this->fotoCrop;
        
        $this->SetFoto($dadosFoto);
        $this->CalculaTamanhoReal();
        
        $img['img']     = $this->foto;
        $img['nome']    = 'g-'.$tempNome;
        $img['dir']     = $this->dir.'/';
        $img['largura'] = $this->larguraG;
        $img['altura']  = $this->alturaG;
        
        $imagem->SetImagem($img);
        $imgG = $imagem->Redimensiona(false, $this->x, $this->y, $this->w, $this->h, false);
        
        @unlink($this->foto['tmp_name']);

        //Imagem pequena
        $dadosFoto[0] = $this->dir;
        $dadosFoto[1] = $imgG[1];
        
        $this->SetFoto($dadosFoto);
        
        $img['img']     = $this->foto;
        $img['nome']    = 'p-'.$tempNome;
        $img['dir']     = $this->dir.'/';
        $img['largura'] = $this->larguraP;
        $img['altura']  = $this->alturaP;
        
        $imagem->SetImagem($img);
        $imgP = $imagem->Redimensiona(true);
        
        $retorno = ($imgP[0] ? array(true, $tempNome.'.jpg') : array(false, $img[1]));
        
        return $retorno;
    }

    
    /**
     * Método para calcular o tamanho real da imagem
     * @access public
     * @return void
     */
    public function CalculaTamanhoReal()
    {
        $tamanho = getimagesize($this->foto['tmp_name']);
        
        //Tamanhos da imagem no painel proporcional
        $img_w       = $this->larguraExibicao;
        $img_h       = ($tamanho[1] * $img_w) / $tamanho[0];

        //Porcentagem de aumento da largura da imagem no painel
        $pct_w      = ((($tamanho[0] - $img_w) * 100) / $tamanho[0]) / 100;
        $tam_w      = $tamanho[0] * $pct_w;

        //Porcentagem de aumento da altura da imagem no painel
        $pct_h      = ((($tamanho[1] - $img_h) * 100) / $tamanho[1]) / 100;
        $tam_h      = $tamanho[1] * $pct_h;

        //Soma da porcentagem de aumento da imagem em cada um dos pontos e tamanhos selecionados
        $this->w   += $tam_w * ((($this->w * 100) / $img_w) / 100);
        $this->h   += $tam_h * ((($this->h * 100) / $img_h) / 100);
        $this->x   += $tam_w * ((($this->x * 100) / $img_w) / 100);
        $this->y   += $tam_h * ((($this->y * 100) / $img_h) / 100);
    }
    
}

?>
