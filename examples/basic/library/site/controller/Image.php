<?php

include_once "library/facil3/core/controller/image/FacilImage.php" ;
include_once "library/facil3/utils/WaterMark.class.php";
/**
 * @author 		: Renato Miawaki
 * @date		: 05/12/2010
 * @version		: 1.0
 * @desc		: Controller de imagens e relação com link
 * 					Retorno padrão é json, nessa versão
 *
 */
class Image extends FacilImage{
	const templateResponse = "{\"success\":php[success], \"response\":\"php[response]\", \"message\":\"php[message]\"}";
	
	public function __construct($arrayRestFolder = NULL){
		//popula info
		parent::__construct($arrayRestFolder);
		$this->defaultFolderForNewImages = "upload/image/";
		//resetando o nome do módulo para linkar para a tabela image, igual foi feita a importação
		$this->moduleName 		= $this->getGalleryType();
		$this->defaultImage404 	= "view/site/assets/images/no_image_avaliable.jpg";
		$this->defaultMinWidth				= 100;//aqui o tamanho padrão de thumb caso não seja enviado
		$this->defaultMinHeight				= 100;
    }
    public function init(){
    	$returno = parent::init();
    	echo utf8_encode($returno->toJson());
    	exit();
    }
    public function getImage(){
    		
    		$this->infoPost->request_image_id = DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "image_id"));
    		parent::getImage();
    		exit();
    }
	private function getGalleryType(){
		
		return DataHandler::getValueByArrayIndex($this->arrayVariable, "type");
	}
    public function exemplo(){
    	//aqui um exemplo de como postar uma imagem para esse módulo
    	//veja a view. ta tudo lá.
    }
    public function insert($echo_json = TRUE){
    	//para inserir imagem precisa estar logado como admin
    	if(UserClient::getTypeId() == Config::ADMIN_USER_TYPE_ID && UserClient::getActiveTime() && UserClient::getActive()){
    		//se tiver ok, blz, o que me importa é o else
    		
    	} else {
    		
    		//exit();//sem explicações
    	}
    	$this->infoPost = new ImageInfoPostVO();
    	$this->infoPost->file_data 	= $_FILES["Filedata"];
    	//passando como null o table, ele não linka
    	
    	$this->infoPost->table = NULL;
    	
    	$retorno = parent::insert();
    	
    	if($retorno->success){
    		//vai pegar a url da imagem
    		include_once "library/facil3/core/modules/image/vo/ImageVO.class.php";
    		$ImageVO = new ImageVO();
    		$ImageVO->setId($retorno->result, TRUE);
			if($this->infoPost->table_id){
	    		//foi enviado para linkar, então pega o id
	    		$url = $ImageVO->getUrl();
	    		//pre-conceito na moral de que se uma imagem é enviada, é para produto
	    		include_once Config::FOLDER_APPLICATION."modules/product_model/vo/ProductModelQuadroVO.class.php";
	    		$ProductModelQuadroVO = new ProductModelQuadroVO();
	    		$retorno_product = $ProductModelQuadroVO->setId($this->infoPost->table_id, TRUE);
	    		//ve se existe o produto
	    		if($retorno_product->success){
	    			//atualiza url
	    			$ProductModelQuadroVO->setQuadroImagePath($url);
	    			//grava
	    			$ProductModelQuadroVO->commit();
	    		}
	    	}
    		
    	}
    	if($echo_json){
    		echo utf8_encode($retorno->toJson());
    		exit();
    	} else {
    		return $retorno;
    	}
    }
}