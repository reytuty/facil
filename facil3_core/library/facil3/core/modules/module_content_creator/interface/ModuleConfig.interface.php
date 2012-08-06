<?php
/**
 * Metodos necessários para um ModuleConfig, objeto que tem informações de como um módulo deve ser criado
 * @author 		Renato Miawaki - reytuty@gmail.com
 * @version 	1.0
 */
interface ModuleConfig{
	//opçoes de modos de criação de um módulo
	/**
	 * Cria a tabela da entidade apenas com os parametros básicos da entidade
	 * Mas para qualquer outro atributo utiliza Paramether, e vínculo utiliza módulo Link
	 * @var string
	 */
	const CREATE_ENTITY_TABLE_AND_USE_PARAMETHER 			= "CREATE_ENTITY_TABLE_AND_USE_PARAMETHER";
	/**
	 * Cria a tabela da entidade com todos os atributos
	 * @var string
	 */
	const CREATE_FULL_TABLE_ATTRIBUTES						= "CREATE_FULL_TABLE_ATTRIBUTES";
	/**
	 * Cria a tabela da entidade com todos os atributos
	 * e também as tabelas de relação N pra N, bem como as classes para tratamento da entidade
	 * @var string
	 */
	const CREATE_FULL_TABLE_ATTRIBUTES_AND_RELATIONS_TABLE 	= "PARANGARICUTIRIMIRUARO";
	/**
	 * utiliza o módulo content (e tabela content), apenas extendendo a VO e DAO
	 * @var string
	 */
	const USE_CONTENT_TABLE									= "USE_CONTENT_TABLE";
	/**
	 * O nome da entidade, possivelmente o nome da tabela no banco, caso haja
	 * @return string
	 */
	function getEntityName();
	/**
	 * Retorna a string da url onde o módulo deve ser criado
	 * @return string
	 */
	function getFolderModule();
	/**
	 * O nome do módulo, teoricamente seria o mesmo que a entidade, mas pode ser diferente
	 * 	@return string
	 */
	function getModuleName();
	/**
	 * array de atributos da entidade, cada objeto possui informações sobre o tipo de atributo e comentário explicativo*
	 * @return array de BaseInfoParametherVO
	 */
	function getArrayParamethers();
	/**
	 * O modo como o módulo será criado.
	 * Inicialmente apenas o modo utilizando parametros
	 * Aqui se trata de uma convensão de nomes para ser utilizado no módulo criador de módulos
	 * 	Futuramente pode ter o modo:
	 * 				utilizando tabelas (criando todas as tabelas (N>N e 1>N) e todos os campos das tabelas)
	 * @return string	
	 */
	function getCreateMode();
	/**
	 * A descrição e comentários sobre a classe
	 * @return string
	 */
	function getModuleDescription();
	
	/**
	 * Deve verificar se os parametros enviados estão ok e retornar uma ReturnResultVO
	 * @return ReturnResultVO
	 */
	function validate();
}