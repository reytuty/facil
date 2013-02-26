<?php 
/**
 * @author		: Mauricio de Amorim
 * @date		: 07/11/2010
 * @version		: 1.0
 * @description	: Objeto Padrão onde são iniciados os campos utilizados para a View Product Model Details
 * @Obs 		:é extenção de uma classe com meta tags pré-definidas.
 **/
include_once(Config::FOLDER_APPLICATION."http/DemocrartPage.class.php");
class HttpClient extends DemocrartPage{
	public $arrayVariable;
	
	public $user_detail;
		
	public function __construct(){
		parent::__construct();
		$this->http_header->setTitle(Translation::text("A maior galeria de arte do Brasil em edição limitada, numerada e assinada de quadros, gravuras e fotografias. Decore com arte."));
		$this->http_header->setDescription(Translation::text("A maior galeria de arte do Brasil em edição limitada, numerada e assinada de quadros, gravuras e fotografias. Decore com arte."));
		$this->http_header->getKeywords(Translation::text("quadros gravuras,quadros online,venda quadros,desenhos,venda de quadros,reproduções,artes,comprar gravuras,comprar quadros,gravura,gravuras,loja quadros,quadros,decoração de interiores quartos,decoração de ambientes interiores,decoração de interiores salas,arte moderna,quadros decorativos,papel de parede decoração de interiores,dicas de decoração para quartos,papel de parede para quarto de bebe,papel de parede decoração quarto,lojas decoração interiores,decoração de paredes interiores,revista de decoração de interiores,fotos decoração de sala,dicas de decoração de interiores,decoração design de interiores,pintura em tela a oleo,pintura em tela,objetos de decoração para sala,quadros de parede,quarto de bebe,gravuras para quadros,adesivos decorativos de parede,papel de parede,papel de parede para quarto,quartos de bebe decorados,galerias de arte sp,molduras para quadros,sites de decoração de interiores,galeria de arte virtual,galerias de arte rio de janeiro,ideias decoração interiores,site de decoração de casas,adesivos de parede,decoração de interiores cozinha,decoração de quarto infantil menina,quadros em mdf,adesivos decoração interiores,quadros abstratos modernos,quadros pintura abstrata,decoração parede quarto,quadros para quarto de bebe,decoração interiores sala estar,decoração pintura parede,quadros pintura em tela,decoração festa junina infantil,adesivos de parede infantil,papel de parede para quarto infantil,quadros para quarto de casal,quarto de menina,galeria arte quadros,pintura quarto de bebe,quadro de fotos,papel de parede infantil,moveis para quarto de bebe,decoração de interiores banheiros,objetos de decoração de interiores,decoração de quarto de bebe com papel de parede,fotos de decoração de cozinha,adesivos de parede para quarto de bebe,
decoração de ambientes casa,cortinas para quarto de bebe,quadro de avisos,loja de quadros,decoração cozinhas planejadas,dicas de decoração de sala,decoração textura parede,decoração parede sala,quartos decorados,site de decoração de quartos,objetos de decoração para quarto,decoração de interiores de apartamentos,pintura em tela abstrato,quadros pintados,cozinha americana decoração,rei dos quadros,adesivos de parede tok stok,quarto de bebe menina,moveis e decoração de casas,adesivos decorativos,pintura de parede,quarto infantil,festa infantil,fotos de decoração de banheiros,papel de parede decorativo,quadros para decoração de sala,quadros decorativos para sala"));		
		$this->arrayProducts = array();
	}
}