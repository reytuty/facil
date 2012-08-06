<?php
	/**
	 * @author		: Mauricio Amorim
	 * @date		: 15/07/2010
	 * @version		: 1.0
	 * @tabela		: tabela category
	 * @slug		: 
	 * 					A tabela category
	 *  
	  						id, 
	  						active, 
	  						category_id,
	  						name,
	  						slug,
	  						order
	 *
	 * @obs			: utiliza a tabela parametro para designar o idioma (tipo:label_ptbr)
	 */
//importa classes de apoio
include_once Config::FOLDER_APPLICATION."/modules/category/dao/CategoryEncDAO.class.php";
include_once "library/facil3/core/modules/category/vo/CategoryVO.class.php";
include_once "library/facil3/interface/vo/PatternVO.class.php";
include_once "library/facil3/core/vo/BaseVO.class.php";
include_once "library/facil3/core/DbInterface.class.php";

class CategoryEncVO extends CategoryVO{
	private $_array_images;
	private $_array_files;
	
	
	/**
	 * @param $description
	 * @return void
	 */
	public function setDescription($description){
//		print_r($description);
		if($description != NULL){
			//adntes vai verificar se já existe um parametro descrição
			$arrayParamether = parent::getParamethersByValues("description", ParametherDAO::TYPE_TEXT);
			//se existir atualiza
			$parametherId = NULL;
			if(count($arrayParamether) > 0){
				if($arrayParamether[0]->getId() > 0){
					$parametherId = $arrayParamether[0]->getId();
				}
			}
			parent::addParamether(ParametherDAO::TYPE_TEXT, "description", $description, NULL, $parametherId);
		}
	}
	
	/**
	 * @return $description
	 */
	public function getDescription(){
		//adntes vai verificar se já existe um parametro descrição
		$arrayParamether = parent::getParamethersByValues("description", ParametherDAO::TYPE_TEXT);
		//se existir retorna
		if(count($arrayParamether) > 0){
			if($arrayParamether[0]->getId() > 0){
				return $arrayParamether[0]->getValue();
			}
			return NULL;
		}
		return NULL;
	}

	/**
	 * precisa enviar uma array dentro dos padr�es dessa VO, pode enviar diretamente o $_POST
	 * @param (array) $array_dados
	 * @return void
	 */
	function setFetchArray($array_dados){
		$this->setDescription(DataHandler::getValueByArrayIndex($array_dados, "description"));
		parent::setFetchArray($array_dados);
	}
	
	/**
	 * @param (object) $obj_dados
	 * @return void
	 */
	function setFetchObject($obj_dados){
		$this->setDescription(DataHandler::getValueByStdObjectIndex($obj_dados, "description"));
		parent::setFetchObject($obj_dados);
	}	
	
	/**
	 * @param $LOCALE string, padrão é pt_BR
	 * @return array de VO
	 */
	public function getImages($LOCALE = NULL){
		if(!$this->_array_images){
			$this->_array_images = array();
			$arrayLinkVO = $this->getLinks("image", 1);
			//varre a array de LinkVO para transformar em ImageVO
			//print_r($arrayLinkVO);exit();
			foreach($arrayLinkVO as $LinkVO){
				if(FALSE){
					//para o aptana me ajudar
					$LinkVO = new LinkVO();
				}
				$ReturnDataVO = $LinkVO->getLinkedVO();
				if($ReturnDataVO->success){
					//pega o VO e da push na array interna
					$this->_array_images[] = $ReturnDataVO->result;
				}
			}
		}
		return $this->_array_images;
		
		//print_r($this->_array_images);exit();
		$arrayRetorno = array();
		//chegando aqui ele já buscou as imagens que possuia e transformou em VO, varre
		foreach($this->_array_images as $VO){
			if(FALSE){
				$VO = new ImageVO();
				//para o aptana me ajudar
			}
			//agora vai filtar pelo locale recebido
			//$arrayRetorno[] = $VO;
			//if($LOCALE != NULL && $LOCALE == $VO->getLocale()){
				$arrayRetorno[] = $VO;
			//}else if($LOCALE == NULL){
			//	$arrayRetorno[] = $VO;
			//}
		}
		//print_r($arrayRetorno);exit();
		return $arrayRetorno;
	}
	public function getFiles($LOCALE = NULL){
		if(!$this->_array_files){
			$this->_array_files = array();
			$arrayLinkVO = $this->getLinks("file", 1);
			
			//varre a array de LinkVO para transformar em ImageVO
			foreach($arrayLinkVO as $LinkVO){
				if(FALSE){
					//para o aptana me ajudar
					$LinkVO = new LinkVO();
				}
				$ReturnDataVO = $LinkVO->getLinkedVO();
				if($ReturnDataVO->success){
					//pega o VO e da push na array interna
					$this->_array_files[] = $ReturnDataVO->result;
				} else {
					//se der erro, veja sua config para ver se o módulo file está cadastrado
				}
			}
		}
		return $this->_array_files;
		
		//Debug::print_r($this->_array_files);
		$arrayRetorno = array();
		//chegando aqui ele já buscou as imagens que possuia e transformou em VO, varre
		foreach($this->_array_files as $VO){
			if(FALSE){
				$VO = new FileVO();
				//para o aptana me ajudar
			}
			//agora vai filtar pelo locale recebido
			//if($LOCALE == $VO->getLocale()){
				$arrayRetorno[] = $VO;
			//}
		}
		return $arrayRetorno;
	}
}