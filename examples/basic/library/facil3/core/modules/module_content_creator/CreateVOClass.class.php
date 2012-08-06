<?php
/**
 * Classe de criar classes VO baseadas em content, utilizando paramether e link para seus parametros criados
 * Essa é uma classe estática, não tente criar estancia.
 * Utilize diretamente CreateVOClass->create(...);
 * @author 		: Renato Miawaki
 * @version 	: 1.0
 */
//adiciona as classes de referencia
include_once 'library/facil3/core/modules/module_content_creator/vo/BaseInfoParametherVO.class.php';
include_once 'library/facil3/core/modules/module_content_creator/vo/BaseInfoLinkVO.class.php';

class CreateVOClass {
	/**
	 * Returna um BaseInfoParametherVO para que se crie uma array e possa ser enviada no $arrayParametrosVO do metodo createVOClass
	 * @param string $variableName
	 * @param string $variableType BaseInfoParametherVO::?
	 * @param int $quantity
	 * @param boolean $acceptLocale
	 * @param * $defaultValue
	 * @param boolean $required
	 * @param (nao lembro) $reciveByDefault
	 * @param string $description
	 * @param int $orderInClass tente utilizar o indice da array criada, pois assim caso de algum erro, pode-se saber em qual variavel o erro foi gerado
	 * @return BaseInfoParametherVO
	 */
	public static function getBaseInfoParametherVO($variableName, $variableType, $quantity, $acceptLocale, $defaultValue, $required, $reciveByDefault, $description, $orderInClass){
		return new BaseInfoParametherVO($variableName, $variableType, $quantity, $acceptLocale, $defaultValue, $required, $reciveByDefault, $description, $orderInClass);
	}
	public static function getBaseInfoLinkVO($variableName, $aliasTableName, $returnEntity = FALSE, $orderInClass = 0, $reciveByDefault = FALSE, $required = FALSE, $quantity = 0, $description = "Link to another entity"){
		return new BaseInfoLinkVO($variableName, $aliasTableName, $returnEntity, $orderInClass, $reciveByDefault, $required, $quantity, $description);
	}
	public static function create(ModuleConfig $ModuleConfigVO){
		//criando variaveis para facilitar a adaptação
		$nomeDaClasse 		= $ModuleConfigVO->getModuleName();
		$arrayParametrosVO 	= $ModuleConfigVO->getArrayParamethers();
		$descricaoDaClasse 	= $ModuleConfigVO->getModuleDescription();
		$aliasModuleName 	= $ModuleConfigVO->getEntityName();
		
		$dataAtual = date("d/m/Y", time());
		
		$temp_stringVars = "";
		$temp_setFetchHandler = "";
		$temp_setFetchArrayHandler = "";
		$temp_sets = "";
		$temp_remove = "";
		$temp_gets = "";
		$temp_validations = "";
		foreach($arrayParametrosVO as $ParametroVO){
			if(FALSE){
				$ParametroVO = new BaseInfoParametherVO($variableName, $variableType, $quantity, $acceptLocale, $defaultValue, $required, $reciveByDefault, $description, $orderInClass);
			}
			$temp_variable_name = $ParametroVO->getVariableName();
			
			// ----- DECLARANDO VARIAVEIS
			//concatena a declaração das variaveis da VO criada
			$temp_stringVars .= CreateVOClass::createDeclaredVariable($ParametroVO);
			
			//---------------------------------------------------- criando script de fetchObject
			$temp_setFetchHandler .= "\$this->".$temp_variable_name." = DataHandler::getValueByStdObjectIndex(\$object, \"".$temp_variable_name."\");
			";
			if($ParametroVO->getReciveByDefault()){
				//---------------------------------------------------- criando script de fetchArray
				$temp_setFetchArrayHandler .= "\$this->".self::getMethodNameToSet($ParametroVO)."(DataHandler::getValueByArrayIndex(\$array, \"".$temp_variable_name."\"));
			";
			}
			//---------------------------------------------------- criando metodos SET e GET
			// AQUI CRIANDO OS SETS
			$temp_sets .= self::createMethodSet($ParametroVO);
				//quando tem mais do que 1, precisa ter o metodo remove
					//AQUI CRIANDO O REMOVE
					if($ParametroVO->getQuantity() != 1){
						//se pode mais de um precisa do metodo remove
						$temp_remove .= self::createRemove($ParametroVO);	
					}
			//AQUI CRIANDO O METODO GET (lembrando que ainda estamos dentro de um loop)
		//adicionando comentários
			$temp_gets .= self::createMethodGet($ParametroVO);
			//---------------------------------------------------- criando script de validação
			//antes ve se a variavel quer ser validada
			if($ParametroVO->getRequired()){
				//diz ser necessário, então faz a validação dependendo do tipo
				$temp_validations .= self::createScriptValidation($ParametroVO);;
			}
		}
		
		
		$class_esqueleto = "
	/**
	 * @desc		$descricaoDaClasse
	 * @author 		autoClass de Renato Miawaki - reytuty@gmail.com
	 * @date		$dataAtual
	 */
	include_once 'library/facil3/core/vo/BaseVO.class.php';
	class {$nomeDaClasse}VO extends BaseVO {
		//private vars cache de parameter ou link
		$temp_stringVars
		public function __construct(\$arrayFetchPost = NULL){
			parent::_construct(\$arrayFetchPost);
			//o default seria ter o __table como content, caso mude, continua gravando na tabela content,
			//mas links e relacionamentos ficam ligadas a esse alias
			\$this->__table = \"$aliasModuleName\";
			//se enviar \$arrayFetchPost é para distrinchar internamente os dados do usuario
			if(\$arrayFetchPost !== NULL){
				//enviou array para distrinchar
				\$this->setFetchArray(\$arrayFetchPost);
			}
		}//end construct
		//setFetchObject
		public function setFetchObject(\$object){
			$temp_setFetchHandler
		}
		//setFetchArray
		public function setFetchArray(\$array){
			$temp_setFetchArrayHandler
		}
		//metodos sets
		$temp_sets
		//metodos remove
		$temp_remove
		//metodos get
		$temp_gets
		//validation
		public function validate(){
			\$ReturnResultVO = new ReturnResultVO();
			\$ReturnResultVO->success = TRUE;
			$temp_validations
			return \$ReturnResultVO;
		}
	}";
			return $class_esqueleto;
	}
	/**
	 * Returna string da declaração de variavel na classe. Apenas uma.
	 * @param BaseInfoParametherVO $ParametroVO
	 * @return string
	 */
	private static function createDeclaredVariable(BaseInfoParametherVO $ParametroVO){
		$temp_stringVars = "
		/**
		 * ".$ParametroVO->getDescription()."
		 * @var ".self::getTypeOfReturn($ParametroVO)."
		 */
		protected \$".$ParametroVO->getVariableName().CreateVOClass::getStringDefaultValue($ParametroVO);
		//finaliza a linha
		$temp_stringVars .= ";
		";
		return $temp_stringVars;
	}
	/**
	 * Retorna uma string com = 'valor' caso possa ter valor. Do contrário retorna string vazia
	 * @param BaseInfoParametherVO $ParametroVO
	 * @return string
	 */
	private static function getStringDefaultValue(BaseInfoParametherVO $ParametroVO){
	//essa variavel é para guardar o simbolo = 1, por exemplo
		$temp_string_default_value =  "";
		//verifica se tem valor default
		if($ParametroVO->getCanHaveDefaultValue()){
			//guarda em variavel para uso futuro
			$temp_string_default_value = " = '".str_replace("'", "\'", $ParametroVO->getDefaultValue())."'";
		}
		return "$temp_string_default_value";
	}
	/**
	 * Retorna a string do tipo de rorno para comentário da classe
	 * @param BaseInfoParametherVO $ParametroVO
	 * @return string
	 */
	private static function getTypeOfReturn(BaseInfoParametherVO $ParametroVO){
		$temp_tipo_variavel = $ParametroVO->getVariableType();
		if($ParametroVO->getVariableType() == BaseInfoParametherVO::TYPE_LINK){
			//se for link tem um tratamento diferente para o retorno
			$temp_tipo_variavel = "Object";
			//verifica a quantidade
			if($ParametroVO->getQuantity() != 1){
				$temp_tipo_variavel = "Array Objects";
			}
		} else if($ParametroVO->getQuantity() != 1){
			//se tiver mais de 1, é uma array do tipo de variavel
			$temp_tipo_variavel = "Array ".$ParametroVO->getVariableType();
		}
		return "$temp_tipo_variavel";
	}
	/**
	 * retorna o prefixo para utilizar no nome do metodo, se set, ou add
	 * @param BaseInfoParametherVO $ParametroVO
	 * @return string
	 */
	private static function getPrefixNameSet(BaseInfoParametherVO $ParametroVO){
		//para set o valor padrão é set para quando é apenas 1, se for mais é add
		$temp_prefix_name_set 		= "set";
		//verifica o tipo
		if($ParametroVO->getQuantity() != 1){
			//para set, quando tiver mais do que 1 varaivel 
			$temp_prefix_name_set = "add";
		}
		return "$temp_prefix_name_set";
	}
	/**
	 * Retorna o nome do metodo set ou add dependendo do caso
	 * @param BaseInfoParametherVO $ParametroVO
	 * @return string
	 */
	private static function getMethodNameToSet(BaseInfoParametherVO $ParametroVO){
		$temp_sulfix_method_name = DataHandler::urlFolderNameToClassName($ParametroVO->getVariableName());
		//criar metodo para retornar o o nome do metodo para set
		$temp_method_name = CreateVOClass::getPrefixNameSet($ParametroVO).$temp_sulfix_method_name;
		return "$temp_method_name";
	}
	/**
	 * Retorna o nome do metodo get
	 * @param BaseInfoParametherVO $ParametroVO
	 * @return string
	 */
	private static function getMethodNameToGet(BaseInfoParametherVO $ParametroVO){
		$temp_sulfix_method_name = DataHandler::urlFolderNameToClassName($ParametroVO->getVariableName());
		//criar metodo para retornar o o nome do metodo para set
		$temp_method_name = "get".$temp_sulfix_method_name;
		return "$temp_method_name";
	}
	/**
	 * Retorna a string do metodo set, ou add, dependendo do caso
	 * @param BaseInfoParametherVO $ParametroVO
	 * @throws ErrorException quando diz que o parametro é do tipo LINK, mas não utilizou a VO do tipo link
	 * @return string
	 */
	private static function createMethodSet(BaseInfoParametherVO $ParametroVO){
		$temp_local_variable_name = "\$".$ParametroVO->getVariableType();
			if($ParametroVO->getIsLink()){
				//se for link coloca o nome da variavel de modo que indique que deve receber o id o que vai ser linkado
				$temp_local_variable_name = "\$linked_id";
			}
			//criar metodo para retornar o o nome do metodo para set
			$temp_method_name = self::getMethodNameToSet($ParametroVO);
			$temp_variable_name = $ParametroVO->getVariableName();
			$temp_sets = "
		/**
		 * @param php([coment_param])
		 * @return void
		 */
		public function $temp_method_name(";
			//agora a variavel que recebe, tratamento para cada tipo de caso
			
			//para a declaração do parametro
			$coment_param = "";
			//variavel para controle de pre tratamento de valor recebido
			$temp_pre_tratamento_info = ""; 
			//salvar o nome da variavel que pretende utilizar no parametro
			$temp_var_param_name = "\$int";
			//para passar o tipo de parametro a ser gravado
			$temp_paramether_type = "varchar";
			switch($ParametroVO->getVariableType()){
				case BaseInfoParametherVO::TYPE_INT:
					$temp_var_param_name ="\$int";
					$coment_param = "$temp_var_param_name (int)";
					//força inteiro
					$temp_pre_tratamento_info = "$temp_var_param_name = DataHandler::forceInt($temp_var_param_name);";
					$temp_paramether_type = "int";
					break;
				case BaseInfoParametherVO::TYPE_NUMBER:
					$temp_var_param_name = "\$number";
					$coment_param = "$temp_var_param_name (number)";
					$temp_pre_tratamento_info = "$temp_var_param_name = DataHandler::forceNumber($temp_var_param_name);";
					$temp_paramether_type = "number";
					break;
				case BaseInfoParametherVO::TYPE_DATE:
					$temp_var_param_name = "\$string";
					$coment_param = "$temp_var_param_name (string date)";
					//tratamento para data inserida no banco de dados
					$temp_pre_tratamento_info = "$temp_var_param_name = DataHandler::convertDateToDB($temp_var_param_name);";
					$temp_paramether_type = "date";
					break;
				case BaseInfoParametherVO::TYPE_LINK:
					$temp_var_param_name = "\$id";
					$coment_param = "$temp_var_param_name (int) da entidade";
					//se link, trata como inteiro também
					$temp_pre_tratamento_info = "$temp_var_param_name = DataHandler::forceInt($temp_var_param_name);";
					$temp_paramether_type = "nao tem tipo de parametro para link";
					break;
				case BaseInfoParametherVO::TYPE_BOOLEAN:
					$temp_var_param_name = "\$bool";
					$coment_param = "$temp_var_param_name (bool)";
					//pre tratamento para boolean recebido, no banco vai 1 ou zero
					$temp_pre_tratamento_info = "$temp_var_param_name = ($temp_var_param_name == true)?1:0;";
					$temp_paramether_type = "int";
					break;
				case BaseInfoParametherVO::TYPE_TEXT:
					$temp_paramether_type = "text";
				case BaseInfoParametherVO::TYPE_VARCHAR:
					$temp_paramether_type = "varchar";
				default:
					$temp_var_param_name = "\$string";
					$coment_param = "$temp_var_param_name (string)";
					//se string, deve tirar os injections, para isso tirar scapes ou algo do tipo - fazer manualmente para cada caso
					$temp_pre_tratamento_info = "
					//nao é automático o tratamento anti-sql-injection
					$temp_var_param_name = DataHandler::forceString($temp_var_param_name);";
					break;
			}
			//str replace para inserir o comentário do parametro
			$temp_sets = str_replace("php([coment_param])", $coment_param, $temp_sets);
			
			//adiciona o nome do parametro para receber no metodo
			$temp_sets .= "$temp_var_param_name";
			//se tiver valor padrao, e não for link, atribui no set por padrao
			$temp_sets .= self::getStringDefaultValue($ParametroVO);
			//se aceita locale, coloca como parametro, no default NULL
			if($ParametroVO->getAcceptLocale() && !$ParametroVO->getIsLink()){
				$temp_sets .= ", \$locale = NULL";
			}
			//fecha os parametros e abre o metodo
			$temp_sets .= "){
			"; 
			
			//--------- DENTRO DO METODO SET
			//se necessário, faz o pré tratamento do valor a receber
			$temp_sets .= $temp_pre_tratamento_info;
			
			if(!$ParametroVO->getIsLink()){
				//se for do tipo parametro
				
				//se pode ter mais do que um parametro, basta adicionar
				if($ParametroVO->getQuantity() > 1){
					//lembre-se de que o methodo deve se chamar add  casso possa mais do que 1
					$temp_sets .= "
			\$this->addParamether('$temp_paramether_type', '$temp_variable_name', $temp_var_param_name";
					if($ParametroVO->getAcceptLocale()){
						$temp_sets .= ", \$locale";
					}
					$temp_sets .= ");";
					//E como pode mais do que 1 item, precisa ter o Remove. To pensando alto apenas
					//o remove já existe em paramether, entao talvez no caso de N itens, deva retornar paramether direto
				} else {
					//só pode ter 1 parametro desses, então precisa fazer um tratamento
					$temp_sets .= 
			"\$arrayReturn = \$this->getParamethersByValues('$temp_variable_name', '$temp_paramether_type', $temp_var_param_name";
					if($ParametroVO->getAcceptLocale()){
						$temp_sets .= ", \$locale";
					}
					$temp_sets .= ");";
					$temp_sets .= "
			if(count(\$arrayReturn) > 0){
				//se só pode 1 - faz o tratamento pegando o primeiro parametro encontrado
				\$ItemParametherVO = \$arrayReturn[0];
				
				//muda o valor do parametro que já existe, e ativa, caso não esteja ativo
				\$ItemParametherVO->setActive(1);
				\$ItemParametherVO->setValue($temp_var_param_name);
				\$this->addParametherVO(\$ItemParametherVO);
			} else {";
					$temp_sets .= "
				\$this->addParamether('$temp_paramether_type', '$temp_variable_name', $temp_var_param_name";
					if($ParametroVO->getAcceptLocale()){
						$temp_sets .= ", \$locale";
					}
					$temp_sets .= ");";
					
					$temp_sets .= "
			}//end if";
				}
				
				
				
			} else {
				//modo de funcionamento para adicionar link
				try{
					//coloquei num try pois um erro provavel é a pessoa passar uma BaseInfoParametherVO e passar que o tipo é link
					$temp_alias_table_name = $ParametroVO->getAliasTableName();
				} catch(Exception $e){
					throw new ErrorException("Para parametros do tipo Link, é necessário utilizar BaseInfoLinkVO. ", $ParametroVO->getOrderInClass());
				}
				//se pode ter mais do que um link, basta adicionar
				if($ParametroVO->getQuantity() != 1){
					$temp_sets .= "
			\$this->addLink(\"$temp_alias_table_name\", $temp_var_param_name);";
				} else {
					//só pode 1
					$temp_sets .= "
			\$arrayReturn = \$this->getLinks(\"$temp_alias_table_name\");
			if(count(\$arrayReturn) > 0){
					//recicla esse link alterando para o novo valor
					\$LinkVO = \$arrayReturn[0];
					\$this->addLink(\"$temp_alias_table_name\", $temp_var_param_name, \$LinkVO->getId());
			} else {
				\$this->addLink(\"$temp_alias_table_name\", $temp_var_param_name);
			}";
				}
				
			}
				
			//fecha o metodo SET
			$temp_sets .= "
		} // end $temp_method_name";
			
			return "$temp_sets";
	}// end createMethodSet
	/**
	 * Retorna String do metodo get
	 * @param BaseInfoParametherVO $ParametroVO
	 * @return string
	 */
	private static function createMethodGet(BaseInfoParametherVO $ParametroVO){
		//cria variavel de retorno
		$temp_gets = "
		/**
		  * php([coment_param])
		  * @param php([params])
		  * @return php([return])
		  */
		";
			//iniciando metodo
			$temp_esqueleto_metodo_get = "public function ".self::getMethodNameToGet($ParametroVO)."(php([content_get_param])){
			php([content_get])
		}";
			//cria o conteudo para replaces
			$temp_get_params = "";
			$temp_get_coment_param = $ParametroVO->getDescription();
			$temp_get_return = "";
			$temp_get_content_get_param = "";
			$temp_get_content_get = "";
			
			$temp_variable_name = $ParametroVO->getVariableName();
			if(!$ParametroVO->getIsLink()){
				//se for paramether
					//cria essa variavel para passar locale null caso nao seja permitido
					$temp_get_pass_locale = "NULL";
					//se tiver locale
					if($ParametroVO->getAcceptLocale()){
						//se aceita locale, altera as variaveis de vazio pra o valor
						$temp_get_params = "string \$locale default = NULL";
						$temp_get_content_get_param = "\$locale = NULL";
						$temp_get_pass_locale = "\$locale";
					}
					$temp_paramether_type = self::getParametherType($ParametroVO);
					//comentario de retorno
					//já que é Paramether da pra saber o tipo de retorno $temp_paramether_type
					//se for só 1, é o tipo do parametro
					if($ParametroVO->getQuantity() == 1){
						$temp_get_return = $temp_paramether_type;
						//agora o conteúdo do metodo quando é paramether e quantidade 1
						
						$temp_get_content_get = "
			if(\$this->$temp_variable_name === NULL){
				\$returnParamethers = \$this->getParamethersByValues(\"$temp_variable_name\", \"$temp_paramether_type\", NULL, $temp_get_pass_locale);
				\$totalResults = count(\$returnParamethers);
				//result é um array
				//se nao tem nada para mostrar, nao mostra, e retorna null
				if(!\$totalResults > 0){
					//infelizmente, o NULL não está dando cache, ver no futuro se isso é um problema
					return NULL;
				}
				//se pode apenas 1, retorna o primeiro registro, e o valor dele
				\$ParametherVO = \$returnParamethers[0];
				
				\$retorno = \$ParametherVO->getValue();;
				//tratamento de cada tipo de retorno
				";
						//se for booelan retorna true ou false
						//se for date, retorna a data no formato BR
						//DataHandler precisava de um upgrade no conversor de data, formatando data to locale
						//exemplo formatDataToLocale($string_date, $locale = "pt-br"), podendo ter um locale de banco
						if($ParametroVO->getVariableType() == BaseInfoParametherVO::TYPE_BOOLEAN){
						$temp_get_content_get .= "
				\$this->$temp_variable_name = (\$retorno == true);//tratamento para cada tipo de caso";
						} else {
							//precisa tratar mais
							$temp_get_content_get .= "
				\$this->$temp_variable_name = \$retorno;//precisava tratar em alguns casos, por exemplo, data, verificar";
						}
						$temp_get_content_get .= "
			}";
			$temp_get_content_get .= "
			return \$this->$temp_variable_name;";
					} else {
						//se é mais de um é array de parametherVO
						$temp_get_return = "array ParametherVO";
						//agora o conteúdo do metodo quando é paramether e quantidade N
						$temp_get_content_get = "
			if(\$this->$temp_variable_name === NULL){
				\$returnParamethers = \$this->getParamethersByValues(\"$temp_variable_name\", \"$temp_paramether_type\", NULL, $temp_get_pass_locale);
				\$totalResults = count(\$returnParamethers);
				//result é um array
				//se nao tem nada para mostrar, nao mostra, e retorna null
				if(!\$totalResults > 0){
					//infelizmente, o NULL não está dando cache, ver no futuro se isso é um problema
					return NULL;
				}
				\$this->$temp_variable_name = \$returnParamethers;
			}
			return \$this->$temp_variable_name;";
					}
					
					
			} else {
				//se for link
				//link nao tem locale
				//comentario de retorno
					//já que é Paramether da pra saber o tipo de retorno $temp_paramether_type
					//se for só 1, é o tipo do parametro
					if($ParametroVO->getQuantity() == 1){
						//verifica se o cara quer que retorne a entidade
						if($ParametroVO->getReturnEntity()){
							//vai retornar 1 VO
							$temp_get_return = "object VO da entidade";
						} else {
							$temp_get_return = "int id da entidade no banco";
						}
					} else {
						//se é mais de um é array, mas array de que?
						if($ParametroVO->getReturnEntity()){
							$temp_get_return = "array object VO da entidade";
						} else {
							//quando é array e não é a entidade, retorna array de LinkVO
							$temp_get_return = "array LinkVO";
						}
					}
			}
			//agora faz os replaces
			$temp_gets = str_replace("php([params])", 				$temp_get_params, $temp_gets);
			$temp_gets = str_replace("php([coment_param])", 		$temp_get_coment_param, $temp_gets);
			$temp_gets = str_replace("php([return])", 				$temp_get_return, $temp_gets);
			$temp_esqueleto_metodo_get = str_replace("php([content_get_param])", 	$temp_get_content_get_param, $temp_esqueleto_metodo_get);
			$temp_esqueleto_metodo_get = str_replace("php([content_get])", 			$temp_get_content_get, $temp_esqueleto_metodo_get);
			
			//e adiciona na string de result
			$temp_gets .= $temp_esqueleto_metodo_get;
			return "$temp_gets";
	}
	/**
	 * Retorna o metodo de remove conforme o tipo de Parametro
	 * @param BaseInfoParametherVO $ParametroVO
	 * @return string
	 */
	private static function createRemove(BaseInfoParametherVO $ParametroVO){
		if($ParametroVO->getQuantity() == 1){
			return "";
		}
		$removeString = "";
		//verifica se é link ou paramether
		if($ParametroVO->getIsLink()){
			//é link, faz o metodo baseado na link
		$removeString .= "
		/**
		 * ".$ParametroVO->getDescription()."
		 * @param int \$id
		 * @return boolean
		 */
		public function remove".DataHandler::urlFolderNameToClassName($ParametroVO->getVariableName())."(\$id){
			return \$this->removeLink('".$ParametroVO->getAliasTableName()."', \$id);
		}
	";
		} else {
			//para remover quando é parametro. Como é mais de 1, não tem como enviar só o valor
			$removeString .= "
		/**
		 * ".$ParametroVO->getDescription()."
		 * @param ParametherVO \$ParametherVO
		 * @return ReturnResultVO
		 */
		public function removeParameter(ParametherVO \$ParametherVO){
			//verifica se o ParametherVO realmente é dessa classe
			if(\$ParametherVO->getTableId() == \$this->id && \$ParametherVO->getTable() == \$this->__table){
				//como remover um id específico, para os casos de usar link com mais de 1, ou seja array
				\$ParametherVO->delete();
				return \$ParametherVO->commit();
			}
			\$ReturnResultVO = new ReturnResultVO();
			\$ReturnResultVO->addMessage(\"Esse parametro não pertence a entidade:\".print_r(\$this, true));
			return \$ReturnResultVO;
		}
";
		}
		return "$removeString";
	}
	/**
	 * cria e retorna a string do script de validação para ser usada dentro do metodo validate
	 * @param BaseInfoParametherVO $ParametroVO
	 * @return string
	 */
	private static function createScriptValidation(BaseInfoParametherVO $ParametroVO){
		$temp_str_validation = "";
		$temp_variable_name = "\$this->{$ParametroVO->getVariableName()}";
		switch($ParametroVO->getVariableType()){
			case BaseInfoParametherVO::TYPE_INT:
				// != null
				$temp_str_validation = "
				if($temp_variable_name !== NULL){
					\$ReturnResultVO->success = FALSE;
					\$ReturnResultVO->addMessage('{$ParametroVO->getVariableName()} é um campo obrigatório');
				}
				";
				break;
			case BaseInfoParametherVO::TYPE_NUMBER:
				//verifica se não é exatamente null
				$temp_str_validation = "
				if($temp_variable_name !== NULL){
					\$ReturnResultVO->success = FALSE;
					\$ReturnResultVO->addMessage('{$ParametroVO->getVariableName()} é um campo obrigatório');
				}
				";
				break;
			case BaseInfoParametherVO::TYPE_DATE:
				//Vadida com data
				$temp_str_validation = "
				if(!Validation::validateDate($temp_variable_name)){
					\$ReturnResultVO->success = FALSE;
					\$ReturnResultVO->addMessage('{$ParametroVO->getVariableName()} é um campo obrigatório e deve ser data');
				}
				";
				break;
			case BaseInfoParametherVO::TYPE_LINK:
				// > 0
				$temp_str_validation = "
				if($temp_variable_name > 0){
					\$ReturnResultVO->success = FALSE;
					\$ReturnResultVO->addMessage('{$ParametroVO->getVariableName()} é um campo obrigatório');
				}
				";
				break;
			case BaseInfoParametherVO::TYPE_BOOLEAN:
				//!= null
				$temp_str_validation = "
				if($temp_variable_name !== NULL){
					\$ReturnResultVO->success = FALSE;
					\$ReturnResultVO->addMessage('{$ParametroVO->getVariableName()} é um campo obrigatório');
				}
				";
				break;
			case BaseInfoParametherVO::TYPE_TEXT:
				//strlen > 0
				$temp_str_validation = "
				if(!strlen($temp_variable_name) > 0){
					\$ReturnResultVO->success = FALSE;
					\$ReturnResultVO->addMessage('{$ParametroVO->getVariableName()} é um campo obrigatório');
				}
				";
				break;
			case BaseInfoParametherVO::TYPE_VARCHAR:
				//str len > 0
				$temp_str_validation = "
				if(!strlen($temp_variable_name) > 0){
					\$ReturnResultVO->success = FALSE;
					\$ReturnResultVO->addMessage('{$ParametroVO->getVariableName()} é um campo obrigatório');
				}
				";
				break;
		}
		return "$temp_str_validation";
	}
	/**
	 * Retorna a string do tipo de parametro a ser gravado, ex: Boolean é int
	 * @param BaseInfoParametherVO $ParametroVO
	 * @return string
	 */
	private static function getParametherType(BaseInfoParametherVO $ParametroVO){
		//para passar o tipo de parametro a ser gravado
		$temp_paramether_type = "varchar";
		switch($ParametroVO->getVariableType()){
			case BaseInfoParametherVO::TYPE_INT:
				$temp_paramether_type = "int";
				break;
			case BaseInfoParametherVO::TYPE_NUMBER:
				$temp_paramether_type = "number";
				break;
			case BaseInfoParametherVO::TYPE_DATE:
				$temp_paramether_type = "date";
				break;
			case BaseInfoParametherVO::TYPE_LINK:
				$temp_paramether_type = "nao tem tipo de parametro para link";
				break;
			case BaseInfoParametherVO::TYPE_BOOLEAN:
				$temp_paramether_type = "int";
				break;
			case BaseInfoParametherVO::TYPE_TEXT:
				$temp_paramether_type = "text";
			case BaseInfoParametherVO::TYPE_VARCHAR:
				$temp_paramether_type = "varchar";
			default:
				break;
		}
		return "$temp_paramether_type";
	}
}