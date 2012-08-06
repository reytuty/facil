<?php
	/* 
	 * @date		: 15/07/2010
	 * @version		: 1.0
	 * @tabela		: tabela comment
	 * @description		: 	
	 * 					A tabela comment
	 *  
			`id;
			`active`, 
			`table`, 
			`table_id`, 
			`user_id`, 
			`title`, 
			`description`, 
			`date`, 
			`order`

	 *
	 * @obs			: utiliza a tabela parametro para designar o idioma (tipo:label_ptbr)
	 */
//importa classes de apoio
include_once "library/facil3/core/modules/comment/dao/CommentDAO.class.php";
include_once "library/facil3/core/modules/comment/vo/ItemCommentVO.class.php";
include_once "library/facil3/interface/vo/PatternVO.class.php";
include_once "library/facil3/core/vo/BaseVO.class.php";

class BaseCommentVO extends BaseVO{
	
	private $__arrayComments;
	
	public function _construct(){
		parent::_construct();
	}

	public function commit($validade = FALSE){
		$ReturnResultVO = parent::commit($validade);
		if($ReturnResultVO->success){
			//cadastra link, se houver
			$ReturnResultLinkVO = $this->insertComment();
			$ReturnResultVO->success = $ReturnResultLinkVO->success;
			if(!$ReturnResultVO->success){
				$ReturnResultVO->appendMessage($ReturnResultLinkVO->array_messages);
			}			
		}
		return $ReturnResultVO;
	}
	
	/**
	 * @param $title
	 * @param $description
	 * @param $user_id
	 * @param $t_id
	 * @param $active
	 * @return void
	 */
	public function addComments($title = NULL, $description = NULL, $user_id = NULL, $t_id = "insert", $active = NULL){
		//se passar o p_id indica que está editando um term já existente
		//criando a array a ser inserida - usado como se fosse um mini VO
		$this->startCommentDAO();
		$CommentVO = $this->CommentDAO->getItemVO();
		
		$CommentVO->setStatus(($t_id == "insert" || $t_id == NULL)? CommentDAO::STATUS_INSERT: CommentDAO::STATUS_UPDATE);
		$CommentVO->setTitle($title);
		$CommentVO->setDescription($description);
		$CommentVO->setUserId($user_id);
		$CommentVO->setTableId($this->id);
		$CommentVO->setTable($this->__table);
		$CommentVO->setActive($active);
		
		//verifica se deve c a array de parameter
		if($this->id > 0){
			//se já tem id é pq é um dado já cadastrado
			$this->getComments();
		} 
		//se não tem, então vai adicionar só os terms novos
		if(!count($this->__arrayComments) > 0){
			//iniciando a array para demonstrar que já buscou ou tentou buscar
			$this->__arrayComments = array();
		}
		$t_id = DataHandler::forceInt($t_id);
		if($t_id > 0){
			$CommentVO->setId($t_id);
		}
		$this->updateComment($CommentVO);
		//else {
			//push/
			//echo "<li>adicionando".var_dump($CommentVO)."</li>";
			//$this->__arrayComments[] = $CommentVO;
		//}
		//s vai realmente adicionar quando for chamado o metodo cadastrar()
	}

	/**
	 * @param $CommentVO
	 * @return true
	 */
	private function updateComment($CommentVO){
		//vai varrer todos os termos existentes, se houver um igual,ele atualiza.
		for($i = 0; $i < count($this->__arrayComments); $i++){
			//echo "<li>procurando parameter [$i] </li>";
			$tempComment = $this->__arrayComments[$i];
			if($tempComment->getId() != NULL && $tempComment->getId() == $CommentVO->getId()){
				$tempComment->setStatus(CommentDAO::STATUS_UPDATE);
				$this->__arrayComments[$i] = $CommentVO;
				//print_r($this->__arrayComments[$i]);exit();
				return TRUE;
			}
		}
		$this->__arrayComments[] = $CommentVO;
		return TRUE;
	}
	
	/**
	 * @param $title
	 * @param $description
	 * @param $active
	 * @param $user_id
	 * @param $force
	 * @return array_comment_vo
	 */
	public function getComments($title = NULL, $description = NULL, $active = NULL, $user_id = NULL, $force = FALSE){
		if(count($this->__arrayComments) > 0 && !$force){
			//já fez a busca, basta retornar			
		} else {
			//echo "<li> não existe, vou buscar </li>";
			$this->__arrayComments = array();
			if($this->validateComment()){
				$this->startCommentDAO();
				$ReturnDataVO = $this->CommentDAO->select(CommentDAO::RETURN_STD_OBJECT, NULL, NULL, $this->__table, $this->getTableId(), NULL);
				if($ReturnDataVO->success){
					$ReturnResultVO->success = TRUE;
					foreach($ReturnDataVO->result as $comment){
						$ItemCommentVO = new ItemCommentVO($comment);
						$ItemCommentVO->setStatus(CommentDAO::STATUS_OK);
						$this->__arrayComments[] = $ItemCommentVO;					
					}
				} else {
					//apenas no debug - nao precisa desse else
					//Debug::pre($ReturnResultVO);
				}
			}
		}
		return $this->filterActiveComment($this->__arrayComments, $title, $description, $active, $user_id);
	}

	private function filterActiveComment($array, $title = NULL, $description = NULL, $active = NULL, $user_id = NULL){
		//filtra os ativos = $ativo para a array geral
		$tempArray = array();
		for($i = 0; $i < count($array); $i++){
			$tempCommentVO = $array[$i];
			//$tempTermsVO = new TermVO();
			$select = TRUE;
			if($active !== NULL){
				if($tempCommentVO->getActive() != $active){
					$select = FALSE;
				}
			}
			if($user_id !== NULL){
				if($tempCommentVO->getUserId() != $user_id){
					$select = FALSE;
				}
			}
			if($title !== NULL){
				if($tempCommentVO->getTitle() != $title){
					$select = FALSE;
				}
			}
			if($description !== NULL){
				if($tempCommentVO->getDescription() != $description){
					$select = FALSE;
				}
			}
			if($select){
				$tempArray[] = $tempCommentVO;
			}
		}
		return $tempArray;
	}	
	
	//retorna false para erro e true com array de ids inseridos
	private function insertComment(){
		//verifica se essa VO tem id e table - minimo para vincular um termo a um elemento
		$ReturnResultVO = new ReturnResultVO();
		if($this->validateComment()){
			//verifica se tem terms para cadastrar novos ou alterados para cadastrar.
			
			//se successo tem term para cadastrar
			if($this->haveCommentToRegister()){
				$this->startCommentDAO();
				$ReturnDataVO = $this->CommentDAO->updateItemVO($this);
				//Debug::li("tem termos....:");
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

	private function validateComment(){
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
	private function haveCommentToRegister(){
		//echo "<li> tem [".count($this->__arrayComments)."] Comments </li>";
		for($i = 0; $i < count($this->__arrayComments); $i++){
			$tempVO = $this->__arrayComments[$i];
			if($tempVO->getStatus() != CommentDAO::STATUS_OK){
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
	 * @param $comment_id
	 * @return $CommenVO
	 */
	public function getComment($title = NULL, $description = NULL, $active = NULL, $user_id = NULL, $comment_id = NULL){
		if(!count($this->__arrayComments) > 0){
			$this->_arrayComments = $this->getComments();
		} 
		foreach($this->__arrayComments as $CommenVO){
			$select = TRUE;
			if($comment_id > 0){
				if($CommenVO->getCommentId() != $comment_id){
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
	 * @desc é para passar o id do commentario
	 * @param $id
	 * @return unknown_type
	 */
	public function deleteComment($id){
		//precisa saber o id do term para remove-lo
		for($i = 0; $i < count($this->__arrayComments); $i++){
			//se achar a array que tenha a tabela
			if($this->__arrayComments[$i]->getId() == $id){
				$CommentItemVO = $this->__arrayComments[$i];
				$CommentItemVO->setStatus($CommentItemVO->STATUS_DELETE);
				return TRUE;
			}//end if
		}//end for $i
		return FALSE;
	}
	
	public function startCommentDAO(){
		$this->CommentDAO = CommentDAO::getInstance();
	}
		
		
}
				