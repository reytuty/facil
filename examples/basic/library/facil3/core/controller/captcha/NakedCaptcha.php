<?php
/**
 * @author 		: Mauricio Amorim
 * @date		: 05/12/2010
 * @version		: 1.0
 * @desc		: 
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
 *  */
//para retorno
include_once "library/facil3/core/vo/ReturnResultVO.class.php";

//inclui classes de criptografa e geraçao de imagens
include_once "library/facil3/core/modules/captcha/encrypt/NakedEncrypt.class.php";
include_once "library/facil3/core/modules/captcha/image/NakedImage.class.php";

//classe para manipulação dessa controller
include_once "library/facil3/core/controller/file/FileInfoPostVO.php";

/**
 * @author Renato
 * @date		: 19/01/2011
 * @version		: 
 * @desc		: 
 */
class NakedCaptcha {
	/** @desc configurações e dados para manipulação dessa controller */
	public $NakedCaptchaInfoPostVO;

	public $arrayVariable;
	public $arrayRestFolder;
	
	public function __construct($arrayRestFolder = NULL){
		if($arrayRestFolder != NULL){
			$this->arrayVariable   = Navigation::getVariableArraySlug($arrayRestFolder);
            $this->arrayRestFolder = $arrayRestFolder;
        }
        //por padrão ele popula as infos que ele possui com o que veio na restFolder, pode ser resetado utilizando resetInfoPost
        $this->NakedCaptchaInfoPostVO = new NakedCaptchaInfoPostVO($this->arrayVariable);
	}

    /*
     * naum faz nada pq é só para mostrar o html
     */
    public function init(){
		//iniciando o retorno padrao em http result
		$returnResult = new HttpResult();
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new HttpRoot();
		$returnResult->setSuccess(TRUE);
		//salvando o objeto de resultado de html no retorno
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		//Debug::print_r($returnResult);
		return $returnResult;
    }

    /**
     * passe uma nova NakedCaptchaInfoPostVO caso não queira usar o que está na rest folder
     * @param NakedCaptchaInfoPostVO $NakedCaptchaInfoPostVO
     * @return void
     */
    public function resetInfoPost(NakedCaptchaInfoPostVO $NakedCaptchaInfoPostVO = NULL){
    	$this->NakedCaptchaInfoPostVO = $NakedCaptchaInfoPostVO;
    }
    
    /**
     * @return ReturnResultVO;
     * @desc é necessário passar o codigo gerado pelo metodo generateCode()
     */
    public function generate(){	
		$ReturnResultVO = new ReturnResultVO();
		$image = NakedImage::generate($this->NakedCaptchaInfoPostVO->encrypt_code);
		if($image){
			$ReturnResultVO->sucess = TRUE;
			$ReturnResultVO->result = $image;
		}else{
			$ReturnResultVO->sucess = FALSE;
			$ReturnResultVO->addMessage(ThermInterface::ERROR_NAKED_CAPTCHA_IMAGE_NOT_GENARATE);
		}
		return $ReturnResultVO; 
    }

    /**
     * @return ReturnResultVO
     * @desc gera um codigo criptografado para posteriormente gerar uma imagem e validar o captcha
     */
    public function generateCode(){
    	//gera codigo criptografado para criar imagem e validar captcha
		$ReturnResultVO = new ReturnResultVO();
		$code = NakedEncrypt::generate();
		if($code){
			$ReturnResultVO->sucess = TRUE;
			$ReturnResultVO->result = $code;
		}else{
			$ReturnResultVO->sucess = FALSE;
			$ReturnResultVO->addMessage(ThermInterface::ERROR_NAKED_CAPTCHA_CODE_NOT_GENARATE);
		}
		return $ReturnResultVO;    			
    }

    /**
     * @return ReturnResultVO
 	 * @desc é necessário passar o codigo gerado pelo metodo generateCode() e o campo preenchido pelo usuario para 
 	 * validação do captcha
    */
    public function validateCode(){
		$ReturnResultVO = new ReturnResultVO();
		if(NakedEncrypt::validate($this->NakedCaptchaInfoPostVO->captcha_code, $this->NakedCaptchaInfoPostVO->captcha_value)){
			$ReturnResultVO->sucess = TRUE;
			$ReturnResultVO->result = $encrypt_code;
		}else{
			$ReturnResultVO->sucess = FALSE;
			$ReturnResultVO->addMessage(ThermInterface::ERROR_NAKED_CAPTCHA_CODE_NOT_GENARATE);
		}
		return $ReturnResultVO;	
    }
    
    /**
	 * @desc exibe uma imagem na tela, é necessário passar o codigo
	 * gerado pelo metodo generateCode()
	 * @return void
	*/
    static function show(){
		$image = NakedImage::generateImage($$this->NakedCaptchaInfoPostVO->captcha_code);
		($image)?NakedImage::show($image):NULL; 
		exit();		
    }

    /**
     * 
     * @return ReturnResultVO
     esta comentado pq é apenas uma versão simples mais funcional
     nau apagar é mportante para futuras alterações ou melhorias
    public function gerateCaptchaCode(){
		// Seleciona uma imagem que está na pasta bg/ com o nome 0.jpg à 9.jpg,
		// está imagem que vai ser o fundo da nossa imagem de segurança.
		$backgoundImage = Config::getFolderView("captcha/");
		$backgoundImage .= rand(0,9);
		$backgoundImage .= ".jpg";
		
		$auth = NULL;
		
		//posicionamento de horizontal
		$x = 10;
		//posicionamento de vertical
		$y = 0;
		
		// Crio uma string com 6 caracteres seus respectivos lugares e fontSize de letra na imagem
		for ($i = 0; $i < 6 ;$i++) {
			// Seleciona de 0 à 2, onde 0 = letra maiúscula, 1 = minúscula e 2 = número.
			$tipo = rand(0,2);
			switch($tipo) {
				// se pegou 0 ele cria uma letra maiúscula de A à Z.
				// com um porém, o chr(rand(65,90)), a função chr retorna um caractere específico, e o rand seleciona aleatório.
				// o motivo de estar rand(56,90), pois temos que usar de acolordo com a tabela ASCII e o A = 65 e o Z = 90.
				case 0 : $str = chr(rand(65,90)); break;
				// mesma coisa que antes, mais aqui é minúsculo.
				case 1 : $str = chr(rand(97,122)); break;
				// mesma coisa que antes só que aqui são números.
				case 2 : $str = chr(rand(48,57)); break;
				// caso ocolorra algun erro no rand tipo ele para por aqui.
				default : break; break;
			}
			
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
			
			// Gera um array carac, com os dados que é o caractere, a color do caractere, a posição x e y.
			$letter[] = array("char" => $str, "size" => $fontSize, "colorR" => $sel_colorR, "colorG" => $sel_colorG, "colorB" => $sel_colorB, "x" => $x, "y" => $y);
			
			// crio a variavel para usar e criar a sessão logo abaixo com os caracteres que forão criados.
			$auth .= $str;
		}

		// Cria a imagem.
		$image = imagecreatefromjpeg($backgoundImage);

		// percore o array letter, e traz os valores.
		foreach($letter as $line) {
			// Aqui crio a color de cada caractere, RGB.
			$color = imagecolorallocate($image, $line["colorR"], $line["colorG"], $line["colorB"]);
			// desenho o lugar dos caracteres de acolordo com as posições x e y.
			imagestring($image, $line["size"], $line["x"], $line["y"], $line["char"], $color);
		}
		
		// ele informa que isso é um arquivo PNG
		header("Content-type: image/png");
		// cria a imagem PNG
		imagepng($image);
				
		//destroi para não ocupar espaço na memoria
		imagedestroy($image);
    }
    */
}