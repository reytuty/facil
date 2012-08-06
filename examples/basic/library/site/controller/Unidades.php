<?php
include_once("library/facil3/core/modules/category/dao/CategoryDAO.class.php");
include_once Config::FOLDER_APPLICATION."modules/content/dao/ContentSiteDAO.class.php";
include_once("library/facil3/core/modules/content/dao/ContentDAO.class.php");
include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/content_module/HttpContentModule.class.php"));

class Unidades implements HTTPControllerInterface{
	private $arrayRestFolder;
	private $arrayVariable;
	private $DAO;

	public function __construct($arrayRestFolder = NULL){
		$this->DAO = new CategoryDAO(); 
		if($arrayRestFolder != NULL){
			$this->arrayRestFolder = $arrayRestFolder;
			$this->arrayVariable   = Navigation::getVariableArraySlug($this->arrayRestFolder);
		}
	}
	/* (non-PHPdoc)
	 * @see library/facil3/core/controller/interface/HTTPControllerInterface#init()
	 */
	public function init(){
		//busca todas as paginas cadastradas na tabela content
		$returnResult = new HttpResult();
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new HttpContentModule();
		$retornoDaPaginaHTML->arrayVariable = $this->arrayVariable;
		/*
		 	$address1 = new stdClass();
			$address1->area = 'Rio de Janeiro (RJ)';
			$address1->description = 'Av. Das Nações Unidas, 4777 Lj 27 - Piso G1 / Tel: (11) 5042-7840';
			$address1->google_maps_url = 'http://maps.google.com.br/maps?f=q&source=s_q&hl=pt-BR&geocode=&q=Avenida+dos+Eucaliptos,+762,+Moema,+S%C3%A3o+Paulo&sll=-23.484148,-46.838983&sspn=0.01234,0.022724&ie=UTF8&hq=&hnear=Av.+dos+Eucaliptos,+762+-+Moema,+S%C3%A3o+Paulo,+04517-050&ll=-23.609531,-46.669793&spn=0.012092,0.022724&z=16';
			
			$place1 = new stdClass();
			$place1->area = 'SUDESTE';
			$place1->addresses = array($address1,$address1,$address1,$address1);
			
			$region1 = new stdClass();
			$region1->name = 'embreve';
			$region1->title = 'Em breve nocas unidades:';
			$region1->places = array($place1,$place1,$place1,$place1);

	$region2 = new stdClass();
	$region2->name = 'sp';
	$region2->title = 'São Paulo';
	$region2->places = array($place1,$place1,$place1,$place1);
	
	$region3 = new stdClass();
	$region3->name = 'ba';
	$region3->title = 'Bahia';
	$region3->places = array($place1,$place1,$place1,$place1);
	
	$region4 = new stdClass();
	$region4->name = 'mt';
	$region4->title = 'Mato Grosso';
	$region4->places = array($place1,$place1,$place1,$place1);
	
	$region4 = new stdClass();
	$region4->name = 'ms';
	$region4->title = 'Mato Grosso do Sul';
	$region4->places = array($place1,$place1,$place1,$place1);
	
	$HttpContentResult->regions = array($region1,$region2,$region3,$region4);
		 */
			//listar categorias de 19
			$array_category = $this->getCategoryCascade(19);
			//Debug::print_r($array_category);
			$arrayRegions = array();
			foreach($array_category as $stdCategory){
				$region = $this->getRegion($stdCategory->id, $stdCategory->name, $stdCategory->name);
				
				foreach($stdCategory->__array_category as $stdCategory2){
					//Debug::print_r($stdCategory2);
					$place = $this->getPlace($stdCategory2->name);
					$LinkDAO = LinkDAO::getInstance();
					$returnDataVO = $LinkDAO->select(LinkDAO::RETURN_VO, "category", $stdCategory2->id, "content", NULL, 1);
					//verifica se o resultado é uma categoryVO
					if($returnDataVO->success && count($returnDataVO->result)>0){
						foreach($returnDataVO->result as $LinkVO){
							$tempReturnDataVO = $LinkVO->getLinkedVO();
							//Debug::print_r($tempReturnDataVO);exit();
							if($tempReturnDataVO->success){
								//Debug::print_r($tempReturnDataVO->result);
								$address = new stdClass();
								$address->area = $tempReturnDataVO->result->title;
								$address->description = $tempReturnDataVO->result->hat;
								$address->google_maps_url = $tempReturnDataVO->result->author;
								$place->addresses[]  = $address;
							}
						}
						//exit();
					}
					
					$region->places[] = $place;
				}
				$arrayRegions[] = $region;
			}
			//Debug::print_r($arrayRegions);
			//em breve
			$retornoDaPaginaHTML->regions = $arrayRegions;//array($region1,$region2,$region3,$region4);
			
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
	private function getCategoryCascade($dad_category_id){
		$CategoryVO = new CategoryVO();
		$CategoryVO->setId($dad_category_id, TRUE);
		return $CategoryVO->selectCascade(CategoryDAO::RETURN_STD_OBJECT, 1);;
	}
	private function getRegion($id, $state, $title, $places = array()){
		$region = new stdClass();
		$region->id = $id;
		$region->name = $state;
		$region->title = $title;
		$region->places = $places;
		return $region;
	}
	private function getPlace($area, $unidades = array()){
		$place = new stdClass();
		$place->area = $area;
		$place->addresses = $unidades;
		return $place;
	}
	private function getUnidades(){
		
	}
	protected function getContent($id){
		//pega a CategoryVO
			$ContentVO = new ContentSiteVO();
			$ContentVO->setId($id, TRUE);
			$images = $ContentVO->getImages();
			
			$stdResult = new stdClass();
			$stdResult->title 		= $ContentVO->getTitle();
			$stdResult->content 	= $ContentVO->getContent();
			$stdResult->image_url 	= array();
			
			if(count($images)> 0){
				foreach($images as $image){
					$url = Config::getRootPath("/image/get_image/image_id.".$image->id."/max_width.$width/max_height.$height/crop.1/");//é o link
					$stdResult->image_url[] = $url;
				}
			}
		
		return $stdResult;
	}
}