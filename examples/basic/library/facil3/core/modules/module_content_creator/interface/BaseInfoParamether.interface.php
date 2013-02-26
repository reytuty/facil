<?php
/**
 * Tipo de objeto que tem todas as infos de 1 único parametro
 * Todo parametro, independente do tipo, precisa ter pelo menos esses metodos, e os tipos padroes são essas constantes
 * @author 		Renato Miawaki
 * @version 	1.0
 */
interface BaseInfoParamether{
	//tipos de Paramethers aceitos
	const TYPE_VARCHAR		= "varchar";
	const TYPE_NUMBER		= "number";
	const TYPE_INT			= "int";
	const TYPE_TEXT			= "text";
	const TYPE_DATE			= "date";
	const TYPE_BOOLEAN		= "bool";
	const TYPE_LINK			= "link";
	
	function setVariableName($p_variable_name);
	function setVariableType($type);
	function setQuantity($quant);
	function setAcceptLocale($boolean = false);
	function setDefaultValue($value = NULL);
	function setRequired($boolean = false);
	function setReciveByDefault($reciveByDefault = true);
	function setDescription($description);
	function setOrderInClass($orderInClass);
	//-------------------------------------------------------- gets
	function getVariableName();
	function getVariableType();
	function getQuantity();
	function getAcceptLocale();
	function getDefaultValue();
	function getRequired();
	function getReciveByDefault();
	function getDescription();
	function getOrderInClass();
	//gets para facilitar o uso
	/**
	 * Retorna true caso ele possa ter valor padrao
	 * @return boolean
	 */
	function getCanHaveDefaultValue();
	/**
	 * Retorna true se for do tipo LINK
	 * @return boolean
	 */
	function getIsLink();
	
	//abaixo os metoso que só são uteis para parametros do tipo link, mas mesmo assim, entra na interface
	
	public function setReturnEntity($b);
	public function getReturnEntity();
	public function setAliasTableName($alias);
	public function getAliasTableName();
}