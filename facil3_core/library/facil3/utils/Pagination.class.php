<?php
/*
 * @autor		: Renato Seiji Miawaki
 * @data		: 27/8/2008
 * @versÃ£o		: 1.0
 * @descriÃ§Ã£o	: Classe abstrata para fazer paginaÃ§Ã£o, trabalha com string para página html
 *
 * @dependencia	: Precisa do padrÃ£o de uso do template html do componente, sem ele fica difÃ­cil. Mas a classe trabalha tambÃ©m sÃ³ com array.
  
 */
include_once "library/facil3/utils/Debug.class.php";
class Pagination{
	public $paginaAtual_int;
	public $limitePaginacao_int;
	public $quantidadePorPagina_int;
	public $quantTotalResultados_int;
	
	public $nomenclaturaDaPaginacao_array;
	
	var $templateItemSelecionado;
	var $templateItem;
	var $templateAnteriorAtivo;
	var $templateAnteriorInativo; 
	var $templateProximoAtivo; 
	var $templateProximoInativo; 
	var $templateSeparador; 
	var $templatePaginacao;

	var $templateUltimo;
	var $templatePrimeiro;
	
	public static $template_esqueleto_paginacao = "
					<ul> 
					<li>&lt; php[primeiro]</li>
					<li>&lt; php[anterior]</li>
					php[paginacao]
					<li>php[proximo] &gt;</li>
					<li>php[ultimo] &gt;</li>
					</ul>";
	
	/**
	 * @param $paginaAtual_int
	 * @param $limitePaginacao_int
	 * @param $quantidadePorPagina_int
	 * @param $quantTotalResultados_int
	 * @param $nomenclaturaDaPaginacao_array
	 * @param $referenciaLimiteInicial_int (opcional)
	 * @return void
	 */
	function __construct($paginaAtual_int, $quantidadePorPagina_int, $quantTotalResultados_int, $nomenclaturaDaPaginacao_array = NULL, $limitePaginacao_int = 10){
		//$limitePaginacao_int envie ZERO para ilimitado
		
		/*
		 * 	paginaAtual_int			: 1
		 	quantidadePorPagina		: 21 
			quantTotalResultados	: 489
			nomenclaturaDaPaginacao_array		: 
			limitePaginacao			: 10
		 */
		$paginaAtual_int = ($paginaAtual_int > 0) ? $paginaAtual_int : 1;//verificação da paginação não ser zero ou negativa
		
		$this->paginaAtual_int					= $paginaAtual_int;
		
		$this->limitePaginacao_int				= $limitePaginacao_int;
		
		$this->quantidadePorPagina_int			= $quantidadePorPagina_int;
		$this->quantTotalResultados_int			= $quantTotalResultados_int;
		$this->nomenclaturaDaPaginacao_array 	= $nomenclaturaDaPaginacao_array;
	}	
	/**
	 * @param $paginaAtual_int 1 é a primeira
	 * @param $quantidadePorPagina_int
	 * @return int
	 */
	public function getLimitDataBase($paginaAtual_int, $quantidadePorPagina_int){
		$limite_temp = $quantidadePorPagina_int*($paginaAtual_int-1);
		$referenciaLimiteInicial_int 	= ($limite_temp >= 0) ? $limite_temp : 0;	//aqui atribui o valor para a pessoa usar na Query completa futuramente
		return $referenciaLimiteInicial_int;
	}
	function getHtml(
									$templateItemSelecionado = "<li class=\"atual\">php[item]</li>" , 
									$templateItem = "<li><a href=\"php[link]\">php[item]</a></li>", 
									$templateAnteriorAtivo = "<li class=\"ativo\"><a href=\"php[link]\">&lt;</a></li>", 
									$templateAnteriorInativo = "<li class=\"inativo\">anterior</li>", 
									$templateProximoAtivo = "<li class=\"ativo\"><a href=\"php[link]\">&gt;</a></li>", 
									$templateProximoInativo = "<li class=\"inativo\">pr&oacute;ximo</li>", 
									$templateSeparador = "  ", 
									$templatePaginacao = "<ul class=\"paginacao\"> 
									php[primeiro]
									php[anterior]
									php[paginacao]
									php[proximo]
									php[ultimo]
									</ul>", 
									$linkAtual = "",
									$returnCleanIfDontNeedPages = TRUE,
									$templatePrimeiro = "<li class=\"ativo\"><a href=\"php[link]\">&lt;&lt;</a></li>", 
									$templateUltimo = "<li class=\"ativo\"><a href=\"php[link]\">&gt;&gt;</a></li>"
									){
									//devolve string
		
		/*
		public $paginaAtual_int;
		public $limitePaginacao_int;
		public $quantidadePorPagina_int;\
		public $quantTotalResultados_int;
		*/
		
		// para uso do fÃ¡cil
		$paginaAtual				= $this->paginaAtual_int;
		$templateItemSelecionado 	= $templateItemSelecionado;
		$templateItem 				= $templateItem;
		$templateAnteriorAtivo 		= $templateAnteriorAtivo;
		$templateAnteriorInativo 	= $templateAnteriorInativo;
		$templateProximoAtivo 		= $templateProximoAtivo;
		$templateProximoInativo 	= $templateProximoInativo;
		$templateSeparador 			= $templateSeparador;
		$templatePaginacao 			= $templatePaginacao;
		$templateUltimo				= $templateUltimo;
		$templatePrimeiro			= $templatePrimeiro;
		
		$result 			= $templatePaginacao;
		$quantTotalPagina	= ceil($this->quantTotalResultados_int/$this->quantidadePorPagina_int);
		$pagFinal 			= $quantTotalPagina;
		
		$metadePaginacao 	= floor($this->limitePaginacao_int/2);
		
		$paginaInicial 		= 1;
		if($this->limitePaginacao_int > 0){// faz o calculo se tiver limite
			if($quantTotalPagina > $this->limitePaginacao_int){
				$paginaInicial 		= $this->paginaAtual_int - $metadePaginacao;
				if($paginaInicial < 1){
					$paginaInicial = 1;
				}
			}
		}
		if($this->limitePaginacao_int > 0){//sÃ³ faz o calculo se tiver limite
			$paginaFinal 		= $paginaInicial+$this->limitePaginacao_int;
		} else {
			$paginaFinal = $quantTotalPagina;
		}
		//verificando se a página final nÃ£o estÃ¡ acima da quantidade de resultados
		if($paginaFinal > $quantTotalPagina){$paginaFinal = $quantTotalPagina;}
		
		
		// montando o link do a href=''
		if(empty($linkAtual)){
			$linkAtual = Config::getRootPath(DataHandler::removeDobleBars(
																		str_replace(Config::getRootApplication(), "", $_SERVER["REQUEST_URI"])
																		)
												);
		}
		if($returnCleanIfDontNeedPages && $this->quantTotalResultados_int <= $this->quantidadePorPagina_int){
			//se não precisar de paginação, e for enviado que quando isso acontecer retornar vazio, lá vai o vazio
			return "";
		}
		
		$primeira 	= str_replace("/pag.$paginaAtual", "/pag.1", $linkAtual);
		if($paginaAtual > 1){
			$ultima		= str_replace("/pag.$paginaAtual", "/pag.".$paginaFinal, $linkAtual);
		}else{
			$ultima		= $linkAtual."/pag.".$pagFinal;
		}
		
		//exit();
		if(strpos($linkAtual, "/pag.$paginaAtual")){
			//entra aqui se existe a tag de página no padrao com a página atual setada
			$anterior 	= str_replace("/pag.$paginaAtual", "/pag.".($this->paginaAtual_int-1), $linkAtual);
			$proximo 	= str_replace("/pag.$paginaAtual", "/pag.".($this->paginaAtual_int+1), $linkAtual);
		} else if(strpos($linkAtual, "/pag.")){
			//entra aqui se tiver em alguma página setada
			$anterior	= @ereg_replace("pag\.[0-9]+", "pag.".($this->paginaAtual_int-1), $linkAtual);
			$proximo	= @ereg_replace("pag\.[0-9]+", "pag.".($this->paginaAtual_int+1), $linkAtual);
		} else {
			//entra aqui se nunca foi setada nenhuma página
			$anterior	= $linkAtual."/pag.".($this->paginaAtual_int-1);
			$proximo	= $linkAtual."/pag.".($this->paginaAtual_int+1);
		}
		
		$anterior	= str_replace("http://", "", $anterior);
		$proximo	= str_replace("http://", "", $proximo);
		$primeira	= str_replace("http://", "", $primeira);
		$ultima		= str_replace("http://", "", $ultima);
		
		$anterior	= str_replace("//", "/", $anterior);
		$proximo	= str_replace("//", "/", $proximo);
		$primeira	= str_replace("//", "/", $primeira);
		$ultima		= str_replace("//", "/", $ultima);
		
		$anterior	= "http://".$anterior;
		$proximo 	= "http://".$proximo;
		$primeira	= "http://".$primeira;
		$ultima 	= "http://".$ultima;
		
		
		$temp_primeira		= str_replace("php[link]", $primeira, $templatePrimeiro);
		$temp_ultima		= str_replace("php[link]", $ultima, $templateUltimo);
		
		//definindo qual template usar para o botao anterior
		$temp_anterior = "";
		if($this->paginaAtual_int > 1){
			//existe uma página proxima a esta
			
			$temp_anterior = $templateAnteriorAtivo;
			//echo Debug::li($temp_anterior);
			$temp_anterior		= str_replace("php[link]", $anterior, $temp_anterior);
			//echo Debug::li($temp_anterior);
			//exit();
		} else {
			$temp_anterior = $templateAnteriorInativo;
		}
		//defidindo qual template usar para o botao prÃ³ximo
		$temp_proximo = "";
		if(($this->paginaAtual_int) < $quantTotalPagina){
			//existe uma página anterior a esta
			$temp_proximo = $templateProximoAtivo;
			$temp_proximo = str_replace("php[link]", $proximo, $temp_proximo);
		} else {
			$temp_proximo = $templateProximoInativo;
		}
		
		//$templateProximo	= ;
		
		$paginacao_str = "";
		for($i = $paginaInicial; $i <= $paginaFinal; $i++){
			//definindo qual template usar
			$temp_pag = "";
			
			// página atual
			// quando estÃ¡ na página atual, $temp_pag Ã© o item selecionado;
			// SENÃ‚O, Ã© apenas um item.
			// mais pra frente, aqui tÃ¡ indo com php[item], mais pra frente tem o str_replace disso.
			if($i == $this->paginaAtual_int){
				$temp_pag = $templateItemSelecionado;
			} else {
				$temp_pag = $templateItem;
			}
			$item	= "";
			if(strpos($linkAtual, "/pag.")){
				//
				$item 		= @ereg_replace("/pag.[0-9]+", "/pag.".$i, $linkAtual);
			}else if(!strpos($linkAtual, "/pag.")){
				//tem variavel masn Ã£o de pagina
				$item 		= $linkAtual."/pag.".$i;
			}
			// tira barra dupla
			$item	= str_replace("http://", "", $item);
			$item	= str_replace("//", "/", $item);
			$item	= "http://".$item;
			 
			$temp_pag = str_replace("php[link]", $item, $temp_pag);
			
			if($this->nomenclaturaDaPaginacao_array[$i]){
				$temp_pag = str_replace("php[item]", $this->nomenclaturaDaPaginacao_array[$i], $temp_pag);
			} else {
				
				$temp_pag = str_replace("php[item]", $i, $temp_pag);
			}
			//adiciona na variavel de buffer
			$paginacao_str .= $temp_pag;
			//poe o separador se não for o ultimo
			if($i+1 <= $paginaFinal){
				$paginacao_str .= $templateSeparador;
			}
		}
		//php[pag_id]
		//php[anterior] - php[paginacao] - php[proximo]
		
		
//		$linkFinal = $linkAtual.$phpVar.$phpId;
		
		$result 			= ($paginaAtual == 1)?str_replace("php[primeiro]", 	"",	$result):str_replace("php[primeiro]", $temp_primeira, $result);
		$result 			= str_replace("php[proximo]", 	$temp_proximo, 	$result);
		$result 			= str_replace("php[anterior]", 	$temp_anterior, $result);
		$result 			= str_replace("php[paginacao]", $paginacao_str, $result);
		$result 			= ($paginaAtual == $pagFinal)?str_replace("php[ultimo]", "", $result):str_replace("php[ultimo]", $temp_ultima, $result);
		//		$result 			= str_replace("php[link]", 		$linkFinal, 	$result);

		return $result;
	}
}
