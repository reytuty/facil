<?php
include_once "library/facil3/core/controller/file/FileInfoPostVO.php";
/**
 * 
 * Copiado do módulo de imagem
 * 
 * @author 	Mauricio Amorim / Renato Miawaki
 * @desc	Classe para tratar o que é enviado e formar um info post para várias Files
 * 
 * caminho para esse arquivo:
 * library/facil3/core/controller/file/FileInfoPostHandler.class.php
 */
class FileInfoPostHandler {
	/**
	 * @var string
	 */
	private $linked_table;
	/**
	 * @var int
	 */
	private $linked_table_id;
	/**
	 * @var array
	 */
	private $arrayInfoPost = array();
	/**
	 * @var array de ids para deletar
	 */
	private $arrayToDelete = array();
	/**
	 * @param $array_send_in_pattern 	array com diversas arrays de informações relativas a File,
	 * 									só é útil para casos que trabalhe dentro do padrão de recebimento
	 * 									caso contrário utilize addItem separadamente
	 * @return void
	 */
	public function __construct($array_send_info = NULL, $array_files = NULL, $table = NULL, $table_id = NULL){
		if($array_send_info){
			$this->setFetchArray($array_send_info, $array_files);
		}
		if($table && $table_id){
			$this->setLinkedTableAndTableId($table, $table_id);
		}
	}
	/**
	 * pega a array e trata para transformar numa array de InfoPost dentro do padrão do plugin
	 * @param $array_send_in_pattern 	ver a construct
	 * @return void
	 */
	public function setFetchArray($array_send_info, $array_files){
			$type = "file";
			//Debug::print_r($array_send_info);
			//echo Debug::print_r($array_files);
			//exit();
			for($i = 0 ; $i< count($array_files["Filedata"]["name"]); $i++){
				//cria uma array no padrao para a FileInfoPostVO
				$data = array(
					$type . "_info_active" 			=> (isset($array_send_info[ $type . '_info_active']))?$array_send_info[ $type . '_info_active'][$i]:"",
					$type . "_info_type" 			=> (isset($array_send_info[ $type . '_info_type']))?$array_send_info[ $type . '_info_type'][$i]:"",
					$type . "_info_author" 			=> (isset($array_send_info[ $type . '_info_author']))?$array_send_info[ $type . '_info_author'][$i]:"",
					$type . "_info_name" 			=> (isset($array_send_info[ $type . '_info_name']))?$array_send_info[ $type . '_info_name'][$i]:"",
					$type . "_info_description" 	=> (isset($array_send_info[ $type . '_info_description']))?$array_send_info[ $type . '_info_description'][$i]:"",
					$type . "_info_order"		 	=> (isset($array_send_info[ $type . '_info_order']))?$array_send_info[ $type . '_info_order'][$i]:"",
					$type . "_info_locale" 			=> (isset($array_send_info[ $type . '_info_locale']))?$array_send_info[ $type . '_info_locale'][$i]:"",
					"Filedata" 						=> array(
															"name"=>$array_files["Filedata"]["name"][$i],
															"type"=>$array_files["Filedata"]["type"][$i],
															"tmp_name"=>$array_files["Filedata"]["tmp_name"][$i],
															"error"=>$array_files["Filedata"]["error"][$i],
															"size"=>$array_files["Filedata"]["size"][$i]
															)
				);
				$FileInfoPostVO = new FileInfoPostVO();
				
				$FileInfoPostVO->setFetchArray($data);
				//verifica se precisa vincular e se essa info foi enviada
				if($this->linked_table){
					$FileInfoPostVO->table 	= $this->linked_table;
					$FileInfoPostVO->table_id 	= $this->linked_table_id;
				}else if(isset($array_send_info[$type."_table"]) &&is_array($array_send_info[$type."_table"])){
					//se enviar array, significa que para cada conjunto de fotos terá um vinculo diferente
					//util para casos em que o conteudo tem mais de uma foto e cada uma para uma determinada utilidade
					$FileInfoPostVO->table 	= $array_send_info[$type."_table"][$i];
					$FileInfoPostVO->table_id 	= $array_send_info[$type."_table_id"][$i];
				} else if(isset($array_send_info[$type."_table"])){
					//se todas as Filens obedecem ao mesmo vinculo, então envie somente o table e table_id sem ser array
					$FileInfoPostVO->table 	= $array_send_info[$type."_table"];
					$FileInfoPostVO->table_id 	= $array_send_info[$type."_table_id"];
				}
				//adiciona na array
				$this->addItemInfoPostVO($FileInfoPostVO);
			}//end for($i = 0 ; $i< count($array_files); $i++){
			//agora o for de item a deletar
			for($i = 0 ; $i< count(DataHandler::getValueByArrayIndex($array_send_info, $type."_delete")); $i++){
				//dentro de cada indice da array $array_send_info[$type."_delete"] deve vir o id da Filem
				//aqui ele só guarda a array
				$this->arrayToDelete[] = $array_send_info[$type."_delete"][$i];
			}
	}
	/**
	 * @return array de FileInfoPostVO
	 */
	public function getArrayInfoPost(){
		return $this->arrayInfoPost;
	}
	/**
	 * @return array de ids para deletar
	 */
	public function getArrayToDelete(){
		return $this->arrayToDelete;
	}
	/**
	 * modifica a table e table id de todas as entradas cadastradas
	 * @param $table
	 * @param $table_id
	 * @return void
	 */
	public function setLinkedTableAndTableId($table, $table_id){
		foreach($this->arrayInfoPost as $InfoPost){
			$InfoPost->table 		= $table;
			$InfoPost->table_id 	= $table_id;
		}
	}
	/**
	 * @param $FileInfoPostVO um objeto do tipo FileInfoPostVO
	 * @return void
	 */
	public function addItemInfoPostVO(FileInfoPostVO  $FileInfoPostVO){
		$this->arrayInfoPost[] = $FileInfoPostVO;
	}
	/**
	 * @param $File_info_locale
	 * @param $File_info_author
	 * @param $File_info_active
	 * @param $File_info_name
	 * @param $File_info_description
	 * @param $File_info_order
	 * @param $posted_file_data
	 * @param $vinculed_table
	 * @param $vinculed_table_id
	 * @return void
	 */
	public function addItem($File_info_locale = NULL, $File_info_author = NULL, $File_info_active = 1, $File_info_name = NULL, $File_info_description = NULL, $File_info_order = NULL, $posted_file_data = NULL, $vinculed_table = NULL, $vinculed_table_id = NULL){
		$FileInfoPostVO = new FileInfoPostVO();
		$FileInfoPostVO->File_info_active				= DataHandler::forceInt($File_info_active);
		$FileInfoPostVO->File_info_name					= DataHandler::forceString($File_info_name);
		$FileInfoPostVO->File_info_description			= DataHandler::forceString($File_info_description);
		$FileInfoPostVO->File_info_order				= DataHandler::forceInt($File_info_order);
		$FileInfoPostVO->File_info_locale				= $File_info_locale;
		$FileInfoPostVO->File_info_author				= DataHandler::forceString($File_info_author);
		//info de id de File, só envie caso a Filem já esteja cadastradas.
		//Caso a Filem já esteja cadastrada, só resta vincular
		$FileInfoPostVO->request_File_id 				= DataHandler::forceInt($File_id);
		//info de vinculo
		$FileInfoPostVO->table 							= DataHandler::forceString($vinculed_table);
		$FileInfoPostVO->table_id 						= DataHandler::forceInt($vinculed_table_id);
		$this->addItemInfoPostVO($FileInfoPostVO);
	}
}