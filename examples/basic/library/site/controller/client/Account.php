<?php

include_once("library/facil3/navigation/http/HttpResult.class.php");

include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/client/account/PurchaseInfo.class.php"));
include_once("library/facil3/core/acl/UserClient.php");
include_once "library/e_commerce/modules/coupon/dao/CouponDAO.class.php";
include_once "library/e_commerce/modules/coupon/vo/CouponVO.class.php";
include_once "library/e_commerce/modules/purchase/dao/PurchaseOrderDAO.class.php";
include_once "library/e_commerce/modules/money_history/MoneyHistory.class.php"; 
include_once 'view/democrart/client/account/MoneyHistoryInfo.class.php';
include_once 'library/democrart/modules/partner/dao/PartnerDAO.class.php';

include_once Config::FOLDER_APPLICATION."controller/admin/default/Content.php";
include_once "library/facil3/core/modules/category/dao/CategoryDAO.class.php";
include_once Config::FOLDER_APPLICATION."modules/content/vo/ContentSiteVO.class.php";
include_once "library/facil3/core/dao/LinkDAO.class.php";

class Account implements HTTPControllerInterface{
	private $arrayRestFolder = array();
	private $arrayVariable = array();

	private $DAO;

	public function __construct($arrayRestFolder = NULL){
		if(!UserClient::isAlive()){
			Navigation::redirect("client/login");
			exit();
		}
		$id_user = UserClient::getId();

		$PartnerDAO = PartnerDAO::getInstance();
		$ReturnPartnerDAO = $PartnerDAO->selectById($id_user);
		if($ReturnPartnerDAO->success && count($ReturnPartnerDAO->result)>0){
			$_SESSION["is_partner"] = 1;
		}
		
		if($arrayRestFolder != NULL){
			$this->arrayRestFolder = $arrayRestFolder;
			$this->arrayVariable   = Navigation::getVariableArraySlug($this->arrayRestFolder);
		}
		$this->DAO = PurchaseOrderDAO::getInstance();
	}
	
	public function init(){
		//chamando a client pois é lá que são tratados os metodos que o pelado fez
		include_once Config::FOLDER_APPLICATION."controller/Client.php";
		$ClientController = new Client();
		if(count($this->arrayRestFolder) == 0){
			return $ClientController->purchase();
		}
		return $ClientController->init();
	}
	
	public function purchase(){
		//iniciando o retorno padrao em http result
		$returnResult = new HttpResult();
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new PurchaseInfo();
		if(FALSE){
			$this->DAO = new PurchaseOrderDAO();
		}
		//buscando na select is ativos
		$ReturnDataVOFinished = $this->DAO->select(PurchaseOrderDAO::RETURN_VO, NULL, PurchaseOrderDAO::STATUS_FINISHED, NULL, UserClient::getId());
//		Debug::print_r($ReturnDataVOFinished);
		if($ReturnDataVOFinished->success && $ReturnDataVOFinished->count_total > 0){
			//setando sucesso true caso tenha dado certo
			$returnResult->setSuccess(TRUE);
			$returnArray = array();
			foreach($ReturnDataVOFinished->result as $PurchaseOrderVO){
				$returnArray[] = $PurchaseOrderVO->toStdClass();
			}
			$retornoDaPaginaHTML->array_pedidos_fechados = $returnArray;
			$retornoDaPaginaHTML->quant_pedidos_fechados = count($returnArray);
		}
		//agora busca os em andamento
		$ReturnDataVONotFinished = $this->DAO->select(PurchaseOrderDAO::RETURN_VO, NULL, PurchaseOrderDAO::STATUS_NOT_FINISHED, NULL, UserClient::getId());
		if($ReturnDataVONotFinished->success && $ReturnDataVONotFinished->count_total > 0){
			 //setando sucesso true caso tenha dado certo
			$returnResult->setSuccess(TRUE);
			$returnArrayNotFinished = array();
			foreach($ReturnDataVONotFinished->result as $PurchaseOrderVO){
				//$PurchaseOrderVO->date_order = 2010-12-21 10:34:55
				$time_stamp = strtotime($PurchaseOrderVO->date_order);
				$time_limite = $time_stamp+(3600*24*Config::PRUCHASE_ORDER_EXPIRATION_TIME);
				//tirando os pedidos que estão expirados
//				if($time_limite > time()){
					$returnArrayNotFinished[] = $PurchaseOrderVO->toStdClass();
//				} else {
					//echo Debug::li($PurchaseOrderVO->date_order." está fora da data");
//				}
			}
			$retornoDaPaginaHTML->array_pedidos_abertos = $returnArrayNotFinished;
			$retornoDaPaginaHTML->quant_pedidos_abertos = count($returnArrayNotFinished);
		}
		
		
		//Debug::print_r($ReturnDataVONotFinished);
		//exit();
		$retornoDaPaginaHTML->quant_pedidos = $retornoDaPaginaHTML->quant_pedidos_abertos + $retornoDaPaginaHTML->quant_pedidos_fechados;
		//salvando o objeto de resultado de html no retorno
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		
		return $returnResult;
	}
	public function purchasePending(){
		//iniciando o retorno padrao em http result
		$returnResult = new HttpResult();
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new PurchaseInfo();
		if(FALSE){
			$this->DAO = new PurchaseOrderDAO();
		}
		//buscando na select is ativos
		$ReturnDataVOFinished = $this->DAO->select(PurchaseOrderDAO::RETURN_VO, NULL, PurchaseOrderDAO::STATUS_NOT_CONCLUED, NULL, UserClient::getId());
		//Debug::print_r($ReturnDataVOFinished);
		
		if($ReturnDataVOFinished->success && $ReturnDataVOFinished->count_total > 0){
			 //setando sucesso true caso tenha dado certo
			$returnResult->setSuccess(TRUE);
			$returnArray = array();
			
			foreach($ReturnDataVOFinished->result as $PurchaseOrderVO){
				//$PurchaseOrderVO->date_order = 2010-12-21 10:34:55
				$time_stamp = strtotime($PurchaseOrderVO->date_order);
				$time_limite = $time_stamp+(3600*24*Config::PRUCHASE_ORDER_EXPIRATION_TIME);
				//tirando os pedidos que estão expirados
//				if($time_limite > time()){
					$returnArray[] = $PurchaseOrderVO->toStdClass();
//				} else {
					//echo Debug::li($PurchaseOrderVO->date_order." está fora da data");
//				}
			}
			$retornoDaPaginaHTML->array_pedidos_abertos = $returnArray;
			//agora busca os em andamento
			$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		}else{
			 //setando sucesso true caso tenha dado certo
			$returnResult->setSuccess(FALSE);
			$returnResult->message = "Nenhum pedido encontrado.";
		}
		//salvando o objeto de resultado de html no retorno
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
	
	public function purchaseClosed(){
		//iniciando o retorno padrao em http result
		$returnResult = new HttpResult();
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new PurchaseInfo();
		if(FALSE){
			$this->DAO = new PurchaseOrderDAO();
		}
		//buscando na select is ativos
		$ReturnDataVOFinished = $this->DAO->select(PurchaseOrderDAO::RETURN_VO, NULL, PurchaseOrderDAO::STATUS_FINISHED, NULL, UserClient::getId());
		//Debug::print_r($ReturnDataVOFinished);
		
		if($ReturnDataVOFinished->success && $ReturnDataVOFinished->count_total > 0){
			 //setando sucesso true caso tenha dado certo
			$returnResult->setSuccess(TRUE);
			$returnArray = array();
			foreach($ReturnDataVOFinished->result as $PurchaseOrderVO){
				$returnArray[] = $PurchaseOrderVO->toStdClass();
			}
			$retornoDaPaginaHTML->array_pedidos_fechados = $returnArray;
			//agora busca os em andamento
			$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		}else{
			 //setando sucesso true caso tenha dado certo
			$returnResult->setSuccess(FALSE);
			$returnResult->message = "Nenhum pedido encontrado.";
		}
		//salvando o objeto de resultado de html no retorno
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
	/**
	 * @param $pedido_id
	 * @param $user_id
	 * @return bool
	 */
	private function checkPurchaseOwner($pedido_id, $user_id){
		$ReturnDataAll = $this->DAO->select(PurchaseOrderDAO::RETURN_STD_OBJECT, NULL, NULL, NULL, $user_id);
		if($ReturnDataAll->success && $ReturnDataAll->count_total > 0){
			//ele tem pedidos, agora falta ver se o pedido é dele
			foreach($ReturnDataAll->result as $PurchaseStdVO){
				if($PurchaseStdVO->id == $pedido_id){
					//achou, é dele mesmo
					return TRUE;
				}
			}
		}
		//o usuário não tem nenhum pedido com esse id como sua propriedade, mas pode ser que o pedido exista
		return FALSE;
	}
	/**
	 * lista o extrato do usuário baseado no restfolder
	 * enviar: 
	 * 		year.N
	 * 		month.N (sendo 1 = janeiro)
	 * caso não seja enviado ano ele pegao ano atual
	 * caso não seja enviado mes, pega o mes atual
	 * 
	 */
	public function afiliateInfo(){
		$year 	= DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "year"));
		$month 	= DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "month"));
		// echo $year.$month;
		$year 	= ($year > 0)?$year:date("Y", time());
		$month	= ($month > 0)?$month:date("m", time());
		$MoneyHistory = new MoneyHistory();
		$result = $MoneyHistory->getExtractByMonth(UserClient::getId(), $month , $year);
		//agora prepara o resultado
		$returnResult = new HttpResult();
		$returnResult->setSuccess($result->success);
		
		$RetornoDaPagina = new MoneyHistoryInfo();
		
		$RetornoDaPagina->year_base 		= $year;
		$RetornoDaPagina->month_base_int 	= $month;
		$RetornoDaPagina->saldo_total_atual = 0;
		$lastDayOfMonth = ($month == 1 || $month == 3 || $month == 5 || $month == 7  || $month == 8 || $month == 10 || $month == 12)?31:30;
		//TODO: quando puder, trocar essa busca do mal para a tabela que foi feita pra isso
		$total_extract = $MoneyHistory->getExtract(UserClient::getId(), NULL, "$year-$month-$lastDayOfMonth");
		
		//para debugar, veja se o extract vem a array completa aqui
		//Debug::print_r($total_extract);
		foreach ($total_extract->result as $VO){
			if(FALSE){
				$VO = new MoneyHistoryVO();
			}
			$RetornoDaPagina->saldo_total_atual += $VO->value;
		}
		//echo Debug::li($RetornoDaPagina->saldo_total_atual);
		$RetornoDaPagina->array_extract 	= $result->result;
		//adiciona o pacotinho de resultado de MoneyHitoryInfo
		$returnResult->setHttpContentResult($RetornoDaPagina);
		
		return $returnResult;
	}

	public function purchaseDetail(){
		//iniciando o retorno padrao em http result
		$returnResult = new HttpResult();
		//id do pedido
		$id_purchase_order = DataHandler::forceInt($this->arrayRestFolder[1]);
		//agora verifica se esse pedido é desse usuário
		$eh_desse_cara = $this->checkPurchaseOwner($id_purchase_order, UserClient::getId());
		if($eh_desse_cara){
			//o pedido é dele
			//busca o detalhe dos pedidos
			$VO = new PurchaseOrderVO();
			$VO->setId($id_purchase_order, TRUE);
			$StdVO = $VO->toStdClass();
			$returnResult->setSuccess(TRUE);
			$retornoDaPaginaHTML = new PurchaseInfo();
			
			//lista os pagamentos efetuados pra esse id
			//$StdVO->id
			include_once "library/e_commerce/modules/payment/dao/PaymentDAO.class.php";
			$PaymentDAO = PaymentDAO::getInstance();
			
			$ReturnResultVO = $PaymentDAO->select(PaymentDAO::RETURN_VO, NULL, 1, NULL, $StdVO->id);
			$ReturnResultVO->transformAllVoInStdClass();
			//~inventa um parametro
			$StdVO->array_payment = array();
			$StdVO->total_payment = 0;
			if($ReturnResultVO->success){
				$StdVO->array_payment = $ReturnResultVO->result;
				foreach($StdVO->array_payment as $StdPaymentVO){
					$StdVO->total_payment += DataHandler::forceNumber($StdPaymentVO->total_value);
				}
			}
			
			$StdVO->total_payment = DataHandler::convertMoneyToBrazil($StdVO->total_payment, FALSE);
			$StdVO->total_a_pagar = 0;
			
			$tempDebito = DataHandler::forceNumber($StdVO->total_value) - DataHandler::forceNumber($StdVO->total_payment);
			
			if($tempDebito > 0){
				$StdVO->total_a_pagar = $tempDebito;
			}
			
			$StdVO->total_a_pagar = DataHandler::convertMoneyToBrazil($StdVO->total_a_pagar, FALSE);
			
			include_once "library/e_commerce/modules/purchase/dao/PurchaseItemDAO.class.php";
			$PurchaseItemDAO = PurchaseItemDAO::getInstance();
			/*
			 * 
			 */
			$ReturnDataVO = $PurchaseItemDAO->select(PurchaseItemDAO::RETURN_VO, NULL, NULL, $StdVO->id);
			$ReturnDataVO->transformAllVoInStdClass();
			//~inventa um parametro
			$StdVO->array_itens = array();
			if($ReturnDataVO->success){
				$StdVO->array_itens = $ReturnDataVO->result;
			}
			
			
			
			$valorSomandoItens = 0;
			foreach($StdVO->array_itens as $item){
				$valorSomandoItens = $valorSomandoItens + (DataHandler::forceNumber($item->product_price) * $item->quantity);
			}
			
			
		    $descontoFinal = 0;	
		    $CouponVO = new CouponVO();
		    $arrayLinkVO = $VO->getLinks("coupon", 1);
			if(count($arrayLinkVO)>0){			
		        //busca todos os cupons dentro da validade e que o codigo token não é obrigatorio
		    	$CouponDAO = CouponDAO::getInstance();
		    	$ReturnDataVO = $CouponDAO->select(
		    					CouponDAO::RETURN_VO, 
								$arrayLinkVO[0]->linked_table_id, 
								2, 
								NULL,
								NULL,
								NULL,
								NULL,
								NULL,
								date('Y-m-d'),
								$date_in_symbol = "<=", 
								date('Y-m-d'),
								$date_out_symbol = ">="								
							);
					
	            if($ReturnDataVO->success && count($ReturnDataVO->result)>0){
	            	$CouponVO = $ReturnDataVO->result[0];
	            	switch ($CouponVO->getType()){
						case "+":
								$descontoFinal = $CouponVO->getValue() + $valorSomandoItens;
							break;
						case "-":
								$descontoFinal = $CouponVO->getValue() - $valorSomandoItens;
							break;
						case "*":
								$descontoFinal = $valorSomandoItens - ($valorSomandoItens * ($CouponVO->getValue()/100));
							break;
						default:
							break;
					}
	            }
			}       	
	
			$CouponStd = $CouponVO->toStdClass();
			$CouponStd->valor_total = $valorSomandoItens;
			$CouponStd->valor_desconto = $valorSomandoItens - $descontoFinal;			
			
			$StdVO->coupon	= $CouponStd;
			
			$retornoDaPaginaHTML->std_vo = $StdVO;
			
			$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		} else {
			$returnResult->setSuccess(FALSE);
			$returnResult->addMessage("Não foi encontrado o detalhe desse pedido.");
		}
		
		//retorna pra view
		return $returnResult;
	}


	public function afiliateBanner(){
		//iniciando o retorno padrao em http result
		$returnResult = new HttpResult();
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new PurchaseInfo();
		
		//define a dao a ser usada em toda a controler
		$ContentSiteDAO = ContentSiteDAO::getInstance();
		$vo = $ContentSiteDAO->getVO();
		//define a vo a ser usada em toda a controler
		$ContentSiteVO = $ContentSiteDAO->getVO();
		//pega id passado na url
		

		$LinkDAO = LinkDAO::getInstance();
		$returnDataVO = $LinkDAO->select(LinkDAO::RETURN_VO, "category", 20, "content", NULL, 1);
//			echo $this->category_id;
//			print_r($returnDataVO);exit();
		//verifica se o resultado é uma categoryVO
		$arrayContentsVO = array();
		if($returnDataVO->success && count($returnDataVO->result)>0){
			foreach($returnDataVO->result as $LinkVO){
//					print_r($LinkVO);;
				$tempReturnDataVO = $LinkVO->getLinkedVO();
//					print_r($tempReturnDataVO);exit();
				if($tempReturnDataVO->success){
					$arrayContentsVO[] = $tempReturnDataVO->result;
				}
			}
		}
				
		$novo_content = array();
		foreach($arrayContentsVO as $ContentsVO){
			if($ContentsVO->active > 1){
				$images = $ContentsVO->getImages();
				$ContentsVO->images = $images;
				$novo_content[] = $ContentsVO;
			}
		}
		$arrayContentsVO = $novo_content;
		
	
		$returnResult->setSuccess(TRUE);
		$retornoDaPaginaHTML->array_banner = $arrayContentsVO;
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
}