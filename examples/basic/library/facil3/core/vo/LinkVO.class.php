<?php
/*
 * @autor		: Renato Miawaki - reytuty@gmail.com - diego@midianova.com.br
 * @data		: 16/07/2010
 * @versao		: 1.0
 * @comentario	: 	Classe fora do padro para trabalhar com os v�nculos.
 					Apenas valores, n�o se conecta com DAO
 */
/*
		
		id, 
		table, 
		table_id, 
		linked_table, 
		linked_table_id, 
		active
		
*/
class LinkVO{
	var $id;//id na table link
	var $table;
	var	$table_id; 
	var	$linked_table; 
	var	$linked_table_id; 
	var	$active;
	var $order;
	var $status;//status � pra ver se � novo, alterado, ok
	
	
	var $linkVO;
	var $linkedVO;
	
	const MODULE_NOT_EXIST = -1;
	const PATH_MODULE_NOT_EXIST = -2; 
	
	/**
	 * @param int $id
	 * @param string $table
	 * @param int $table_id
	 * @param string $linked_table
	 * @param int $linked_table_id
	 * @param int $active
	 * @coment	Passar os dados na construtora � opcional
	 */
	function __construct($id = NULL, $table = NULL, $table_id = NULL, $linked_table = NULL, $linked_table_id = NULL, $active = NULL){
		$this->setId($id);
		$this->setTable($table);
		$this->setTableId($table_id);
		$this->setLinkedTable($linked_table);
		$this->setLinkedTableId($linked_table_id);
	}
	/**
	 * @param 		int	$id
	 * @coment		o id do Link na tabela link
	 */
	public function setId($id){
		$this->id = DataHandler::forceInt($id);
	}
	/**
	 * @param 	string $table
	 * @return 	void
	 * @coment	Registra a table da unidade que tem algo vinculado
	 */
	public function setTable($table){
		$this->table = DataHandler::forceString($table);
	}
	/**
	 * @param 	string $table
	 * @return 	void
	 * @coment	Registra o id na table da unidade que tem algo vinculado
	 */
	public function setTableId($table_id){
		$this->table_id = DataHandler::forceInt($table_id);
	}
	/**
	 * @param 	string $linked_table
	 * @return 	void
	 * @coment	Registra a table da unidade vinculada
	 */
	public function setLinkedTable($linked_table){
		$this->linked_table = DataHandler::forceString($linked_table);
	}
	/**
	 * @param 	int $linked_table_id
	 * @return 	void
	 * @coment	Registra o id na table da unidade vinculada
	 */
	public function setLinkedTableId($linked_table_id){
		$this->linked_table_id = DataHandler::forceInt($linked_table_id);
	}
	/**
	 * @param int $active
	 * @return 	void
	 * @coment	Diz se o vinculo est� ativo ou n�o
	 */
	public function setActive($active){
		$this->active = DataHandler::forceInt($active);
	}
	/**
	 * @param int $order
	 */
	public function setOrder($order){
		$this->order = DataHandler::forceInt($order);
	}
	/**
	 * @param string $status
	 * @coment grava o status para saber se na hora do update, precisa atualizar esse Vinculo
	 */
	public function setStatus($status){
		switch($status){
			case LinkDAO::STATUS_OK:
				$status = LinkDAO::STATUS_OK;
				break;
			case LinkDAO::STATUS_DELETE:
				$status = LinkDAO::STATUS_DELETE;
				break;
			case LinkDAO::STATUS_UPDATE:
				$status = LinkDAO::STATUS_UPDATE;
				break;
			case LinkDAO::STATUS_INSERT:
			default:
				$status = LinkDAO::STATUS_INSERT;
				break;
		}
		$this->status = $status;
	}
	/**
	 * @param object $object
	 */
	public function setFetchObject($object){
		$this->setId($object->id);
		$this->setTable($object->table);
		$this->setTableId($object->table_id);
		$this->setLinkedTable($object->linked_table);
		$this->setLinkedTableId($object->linked_table_id);
		$this->setOrder($object->order);
		$this->setActive($object->active);
		$this->setStatus(LinkDAO::STATUS_OK);
	}
	
	
	
	public function setLinkVO($vo){
	   $this->linkVO = $vo;
	}	
	
	//---------------------[GETs]
	public function getId(){
		return $this->id;
	}
	public function getTable(){
		return $this->table;
	}
	public function getTableId(){
		return $this->table_id;
	}
	public function getLinkedTable(){
		return $this->linked_table;
	}
	public function getLinkedTableId(){
		return $this->linked_table_id;
	}
	public function getStatus(){
		return $this->status;
	}
	public function getActive(){
		return $this->active;
	}
	/**
	 * @return int $order
	 */
	public function getOrder(){
		return $this->order;
	}
	function validate(){
		//vai validar a VO inteira conforme as regras
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->success = TRUE;
		if(!Validation::blank($this->table, 1)){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_LINK_VALIDATE_TABLE"));
		}
		if(!Validation::blank($this->linked_table, 1)){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_LINK_VALIDATE_LINKED_TABLE"));
		}
		if(!$this->table_id > 0){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_LINK_VALIDATE_TABLE_ID"));
		}
		if(!$this->linked_table_id > 0){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_LINK_VALIDATE_LINKED_TABLE_ID"));
		}
		return $ReturnResultVO;
	}
	/**
	 * @return	:	ObjectVO
	 * @coment	:	Retorna a VO do item vinculado ao item principal
	 * 				o nome da linked_table precisa ser o mesmo do m�dulo, s� funciona se tiver start.php no m�dulo e se DAO->getVO retornar a VO requerida
	 * @return	:   ReturnDataVO object
	 */
	public function getLinkedVO(){
		$ReturnDataVO = new ReturnDataVO();

		
		if(count($this->linkedVO) > 0){
			$ReturnDataVO->success = TRUE;
			$ReturnDataVO->result = $this->linkedVO;
			return $ReturnDataVO;
			exit();
		}
		
		//verifica se existe a o m�dulo $this->table
		$ReturnDataVO = new ReturnDataVO();
		$ReturnDataVO->success = FALSE;
		// echo $this->linked_table;
		$resultSearchModuleInfo = Config::getModuleInfo($this->linked_table);
		// print_r($resultSearchModuleInfo);
		if($resultSearchModuleInfo){
			if(file_exists($resultSearchModuleInfo->path)){
				// echo $resultSearchModuleInfo->path;
				//iniciando a start do modulo
				include_once($resultSearchModuleInfo->path);
				$ReturnDataVO->success = TRUE;
				$class_name = $resultSearchModuleInfo->name;
				$DAO = new $class_name();
				$ReturnDataVO->result = $DAO->getVO();
				//populando a VO
				$ReturnDataVO->result->setId($this->linked_table_id, TRUE);
				$this->linkedVO = $ReturnDataVO->result;
				return $ReturnDataVO;
			} else {
				$ReturnDataVO->code_return = LinkVO::PATH_MODULE_NOT_EXIST;
			}
		} else {
			// echo "oi";exit();
			//xi marquinho
			$ReturnDataVO->code_return = LinkVO::PATH_MODULE_NOT_EXIST;
		}
		
		return $ReturnDataVO;
	}
	
	/**
	 * @return	:	ObjectVO
	 * @coment	:	Retorna a VO do item principal
	 * 				o nome da link_table precisa ser o mesmo do m�dulo, s� funciona se tiver start.php no m�dulo e se DAO->getVO retornar a VO requerida
	 * @return	:   ReturnDataVO object
	 */
	public function getLinkVO(){
		//verifica se existe a o m�dulo $this->table
		$ReturnDataVO = new ReturnDataVO();


		if(count($this->linkVO) > 0){
			$ReturnDataVO->success = TRUE;
			$ReturnDataVO->result = $this->linkVO;
			return $ReturnDataVO;
			exit();
		}		
		
		$ReturnDataVO->success = FALSE;
		$resultSearchModuleInfo = Config::getModuleInfo($this->table);
		if($resultSearchModuleInfo){
			if(file_exists($resultSearchModuleInfo->path)){
				//iniciando a start do modulo
				include_once($resultSearchModuleInfo->path);
				$ReturnDataVO->success = TRUE;
				$class_name = $resultSearchModuleInfo->name;
				$DAO = new $class_name();
				$ReturnDataVO->result = $DAO->getVO();
				//populando a VO
				$ReturnDataVO->result->setId($this->table_id, TRUE);
				$this->linkVO = $ReturnDataVO->result;
				return $ReturnDataVO;
			} else {
				$ReturnDataVO->code_return = LinkVO::PATH_MODULE_NOT_EXIST;
			}
		} else {
			//xi marquinho
			$ReturnDataVO->code_return = LinkVO::PATH_MODULE_NOT_EXIST;
		}
		
		return $ReturnDataVO;
	}
}