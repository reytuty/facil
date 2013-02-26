<?php
	/* 
	 * @date		: 15/07/2010
	 * @version		: 1.0
	 * @tabela		: tabela paramether
	 * @description		:classe base onde contem os metodos padões de menipulação da BaseVO somado 
	 * 					a metodos de manipulação de Paremetros.
	 * 					A tabela paramether
	 *  
							id
							table
							table_id
							name
							type
							varchar
							text
							number
							int
							date
							active

	 *
	 * @obs	: utiliza a tabela paramether para designar o idioma (tipo:label_ptbr)
	 */
//importa classes de apoio
include_once "library/facil3/core/modules/paramether/dao/ParametherDAO.class.php";
include_once "library/facil3/core/modules/paramether/vo/ItemParametherVO.class.php";
include_once "library/facil3/interface/vo/PatternVO.class.php";
include_once "library/facil3/core/vo/BaseVO.class.php";

class BaseParametherVO extends BaseVO{
	
	private $__arrayParamethers;
	
	public function _construct(){
		parent::_construct();
	}

	/* 
	 * metodo de inserção e atualização da classe extendida, mais a inserção eu atualização 
	 * de parametros 
	 * @param $type
	 * @return $ReturnResultVO
	 */
	public function commit($validade = FALSE){
		$ReturnResultVO = parent::commit($validade);
		if($ReturnResultVO->success){
			//cadastra link, se houver
			$ReturnResultLinkVO = $this->insertParamether();
			$ReturnResultVO->success = $ReturnResultLinkVO->success;
			if(!$ReturnResultVO->success){
				$ReturnResultVO->appendMessage($ReturnResultLinkVO->array_messages);
			}			
		}
		return $ReturnResultVO;
	}
	
	/**
	 * para adicionar ou atualizar parametros a uma tabela
	 * 
	 * @param $type
	 * @param $value
	 * @param $t_id
	 * @param $active
	 * @return void
	 */
	public function addParamethers($type = NULL, $value = NULL, $t_id = "insert", $active = NULL){
		//se passar o t_id indica que está editando um parametro já existente
		//criando a array a ser inserida - usado como se fosse um mini VO
		$this->startParametherDAO();
		$ParametherVO = $this->ParametherDAO->getItemParametherVO();

		$ParametherVO->setStatus(($t_id == "insert" || $t_id == NULL)? ParametherDAO::STATUS_INSERT: ParametherDAO::STATUS_UPDATE);
		$ParametherVO->setType($type);
		$ParametherVO->setValueByType($ParametherVO->getType(), $value);
		$ParametherVO->setTableId($this->id);
		$ParametherVO->setTable($this->__table);
		$ParametherVO->setActive($active);

		//verifica se deve c a array de parameter
		if($this->id > 0){
			//se já tem id é pq é um dado já cadastrado
			$this->getParamethers();
		}
		//se não tem, então vai adicionar só os terms novos
		if(!count($this->__arrayParamethers) > 0){
			//iniciando a array para demonstrar que já buscou ou tentou buscar
			$this->__arrayParamethers = array();
		}
		$t_id = DataHandler::forceInt($t_id);
		if($t_id > 0){
			$ParametherVO->setId($t_id);
		}
		$this->updateParamether($ParametherVO);
		//else {
			//push/
			//echo "<li>adicionando".var_dump($ParametherVO)."</li>";
			//$this->__arrayParamethers[] = $ParametherVO;
		//}
		//s vai realmente adicionar quando for chamado o metodo cadastrar()
	}

	/**
	 * metodo para atualizar um parametro
	 * @param $ParametherVO
	 * @return true
	 */
	private function updateParamether($ParametherVO){
		//vai varrer todos os parametros existentes, se houver um igual,ele atualiza.
		for($i = 0; $i < count($this->__arrayParamethers); $i++){
			//echo "<li>procurando parameter [$i] </li>";
			$tempParamether = $this->__arrayParamethers[$i];
			if($tempParamether->getId() != NULL && $tempParamether->getId() == $ParametherVO->getId()){
				$tempParamether->setStatus(ParametherDAO::STATUS_UPDATE);
				$this->__arrayParamethers[$i] = $ParametherVO;
				//print_r($this->__arrayParamethers[$i]);exit();
				return TRUE;
			}
		}
		$this->__arrayParamethers[] = $ParametherVO;
		return TRUE;
	}
	
	/**
	 * @param $title
	 * @param $description
	 * @param $active
	 * @param $user_id
	 * @param $force
	 * @return array_paramether_vo
	 */
	public function getParamethers($type = NULL, $value = NULL, $active = NULL, $force = FALSE){
		if(count($this->__arrayParamethers) > 0 && !$force){
			//já fez a busca, basta retornar			
		} else {
			//echo "<li> não existe, vou buscar </li>";
			$this->__arrayParamethers = array();
			if($this->validateParamether()){
				$this->startParametherDAO();
				$ReturnDataVO = $this->ParametherDAO->select($this->__table, $this->getTableId(), ParametherDAO::RETURN_STD_OBJECT);
				if($ReturnDataVO->success){
					$ReturnResultVO->success = TRUE;
					foreach($ReturnDataVO->result as $paramether){
						$ItemParametherVO = new ItemParametherVO($paramether);
						$ItemParametherVO->setStatus(ParametherDAO::STATUS_OK);
						$this->__arrayParamethers[] = $ItemParametherVO;					
					}
				} else {
					//apenas no debug - nao precisa desse else
					//Debug::pre($ReturnResultVO);
				}
			}
		}
		return $this->filterActiveParamether($this->__arrayParamethers, $type, $value, $active);
	}

	private function filterActiveParamether($array, $type = NULL, $value = NULL, $active = NULL){
		//filtra os ativos = $ativo para a array geral
		$tempArray = array();
		for($i = 0; $i < count($array); $i++){
			$tempParametherVO = $array[$i];
			//$tempTermsVO = new TermVO();
			$select = TRUE;
			if($active !== NULL){
				if($tempParametherVO->getActive() != $active){
					$select = FALSE;
				}
			}
			if($type !== NULL){
				if($tempParametherVO->getType() != $type){
					$select = FALSE;
				}
			}
			if($value !== NULL){
				if($tempParametherVO->getValue() != $value){
					$select = FALSE;
				}
			}
			if($select){
				$tempArray[] = $tempParametherVO;
			}
		}
		return $tempArray;
	}	
	
	//retorna false para erro e true com array de ids inseridos
	private function insertParamether(){
		//verifica se essa VO tem id e table - minimo para vincular um parametro a um elemento
		$ReturnResultVO = new ReturnResultVO();
		if($this->validateParamether()){
			//verifica se tem terms para cadastrar novos ou alterados para cadastrar.
			
			//se successo tem term para cadastrar
			if($this->haveParametherToRegister()){
				$this->startParametherDAO();
				$ReturnDataVO = $this->ParametherDAO->updateItemVO($this);
				//Debug::li("tem parametros....:");
				//Debug::print_r($ReturnDataVO);
				
			if(!$ReturnDataVO->success){
					//obs $ReturnDataVO retorna array somente em caso de erro no updateVO()
					$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_COMMENT_UPDATE_COMMIT"));
				}
				$ReturnResultVO->success = $ReturnDataVO->success;
				return $ReturnResultVO;
			} else {
				//echo "<li>nao tem parameter</li>";
				$ReturnResultVO = new ReturnResultVO();
				$ReturnResultVO->success = TRUE;
				return $ReturnResultVO;
			}
		} else {
			//nao tem id
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_COMMENT_ADD_DONOT_HAVE_TABLE_ID"));
			return $ReturnResultVO;
		}
	}

	private function validateParamether(){
		//vai validar a VO inteira conforme as regras
		if(!Validation::blank($this->__table, 2)){
			return FALSE;
		}
		if(!$this->getId() > 0){
			return FALSE;
		}
		return TRUE;
	}

	//se tiver alguma coisa pra registrar retorna ReturnResult->success = TRUE
	private function haveParametherToRegister(){
		//echo "<li> tem [".count($this->__arrayParamethers)."] Paramethers </li>";
		for($i = 0; $i < count($this->__arrayParamethers); $i++){
			$tempVO = $this->__arrayParamethers[$i];
			if($tempVO->getStatus() != ParametherDAO::STATUS_OK){
				//$tempVO->setTableId($this->id);
				return TRUE;
			}
		}
		return FALSE;
	}
	
	
	/**
	 * @param $title
	 * @param $description
	 * @param $active
	 * @param $user_id
	 * @param $paramether_id
	 * @return $CommenVO
	 */
	public function getParamether($title = NULL, $description = NULL, $active = NULL, $user_id = NULL, $paramether_id = NULL){
		if(!count($this->__arrayParamethers) > 0){
			$this->_arrayParamethers = $this->getParamethers();
		} 
		foreach($this->__arrayParamethers as $CommenVO){
			$select = TRUE;
			if($paramether_id > 0){
				if($CommenVO->getParametherId() != $paramether_id){
					$select = FALSE;
				}
			}
			if($active !== NULL){
				if($CommenVO->getActive() != $active){
					$select = FALSE;
				}
			}
			if($title !== NULL){
				if($CommenVO->getTitle() != $title){
					$select = FALSE;
				}
			}
			if($description !== NULL){
				if($CommenVO->getDescription() != $description){
					$select = FALSE;
				}
			}
			if($user_id > 0){
				if($CommenVO->getUserId() != $user_id){
					$select = FALSE;
				}
			}
			if($select){
				return $CommenVO;
			}
		}
	}		
	
	/**
	 * @desc é para passar o id do parametherario
	 * @param $id
	 * @return unknown_type
	 */
	public function deleteParamether($id){
		//precisa saber o id do term para remove-lo
		for($i = 0; $i < count($this->__arrayParamethers); $i++){
			//se achar a array que tenha a tabela
			if($this->__arrayParamethers[$i]->getId() == $id){
				$ParametherItemVO = $this->__arrayParamethers[$i];
				$ParametherItemVO->setStatus($ParametherItemVO->STATUS_DELETE);
				return TRUE;
			}//end if
		}//end for $i
		return FALSE;
	}
	
	public function startParametherDAO(){
		$this->ParametherDAO = ParametherDAO::getInstance();
	}
		
		
}
				