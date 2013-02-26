<?php 
/**
 * @author Renato Miawaki
 * @desc classe padrão para retorno de busca de arquivo
 */
class ReturnSearchClassVO{
	/**
	 * @var bool
	 */
	public $success = FALSE;
	/**
	 * @var string do nome da classe encontrada (só se encontrar)
	 */
	public $className;
	/**
	 * @var string do nome do methodo encontrado
	 */
	public $methodName;
	/**
	 * @var string da url caso encontre, com folder e arquivo
	 */
	public $urlToInclude;
	/**
	 * @var string da pasta caso ache uma pasta
	 */
	public $folder;
	/**
	 * @var string do arquivo, sem o folder, caso encontra na url da folder
	 */
	public $file;
	/**
	 * @var array das pastas que estão após o arquivo encontrado
	 */
	public $arrayRestFolder;
	public function __construct(){
		//
	}
}