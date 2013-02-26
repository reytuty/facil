<?php 
include_once("library/facil3/navigation/http/HttpResult.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/HttpRoot.class.php"));
include_once "library/site/modules/content/dao/ContentSiteDAO.class.php";
include_once "library/facil3/core/dao/LinkDAO.class.php";
include_once "library/facil3/core/modules/content/ContentFormView.class.php";
include_once "library/facil3/core/modules/content/ContentSelectView.class.php";
include_once "library/site/controller/admin/default/GenericAdminController.class.php";
include_once "library/facil3/core/modules/category/vo/CategoryVO.class.php";
/**
 * @author 		Renato Seiji Miawaki
 * @desc  		para trocar e definir ordem de links
 * 				acessar admin/order_change/link/
 */
class OrderChange extends GenericAdminController{
	
	public function __construct($arrayRestFolder = NULL){
		parent::__construct($arrayRestFolder);
	}
	/**
	 * @return void da echo de string
	 */
	public function link(){
		$LinkDAO = LinkDAO::getInstance();
		if(FALSE){
			$LinkDAO = new LinkDAO();
		}
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->success = TRUE;
		
		//enviar por post:
			//category_id
			//array_content_id
		if(DataHandler::getValueByArrayIndex($_POST, "category_id") && DataHandler::getValueByArrayIndex($_POST, "array_content_id")){
			$category_id = DataHandler::forceInt(DataHandler::getValueByArrayIndex($_POST, "category_id"));
			//talvez não seja array
			$array_content_id = DataHandler::getValueByArrayIndex($_POST, "array_content_id");
			$table = DataHandler::getValueByArrayIndex($_POST, "table");
			$linked_table = DataHandler::getValueByArrayIndex($_POST, "linked_table");
			
			if(!is_array($array_content_id)){
				$array_content_id = explode(",", $array_content_id);
			}
			if(is_array($array_content_id)){
				$contador = 0;
				foreach($array_content_id as $content_id){
					//inicia a linkVO se existir
					$ReturnDataVO = $LinkDAO->select(LinkDAO::RETURN_VO, $table, $category_id, $linked_table, $content_id, 1);
					if(FALSE){
						$ReturnDataVO = new ReturnDataVO();
					}
					if($ReturnDataVO->success){
						$arrayResults = $ReturnDataVO->result;
						if($ReturnDataVO->count_total > 0){
							//pega só o primeiro, mas se tiver mais do que 1 poderia dar um warning
							if($ReturnDataVO->count_total > 1){
								//warning, deveria ter só 1
								
							}
							$LinkVO = $arrayResults[0];
							//para ajuda do aptana
							if(FALSE){
								$LinkVO = new LinkVO();
							}
							$LinkVO->setOrder($contador);
							$ReturnDataVO = $LinkDAO->update($LinkVO->getId(), $LinkVO->getActive(), $LinkVO->getTable(), $LinkVO->getTableId(), $LinkVO->getLinkedTable(), $LinkVO->getLinkedTableId(), $LinkVO->getOrder());
							if(!$ReturnDataVO->success){
								$ReturnResultVO->success = FALSE;
								$ReturnResultVO->addMessage("erro ao atualizar o item de id:".$LinkVO->getLinkedTableId());
							}
							$contador++;
						}
					}
				}//end foreach
			} else {
				$ReturnResultVO->success = FALSE;
				$ReturnResultVO->addMessage("Enviar content_id por POST em array");
			}//end if array
		} else {
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage("Enviar por POST category_id e array_content_id.");
		}//end if foi enviado posts
		if($ReturnResultVO->success){
			$ReturnResultVO->addMessage("Ordem definida com sucesso.");
		}
		echo $ReturnResultVO->toJson();
		exit();
	}
}
