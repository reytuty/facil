<?php
include_once("library/facil3/core/modules/category/dao/CategoryDAO.class.php");
include_once("library/facil3/core/modules/category/vo/CategoryVO.class.php");
include_once "library/forum/modules/highlights/dao/GenericHighLightsDAO.class.php";
include_once "library/facil3/core/controller/highlights/HighlightsInfoPostVO.php";
include_once "library/forum/modules/generic_content/dao/GenericContentDAO.class.php";
include_once "library/facil3/core/controller/content/ContentInfoPostVO.php";

include_once "library/facil3/core/modules/file/dao/FileDAO.class.php";
include_once "library/facil3/core/modules/image/dao/ImageDAO.class.php";
include_once "library/facil3/core/dao/LinkDAO.class.php";

//para retorno
include_once "library/facil3/core/vo/ReturnResultVO.class.php";

/**
 * @author 		: Mauricio Amorim
 * @date		: 05/12/2010
 * @version		: 1.0
 * @desc		: Controller de contentns e relação com link
 * 					
 */
class Footer{
	
	public function __construct(){
    }
    
	public function getFooter(){
		//iniciando o retorno padrao $ReturnResultVO
		$ReturnResultVO = new ReturnResultVO();
		//inicia a DAO
		$DAO = CategoryDAO::getInstance();
		$selectCategory = "footer";
		//pesquisa pela categoria pai
		$ReturnDataVO = $DAO->select(CategoryDAO::RETURN_VO, NULL, NULL, NULL, NULL, $selectCategory);
		if($ReturnDataVO->success && count($ReturnDataVO->result)>0){
			//pega a CategoriaVO pai da resposta
			$DadCategoryVO = $ReturnDataVO->result[0];
			//traduz os compos da categoria pai conforme o locale
			$StdClassDadCategory = $DadCategoryVO->toStdClass(Config::getLocale());
			
			
			//inicia um array para armazenar os contents da categoria pai
			$arrayStdClassHighlights = array();
			//adiciona os conents encontrados no campo $array_highlights da categoria pai $StdClassDadCategory
			$StdClassDadCategory->array_highlights = $arrayStdClassHighlights;
		
			
			$arrayChildsCategoryVO = array();
			//busca as categorias Filhas da pai
			$ReturnDataVO = $DAO->select(CategoryDAO::RETURN_VO, NULL, NULL, $StdClassDadCategory->id, NULL, NULL, "<");
			if($ReturnDataVO->success){
				$arrayChildsCategoryVO = $ReturnDataVO->result;
				$StdClassArrayChildsCategoryVO = array();
				foreach($arrayChildsCategoryVO as $ChildsCategoryVO){
					//traduz os compos da categoria filha conforme o locale
					$StdClassChildCategory = $ChildsCategoryVO->toStdClass(Config::getLocale());				
					
						
					//inicia a GenericHighLghtDAO para fazer a consulta
			    	$DAO = GenericHighLightsDAO::getInstance();		
					//busca highlightss vinculados a tabela e tabela id passados
					$ReturnDataVO = $DAO->selectByTableId("category", $StdClassChildCategory->id, GenericHighLightsDAO::RETURN_VO, "highlights");
					//Debug::print_r($ReturnDataVO);exit();
					
					//Debug::print_r($ReturnResultVO);
					//inicia um array para armazenar os contents da categoria filho
					$arrayStdClassHighlights = array();
					//pega as imagens vinculadas ao content e tabem traduz os campos do mesmo content da categoria filho conforme o locale solicitado
					foreach($ReturnDataVO->result as $HighLightsVO){
						$arrayStdClassHighlights[] = $HighLightsVO->toStdClass(Config::getLocale());
					}
					//adiciona os conents encontrados no campo $array_highlights da categoria pai $StdClassDadCategory
					$StdClassChildCategory->array_highlights = $arrayStdClassHighlights;
					
					
					//adiciona o a categoria filho no array de categorias filho
					$StdClassArrayChildsCategoryVO[] = $StdClassChildCategory;
				}
				//setando sucesso true caso tenha dado certo
				$ReturnResultVO->success = TRUE;
				$ReturnResultVO->result = $StdClassArrayChildsCategoryVO;
			}

		}
		//Debug::print_r($ReturnResultVO);
		return $ReturnResultVO;
    } 
}