<?php

class Imagem
{

    public $img;        //$_FILE[img]
    public $dir;        //dir_nome destino
    public $nome;       //dir_nome da foto original
    public $largura;    //largura para redimensionar
    public $altura;     //altura para redimensionar

    private $tipos      = array('.jpg', '.jpeg', '.gif', '.png');    //tipos permitidos
    private $qualidade  = 95;                               //qualidade do arquivo


    public function SetImagem($array)
    {
        $this->dir      = $array['dir'];
        $this->img      = $array['img'];
        $this->nome     = $array['nome'];
        $this->altura   = $array['altura'];
        $this->largura  = $array['largura'];
    }


    // TODO: verify if target path exists, if not create it.
    public function EnviaImagem()
    {
        $imgTmpNome  = $this->img['tmp_name'];  //caminho completo da imagem
        $imgNome     = $this->img['name'];      //nome do arquivo com extensao
        $imgTamanho  = $this->img['size'];      //tamanho do arquivo ()

        $extensao    = strtolower(substr($imgNome, strrpos($imgNome, '.')));
        $imagem      = time().$extensao;

        // $this->img['tmp_name']  = $this->dir.'/'.$imagem;
        // $this->img['name']      = $imagem;

        if($imgTamanho > 0)
        {
            if(in_array($extensao, $this->tipos))
            {
                if(copy($imgTmpNome, $this->dir.'/'.$imagem))
                    $retorno = array(true, $imagem);
                else
                    $retorno = array(false, "Não foi possível enviar a imagem!");
            }
            else
                $retorno = array(false, "Tipo de arquivo inválido!");
        }
        else
            $retorno = array(false, "Selecione uma imagem para enviar");

        return $retorno;
    }


    public function Redimensiona($corta = false, $x = 0, $y = 0, $largura = 0, $altura = 0, $proporcional = true, $marca = false) //método para redimensionar imagens
    {
        // $imgTmpNome  = $this->img['tmp_name'];  //caminho completo da imagem
        // $imgNome     = $this->img['name'];      //nome do arquivo com extensao
        // $imgTamanho  = $this->img['size'];      //tamanho do arquivo ()

        $extensao           = strtolower(substr($this->nome, strrpos($this->nome, '.')));
        $imagemOrig         = $this->nome;
        $imagemOrigTamanho  = filesize($imagemOrig);

        $imgNovoNome = $this->dir;
        // $imgNovoNome = $this->dir.'/'.$this->nome.'.jpg';

        if($imagemOrigTamanho > 0)
        {
            if(in_array($extensao, $this->tipos))
            {
                if(copy($imagemOrig, $imgNovoNome) || $imagemOrig && $imgNovoNome)
                {
                    if($proporcional)
                    {
                        $tamanho = getimagesize($imagemOrig);
                        if($tamanho[1] > $this->altura || $tamanho[0] > $this->largura)
                        {
                            //w > h
                            if ($tamanho[0] > $tamanho[1])
                            {
                                $w = $this->largura;
                                $h = ($w / $tamanho[0]) * $tamanho[1];

                                if($h > $this->altura)
                                {
                                    $h = $this->altura;
                                    $w = ($h / $tamanho[1]) * $tamanho[0];
                                }
                            }
                            //h > w
                            else
                            {
                                $h = $this->altura;
                                $w = ($h / $tamanho[1]) * $tamanho[0]; 
                            }


                            if($corta && empty($x) && empty($y) && ($h < $this->altura || $w < $this->largura))
                            {
                                $x = 0;
                                $y = 0;
                                if($h < $this->altura)
                                {
                                    $h = $this->altura;
                                    $w = ceil($tamanho[0] * $h / $tamanho[1]);
                                    
                                    $meiop  = ($w - $this->largura)/2;
                                    $pct    = ($meiop * 100 / $w)/100;
                                    $x      = ($tamanho[0] * $pct);
                                    
                                    $tamanho[0] -= $x*2;
                                }
                                elseif($w < $this->largura)
                                {
                                    $w = $this->largura;
                                    $h = ceil($tamanho[1] * $w / $tamanho[0]);
                                    
                                    $meiop  = ($h - $this->altura)/2;
                                    $pct    = ($meiop * 100 / $h)/100;
                                    $y      = ($tamanho[1] * $pct);
                                    
                                    $tamanho[1] -= $y*2;
                                }
                                $w = $this->largura;
                                $h = $this->altura;
                            }
                            
                        }
                        else
                        {
                            $h = $tamanho[1];
                            $w = $tamanho[0];
                        }
                    }
                    else
                    {
                        $tamanho[1] = $altura;
                        $tamanho[0] = $largura;
                        $w          = $this->largura;
                        $h          = $this->altura;
                    }

                    $imgNova = imagecreatetruecolor($w, $h);
                    switch ($extensao)
                    {
                        case '.jpeg' :
                        case '.jpg' : $img = imagecreatefromjpeg($imagemOrig);  break;
                        case '.gif' : $img = imagecreatefromgif($imagemOrig);   break;
                        case '.png' : $img = imagecreatefrompng($imagemOrig);   break;
                    }

                    imagecopyresampled($imgNova, $img, 0, 0, $x, $y, $w, $h, $tamanho[0], $tamanho[1]);
                    // imagecopyresampled($imgNova, $img, 0, 0, $x, $y, $w, $h, $tamanho[0], $tamanho[1]);


                    if($marca)
                    {
                        $tamMarca[0]    = 10;                                                   //Largura da imagem para marca d'agua
                        $tamMarca[1]    = $this->altura;                                        //Altura da imagem para marca d'agua
                        $corMarca       = imagecolorallocatealpha($imgNova, 36, 22, 11, 50);    //Define uma cor em RGB + porcentagem de transparência (30%)
                        $xMarca         = ($this->largura - $tamMarca[0]);                      //Define posicao x onde sera inseria a marca d'agua
                        $yMarca         = ($this->altura - $tamMarca[1]);                       //Define posicao y onde sera inseria a marca d'agua
                        imagefilledrectangle($imgNova, $xMarca, $yMarca, $this->largura, $this->altura, $corMarca);
                    }

                    switch ($extensao)
                    {
                        case '.jpeg':
                        case '.jpg':
                            $output = imagejpeg($imgNova, $imgNovoNome, $this->qualidade);
                            break;

                        case '.gif':
                            $output = imagegif($imgNova, $imgNovoNome);
                            break;

                        case '.png':
                            $output = imagepng($imgNova, $imgNovoNome);
                            break;
                    }
                    // imagedestroy($imgNova);
                    // imagedestroy($img);
                    // unlink($imagemOrig);

                    $retorno = array(true, $this->nome);
                }
                else
                    $retorno = array(false, "Não foi possível enviar a imagem!");
            }
            else
                $retorno = array(false, "Tipo de arquivo inválido!");
        }
        else
            $retorno = array(false, "Selecione uma imagem para enviar");

        return $retorno;
    }


    public function Excluir() //método para excluir imagens
    {
        if (file_exists('../'.$this->dir.'/'.$this->img) && is_file('../'.$this->dir.'/'.$this->img))
        {
            $fotoG = @unlink('../'.$this->dir.'/'.$this->img);
            $fotoP = @unlink('../'.$this->dir.'/mini/'.$this->img);
            return ($fotoG && $fotoP ? true : false);
        }
        else
            return array(false, 'Arquivo não encontrado!');
    }

}

/*
$thumb['nome']        = '../teste.jpg';
$thumb['dir']         = '../_view';
$thumb['largura']     = 180;
$thumb['altura']      = 180;

$obj = new Imagem;
$obj->SetImagem($thumb);
$redimensiona = $obj->Redimensiona(true);
 */
?>

