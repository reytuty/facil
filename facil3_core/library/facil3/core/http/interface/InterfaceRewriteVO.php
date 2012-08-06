<?php
/**
 * @author 	Mauricio Amorim
 * @desc	Interface da Classe de RewriteVO.
*/
Interface InterfaceRewriteVO {
	/**
	 * @var (array) array de RewriteRuleVO
	 */
	//private $arrayRewriteRuleVO;
	
	/**
	 * @desc adiciona uma RewriteRuleVO a ser utilizada na string a ser passada
	 * @return void
	 */
	public function addRewriteRuleVO(InterfaceRewriteRuleVO $RewriteRuleVO);
	
	/**
	 * @desc Recebe uma string que será reescrita caso haja uma expressão regular dentro
	 *  de algum indice do array de expressões regulares de cada RewriteRule.
	 *  Se houver um tratamento para a mesma, retorna um ReturnResultVO onde ReturnResultVO->result = string reescrita e
	 *  ReturnResultVO->array_messages recebe a descrição da regra utilizada
	 * @param $string
	 * @return $ReturnResultVO
	 */
	public function rewrite($string);
}