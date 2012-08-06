<?php

/**
 * @author mauricio amorim
 * ver http://www.correios.com.br/webServices/PDF/SCPP_manual_implementacao_calculo_remoto_de_precos_e_prazos.pdf
 
	#Variaveis
	        NOME DO CAMPO
	                O QUE É
	                TIPO
	                OBRIGATÓRIO ?
	                
	                
	        nCdEmpresa 
	                Seu código administrativo junto à ECT. O código está disponível no corpo do contrato firmado com os Correios.
	                String
	                Não, mas o parâmetro tem que ser passado mesmo vazio.
	                
	        sDsSenha
	                Senha para acesso ao serviço, associada ao seu código administrativo. A senha inicial corresponde aos 8 primeiros dígitos do CNPJ informado no contrato. A qualquer momento, é possível alterar a senha no endereço http://www.corporativo.correios.com.br/encomendas/servicosonline/recuperaSenha.
	                String
	                Não, mas o parâmetro tem que ser passado mesmo vazio.
	                
	        nCdServico
	                Código do serviço
	                String
	                Sim.Pode ser mais de um numa consulta separados por vírgula.
	        
	        sCepOrigem
	                CEP de Origem sem hífen.Exemplo: 05311900
	                String
	                Sim
	                
	        sCepDestino
	                CEP de Destino sem hífen
	                String
	                Sim
	        
	        nVlPeso
	                Peso da encomenda, incluindo sua embalagem. O peso deve ser informado em quilogramas
	                Decimal
	                Sim,Se o serviço não exigir as medidas informar zero.
	        
	        
	        nCdFormato
	                Formato da encomenda (incluindo embalagem).Valores possíveis: 1 ou 2 (1 – Formato caixa/pacote/2 – Formato rolo/prisma)
	                Int
	                Sim
	                
	        nVlComprimento
	                Comprimento da encomenda (incluindo embalagem), em centímetros.
	                Decimal
	                Sim.Se o serviço não exigir medidas informar zero.
	                
	        nVlAltura
	                Altura da encomenda (incluindo embalagem), em centímetros.
	                Decimal
	                Sim.Se o serviço não exigir medidas informar zero.
	                
	        nVlLargura
	                Largura da encomenda (incluindo embalagem), em centímetros.
	                Decimal
	                Sim.
	                Se o serviço não exigir medidas informar zero.
	                
	        nVlDiametro
	                Diâmetro da encomenda (incluindo embalagem), em centímetros.
	                Decimal
	                Sim.Se o serviço não exigir medidas informar zero.
	                
	        sCdMaoPropria
	                Indica se a encomenda será entregue com o serviço adicional mão própria.Valores possíveis: S ou N (S – Sim, N – Não)
	                String
	                Sim
	                        
	        nVlValorDeclarado
	                Indica se a encomenda será entregue com o serviço adicional valor declarado. Neste campo deve ser apresentado o valor declarado desejado, em Reais.
	                Decimal
	                Sim.Se não optar pelo serviço informar zero.
	                
	        sCdAvisoRecebimento
	                Indica se a encomenda será entregue com o serviço adicional aviso de recebimento.Valores possíveis: S ou N (S – Sim, N – Não)
	                String
	                Sim.Se não optar pelo serviço informar ‘N’
	        
	        
	        
	
	CÓDIGOS
	
	        # Sem Contrato
	        PAC - 41106
	        SEDEX - 40010
	        SEDEX A COBRAR - 40045
	        SEDEX 10 - 40215
	        SEDEX HOJE - 40290
	        
	        #Com Contrato
	        SEDEX - 40096 - 40436 - 40444
	        E-Sedex - 81019
	        PAC - 41068

 */
class Correios{
	
	
	const FRETE_PAC 		= 41106;
	const FRETE_SEDEX		= 40010;
	const FRETE_SEDEX_10	= 40215;
	const FRETE_SEDEX_HOJE	= 40290;
	const FRETE_E_SEDEX		= 81019;
	//const FRETE_MALOTE	= 44105;
	
	public static $types;
	 
	/**
	 * @return array
	 */
	public static function get_types(){
		$types = array(
			(object) array( "id"=> Correios::FRETE_PAC, 'name'=> 'PAC' , 'desc'=> 'serviço de remessa económica de mercadorias com entrega domiciliar.'),
			(object) array( "id"=> Correios::FRETE_SEDEX, 'name'=> 'Sedex' , 'desc'=> 'Serviço de remessa expressa de mercadorias. Caso sua cidade não esteja na lista será usada outra transportadora para entrega sua obra.'),
			(object) array( "id"=> Correios::FRETE_SEDEX_10, 'name'=> 'Sedex 10' ,'desc'=> 'Serviço de remessa expressa de mercadorias com entrega para o dia seguinte. Caso sua cidade não esteja na lista será usada outra transportadora para entrega.'),
			//(object) array( "id"=> Correios::FRETE_SEDEX_HOJE, 'name'=> 'Sedex Hoje' ,'desc'=> 'serviço de remessa expressa de documentos e mercadorias com entrega garantida no mesmo dia da postagem.'),
		);
		
		return $types ;
	} 
	
	/**
	 * @param $id of the type
	 * @return object or false
	 */
	public static function getByID($id){
		
		$types = Correios::get_types();
		foreach ($types as $t){
			if($t->id == $id)
				return $t;
		} 
		return false;
	}
	

	/**
	* calcula_frete
	*
	* @param mixed $servico
	* @param string $origem CEP da origem
	* @param string $destino CEP do destino
	* @param float $peso Peso em Kg
	* @param mixed $nCdFormato ???
	* @param int $p_time_limit tempo limite de espera do soket no site dos correios
	* @return array
	*/
	public static function calcula_frete($servico, $origem, $destino, $peso, $nCdFormato, $p_time_limit = 60){
		//DADOS PARA O CORREIO
		        $nCdEmpresa          = "";
		        $sDsSenha            = "";
		        $sCepOrigem          = $origem; //CEP DE ORIGEM
		        $sCepDestino		 = $destino; //CEP DE DESTINO
				$nVlPeso             = 1; //PESO É IMPORTANTE PARA O CALCULO
		        $nCdFormato          = 2;
		        $nVlComprimento      = 90;
		        $nVlAltura           = 10;
		        $nVlLargura          = 10;
		        $nVlDiametro         = 5;
		        $sCdMaoPropria       = "N";
		        $nVlValorDeclarado   = 0;
		        $sCdAvisoRecebimento = "N";
		        $nCdServico			 = $servico;
		//
				
		// URL QUE FARÁ AS CONSULTAS NOS CORREIOS
		$URLcorreios ="http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?"
		."nCdEmpresa=".$nCdEmpresa."&"
		."sDsSenha=".$sDsSenha."&"
		."sCepOrigem=".$sCepOrigem."&"
		."sCepDestino=".$sCepDestino."&"
		."nVlPeso=".$nVlPeso."&"
		."nCdFormato=".$nCdFormato."&"
		."nVlComprimento=".$nVlComprimento."&"
		."nVlAltura=".$nVlAltura."&"
		."nVlLargura=".$nVlLargura."&"
		."sCdMaoPropria=".$sCdMaoPropria."&"
		."nVlValorDeclarado=".$nVlValorDeclarado."&"
		."sCdAvisoRecebimento=".$sCdAvisoRecebimento."&"
		."nCdServico=".$nCdServico."&"
		."nVlDiametro=".$nVlDiametro."&"
		."StrRetorno=xml";

		//PEGAMOS OS DADOS DE RETORNO XML COM O SIMPLEXML DO PHP  
		$dados_correios = @simplexml_load_file($URLcorreios);
		
		$dados_correios = (object) $dados_correios;
		$dados_correios = $dados_correios->cServico;
		
		$data['valor']		= floatval(str_replace(',', '.', $dados_correios->Valor[0]));
		$data['id']			= $dados_correios->Codigo;
	  	$data['erro'] 		= $dados_correios->Erro;
	 
	  return $data;
	}
}
