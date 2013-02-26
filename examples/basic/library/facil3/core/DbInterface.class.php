<?php
/*
 * @author		: Mauricio Amorim
 * @data		: 14/11/2010
 * @version		: 0.1
 * @description	: 	Classe para conexões com o banco de dados mysql
 Essa classe precisa que tenha um link de conexão com o banco aberta
 para evitar abrir conexões constantemente
 */
include_once "library/facil3/core/vo/ReturnDataVO.class.php";
class DbInterface{
	//confirmado
	const RETURN_STD_OBJECT							= "STR_OBJECT";
	const RETURN_VO									= "VO";
	const RETURN_ARRAY								= "ARRAY";

	const ORDER_ASC									= "ASC";
	const ORDER_DESC								= "DESC";
		
	
	const ERROR_UPDATE_DONOT_HAVE_ID					= -1;//não foi enviado o id para atualização
	const ERROR_UPDATE_DONOT_HAVE_UPDATES				= -2;//não foi enviado os dados para atualização
	const ERROR_UPDATE_COMMIT 							= -3;
	const ERROR_INSERT_COMMIT 							= -4;
	const ERROR_PARAMETER_DONOT_HAVE_TABLE_OR_TABLE_ID	= -5;//erro gerado quando no envidado o nome e o id referencia para ThermDAO ou ParameterDAO
	const ERROR_DUPLICATE_ENTRY		 					= 1062;//dois cadastros com unique key
	const ERROR_SQL_SINTAX			 					= 1064;//erro de sintax de SQL
	const ERROR_INSERT_WITHOUT_FOREIGN_KEY				= 1452;//chave primaria não existe para inserção
	const ERROR_INSERT_WITHOUT_OBRIGATORY_CAMP			= 1048;//algum campo obrigatorio recebeu vazio
	const ERROR_DELETE_OR_UPDATE_WITHOUT_FOREIGN_KEY	= 1451;//chave primaria não existe para exclusão
	
	//Cannot add or update a child row
	const ERROR_DONT_HAVE_LAST_INSERT_ID				= -6;//
	//erros para ThermDAO
	const ERROR_TERM_UPDATE_DONOT_HAVE_ID				= -11;//não foi enviado o id para atualização
	const ERROR_TERM_UPDATE_DONOT_HAVE_UPDATES			= -12;//não foi enviado os dados para atualização
	const ERROR_TERM_UPDATE_COMMIT 						= -13;
	const ERROR_TERM_INSERT_COMMIT 						= -14;
	const ERROR_TERM_DONOT_HAVE_TABLE_OR_TABLE_ID		= -15;//erro gerado quando no envidado o nome e o id referencia para ThermDAO ou ParameterDAO
	
	//erros para LinkDAO
	const ERROR_LINK_UPDATE_DONOT_HAVE_ID				= -21;//no foi enviado o id para atualização
	const ERROR_LINK_UPDATE_DONOT_HAVE_UPDATES			= -22;//no foi enviado os dados para atualização
	const ERROR_LINK_UPDATE_COMMIT 						= -23;
	const ERROR_LINK_INSERT_COMMIT 						= -24;
	const ERROR_LINK_DONOT_HAVE_TABLE_OR_TABLE_ID		= -25;//erro gerado quando no envidado o nome e o id referencia para ThermDAO ou ParameterDAO
	
	const ERROR_DELETE_RESTRICT							= -26;
	
	
	//confirmado
	const SUCCESS										= 1;
	
	private static $count_querys = 0;
	public static function getTotalQuerys(){
		return self::$count_querys;
	}
	/* 
	 * a classe que extends a DbInterface, tem opção de criar variaveis para tratamento query
	 * ver metodo getQueryFilter
	 */
	public function __construct(){
		//
	}
	/**
	 *
	 * @param  (int)	$quant_limite = NULL
	 * @param  (int)	$quant_inicial = NULL
	 * @return string
	 */

	public function limit($quant_limit = NULL, $quant_start = NULL){
		//$quant_start
		$quant_limit 	= DataHandler::forceInt($quant_limit);
		$quant_limit 	= ($quant_limit < 0)?0:$quant_limit;
		$quant_start 	= DataHandler::forceInt($quant_start);
		$quant_start = ($quant_start < 0)?0:$quant_start;
		if($quant_limit > 0){
			return " LIMIT $quant_start, $quant_limit ";
		}
		return "";
	}
	/**
	 * @param string $simble
	 * @return string
	 */
	public function compareSimble($simble = "="){
		switch($simble){
			case ">":
				$simble = ">";
				break;
			case "<":
				$simble = "<";
				break;
			case "<=":
				$simble = "<=";
				break;
			case ">=":
				$simble = ">=";
				break;
			case "<>":
			case "!=":
				$simble = "<>";
				break;
			default:
				$simble = "=";
				break;
		}
		return $simble;
	}
	public function verifyOrderType($order){
		switch($order){
			case DbInterface::ORDER_DESC:
				$order = DbInterface::ORDER_DESC;
				break;
			case DbInterface::ORDER_ASC:
			default:
				$order = DbInterface::ORDER_ASC;
				break;
		}
		return $order;
	}
	
	/**
	 * @param string $date
	 * @return string
	 */
	public function dateHandlerScape($date = "NOW()"){
		return (strtoupper($date) == "NOW()" || strtoupper($date) == "NOW")?"NOW()":"'".$date."'";
	}
	public function lastInsertId(){
		$id = mysql_insert_id();
		if($id){
			$ReturnDataVO = new ReturnDataVO(TRUE, $id, DbInterface::SUCCESS);
			$ReturnDataVO->setReturnId($id);
			return $ReturnDataVO;
		}else{
			return new ReturnDataVO(FALSE, mysql_error(), DbInterface::ERROR_DONT_HAVE_LAST_INSERT_ID);
		}
	}
	public function query($query, $returnId = FALSE, $uniqueResult = FALSE, $runFunc = FALSE){
		self::$count_querys++;
		$save_result = FALSE;
		
		if(Config::USE_QUERY_CACHE){
			/*
			$folder = Config::getRootApplication().Config::QUERY_CACHE_FOLDER;
			$file_cache_name = md5($query);
			$file_cache_local = DataHandler::removeDobleBars($folder."/".$file_cache_name);
			if(file_exists($file_cache_local)){
				
				return unserialize(file_get_contents($file_cache_local));
			} else {
				$save_result = TRUE;
			}
			//o arquivo não existe, vai ter que gravar.
			DataHandler::createRecursiveFoldersIfNotExists($folder);
			*/
		}
	    Config::getConection(); 
		try{
			
			$result = mysql_query($query);
			
			if(isset($_GET["debug"])){
				echo Debug::li($query);
			}
			$erro_number = mysql_errno();
			
			$returnResult;
			if($erro_number){
				$returnResult = new ReturnDataVO(FALSE, mysql_error(), $erro_number, $query, $uniqueResult);
			}else{
				if($returnId){
					$returnResult = $this->lastInsertId();
				}else{
					$returnResult = new ReturnDataVO(TRUE, $result, NULL, $query, $uniqueResult);
				}
			}
			if($save_result){
				//;cache não implementado
				//grava o resultado caso ele já não exista
				//$content = serialize($returnResult);
				//DataHandler::writeFile($folder, $file_cache_name, $content);
			}
			return $returnResult;
		} catch (Exception $e){
			$ReturnDataVO->success  = FALSE;
			$ReturnDataVO->result = $e;
			$ReturnDataVO->code_return = mysql_errno();
			return $ReturnDataVO;
		}
	}//end function query
	
}//end class

?>