<?php
include_once "library/facil3/core/modules/content/vo/ContentVO.class.php";

/**
 * @author 		: Renato Seiji Miawaki
 * @desc		: Para facilitar a vida e já buscar automático as imagens e files vinculados, pelo locale
 */
class ContentSiteVO extends ContentVO{
	private $_array_images;
	private $_array_files;
	protected $_array_categories_dad;
	/**
	 * @param $LOCALE string, padrão é pt_BR
	 * @return array de VO
	 */
	public function getImages($LOCALE = NULL, $baseTable = "image", $force = FALSE){
		//if($LOCALE == NULL){
			//se locale for null, transforma no padrao, que pra esse projeto é pt_BR
		//	$LOCALE = "pt_BR";
		//}
		if(!$this->_array_images || $force){
			$this->_array_images = array();
			$arrayLinkVO = $this->getLinks($baseTable, 1);
			//varre a array de LinkVO para transformar em ImageVO
//			print_r($arrayLinkVO);exit();
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
	public function getCategoriesDad(){
		if(!$this->_array_categories_dad){
			$LinkDAO = LinkDAO::getInstance();
			if(FALSE) $LinkDAO = new LinkDAO();
			$ReturnResultDAO = $LinkDAO->select(LinkDAO::RETURN_STD_OBJECT, "category", NULL, "content", $this->id, 1);
			if($ReturnResultDAO->success && $ReturnResultDAO->count_total > 0){
				$this->_array_categories_dad = array();
				foreach($ReturnResultDAO->result as $linkVO){
					$this->_array_categories_dad[] = $linkVO->table_id;
				}
			}
		}
		return $this->_array_categories_dad;
	}
}