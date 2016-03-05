<?php


class Email
{

	private $destinatarios;
	private $cc;
	private $cco;
	private $sender;            // email@dominio
	private $remetente;
	private $assunto;
	private $arquivo;
	private $conteudo;
	
	
	/**
	 * Método que popula os atributos da classe
	 * @access public
	 * @param Array[destinatarios, assunto, remetente, sender, arquivo, conteudo] $dadosEmail Array contendo dados do email
	 * @return void
	 */
	public function setEmail($dadosEmail)
	{
		$this->destinatarios    = $dadosEmail['destinatarios'];
		$this->cc               = $dadosEmail['cc'];
		$this->cco              = $dadosEmail['cco'];
		$this->sender           = $dadosEmail['sender'];
		$this->remetente        = $dadosEmail['remetente'];
		$this->assunto          = $dadosEmail['assunto'];
		$this->arquivo          = $dadosEmail['arquivo'];
		$this->conteudo         = $dadosEmail['conteudo'];
	}

	
	/**
	 * Método para enviar e-mail sem anexo
	 * @access public
	 * @return Boolean
	 */
	public function enviaEmail()
	{
		$quebra_linha   = "\r\n";
		
		//Cabeçalhos da Mensagem
		$headers  = 'MIME-Version: 1.0'.$quebra_linha;
		$headers .= 'Content-Type: text/html; charset="utf-8"'.$quebra_linha;
		$headers .= 'From: '.$this->sender.$quebra_linha;
		$headers .= 'Reply-To: '.$this->remetente.$quebra_linha;
		$headers .= 'Return-Path: '.$this->destinatarios.$quebra_linha;

		$mensagem = $this->conteudo;

		//Envia e-mail
		$retorno = mail($this->destinatarios, $this->assunto, $mensagem, $headers);

		return $retorno;
	}

	
	/**
	 * Método para enviar e-mail com anexo
	 * @access public
	 * @return Boolean
	 */
	public function enviaEmailAnexo()
	{   
		if(file_exists($this->arquivo["tmp_name"]) && !empty($this->arquivo))
		{
			$mensagem = $this->conteudo;
			
			$fp     = fopen($this->arquivo["tmp_name"], "rb");
			$anexo  = fread($fp, filesize($this->arquivo["tmp_name"]));
			$anexo  = base64_encode($anexo);
			fclose($fp);

			$anexo          = chunk_split($anexo);
			$boundary       = 'XYZ-'.date("dmYis").'-ZYX';
			$quebra_linha   = "\r\n";

			$mens  = '--'.$boundary.$quebra_linha;
			$mens .= 'Content-Transfer-Encoding: 8bits'.$quebra_linha;
			$mens .= 'Content-Type: text/html; charset="utf-8"'.$quebra_linha.$quebra_linha;
			$mens .= $mensagem_cabecalho.$quebra_linha;
			$mens .= $mensagem.$quebra_linha;
			$mens .= '--'.$boundary.$quebra_linha;
			$mens .= 'Content-Type: '.$this->arquivo["type"].$quebra_linha;
			$mens .= 'Content-Disposition: attachment; filename="'.$this->arquivo["name"].'"'.$quebra_linha;
			$mens .= 'Content-Transfer-Encoding: base64'.$quebra_linha.$quebra_linha;
			$mens .= $anexo.$quebra_linha;
			$mens .= '--'.$boundary.'--'.$quebra_linha;

			$headers  = 'MIME-Version: 1.0'.$quebra_linha;
			$headers .= 'From: '.$this->sender.$quebra_linha;
			$headers .= 'Reply-To: '.$this->remetente.$quebra_linha;
			$headers .= 'Return-Path: '.$this->destinatarios.$quebra_linha;
			$headers .= 'Content-type: multipart/mixed; boundary="'.$boundary.'"'.$quebra_linha;
			$headers .= $boundary.$quebra_linha;
			
			//Envia o email com o anexo
			return mail($this->destinatarios, $this->assunto, $mens, $headers);
		}
		else //Caso não tenha anexo
			return $this->EnviaEmail($conteudo);
	}


	/**
	 * Método para enviar e-mail sem anexo pela LOCAWEB.
	 * @access public
	 * @param $plataforma = [windows, linux]
	 * @return Boolean
	 */
	public function enviaEmailLocaweb($plataforma)
	{
		if ($plataforma == 'windows')
			$quebra_linha = "\r\n";
		else
			$quebra_linha = "\n";


		// Medida preventiva para evitar que outros domínios sejam remetente da sua mensagem.
		if(!preg_match('/tempsite.ws$|locaweb.com.br$|hospedagemdesites.ws$|websiteseguro.com$/', $_SERVER[HTTP_HOST]))
			$this->sender = "contato@".$_SERVER[HTTP_HOST];


		// Montando o cabeçalho da mensagem
		$headers  = "MIME-Version: 1.1".$quebra_linha;
		$headers .= "Content-type: text/html; charset=UTF-8".$quebra_linha;
		$headers .= "From: ".$this->sender.$quebra_linha;
		$headers .= "Cc: ".$this->cc.$quebra_linha;
		$headers .= "Bcc: ".$this->cco.$quebra_linha;
		$headers .= "Reply-To: ".$this->remetente.$quebra_linha;


		// Enviando a mensagem
		if(mail($this->destinatarios, $this->assunto, $this->conteudo, $headers, "-r".$this->sender))
		{
			$retorno = true;
		}
		else
		{
			$headers .= "Return-Path: ".$this->sender.$quebra_linha;
			if(mail($this->destinatarios, $this->assunto, $this->conteudo, $headers))
				$retorno = true;
			else
				$retorno = false;
		}


		return $retorno;
	}


	/**
	 * Método para enviar e-mail sem anexo pela LOCAWEB.
	 * @access public
	 * @param $plataforma = [windows, linux]
	 * @return Boolean
	 */
	public function EnviaEmailLocawebAnexo($plataforma)
	{

		if(file_exists($this->arquivo["tmp_name"]) && !empty($this->arquivo))
		{

			if ($plataforma == 'windows')
				$quebra_linha = "\r\n";
			else
				$quebra_linha = "\n";


			// Medida preventiva para evitar que outros domínios sejam remetente da sua mensagem.
			if(!preg_match('/tempsite.ws$|locaweb.com.br$|hospedagemdesites.ws$|websiteseguro.com$/', $_SERVER[HTTP_HOST]))
				$this->sender = "contato@".$_SERVER[HTTP_HOST];


			// Anexando arquivo
			$fp         = fopen($this->arquivo["tmp_name"], "rb");
			$anexo      = fread($fp, filesize($this->arquivo["tmp_name"]));
			$anexo      = base64_encode($anexo);
			fclose($fp);
			$anexo      = chunk_split($anexo);
			$boundary   = 'XYZ-'.date("dmYis").'-ZYX';
			$mensagem_temp  = '--'.$boundary.$quebra_linha;
			$mensagem_temp .= 'Content-Transfer-Encoding: 8bits'.$quebra_linha;
			$mensagem_temp .= 'Content-Type: text/html; charset="utf-8"'.$quebra_linha.$quebra_linha;
			$mensagem_temp .= $this->conteudo.$quebra_linha;
			$mensagem_temp .= '--'.$boundary.$quebra_linha;
			$mensagem_temp .= 'Content-Type: '.$this->arquivo["type"].$quebra_linha;
			$mensagem_temp .= 'Content-Disposition: attachment; filename="'.$this->arquivo["name"].'"'.$quebra_linha;
			$mensagem_temp .= 'Content-Transfer-Encoding: base64'.$quebra_linha.$quebra_linha;
			$mensagem_temp .= $anexo.$quebra_linha;
			$mensagem_temp .= '--'.$boundary.'--'.$quebra_linha;

			//$arquivo = isset($_FILES["arquivo"]) ? $_FILES["arquivo"] : FALSE; 
			
			// $fp = fopen($_FILES["arquivo"]["tmp_name"],"rb"); 
			// $anexo = fread($fp,filesize($_FILES["arquivo"]["tmp_name"])); 
			// $anexo = base64_encode($anexo); 
			 
			// fclose($fp); 
			 
			// $anexo = chunk_split($anexo); 
			 
			 
			// $boundary = "XYZ-" . date("dmYis") . "-ZYX"; 
			 
			// $mens = "--$boundary" . $quebra_linha . ""; 
			// $mens .= "Content-Transfer-Encoding: 8bits" . $quebra_linha . ""; 
			// $mens .= "Content-Type: text/html; charset=\"ISO-8859-1\"" . $quebra_linha . "" . $quebra_linha . ""; //plain 
			// $mens .= "$mensagem" . $quebra_linha . ""; 
			// $mens .= "--$boundary" . $quebra_linha . ""; 
			// $mens .= "Content-Type: ".$arquivo["type"]."" . $quebra_linha . ""; 
			// $mens .= "Content-Disposition: attachment; filename=\"".$arquivo["name"]."\"" . $quebra_linha . ""; 
			// $mens .= "Content-Transfer-Encoding: base64" . $quebra_linha . "" . $quebra_linha . ""; 
			// $mens .= "$anexo" . $quebra_linha . ""; 
			// $mens .= "--$boundary--" . $quebra_linha . ""; 
			 
			// $headers = "MIME-Version: 1.0" . $quebra_linha . ""; 
			// $headers .= "From: $email_from " . $quebra_linha . ""; 
			// $headers .= "Return-Path: $email_from " . $quebra_linha . ""; 
			// $headers .= "Content-type: multipart/mixed; boundary=\"$boundary\"" . $quebra_linha . ""; 
			// $headers .= "$boundary" . $quebra_linha . ""; 


			// atribuindo conteudo com anexo
			$this->conteudo = $mensagem_temp;


			// Montando o cabeçalho da mensagem
			$headers  = "MIME-Version: 1.1".$quebra_linha;
			$headers .= 'Content-type: multipart/mixed; boundary="'.$boundary.'"'.$quebra_linha;
			$headers .= "From: ".$this->sender.$quebra_linha;
			$headers .= "Cc: ".$comcopia.$quebra_linha;
			$headers .= "Bcc: ".$comcopiaoculta.$quebra_linha;
			$headers .= "Reply-To: ".$this->remetente.$quebra_linha;


			// Enviando a mensagem com anexo
			if(mail($this->destinatarios, $this->assunto, $this->conteudo, $headers, "-r".$this->sender))
			{
				$retorno = true;
			}
			else
			{
				$headers .= "Return-Path: ".$this->sender.$quebra_linha;
				if(mail($this->destinatarios, $this->assunto, $this->conteudo, $headers))
					$retorno = true;
				else
					$retorno = false;
			}


			return $retorno;

		}
		else
			return $this->EnviaEmailLocaweb('linux');
	}

}


/*

	EXAMPLE:

		$data['destinatarios']    = '';
		$data['cc']               = '';
		$data['cco']              = '';
		$data['sender']           = '';
		$data['remetente']        = '';
		$data['assunto']          = '';
		$data['arquivo']          = '';
		$data['conteudo']         = '';

		$objEmail = new Email();
		$objEmail->setEmail($data);
		
		$output = $objEmail->enviarEmail();

*/
?>