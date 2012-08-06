<?php
include_once "library/facil3/core/vo/ReturnDataApiVO.class.php";
include_once "library/facil3/utils/rest/RestClient.class.php";
/**
 * @author 			: Renato Seiji Miawaki
 * @data			: 21/02/2011
 * @desc			: Integração com api do que barato
 */
class QueBaratoCategoria{
	
	private $urlBase;//url para envio dos gets
	private $RestClient;
	/**
	 * @param $p_urlBase por padrão é a api de teste http://sandbox.api.quebarato.com/v1/category/
	 * @return void
	 */
	public function __construct($p_urlBase = "http://sandbox.api.quebarato.com/v1/category/"){
		$this->urlBase = $p_urlBase;
		$this->RestClient = new RestClient();
	}
	/**
	 * @param $id
	 * @return ReturnDataApiVO que extends ReturnDataVO
	 */
	public function selectById($id){
		
	}
	/**
	 * @return ReturnDataApiVO que extends ReturnDataVO
	 	2.1.1 Lista de Categorias
		Representação
		XML
		<?xml version=”1.0” enconding=”UTF-8” >
		<list>
		<item href=”uri de uma categoria de nível 1” />
		<item href=”uri de uma categoria de nível 1” /> …
		</list>
		JSON
		{
		[ {"class":"category","href":"uri de uma categoria de nível 1"}, {"class":"category","href":"uri de uma
		categoria de nível 1"} ]
		}
		Método GET
		Obedecendo o princípio da interface uniforme, o método GET irá recuperar um
		recurso lista de categorias.
		Regras de negócio:
		1) A resposta da requisição HTTP deverá retornar uma representação
		somente com as categorias-pais do QueBarato.
		Referência: FE-01
		URI
		/v1/category/
		Cabeçalhos HTTP
		Nome Obrigatório Descrição Exemplo
		“X-QB-Key” Sim Chave de acesso a API “2:7:1”
		“Accept” Sim Especifica o tipo de mídia
		que o cliente aceita
		“application/xml”
	 */
	public function select(){
		$resultado = file_get_contents($this->urlBase."");
	}
}