<?php
include_once "library/facil3/core/controller/interface/HTTPControllerInterface.class.php" ;
include_once "library/facil3/utils/Navigation.class.php";
include_once "library/facil3/core/modules/highlights/dao/HighLightsDAO.class.php";
include_once "library/facil3/core/controller/highlights/HighlightsInfoPostVO.php";
include_once "library/facil3/core/dao/LinkDAO.class.php";

//para retorno
include_once "library/facil3/core/vo/ReturnResultVO.class.php";
/**
 * @author 		: Mauricio Amorim
 * @date		: 05/12/2010
 * @desc		: controller a ser extendida por outra controller para poder manipular a highlights
 */
class FacilHighlights implements HTTPControllerInterface{
	public $HighlightsInfoPostVO;
	public $array_highlights;

	protected $arrayVariable;
	protected $arrayRestFolder;
	
	public function __construct($arrayRestFolder = NULL){
		if($arrayRestFolder != NULL){
			$this->arrayVariable   = Navigation::getVariableArraySlug($arrayRestFolder);
            $this->arrayRestFolder = $arrayRestFolder;
            //por padrÃ£o ele popula as infos que ele possui com o que veio na restFolder, pode ser resetado utilizando resetInfoPost
            $this->HighlightsInfoPostVO = new HighlightsInfoPostVO($this->arrayVariable);
        }else{
        	$this->HighlightsInfoPostVO = new HighlightsInfoPostVO();
        }
	}

    /**
     * @return ReturnResultVO
     */
    public function init(){
    	//por padrÃ£o nÃ£o faz nada
    	return new ReturnResultVO(FALSE, array("nenhuma ação padrão"));
    }
    
 	/**
 	 * busca todos os highlightss conforme requisições na HighlightsInfoPostVO
	 * @return ReturnResultVO
	 */
	public function selectHighlights(){
    	$table		 		= DataHandler::forceString($this->HighlightsInfoPostVO->request_table);
    	$table_id			= DataHandler::forceInt($this->HighlightsInfoPostVO->request_table_id);
		$quant_start		= DataHandler::forceInt($this->HighlightsInfoPostVO->request_quant_start);
		$quant_limit		= DataHandler::forceInt($this->HighlightsInfoPostVO->request_quant_limit);
		$type_of_return		= DataHandler::forceString($this->HighlightsInfoPostVO->request_type_of_return);
		$locale				= DataHandler::forceString($this->HighlightsInfoPostVO->request_locale);
		
		// se nao foi passado table e table id naum da pra procurar
    	if($table == NULL && $table_id < 1){
    		$ReturnResultVO->success = FALSE;
			$ReturnResultVO->result = $this->array_highlights;
			$ReturnResultVO->array_messages[] = Translation::text("LibraryLanguage::ERROR_HIGHLIGHTS_NOT_FOUND");
			return $ReturnResultVO;
		}
		//inicia a LinkDAO para fazer a consulta
    	$DAO = HighLightsDAO::getInstance();		
	
		//busca highlightss vinculados a tabela e tabela id passados
		$ReturnDataVO = $DAO->selectByTableId($table, $table_id, $type_of_return, "highlights", $quant_limit, $quant_start);
		if($ReturnDataVO->success){
			//Debug::print_r($ReturnDataVO);exit();
			$ReturnResultVO->success = $ReturnDataVO->success;
			$ReturnResultVO->result = $ReturnDataVO->result;
			return $ReturnResultVO;
		}else{
			$ReturnResultVO->success = $ReturnDataVO->success;
			$ReturnResultVO->result = $this->array_highlights;
			$ReturnResultVO->array_messages[] = Translation::text("LibraryLanguage::ERROR_HIGHLIGHTS_NOT_FOUND");
			return $ReturnResultVO;
		}
	}
    
     /*
     * recebe o highlights por post e insere, observação para q deve ser passado table e table_id para que 
     * o conteudo seja vinculado
     * @return ReturnResultVO
     */
    public function insertHighlights(){
    	//inicia um retorno de ReturnResultVO
    	$ReturnResultVO = new ReturnResultVO();
    	
    	//pega os dados baseado na HighlightsInfoPostVO
	    $VO = new HighLightsVO();
		//popula no objeto
		$VO->setId($this->HighlightsInfoPostVO->id); 
		$VO->setActive($this->HighlightsInfoPostVO->active); 
		$VO->setName($this->HighlightsInfoPostVO->name);
		$VO->setLink($this->HighlightsInfoPostVO->link, $locale);
		$VO->setContent($this->HighlightsInfoPostVO->content, $locale);
		$VO->setImageUrl($this->HighlightsInfoPostVO->image_url, $locale);
		$VO->setDate($this->HighlightsInfoPostVO->date);
		$VO->setDateIn($this->HighlightsInfoPostVO->date_in);
		$VO->setDateOut($this->HighlightsInfoPostVO->date_out);
		$VO->setOrder($this->HighlightsInfoPostVO->order);
		
		//("Ja") gera id para criar pasta onde vai ser guardado o arquivo
		$ReturnResultHighLightsVO = $VO->commit();
		if($ReturnResultHighLightsVO->success){
			$ReturnResultHighLightsVO->result = $VO->getId();
		} else {
			//erro, os motivos estÃ£o na ReturnResultVO abaixo
			return $ReturnResultHighLightsVO;
		}
		
		if($ReturnResultHighLightsVO->success){
			//incluir o vinculo com a linked_table e linked_table_id
			//receber 	table
			//			table_id
			if($this->HighlightsInfoPostVO->request_table != NULL && $this->HighlightsInfoPostVO->request_table_id > 0){
				$table 		= 	$this->HighlightsInfoPostVO->request_table;
				$table_id	= 	$this->HighlightsInfoPostVO->request_table_id;
				
				include_once "library/facil3/core/dao/LinkDAO.class.php";
				
				$LinkDAO = new LinkDAO();
				//vincula a foto ao table e table_id enviado
				$ReturnResultVinculoVO = $LinkDAO->insert($table, $table_id, $this->moduleName, $VO->getId(), 1);
				if(!$ReturnResultVinculoVO->success){
					//deu erro ao vincular
					$ReturnResultVO->success = false;
					$ReturnResultVO->appendMessage($ReturnResultVinculoVO->array_messages);
					return $ReturnResultVO;
				}
			} else {
				return $ReturnResultVO->addMessage(Translation::text("LibraryLanguage::WARNING_HIGHLIGHTS_NO_LINKED_TABLE"));
			}
		} else {
			return $ReturnResultHighLightsVO;
		}
    }
    
    /**
     * reseta o atual HighlightsInfoPostVO
     * @return void
     */
    public function resetHighlightsInfoPostVO(){
    	$this->HighlightsInfoPostVO = new HighlightsInfoPostVO();
    }
}
