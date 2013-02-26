<?php
/*
 * @author		: Diego Faria Montania
 * @data		: 16/12/2009
 * @version		: 1.0
 * @description	: 	Classe de VO padr�o pra evitar reescrita obrigat�ria de c�digos
 */
/*
 * @author		: Mauricio AMorim
 * @data		: 13/07/2010
 * @version		: 1.1
 * @description	: 	Classe de VO padr�o pra evitar reescrita obrigat�ria de c�digos
 */
//importa classes de apoio
include_once "library/facil3/utils/DataHandler.class.php";
include_once "library/facil3/utils/Validation.class.php";
include_once "library/facil3/core/dao/ThermDAO.class.php";
include_once "library/facil3/core/dao/LinkDAO.class.php";
include_once "library/facil3/core/modules/paramether/dao/ParametherDAO.class.php";
include_once "library/facil3/core/vo/ReturnResultVO.class.php";

class BaseVO {
	/**
	 * array de nome de metodos para serem ignorados no toStdClass 
	 */
	var $arrayMethodExeptionGET = array(
		"getDAO",
		"getComment",
		"getComments",
		"getTherms",
		"getWhoILinked",
		"getStringTherm",
		"getLinks",
		"getParamethers",
		"getParamethersByValues",
		"toStdClass"	
	);
	
	/**
	 * guarda o id da unidade
	 * @var int $referencia
	 */
	var $id;
	
	/**
	 * n�o � obrigat�rio ter active, mas a BaseVO tem
	 * @var int
	 */
	var $active;
	/**
	 * @var object $DAO
	 */
	var $DAO;
	/**
	 * Guarda o nome da tabela de referencia, se n�o houver, n�o funciona
	 * @var string $__tabela
	 */
	var $__table;
	/**
	 * Guarda os termos relacionados a essa entrada, se houver
	 * @var array $__arrayTherms
	 */
	var $__arrayTherms;
	
	/**
	 * Guarda os vinculos numa array de LinkVO ( a quem estou linkado)
	 * @var array $__arrayWhoILinked
	 */
	var $__arrayWhoILinked;
	
	/**
	 * Guarda os vinculos numa array de LinkVO ( quem está linkado a mim)
	 * @var array $__arrayLinks 
	 */
	var $__arrayLinks;
	/**
	 * Guarda os parmethers numa array ItemParamethersVO
	 * @var array $__arrayParametherss
	 */
	var $__arrayParamethers = array();
	/**
	 * Classe de acesso ao banco de dados para termos
	 * @var object $ThermDAO
	 */
	var $ThermDAO;
	
	/**
	 * Classe de acesso ao banco de dados para vinculos utilizando link
	 * @var object $LinkDAO
	 */
	var $LinkDAO;
	
	/**
	 * Classe de acesso ao banco de dados para vinculos utilizando link
	 * @var object $ParametherDAO
	 */
	var $ParametherDAO;
	
	function _construct(){
		//envie como parameter a classe que extends essa classe na construtora
	}//end construct
	
	
	//-------------------[A��es]
	/**
	 * cadastra e atualiza, conforme VO populada, retorna obj ReturnResultVO
	 * @param (boolean) $valida
	 * @return obj ReturnResultVO;

	 */
	function commit($validate = FALSE){
		//tenta validar
		if($validate){
			//se for pra validar ele ja valida antes de tentar cadastrar
			$ReturnResultVO = $this->validate();			
			if(!$ReturnResultVO->success){
				return $ReturnResultVO;
			}
		}
		
		$ReturnResultVO = new ReturnResultVO();	
		$ReturnResultVO->success = TRUE;
		$this->startDAO();
		//se ja tiver id ele tenta update.
		if($this->id > 0){
			//Debug::pre($this->reference);
			$ReturnDataVO = $this->DAO->updateVO($this);
			if($ReturnDataVO->success){
				$ReturnDataVO->code_return = DbInterface::SUCCESS;
			}
			$this->resultHandler($ReturnDataVO->getCode(), $ReturnResultVO->array_messages);
		} else {
			//nao tem id, vai cadastrar como novo
			//se precisar de validacao, o usuario da VO deve chamar o metodo: $VO->valida()
			$ReturnDataVO = $this->DAO->insertVO($this);
			
			if($ReturnDataVO->success){
				$this->setId($ReturnDataVO->result);
				$ReturnResultVO->result = $ReturnDataVO->result;
				$this->resultHandler($ReturnDataVO->getCode(), $ReturnResultVO->array_messages);
			} else {
				$this->resultHandler($ReturnDataVO->getCode(), $ReturnResultVO->array_messages);
			}
		}//end if id
		
		$ReturnResultVO->success = $ReturnDataVO->success;
		//Debug::pre($ReturnResultVO);
		if($ReturnResultVO->success){
			//cadastra terms, se houver
			$ReturnResultVO_term = $this->insertTherm();
			//Debug::pre($ReturnResultVO_term);
			//Debug::print_r($ReturnResultVO_term);
			$ReturnResultVO->success = $ReturnResultVO_term->success;
			if(!$ReturnResultVO->success){
				$ReturnResultVO->appendMessage($ReturnResultVO_term->array_messages);
			}
			
			//cadastra parametros, se houver
			$ReturnResultVO_paramether = $this->insertParamether();
			$ReturnResultVO->success = $ReturnResultVO_paramether->success;
			if(!$ReturnResultVO->success){
				$ReturnResultVO->appendMessage($ReturnResultVO_paramether->array_messages);
			}
			
			//cadastra link, se houver
			$ReturnResultVO_link = $this->insertLink();
			$ReturnResultVO->success = $ReturnResultVO_link->success;
			if(!$ReturnResultVO->success){
				$ReturnResultVO->appendMessage($ReturnResultVO_link->array_messages);
			}
			//cadastra parameters, se houver
		}
		// Debug::li("_______");
		// Debug::pre($ReturnResultVO);
		//exit();
		return $ReturnResultVO;
	}
	/**
	 * Esse metodo atualiza automaticamente esta entrada no banco
	 *
	 * @return obj ReturnResultVO
	 * 
	 */
	function active(){
		$ReturnResultVO = new ReturnResultVO();	
		$ReturnResultVO->success = TRUE;
		if($this->id <= 0){//erro nao foi passado id
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_BASEVO_ACTIVE_DONOT_HAVE_ID"));
			return $ReturnResultVO;
		}else{
			$this->startDAO();
			$ReturnDataVO = $this->DAO->active($this->id);
			if($ReturnDataVO->success){
				$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::SUCCESS_BASEVO_ACTIVE"));
			} else {
				$this->resultHandler($ReturnDataVO->getCode(), $ReturnResultVO->array_messages);
			}
			$ReturnResultVO->success = $ReturnDataVO->success;
			//Debug::print_r($ReturnResultVO);
		}
		return $ReturnResultVO;
	}
	/**
	 * Esse metodo atualiza automaticamente esta entrada no banco
	 *
	 * @return obj ReturnResultVO
	 */
	function deactive(){
		$ReturnResultVO = new ReturnResultVO();	
		$ReturnResultVO->success = TRUE;
		if($this->id <= 0){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_BASEVO_DEACTIVE_DONOT_HAVE_ID"));
			return $ReturnResultVO;
		} else {
			//echo $this->__table;
			$this->startDAO();
			$ReturnDataVO = $this->DAO->deactive($this->id);
			if($ReturnDataVO->success){
				$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::SUCCESS_BASEVO_DEACTIVE"));
			} else {
				$this->resultHandler($ReturnDataVO->getCode(), $ReturnResultVO->array_messages);
			}
		}
		return $ReturnResultVO;
	}
	/**
	 * Esse metodo atualiza automaticamente esta entrada no banco
	 *
	 * @return obj ReturnResultVO
	 */
	function delete(){
		$ReturnResultVO = new ReturnResultVO();	
		$ReturnResultVO->success = TRUE;
		if($this->id <= 0){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_BASEVO_DELETE_DONOT_HAVE_ID"));
			return $ReturnResultVO;
		} else {
			$this->startDAO();
			$ReturnDataVO = $this->DAO->delete($this->id);
			if($ReturnDataVO->success){
				$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::SUCCESS_BASEVO_DELETE"));
			} else {
				$this->resultHandler($ReturnDataVO->getCode(), $ReturnResultVO->array_messages);
			}
		}
		return $ReturnResultVO;
	}
	/**
	 * Esse metodo faz a auto busca dos dados baseado nesse id
	 * @return obj ReturnResultVO
	 */
	public function autoSearch(){
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->success = TRUE;
		if($this->id <= 0){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_BASEVO_AUTO_LOAD_DONOT_HAVE_ID"));
		}else{
			$this->startDAO();
			$ReturnDataVO = $this->DAO->selectById($this->id);
//			print_r($ReturnDataVO);
			if($ReturnDataVO->success){
				if(count($ReturnDataVO->result)>0){
					$this->setFetchObject($ReturnDataVO->result[0]);
				}else{
					$ReturnResultVO->success = FALSE;
					$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_QUERY_NOT_FOUND"));
				}
			}else{
				$ReturnResultVO->success = FALSE;
				$this->resultHandler($ReturnDataVO->getCode(), $ReturnResultVO->array_messages);
			}
		}
		return $ReturnResultVO;
	}
	//---------------SETs
	/**
	 * id seta o id, autoLoad define se a VO deve se auto-popular
	 * @param (int) $id
	 * @param (boolean) $autoLoad
	 * @return obj ReturnResultVO
	 */
	public function setId($id, $autoSearch = FALSE){
		$this->id = DataHandler::forceInt($id);
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->success = TRUE;
		if($this->id <= 0){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_BASEVO_SET_ID_DONOT_HAVE_ID"));
		}else{
			if($autoSearch){
				$ReturnResultVO = $this->autoSearch();
			}
		}
		return $ReturnResultVO;
	}
	/**
	 * @param int $active
	 */
	public function setActive($active){
		$this->active = DataHandler::forceInt($active);
	}
	//---------------GETs
	/**
	 * retorna o id da VO
	 * @return int
	 */
	public function getId(){
		return $this->id;
	}
	/**
	 * retorna active da VO
	 * @return int
	 */
	public function getActive(){
		return $this->active;
	}
	public function getTable(){//usado para Therm e Paramether
		return $this->__table;
	}
	public function getTableId(){//usado para Therm e Paramether
		return $this->id;
	}
//---------------------------------------------------------------------------------------Therms	
				//retorna false para erro e true com array de ids inseridos
				//nao retorna dada se nao tiver Thermos para cadastrar ou update
				private function insertTherm(){
					//verifica se essa VO tem id e table - minimo para vincular um termo a um elemento
					$ReturnResultVO = new ReturnResultVO();
					if($this->validateTherm()){
						//verifica se tem terms para cadastrar novos ou alterados para cadastrar.
						
						//se successo tem term para cadastrar
						if($this->haveThermToRegister()){
							$this->startThermDAO();
							$ReturnDataVO = $this->ThermDAO->updateVO($this);
							//Debug::li("tem termos....:");
							//Debug::print_r($ReturnDataVO);
							
						if(!$ReturnDataVO->success){
								//obs $ReturnDataVO retorna array somente em caso de erro no updateVO()
								$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_TERM_UPDATE_COMMIT"));
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
						//n�o tem id
						$ReturnResultVO->success = FALSE;
						$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_TERM_ADD_DONOT_HAVE_TABLE_ID"));
						return $ReturnResultVO;
					}
				}
				//se tiver alguma coisa pra registrar retorna ReturnResult->success = TRUE
				private function haveThermToRegister(){
					//echo "<li> tem [".count($this->__arrayTherms)."] Therms </li>";
					for($i = 0; $i < count($this->__arrayTherms); $i++){
						$tempVO = $this->__arrayTherms[$i];
						if($tempVO->getStatus() != ThermDAO::STATUS_OK){
							//$tempVO->setTableId($this->id);
							return TRUE;
						}
					}
					return FALSE;
				}
				private function validateTherm(){
					//vai validar a VO inteira conforme as regras
					if(!Validation::blank($this->__table, 2)){
						return FALSE;
					}
					if(!$this->getId() > 0){
						return FALSE;
					}
					return TRUE;
				}	
				public function addTherms($therm, $locale, $value, $t_id = "insert"){
					//se passar o p_id indica que est� editando um term j� existente
					//criando a array a ser inserida - usado como se fosse um mini VO
					$this->startThermDAO();
					$ThermVO = $this->ThermDAO->getVO();
					
					$ThermVO->setStatus(($t_id == "insert" || $t_id == NULL)? ThermDAO::STATUS_INSERT: ThermDAO::STATUS_UPDATE);
					$ThermVO->setTherm($therm);
					$ThermVO->setLocale($locale);
					$ThermVO->setValue($value);
					//$ThermVO->setTableId($this->id);
					//$ThermVO->setTable($this->__table);
					$ThermVO->setActive(1);
					
					//verifica se deve c a array de parameter
					if($this->id > 0){
						//se j� tem id � pq � um dado j� cadastrado
						$this->getTherms();
					} 
					//se n�o tem, ent�o vai adicionar s� os terms novos
					if(!count($this->__arrayTherms) > 0){
						//iniciando a array para demonstrar que j� buscou ou tentou buscar
						$this->__arrayTherms = array();
					}
					$t_id = DataHandler::forceInt($t_id);
					if($t_id > 0){
						$ThermVO->setId($t_id);
					}
					$this->updateTherm($ThermVO);
					//else {
						//push/
						//echo "<li>adicionando".var_dump($ThermVO)."</li>";
						//$this->__arrayTherms[] = $ThermVO;
					//}
					//s� vai realmente adicionar quando for chamado o metodo cadastrar()
				}
				/**
				 * @param $therm
				 * @param $locale
				 * @param $active
				 * @param $force
				 * @return array
				 */
				public function getTherms($therm = NULL, $locale = NULL, $active = NULL, $force = FALSE){
					if(count($this->__arrayTherms) > 0 && !$force){
						//j� fez a busca, basta retornar
						
					} else {
						//echo "<li> n�o existe, vou buscar </li>";
						if($this->validateTherm()){
							$this->startThermDAO();
							$ReturnDataVO = $this->ThermDAO->select($this->__table, $this->getTableId(), ThermDAO::RETURN_VO);
							if($ReturnDataVO->success){
								$ReturnResultVO->success = TRUE;
								$this->__arrayTherms = $ReturnDataVO->result;
								
							} else {
								//apenas no debug - nao precisa desse else
								//Debug::pre($ReturnResultVO);
							}
						}
					}
					return $this->filterActiveTherm($this->__arrayTherms, $therm, $locale, $active);
				}
				/**
				 * @param $therm
				 * @param $locale
				 * @return string do valor único já do termo
				 */
				public function getStringTherm($therm, $locale){
					$temp_array = $this->getTherms($therm, $locale, 1);
					//print_r($temp_array);
					if(count($temp_array) > 0){
						$ThermVO = $temp_array[0];
						//print_r($ThermVO);
						return $ThermVO->getValue();
					} else {
						return "";
					}
				}
				/**
				 * @param $ThermVO
				 * @return unknown_type
				 */
				private function updateTherm($ThermVO){
					//vai verrer todos os termos existentes, se houver um igual,ele atualiza.
					for($i = 0; $i < count($this->__arrayTherms); $i++){
						//echo "<li>procurando parameter [$i] </li>";
						$tempTherm = $this->__arrayTherms[$i];
						if($tempTherm->getId() != NULL && ($tempTherm->getId() == $ThermVO->getId() || ($tempTherm->getTherm() == $ThermVO->getTherm() && $ThermVO->getLocale() == $tempTherm->getLocale()))){
							$tempTherm->setStatus(ThermDAO::STATUS_UPDATE);
							$tempTherm->setValue($ThermVO->getValue());
							
							$this->__arrayTherms[$i] = $tempTherm;
							return TRUE;
						}
					}
					$this->__arrayTherms[] = $ThermVO;
					return TRUE;
				}
				public function deleteTherm($id){
					//precisa saber o id do term para remove-lo
					for($i = 0; $i < count($this->__arrayTherms); $i++){
						//se achar a array que tenha a tabela
						if($this->__arrayTherms[$i]->getId() == $id){
							$ThermItemVO = $this->__arrayTherms[$i];
							$ThermItemVO->setStatus($ThermItemVO->STATUS_DELETE);
							return TRUE;
						}//end if
					}//end for $i
					return FALSE;
				}
				public function startThermDAO(){
					$this->ThermDAO = ThermDAO::getInstance();
				}
//---------------------------------------------------------------------------------------Therms	

				
//---------------------------------------------------------------------------------------Link	
							//retorna false para erro e true com array de ids inseridos
							//n�o retorna dada se n�o tiver Linkos para cadastrar ou update
							private function insertLink(){
								//verifica se essa VO tem id e table - minimo para vincular um linko a um elemento
								$ReturnResultVO = new ReturnResultVO();
								if($this->validateLink()){
									//verifica se tem links para cadastrar novos ou alterados para cadastrar.
									
									//se successo tem link para cadastrar
									if($this->haveLinkToRegister()){
										$this->startLinkDAO();
										$ReturnDataVO = $this->LinkDAO->updateVO($this);
										//Debug::li("tem links....:");
										//Debug::print_r($ReturnDataVO);
										
										if(!$ReturnDataVO->success){
											//obs $ReturnDataVO retorna array somente em caso de erro no updateVO()
											$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_LINK_UPDATE_COMMIT"));
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
									//n�o tem id
									$ReturnResultVO->success = FALSE;
									$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_LINK_ADD_DONOT_HAVE_TABLE_ID"));
									return $ReturnResultVO;
								}
							}
							//se tiver alguma coisa pra registrar retorna ReturnResult->success = TRUE
							private function haveLinkToRegister(){
								//echo "<li> tem [".count($this->__arrayLinks)."] Links </li>";
								for($i = 0; $i < count($this->__arrayLinks); $i++){
									$tempVO = $this->__arrayLinks[$i];
									if($tempVO->getStatus() != LinkDAO::STATUS_OK){
										//$tempVO->setTableId($this->id);
										return TRUE;
									}
								}
								return FALSE;
							}
							private function validateLink(){
								//vai validar a VO inteira conforme as regras
								if(!Validation::blank($this->__table, 2)){
									return FALSE;
								}
								if(!$this->id > 0){
									return FALSE;
								}
								return TRUE;
							}
							public function removeLink($linked_table, $linked_table_id){
								//atualiza a lista de links
								$this->getLinks();
								//essa variavel faz com que se delete todos os links que atendam a table e table_id, assim retira os repetidos
								$return = FALSE;
								//Debug::print_r($this->__arrayLinks);
								for($i = 0; $i < count($this->__arrayLinks); $i++){
									$tempLink = $this->__arrayLinks[$i];
									//verificando se linked_table e linked_table_id s�o iguais para deletar
									if($tempLink->getLinkedTable() == $linked_table && $tempLink->getLinkedTableId() == $linked_table_id){
										$tempLink->setStatus(LinkDAO::STATUS_DELETE);
										//echo Debug::li("setar o status para deletar o link".Debug::print_r($tempLink, true));
										$return = TRUE;
									} else {
										//echo Debug::li("nao:".Debug::li($tempLink->getLinkedTable()." == ".$linked_table." && ".$tempLink->getLinkedTableId()." == ".$linked_table_id));
									}
								}
								return $return;
							}
							public function addLink($linked_table, $linked_table_id, $t_id = "insert", $order = NULL){
								//se passar o p_id indica que est�o editando um link j� existente
								//criando a array a ser inserida - usado como se fosse um mini VO
								$this->startLinkDAO();
								$LinkVO = $this->LinkDAO->getVO();
								$LinkVO->setStatus(($t_id == "insert" || $t_id == NULL)? LinkDAO::STATUS_INSERT: LinkDAO::STATUS_UPDATE);
								$LinkVO->setLinkedTable($linked_table);
								$LinkVO->setLinkedTableId($linked_table_id);
								if($order != NULL){
									$LinkVO->setOrder($order);
								}
								$LinkVO->setActive(1);
								
								//verifica se deve c a array de parameter
								if($this->id > 0){
									//se j� tem id � pq � um dado j� cadastrado
									$this->getLinks();
								} 
								//se n�o tem, ent�o vai adicionar s� os links novos
								if(!count($this->__arrayLinks) > 0){
									//iniciando a array para demonstrar que j� buscou ou tentou buscar
									$this->__arrayLinks = array();
								}
								$t_id = DataHandler::forceInt($t_id);
								if($t_id > 0){
									$LinkVO->setId($t_id);
								}
								$this->updateLink($LinkVO);
								//else {
									//push/
									//ech,dicionando".var_dump($LinkVO)."</li>";
									//$this->__arrayLinks[] = $LinkVO;
								//}
								//s� vai realmente adicionar quando for chamado o metodo cadastrar()
							}



							/*
							 * pega os links aos quais eu estou relacionado de acordo com tabela passada
							 * */
							public function getWhoILinked ($table = NULL, $active = NULL, $force = FALSE){
								if(count($this->__arrayWhoILinked) > 0 && !$force){
									//j� fez a busca, basta retornar
									
								} else {
									// echo "<li> nýo existe, vou buscar </li>";
									
									if($this->validateLink()){
										
										
										$this->startLinkDAO();
										$ReturnDataVO = $this->LinkDAO->select(LinkDAO::RETURN_VO, NULL, NULL,  $this->__table, $this->id );
										if($ReturnDataVO->success){
											$ReturnResultVO->success = TRUE;
											$this->__arrayWhoILinked = $ReturnDataVO->result;
											
										} else {
											//Debug::pre($ReturnResultVO);
										}
									}
								}
								if($table !== NULL || $active !== NULL){
									return $this->filterActiveLinks($this->__arrayWhoILinked, $this->__table, $active, $table);
								} else {
									//retorna sem filtro
									return $this->__arrayWhoILinked;
								}
							}

							/*
							 * 	pega itens relacionados a mim de acordo com a tabela passada
							 */
							public function getLinks($linked_table = NULL, $active = NULL, $force = FALSE){
								if(count($this->__arrayLinks) > 0 && !$force){
									//j� fez a busca, basta retornar
									
								} else {
									//echo "<li> n�o existe, vou buscar </li>";
									if($this->validateLink()){
										$this->startLinkDAO();
										if(FALSE){
											$this->LinkDAO = new LinkDAO();
										}
										$ReturnDataVO = $this->LinkDAO->select(LinkDAO::RETURN_VO, $this->__table, $this->id);
										if($ReturnDataVO->success){
											$ReturnResultVO->success = TRUE;
											$this->__arrayLinks = $ReturnDataVO->result;
											
										} else {
											//apenas no debug - nao precisa desse else
											//Debug::pre($ReturnResultVO);
										}
									}
								}
								if($linked_table !== NULL || $active !== NULL){
									return $this->filterActiveLinks($this->__arrayLinks, $linked_table, $active);
								} else {
									//retorna sem filtro
									return $this->__arrayLinks;
								}
							}
							private function updateLink($LinkVO){
								//vai verrer todos os linkos existentes, se houver um igual,ele atualiza.
								for($i = 0; $i < count($this->__arrayLinks); $i++){
									//echo "<li>procurando parameter [$i] </li>";
									$tempLink = $this->__arrayLinks[$i];
										if	(
												($tempLink->getId() > 0 && $tempLink->getId() == $LinkVO->getId()) 
													|| 
												(($tempLink->getLinkedTable() == $LinkVO->getLinkedTable() && $LinkVO->getLinkedTableId() == $tempLink->getLinkedTableId()))
											){
													//N�o precisa fazer update sendo que j� existe uma igual, mas atualiza o active pra 1
													$tempLink->setStatus(LinkDAO::STATUS_UPDATE);
													$tempLink->setActive(1);
													$this->__arrayLinks[$i] = $tempLink;
													return TRUE;
											}
								}
								$this->__arrayLinks[] = $LinkVO;
								return TRUE;
							}
							public function deleteLink($id){
								if(count($this->__arrayLinks)<1){
									$this->getLinks();
								}
								//precisa saber o id do link para remove-lo
								for($i = 0; $i < count($this->__arrayLinks); $i++){
									//se achar a array que tenha a tabela
									if($this->__arrayLinks[$i]->getId() == $id){
										$LinkItemVO = $this->__arrayLinks[$i];
										$LinkItemVO->setStatus("delete");
										return TRUE;
									}//end if
								}//end for $i
								return FALSE;
							}
							public function startLinkDAO(){
								$this->LinkDAO = LinkDAO::getInstance();
								return $this->LinkDAO;
							}
//--------------------------------------------------------------------------------------- Link 

//------------------------------------------------------------------------------------------------------------[  parameters  ]
		/**
		 * @param $ItemParametherVO
		 * @return unknown_type
		 * utilize esse metodo quando for necessario registrar mais de um tipo de valor em um unico 
		 * registro da tabela parametro
		 * obs. só é possivel usar esse metodo passando como parametro uma ItemParametherVO ou uma ParametherVO 
		 */
		public function addParametherVO($ItemParametherVO){
			$ReturnResultVO = new ReturnResultVO();

			//inicia a ParametherDAO
			$this->startParametherDAO();
			//verifica se é um novo parametro ou uma atualização	
			$ItemParametherVO->setStatus(($ItemParametherVO->getId() == ParametherDAO::STATUS_INSERT || $ItemParametherVO->getId() == NULL)? ParametherDAO::STATUS_INSERT: ParametherDAO::STATUS_UPDATE);

			//verifica se deve buscar a array de paramether
			if($this->getId() > 0){
				//se já tem id é pq é um dado já cadastrado então vai buscar se existe parametros registrados 
				//no banco
				$this->getParamethers();
			} else {
				//se não tem, então vai adicionar só os parameters novos
				if(!count($this->__arrayParamethers) > 0){
					$this->__arrayParamethers = array();
				}
			}

			//verifica se existe um parametro com o id igual ao do parametro enviado
			$this->updateParamether($ItemParametherVO);
			//só vai realmente adicionar ou atualizar quando for chamado o metodo cadastrar()
		}

		//utilize esse metodo apenas quando tiver um unico valor a ser registrado						
		/**
		 * @param $type
		 * @param $name
		 * @param $value
		 * @param $locale
		 * @param $p_id
		 * @return void
		 */
		public function addParamether($type = ParametherDAO::TYPE_VARCHAR, $name = NULL, $value = NULL, $locale = NULL, $p_id = ParametherDAO::STATUS_INSERT){
			//se passar o p_id indica que está editando um parameter já existente
			//criando a array a ser inserida - usado como se fosse um mini VO
			$ReturnResultVO = new ReturnResultVO();
			//echo Debug::li("adicionando parametro: $type  ->  $name ->  $value  : $p_id");
			$this->startParametherDAO();
			//print_r($this->ParametherDAO);exit();
			$ItemParametherVO = $this->ParametherDAO->getItemParametherVO();
			$ItemParametherVO->setStatus(($p_id == ParametherDAO::STATUS_INSERT || $p_id == NULL)? ParametherDAO::STATUS_INSERT: ParametherDAO::STATUS_UPDATE);		
			$ItemParametherVO->setType($type);
			$ItemParametherVO->setName($name);
			$ItemParametherVO->setValue($value);
			$ItemParametherVO->setLocale($locale);
			$ItemParametherVO->setActive(1);
			
			//verifica se deve c a array de parameter
			if($this->getId() > 0){
				//se já tem id é pq é um dado já cadastrado
				$this->getParamethers();
				//print_r($this->__arrayParamethers);exit();
			} else {
				//se não tem, então vai adicionar só os parameters novos
				if(!count($this->__arrayParamethers) > 0){
					$this->__arrayParamethers = array();
				}
			}

			if($p_id > 0){
				$ItemParametherVO->setId($p_id);
			}
			//verifica qual o indice - se houver - ele está na array
			$this->updateParamether($ItemParametherVO);
			//só vai realmente adicionar ou atualizar quando for chamado o metodo cadastrar()
		}

		/**
		 * @param $ItemParametherVO
		 * @return true or void
		 */
		private function updateParamether($ItemParametherVO){
			for($i = 0; $i < count($this->__arrayParamethers); $i++){
				//echo "<li>procurando parameter [$i] </li>";
				if($this->__arrayParamethers[$i]->getId() != NULL && ($this->__arrayParamethers[$i]->getId() == $ItemParametherVO->getId())){
					//echo "<li>encontrado parameter de id [".$ItemParametherVO->getId()."] </li>";
					$this->__arrayParamethers[$i] = $ItemParametherVO;
					$this->__arrayParamethers[$i]->setStatus(ParametherDAO::STATUS_UPDATE);
					return TRUE;
				}else{
					//print_r($this->__arrayParamethers[$i]);
					//print_r($ItemParametherVO);
					//Debug::li("separa");
				}
			}
			$this->__arrayParamethers[] = $ItemParametherVO;
			//print_r($this->__arrayParamethers);
			return TRUE;
		}

		private function insertParamether(){
			$ReturnResultVO = new ReturnResultVO();
			//valida os campos necessarios 
			if($this->validateParamether()){
				//verifica se tem alguma ação a ser realizada com algum parametro
				if($this->haveParametherToRegister()){
					$this->startParametherDAO();
					$ReturnDataVO = $this->ParametherDAO->updateVO($this);
					if(!$ReturnDataVO->success){
						$ReturnResultVO->addMessage("ERROR_PARAMETHER_UPDATE_COMMIT");
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
				$ReturnResultVO->success = FALSE;
				$ReturnResultVO->addMessage("ERROR_PARAMETHER_ADD_DONOT_HAVE_TABLE_ID");
				return $ReturnResultVO;
			}
		}

		/**
		 * verifica se tem algum paramtro a ser alterado, excluido ou inserido
		 * @return true or falseS
		 */
		private function haveParametherToRegister(){
			//print_r($this->__arrayParamethers);
			//echo "<li> tem [".count($this->__arrayParamethers)."] parameters </li>";
			for($i = 0; $i < count($this->__arrayParamethers); $i++){
				$tempVO = $this->__arrayParamethers[$i];
				//Debug::print_r($tempVO);
				if($tempVO->getStatus() != ItemParametherVO::STATUS_OK){
					return TRUE;
				}
			}
			return FALSE;
		}
		
		/**
		 * valida os campos necessarios para cadastro  
		 * @return true or false
		 */
		private function validateParamether(){
			//vai validar a VO inteira conforme as regras
			if(!Validation::blank($this->__table, 2)){
				return FALSE;
			}
			if(!$this->id > 0){
				return FALSE;
			}
			return TRUE;
		}	

		/**
		 * atualiza o status do parametro para delete
		 * obs. o parametro só é apagado apos o comando commit 
		 * @param $id
		 * @return true or false
		 */
		public function deleteParamether($id){
			//precisa saber o id do parameter para remove-lo
			for($i = 0; $i < count($this->__arrayparameter); $i++){
				//se achar a array que tenha a tabela
				if($this->__arrayparameter[$i]->getId() == $id){
					$ItemParametherVO = $this->__arrayparameter[$i];
					$ItemParametherVO->setStatus($ItemParametherVO->STATUS_DELETE);
					return TRUE;
				}//end if
			}//end for $i
			return FALSE;
		}
	
	//-------------------------------------------------------------------[GETs]
	
	/**
	 * busca todos os parametros cadastrados com essa tabela e o id da vo vinculada informado
	 * @param $active
	 * @param $force
	 * @return array
	 */
	public function getParamethers($active = NULL, $force = FALSE){
		if(count($this->__arrayParamethers) > 0 && !$force){
			//já fez a busca, basta retornar
		} else {
			//echo "<li> não existe, vou buscar </li>";
			if($this->validateParamether()){
				$this->startParametherDAO();
				$ReturnDataVO = $this->ParametherDAO->select($this->__table, $this->getTableId(), ParametherDAO::RETURN_VO);
				//Debug::pre($ReturnDataVO);
				if($ReturnDataVO->success){
					$ReturnResultVO->success = TRUE;
					$this->__arrayParamethers = $ReturnDataVO->result;
				} else {
					//apenas no debug - nao precisa desse else
					//Debug::pre($ReturnResultVO);
				}
			}
		}
		//filtra os paremtros para que fiquem somente os ativos
		return $this->filterActiveParamethers($this->__arrayParamethers, $active);
	}
	
	/**
	 * busca parametros cadastrados por nome, e ou locale, e ou ativo
	 * @param $name
	 * @param $type
	 * @param $value
	 * @param $locale
	 * @param $active
	 * @return array
	 */
	public function getParamethersByValues($name = NULL, $type = NULL, $value = NULL, $locale = NULL, $active = NULL){
		if(!count($this->__arrayParamethers) > 0){
			$this->getParamethers($active);
		}
		$arrayReturn = array();
		foreach($this->__arrayParamethers as $tempVO){
			if($tempVO->getName() == $name && $name != NULL){
				$arrayReturn[] = $tempVO;
				continue;
			}
			if($tempVO->getType() == $type && $type != NULL){
				$arrayReturn[] = $tempVO;
				continue;
			}
			if($tempVO->getValue() == $value && $value != NULL){
				$arrayReturn[] = $tempVO;
				continue;
			}
			if($tempVO->getLocale() == $locale && $locale != NULL){
				$arrayReturn[] = $tempVO;
				continue;
			}
			if($tempVO->getActive() == $active && $active != NULL){
				$arrayReturn[] = $tempVO;
				continue;
			}
		}
		return $arrayReturn;
	}
	/**
	 * @param $name
	 * @param $type
	 * @param $value
	 * @param $locale
	 * @param $active
	 * @return unknown_type but return just one value
	 */
	public function getUniqueParemetherByValue($name = NULL, $type = NULL, $value = NULL, $locale = NULL, $active = NULL){
		$array_result = $this->getParamethersByValues($name, $type, $value, $locale, $active);
		if(count($array_result) > 0){
			return $array_result[0];
		} else {
			//se não encontrar resultado satisfatório retorna null
			return NULL;
		}
	}
	/**
	 * @return void
	 */
	public function startParametherDAO(){
		if(!$this->ParametherDAO){
			$this->ParametherDAO = ParametherDAO::getInstance();
		}
	}

	/**
	 * @param $array
	 * @param $active
	 * @return array
	 */
	private function filterActiveParamethers($array, $active = NULL){
		//filtra os actives = $active para a array geral
		$tempArray = array();
		for($i = 0; $i < count($array); $i++){
			$tempVO = $array[$i];
			//$tempThermsVO = new ThermVO();
			$select = TRUE;
			if($active !== NULL){
				if($tempVO->getActive() != $active){
					$select = FALSE;
				}
			}
			if($select){
				$tempArray[] = $tempVO;
			}
		}
		return $tempArray;
	}
	
	/**
	 * @param $array array de todos os seus links sendo que é um array de LinkVO
	 * @param $linked_table tabela que está linkada a você 
	 * @param $active
	 * @table 
	 * @return array
	 */
	private function filterActiveLinks($array, $linked_table = NULL, $active = NULL, $table = NULL){
		//filtra os actives = $active para a array geral
		$tempArray = array();
		for($i = 0; $i < count($array); $i++){
			$tempVO = $array[$i];
			//$tempThermsVO = new ThermVO();
			$select = TRUE;
			if($active !== NULL){
				if($tempVO->getActive() != $active){
					$select = FALSE;
				}
			}
			if($linked_table !== NULL){
				if($tempVO->getLinkedTable() != $linked_table){
					$select = FALSE;
				}
			}
			if($table !== NULL){
				if($tempVO->getTable() != $table){
					$select = FALSE;
				}
			}
			if($select){
				$tempArray[] = $tempVO;
			}
		}
		return $tempArray;
	}
	
	/**
	 * @param $array
	 * @param $therm
	 * @param $locale
	 * @param $active
	 * @return array
	 */
	private function filterActiveTherm($array, $therm = NULL, $locale = NULL, $active = NULL){
		//filtra os actives = $active para a array geral
		$tempArray = array();
		for($i = 0; $i < count($array); $i++){
			$tempThermsVO = $array[$i];
			//$tempThermsVO = new ThermVO();
			$select = TRUE;
			if($active !== NULL){
				if($tempThermsVO->getActive() != $active){
					$select = FALSE;
				}
			}
			if($therm !== NULL){
				if($tempThermsVO->getTherm() != $therm){
					$select = FALSE;
				}
			}
			if($locale !== NULL){
				if($tempThermsVO->getLocale() != $locale){
					$select = FALSE;
				}
			}
			if($select){
				$tempArray[] = $tempThermsVO;
			}
		}
		return $tempArray;
	}
	/**
	 * adiciona método para ser ignorado na chamada do toStdClass
	 * @param string $method_name
	 * @return void
	 */
	public function addMethodToIgnoreList($method_name){
		$this->arrayMethodExeptionGET[] = $method_name;
	}

	/**
	 * @param string $LOCALE
	 * @return stdClass
	 */
	public function toStdClass($LOCALE = NULL){
		$obj = new stdClass();
		$arrayMetodos = get_class_methods($this);
		//Debug::print_r($arrayMetodos);
		
		//filtra os metodos do tipo get
		foreach($arrayMetodos as $metodo){
			if(!in_array($metodo , $this->arrayMethodExeptionGET ) && @ereg("^get", $metodo)){
				$atributo = @ereg_replace("^get_", "", DataHandler::ecmaToUnderline($metodo));
				//pegando o valor
				//echo Debug::li("\$tempValor = \$this->".$metodo."(\"".$LOCALE."\");");
				$tempValor = $this->$metodo($LOCALE);
				//eval("\$tempValor = \$this->".$metodo."(\"".$LOCALE."\");"); 
				//eval("\$obj->".$atributo." = \"".$tempValor."\";");
				//echo Debug::li("......");
				//print_r($tempValor);
				$obj->$atributo = $tempValor;
				
				//Debug::li("metodo: $metodo retornando $tempValor ");
			}
		}
		return $obj;
	}
}
?>