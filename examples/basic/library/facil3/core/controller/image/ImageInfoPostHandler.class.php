<?php
include_once "library/facil3/core/controller/image/ImageInfoPostVO.php";
/**
 * @author 	Renato Miawaki
 * @desc	Classe para tratar o que é enviado e formar um info post para várias imagens
 */
class ImageInfoPostHandler {
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
	 * @param $array_send_in_pattern 	array com diversas arrays de informações relativas a image,
	 * 									só é útil para casos que trabalhe dentro do padrão de recebimento
	 * 									caso contrário utilize addItem separadamente
	 * @return void
	 */
	public function __construct($array_send_info = NULL, $array_files = NULL){
		if($array_send_info){
			$this->setFetchArray($array_send_info, $array_files);
		}
	}
	/**
	 * pega a array e trata para transformar numa array de InfoPost dentro do padrão do plugin
	 * @param $array_send_in_pattern 	ver a construct
	 * @return void
	 */
	public function setFetchArray($array_send_info, $array_files){
			$type = "image";
			for($i = 0 ; $i< count($array_files); $i++){
				//cria uma array no padrao para a ImageInfoPostVO
				$data = array(
					$type . "_info_active" 			=> $array_send_info[ $type . '_active'][$i],
					$type . "_info_type" 			=> $array_send_info[ $type . '_type'][$i],
					$type . "_info_author" 			=> $array_send_info[ $type . '_author'][$i],
					$type . "_info_name" 			=> $array_send_info[ $type . '_name'][$i],
					$type . "_info_description" 	=> $array_send_info[ $type . '_description'][$i],
					$type . "_info_order"		 	=> $array_send_info[ $type . '_order'][$i],
					$type . "_info_locale" 			=> $array_send_info[ $type . '_locale'][$i],
					"Filedata" 						=> $array_files[$i]
				);
				$ImageInfoPostVO = new ImageInfoPostVO();
				$ImageInfoPostVO->setFetchArray($data);
				//verifica se precisa vincular e se essa info foi enviada
				if($this->linked_table){
					$ImageInfoPostVO->table 	= $this->linked_table;
					$ImageInfoPostVO->table_id 	= $this->linked_table_id;
				}else if(is_array($array_send_info[$type."_table"])){
					//se enviar array, significa que para cada conjunto de fotos terá um vinculo diferente
					//util para casos em que o conteudo tem mais de uma foto e cada uma para uma determinada utilidade
					$ImageInfoPostVO->table 	= $array_send_info[$type."_table"][$i];
					$ImageInfoPostVO->table_id 	= $array_send_info[$type."_table_id"][$i];
				} else if($array_send_info[$type."_table"]){
					//se todas as imagens obedecem ao mesmo vinculo, então envie somente o table e table_id sem ser array
					$ImageInfoPostVO->table 	= $array_send_info[$type."_table"];
					$ImageInfoPostVO->table_id 	= $array_send_info[$type."_table_id"];
				}
				//adiciona na array
				$this->addItemInfoPostVO($ImageInfoPostVO);
			}//end for($i = 0 ; $i< count($array_files); $i++){
			//agora o for de item a deletar
			for($i = 0 ; $i< count($array_send_info[$type."_delete"]); $i++){
				//dentro de cada indice da array $array_send_info[$type."_delete"] deve vir o id da imagem
				//aqui ele só guarda a array
				$this->arrayToDelete[] = $array_send_info[$type."_delete"][$i];
			}
	}
	/**
	 * @return array de ImageInfoPostVO
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
	 * @param $ImageInfoPostVO um objeto do tipo ImageInfoPostVO
	 * @return void
	 */
	public function addItemInfoPostVO(ImageInfoPostVO  $ImageInfoPostVO){
		$this->arrayInfoPost[] = $ImageInfoPostVO;
	}
	/**
	 * @param $image_info_locale
	 * @param $image_info_author
	 * @param $image_info_active
	 * @param $image_info_name
	 * @param $image_info_description
	 * @param $image_info_order
	 * @param $posted_file_data
	 * @param $vinculed_table
	 * @param $vinculed_table_id
	 * @return void
	 */
	public function addItem($image_info_locale = NULL, $image_info_author = NULL, $image_info_active = 1, $image_info_name = NULL, $image_info_description = NULL, $image_info_order = NULL, $posted_file_data = NULL, $vinculed_table = NULL, $vinculed_table_id = NULL){
		$ImageInfoPostVO = new ImageInfoPostVO();
		$ImageInfoPostVO->image_info_active				= DataHandler::forceInt($image_info_active);
		$ImageInfoPostVO->image_info_name				= DataHandler::forceString($image_info_name);
		$ImageInfoPostVO->image_info_description		= DataHandler::forceString($image_info_description);
		$ImageInfoPostVO->image_info_order				= DataHandler::forceInt($image_info_order);
		$ImageInfoPostVO->image_info_locale				= $image_info_locale;
		$ImageInfoPostVO->image_info_author				= DataHandler::forceString($image_info_author);
		//info de id de image, só envie caso a imagem já esteja cadastradas.
		//Caso a imagem já esteja cadastrada, só resta vincular
		$ImageInfoPostVO->request_image_id 				= DataHandler::forceInt($image_id);
		//info de vinculo
		$ImageInfoPostVO->table 						= DataHandler::forceString($vinculed_table);
		$ImageInfoPostVO->table_id 						= DataHandler::forceInt($vinculed_table_id);
		$this->addItemInfoPostVO($ImageInfoPostVO);
	}
}