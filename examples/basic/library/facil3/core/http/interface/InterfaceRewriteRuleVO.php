<?php
/**
 * @author 	Mauricio Amorim
 * @desc	Interface da Classe RewriteRulerVO.
*/
Interface InterfaceRewriteRuleVO {
	/**
	 * @var (string) uma breve descrição da expressão regular
	 */
	//private $description;
	
	/**
	 * @var (string or regexp) expressão regular ou string para verificar se bate com a string passada
	 */
	//private $valueToFind;
	
	/**
	 * @var (string or regexp) expressão regular ou string para reescritura da string passada
	 */
	//private $valueToRewrite;
	
	
	//-------------------------------------------------------------------------- sets
	
	/**
	 * @desc uma breve descrição da regra
	 * @param $description 
	 * @return void
	 */
	public function setDescription($description);
	
	/**
	 * @desc seta uma expressão regular ou string para verificar se bate com a string passada
	 * @param (string or regexp)
	 * @return void
	 */
	public function setValueToFind($regexp_or_string);	
	
	/**
	 * @desc seta uma expressão regular ou string para reescritura da string passada
	 * @param (string or regexp)
	 * @return void
	 */
	public function setValueToRewrite($regexp_or_string);
	
	
	//-------------------------------------------- gets
	
	/**
	 * @desc retorna uma breve descrição dessa regra
	 * @return string
	 */
	public function getDescription();
	
	/**
	 * @desc retorna uma expressão regular ou string para verificar se bate com a string passada
	 * @return (string or regexp)
	 */
	public function getValueToFind();

	/**
	 * @desc retorna uma expressão regular ou string para reescritura da string passada
	 * @return (string or regexp)
	 */
	public function getValueToRewrite();
}