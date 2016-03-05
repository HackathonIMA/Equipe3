<?php


class Common
{

    public static $estados = array("AC"=>"Acre", "AL"=>"Alagoas", "AM"=>"Amazonas", "AP"=>"Amapá","BA"=>"Bahia","CE"=>"Ceará","DF"=>"Distrito Federal","ES"=>"Espírito Santo","GO"=>"Goiás","MA"=>"Maranhão","MT"=>"Mato Grosso","MS"=>"Mato Grosso do Sul","MG"=>"Minas Gerais","PA"=>"Pará","PB"=>"Paraíba","PR"=>"Paraná","PE"=>"Pernambuco","PI"=>"Piauí","RJ"=>"Rio de Janeiro","RN"=>"Rio Grande do Norte","RO"=>"Rondônia","RS"=>"Rio Grande do Sul","RR"=>"Roraima","SC"=>"Santa Catarina","SE"=>"Sergipe","SP"=>"São Paulo","TO"=>"Tocantins");


	public static function convertArrayToOptions($array, $value_key = null, $label_key = null, $selected_value_key = null)
	{
		$categoria_options = '';
		foreach($array as $item)
		{
			if($item[$value_key] == $selected_value_key)
			{
				$selected = 'selected';
			}

			$categoria_options .= '<option value="'.$item[$value_key].'" '.$selected.'>'.$item[$label_key].'</option>';
			unset($selected);
		}

		return $categoria_options;
	}


	//TODO: rename method
	public static function setHora($hora)
	{
		return date_format(date_create($hora),'H:i');
	}


	//TODO: rename method
	public static function getHora($hora)
	{
		return date_format(date_create($hora),'H:i');
	}


	public static function nomeDoDia($timestamp)
    {
        $diaSemana  = array('sunday'    => 'domingo',
                            'monday'    => 'segunda-feira',
                            'tuesday'   => 'terca-feira',
                            'wednesday' => 'quarta-feira',
                            'thursday'  => 'quinta-feira',
                            'friday'    => 'sexta-feira' ,
                            'saturday'  => 'sábado');

        return $diaSemana[date('l', $timestamp)];
    }
    

    public static function nomeDoMes($timestamp)
    {
        $nomeMes    = array('January'   => 'janeiro',
                            'February'  => 'fevereiro',
                            'March'     => 'março',
                            'April'     => 'abril',
                            'May'       => 'maio',
                            'June'      => 'junho',
                            'July'      => 'julho',
                            'August'    => 'agosto',
                            'September' => 'setembro',
                            'October'   => 'outubro',
                            'November'  => 'novembro',
                            'December'  => 'dezembro');

        return $nomeMes[date('F', $timestamp)];
    }


	public static function convertDataBrToUs($data)
	{
		return date_format(date_create(str_replace('/','-',$data)),'Y-m-d');
	}


	public static function convertDataUsToBr($data)
	{
		return date_format(date_create($data), 'd/m/Y');
	}


	public static function convertEstadosToOptions($selected_sigla = null)
    {
        $template   = '<option value="{sigla}" {selected}>{nome}</option>';
        $output     = '';

        foreach (Common::$estados as $sigla => $nome)
        {
           	$selected = ($sigla == $selected_sigla) ? 'selected="selected"' : '';

            $output .= str_replace( array('{sigla}', '{nome}', '{selected}'), array($sigla, $nome, $selected), $template);
        }

        return $output;
    }


	public static function convertHexToRgb($hex)
	{
		$hex = str_replace("#", "", $hex);

		if(strlen($hex) == 3)
		{
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
		}
		else
		{
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
		}

		$rgb = array($r, $g, $b);
		
		// returns the rgb values separated by commas
		return 'rgb('.implode(",", $rgb).')';

		// returns an array with the rgb values
		// return $rgb; 
	}


	public static function convertValueBrToUs($value_br)
	{
		if(!empty($value_br))
		{
			$value_br = str_replace(array('.', ','),array('', '.'),$value_br);
		}

		return $value_br;
	}


	public static function convertValueUstoBr($value_us)
	{
		if(!empty($value_us))
		{
			$value_us = number_format($value_us,2,',','.');
		}

		return $value_us;
	}

}