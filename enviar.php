<?php

	$validos = 0;
	$invalidos = 0;

	function validaemail($email){

		//verifica se e-mail esta no formato correto de escrita
		if(!filter_var($email,FILTER_VALIDATE_EMAIL)) {
			return $mensagem= 'E-mail invalido - sintaxe incorreta';
	    }
	    else{

			//Valida o dominio 
			$dominio=explode('@',$email);

			if(!checkdnsrr($dominio[1],'A')){
				$mensagem= 'E-mail invalido - dominio incorreto';
				return $mensagem;
			}

			// Retorno true para indicar que o e-mail é valido
			else{return 'E-mail valido';}
		}
	}

	function download ($dadosCsv) {

		// Configurações header para forçar o download
		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: application/csv");
		header ("Content-Disposition: attachment; filename=emailsGerados.csv" );
		header ("Content-Description: PHP Generated Data" );

		// Abrimos um arquivo temporário na saída do PHP
		$fp = fopen('php://output', 'wb');

		foreach ($dadosCsv as $linha) {
			$valor = explode(",", $linha);

			// Colocamos dentro do arquivo temporário cada linha em sua coluna
			fputcsv($fp, $valor);
		}

		// Fechamos o arquivo e ele vai baixar em seguida
		fclose($fp);
	}

	if (!empty($_FILES['arquivo']['tmp_name'])){
		$arquivo = new DomDocument();
		$arquivo -> load($_FILES['arquivo']['tmp_name']);
		//$columns = $arquivo ->getElementsByTagName("Row");
		$linhas = $arquivo ->getElementsByTagName("Row");

		foreach ($linhas as $linha) {
			//$cpf = $linha->getElementsByTagName("Data")->item(0)->nodeValue;
			$email = $linha->getElementsByTagName("Data")->item(0)->nodeValue;
			$emailValido = validaemail($email);

			// Adicionamos um novo índice no formato de CSV (separado por vírgula)
			$dadosCsv[] = "$email;$emailValido";
		}
	}

	// Enviamos os dados do CSV para a função
	download($dadosCsv);
?>