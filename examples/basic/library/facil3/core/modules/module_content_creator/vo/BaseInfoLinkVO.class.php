<?php
/**
 * VO para facilitar o uso de parametros que na verdade são links com outros módulos
 * Veja a BaseInfoParametherVO para saber mais
 * @author 	: Renato Miawaki - reytuty@gmail.com
 * @version	: 1.0
 */

include_once 'library/facil3/core/modules/module_content_creator/vo/BaseInfoParametherVO.class.php';

class BaseInfoLinkVO extends BaseInfoParametherVO{
	private $aliasTableNameIfLink = "";//caso o tipo de dado seja link, precisa do table_name da entidade a linkar,  e o id ele vai receber no set
	private $returnEntity = FALSE;//se nao for true, ele retorna só o id do que ta linkado, se for true, ele retorna a VO (pelo menos tenta, utilizando o getLinkedVO() da LinkVO)
	public function __construct($variableName, $aliasTableName, $returnEntity = FALSE, $orderInClass = 0, $reciveByDefault = FALSE, $required = FALSE, $quantity = 0, $description = "Link to another entity"){
		parent::__construct($variableName, 
							$variableType = BaseInfoParametherVO::TYPE_LINK, 
							$quantity, 
							$acceptLocale = FALSE, 
							$defaultValue = NULL, 
							$required, 
							$reciveByDefault, 
							$description, 
							$orderInClass);
		$this->setAliasTableName($aliasTableName);
		$this->setReturnEntity($returnEntity);
	}
	
	public function setReturnEntity($b){
		//se true, ele tenta retornar VO no metodo get de sua classe, se false ele retorna apenas o id
		//MAS se for FALSE (para retornar id) e quantity tiver Zero ou Maior que 1, ele retorna array de LinkVO
		$this->returnEntity = ($b == TRUE);
	}
	public function getReturnEntity(){
		return $this->returnEntity;
	}
	public function setAliasTableName($alias){
		//evite usar espaço em branco, acento, e caracteres especiais
		$this->aliasTableNameIfLink = $alias;
	}
	public function getAliasTableName(){
		return $this->aliasTableNameIfLink;
	}
}