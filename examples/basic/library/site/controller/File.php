<?php
include_once("library/facil3/navigation/http/HttpResult.class.php");
include_once("facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once("library/facil3/core/modules/file/dao/FileDAO.class.php");

class File implements HTTPControllerInterface{
	
	private $DAO;
	protected $arrayRestFolder 	= array();
	protected $arrayVariable 		= array();
	public function __construct($arrayRestFolder = NULL){
		if($arrayRestFolder != NULL){
			$this->arrayRestFolder = $arrayRestFolder;
			$this->arrayVariable   = Navigation::getVariableArraySlug($this->arrayRestFolder);
		}
	}
	/* 
	 * @return lista de usuarios ativos
	 */
	public function init(){
		echo "404";
		exit();
	}
	/**
	 * envie por post uma array de ids de files, só os ids
	 * file/get_ziped_files
	 */
	public function getZipedFiles(){
		if(!UserClient::getId() > 0){
			//nao tem permissao
			Navigation::redirect("405");
			exit();
		}
		$array_file_ids = DataHandler::getValueByArrayIndex($_POST, "file_id");
		//Debug::print_r($_REQUEST);
		$array_file_vo = array();
		if(is_array($array_file_ids)){
			foreach($array_file_ids as $id){
				$FileVO = new FileVO();
				$Result = $FileVO->setId($id, TRUE);
				if($Result->success == TRUE) $array_file_vo[] = $FileVO;
			}
		} else {
			//erro, não é uma array, verifica se pelo menos é 1 único id
			$id = DataHandler::forceInt($array_file_ids);
			if($id > 0){
				//é um id único
				$FileVO = new FileVO();
				$Result = $FileVO->setId($id, TRUE);
				if($Result->success == TRUE)
					$array_file_vo[] = $FileVO;
			} else {
				//erro mesmo, esse dado é zoado, estoura excessão
				throw new Exception("No ids sended", 404);
				exit();
			}
		}
		//a pasta zip precisa existir
		DataHandler::createFolderIfNotExist("upload/zip/");
		//verifica o nome do arquivo baseado nos ids enviados conforme regra inventada agora
		$zip_name = "upload/zip/".md5(implode("|", $array_file_ids));
		DataHandler::createFolderIfNotExist($zip_name);
		$zip_name = $zip_name."/teto.zip";
		if(!file_exists($zip_name)){
			//echo Debug::li($zip_name);exit();
			$Zip = new ZipArchive();
			$Zip->open($zip_name, ZipArchive::CREATE);
			foreach($array_file_vo as $FileVO){
				$url 	= $FileVO->getUrl();
				$array 	= explode("/", $url);
				$file 	= $array[count($array)-1];
				$Zip->addFile($url, $file);
			}
			$Zip->close();
		}
		header("Location: ".Config::getRootPath($zip_name));
		exit();
	}
	public function delete(){
		
	}

}