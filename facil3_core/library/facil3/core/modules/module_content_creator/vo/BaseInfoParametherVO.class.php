<?php
include_once 'library/facil3/core/modules/module_content_creator/interface/BaseInfoParamether.interface.php';
/**
 * VO para criar uma array e utilizar o create da classe CreateVOClass, que exite uma array de ParametherVO (ou algo que extenda isso)
 * A OOP dessa classe ainda não está no formato ideal, mas já é melhor que nada, esse é apenas a primeira versao e deve haver melhorias no futuro
 * @author 	: Renato Miawaki - reytuty@gmail.com
 * @version	: 1.0
 */

class BaseInfoParametherVO implements BaseInfoParamether{
		
	private $variableName;//nome da variavel, cuidado com as variaveis protegidas, como id, active e etc
	private $variableType;//int, decimal, varchar, text, date
	private $quantity = 1;//se array, deixe maior que 1, zero é infinito, se for link, quando maior que 1, cria metodo addNomeVariavel e removeNomeVariavel
	private $acceptLocale = false;//bool se true, vai aceitar a variavel locale para buscar em therms
	private $defaultValue;//valor padrão para caso a pessoa não passe nada. para NULL, passe NULL em string
	private $required = false;//essa variavel é necessária na validação? true ou false
	private $reciveByDefault = true;//caso o valor possa ser modificado pelo setFetchArray, variaveis como active, muitas vezes nao devem ser modificadas por parametro
	private $description = "";//descrição no comentário da variavel
	private $orderInClass = 0;//a posição da variavel na classe, a primeira é Zero
	
	public function __construct(
								$variableName,
								$variableType,
								$quantity,
								$acceptLocale,
								$defaultValue,
								$required,
								$reciveByDefault,
								$description,
								$orderInClass
							){
		$this->setVariableName($variableName);
		$this->setVariableType($variableType);
		$this->setQuantity($quantity);
		$this->setAcceptLocale($acceptLocale);
		$this->setDefaultValue($defaultValue);
		$this->setRequired($required);
		$this->setReciveByDefault($reciveByDefault);
		$this->setDescription($description);
		$this->setOrderInClass($orderInClass);
	}
	//criando get e sets para ajudar a evitar e tratar erros
	public function setVariableName($p_variable_name){
		//mensagem de erro e também controle de validação interna
		$errorMessage = "";
		//se nome vazio ta bem errado
		if($p_variable_name == ""){
			$errorMessage = "variavel não pode ter nome vazio";
		}
		//echo Debug::li("p_variable_name ($p_variable_name) ");
		$temp_primeira_letra = substr($p_variable_name, 0, 1);
		//echo Debug::li("primeira letra:[".$temp_primeira_letra."]");
		//se iniciado com número ou outro caractere invalido ta errado tb
		if(in_array($temp_primeira_letra, array("0", 1, 2, 3, 4, 5, 6, 7, 8, 9))){
			$errorMessage = "variavel não pode começar com número $p_variable_name .";
			//echo $errorMessage." . ".substr($p_variable_name, 0, 1); 
			//exit();
		}
		//se tem caractere não aceitos no php, mais erro
		if(preg_match("/[^a-zA-Z0-9_]/", $p_variable_name)){
			$errorMessage = "variaveis só aceitam letras, números e underline em seus nomes. O valor $p_variable_name não é aceito.";
		}
		//se tiver na array de proibidos, ta errado
		if(in_array($p_variable_name, array("id", "active", "order", "date_id", "date_out", "slug", "date", "key_words"))){
			$errorMessage = "Esse nome de variavel é restrito ao content $p_variable_name ";
		}
		if($errorMessage != ""){
			//estoura o erro e precisa ser tratado por aquele que está usando a classe
			throw new Exception($errorMessage, $this->orderInClass);
			//exit();
		}
		//se passou por tudo isso, ok
		$this->variableName = $p_variable_name;
	}
	public function setVariableType($type){
		//nesse caso os tipos de variaveis devem ser diferentes do paramether
		//puxando mais pro lado do uso cotidiano
		//boolean por exemplo deve ser aceito, e o sistema faz a lógica de usar int, mas retorna boolean e recebe boolean
		//o mesmo para links com outras contents (pelo menos) ou link com outros módulos registrados
		//pois pela LinkVO da para dar um ->getLinkedVO e retornar a VO em questão
		//link é outro tipo comum, esse, claro, não usa parameter, e sim link
		//mas como cadastrar algo baseado em link, provavelmente pediria o id para usar no linked_table
		//e também o alias_table com o que isso é link seria necessário, e isso iria no valor.
		//mas onde iria o linked_table para usar no alias da entidade?
		switch(strtolower($type)){
			case BaseInfoParametherVO::TYPE_NUMBER:
				$this->variableType = $value;
				break;
			case BaseInfoParametherVO::TYPE_TEXT:
				$this->variableType = BaseInfoParametherVO::TYPE_TEXT;
				break;
			case BaseInfoParametherVO::TYPE_INT:
				$this->variableType = BaseInfoParametherVO::TYPE_INT;
				break;
			case BaseInfoParametherVO::TYPE_DATE:
				$this->variableType = BaseInfoParametherVO::TYPE_DATE;
				break;
			case BaseInfoParametherVO::TYPE_BOOLEAN:
				$this->variableType = BaseInfoParametherVO::TYPE_BOOLEAN;
				break;
			case BaseInfoParametherVO::TYPE_LINK:
				//quando é link, precisa da info de linked_table e o dado seria gravado na tabela link
				$this->variableType = BaseInfoParametherVO::TYPE_LINK;
				break;
			default:
				$this->variableType = BaseInfoParametherVO::TYPE_VARCHAR;
				break;
				
		}
	}
	public function setQuantity($quant){
		//0 é infinito, 1 é um e N é N
		$this->quantity = (int) $quant;
	}
	public function setAcceptLocale($boolean = false){
		if($boolean){
			$this->acceptLocale = true;
		} else {
			$this->acceptLocale = false;
		}
	}
	public function setDefaultValue($value = NULL){
		//se passar null, não tem default value, para que o valor null seja default, passe "NULL" em string
		$this->defaultValue = $value;
	}
	public function setRequired($boolean = false){
	if($boolean){
			$this->acceptLocale = true;
		} else {
			$this->acceptLocale = false;
		}
	}
	public function setReciveByDefault($reciveByDefault = true){
		$this->reciveByDefault = $reciveByDefault;
	}
	public function setDescription($description){
		//qualquer coisa, se o programador errar, vai ficar errado a classe criada e lá ele arruma
		$this->description = $description;
	}
	public function setOrderInClass($orderInClass){
		$this->orderInClass = $orderInClass;
	}
	//-------------------------------------------------------- gets
	public function getVariableName(){
		return $this->variableName;
	}
	public function getVariableType(){
		return $this->variableType;
	}
	public function getQuantity(){
		return $this->quantity;
	}
	public function getAcceptLocale(){
		return $this->acceptLocale;
	}
	public function getDefaultValue(){
		return $this->defaultValue;
	}
	public function getRequired(){
		return $this->required;
	}
	public function getReciveByDefault(){
		return $this->reciveByDefault;
	}
	public function getDescription(){
		return $this->description;
	}
	public function getOrderInClass(){
		return $this->orderInClass;
	}
	//gets para facilitar o uso
	/**
	 * Retorna true caso ele possa ter valor padrao
	 * @return boolean
	 */
	public function getCanHaveDefaultValue(){
		return ($this->getDefaultValue() && !($this->getVariableType() == BaseInfoParametherVO::TYPE_LINK));
	}
	/**
	 * Retorna true se for do tipo LINK
	 * @return boolean
	 */
	public function getIsLink(){
		return ($this->getVariableType() == BaseInfoParametherVO::TYPE_LINK);
	}
	//abaixo apenas para manter o padrão e poder implementar a interface 
	public function setReturnEntity($b){}
	public function getReturnEntity(){
		return FALSE;
	}
	public function setAliasTableName($alias){}
	public function getAliasTableName(){
		return "";
	}
}