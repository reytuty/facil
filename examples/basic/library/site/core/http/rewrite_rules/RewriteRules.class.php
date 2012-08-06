<?php
include_once("library/facil3/core/http/routes/rules/RuleVO.class.php");
/**
 * @author Mauricio Amorim
 * @desc contem as regras dassa rota
 */
class EventRules{
	public $arrayRulesVO = array();
	
	/**
	 * @desc cria as regras para essa rota
	 * @return void
	 */
	public function EventRules(){
		//regra de indice 0 da rota do palestrante
		$EventRuleVO = new RuleVO();
		$EventRuleVO->addName("evento");
		$EventRuleVO->addName("eventos");
		$EventRuleVO->addName("events");
		$EventRuleVO->setTranslation("event");
		$this->addRuleVO($EventRuleVO, 0);
	}

	/**
	 * @param $RuleRouteVO obj
	 * @param $indice int
	 * @return void
	 * @desc adiciona uma regra para um indice dessa rota
	 */
	public function setRuleVO($EventRuleVO, $indice){
		$this->arrayRulesVO[$indice] = $EventRuleVO;
	}
	
	/**
	 * @desc verifica se existe a regra para a string passada
	 * @return boolean
	 */
	public function ruleNameExist($RuleVO, $name){
		foreach($RuleVO->array_names as $name_rules){
			if($name == $name_rules){
				return TRUE;
			}
		}
		return FALSE;
	}
	
	/**
	 * @param $string
	 * @param $index
	 * @return $string
	 * @desc se encontrar tradução retorna a mesma, se não retorna a string passada
	 */
	public function switchRule($string, $index){
		if(array_key_exists($index, $this->arrayRulesVO)){
			$RuleVO = $this->arrayRulesVO[$index];
			//verifica se existe algum nome com a string passada
			if($this->ruleNameExist($RuleVO, $string)){
				return $RuleVO->getTranslation();
			}
		}
		return $string;
	}
	
}
