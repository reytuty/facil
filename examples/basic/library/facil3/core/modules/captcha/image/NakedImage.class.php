<?php 
//inclui a interface
include_once "library/facil3/core/modules/captcha/interface/ImageCaptcha.interface.php";

/**
 * @author Mauricio Amorim
 * @desc essa classe uitliza codigos criptografados pela classe NakedEncrypt
 * 
  *  - essa controler gera codigo para gerar imagem de captcha, uma imagem com 6 caracteres com um codigo passado
 *  segundo as regras e confirmação de validação atravez do codigo da imagem e o campo preenchido no formulario.
 *  
 *  - o codigo a ser passado para gerar a imagem e passar como verificação no compo imput
 *  deve ser a mutiplicação por 2 de seis letras transformadas uma por uma em numeros da tabela ASCII
 *  
 *  - a string da imagem deve ser gerada dividindo o codigo passado por url por 2 e trasformado os
 *  numeros de dois em dois em caracteres atraves da função ord() do php
 *  
 *  - a regra para validação do captcha é pegar os caracteres da imagem um por um e transforma-los
 *  em numeros da tabela ASCII atravez da função ord() do php, multiplicar por 2 e comparar com o codigo 
 *  passado por imput  
 *  
 */
/**
 * @author Renato
 * @date		: 19/01/2011
 * @version		: 1.0
 * @desc		: 
 */
class NakedImage implements ImageCaptcha{
	
    /**
     * @param $captcha_code
     * @return exibição de imagem
     * @desc é necessário passar o codigo gerado pela classe NakedEncrypt
     */
    static function generate($captcha_code){    	
		// Seleciona uma imagem que está na pasta bg/ com o nome 0.jpg à 9.jpg,
		// está imagem que vai ser o fundo da nossa imagem de segurança.
		$backgoundImage = Config::getFolderView("captcha/naked/");
		$backgoundImage .= rand(0,9);
		$backgoundImage .= ".jpg";
		
		//posicionamento de horizontal
		$x = 10;
		//posicionamento de vertical
		$y = 0;
		
		// Separo o codigo de dois em dois numeros e transformo em caracter para formar a string do captcha
		for ($i = 1; $i < strlen($captcha_code)/3 ;$i++) {
			$char = chr($captcha_code[$i].$captcha_code[$i+1]);
			
			// Gera um fontSize para a fonte de 3 à 5.
			$fontSize = rand(3,5);
			
			// Seleciona as color RGB, menos muito clara, pois o fundo é branco, por isso de 0 à 200.
			$sel_colorR = rand(0,200);
			$sel_colorG = rand(0,200);
			$sel_colorB = rand(0,200);
			
			// Joga os caracteres em um determinado lugar da imagem, x e y, sendo x sempre ele mais ele, pra não perder a ordem.
			// Nome que começa em 10 e termina em 30, pois temos 6 caracteres, e nossa imagem tem 180px,
			// por isso que vai ser de 30 em 30. e 10 para não ficar um caractere em cima do outro.
			$x += rand(10,30);
			// o Y vai de 0 a 30, não a 50, pois pode colortar pois nossa imagem é de 50 px de altura.
			$y = rand(0,30);
			
			// Aqui crio a color de cada caractere, RGB.
			$color = imagecolorallocate($image, $sel_colorR, $sel_colorG, $sel_colorB);
			// desenho o lugar dos caracteres de acolordo com as posições x e y.
			imagestring($image, $fontSize, $x, $y, $char, $color);
		}

		// Cria a imagem.
		return $image = imagecreatefromjpeg($backgoundImage);

    }
    
    /**
	 * @param $image imagecreatefromjpeg($backgoundImage)
	 * @desc exibe uma imagem criada pelo comando imagecreatefromjpeg()
	 * @return void
	*/
    static function show($image){
     	//ele informa que isso é um arquivo PNG
		header("Content-type: image/png");
		
		//cria a imagem PNG
		imagepng($image);
				
		//destroi para não ocupar espaço na memoria
		imagedestroy($image);
    }
}