<?php
/**
 * Essa classe serve para configurar um módulo a ser criado
 * Nela tem (e precisa ter) todas as informações necessárias para criar um módulo baseado em banco de dados
 * Futuramente pode também ser a entidade do módulo e com isso:
 * 	poder alterar o módulo
 *  excluir módulo
 * Futuramente também pode ter as configurações de alias e permissões
 *  e fazer tudo que se faz com uma entidade VO
 *  Mas essa classe não precisa ser um ModuleVO, não precisa ter getDAO e coisas do tipo
 * @author 		Renato Miawaki
 * @version 	1.0
 */
include_once 'library/facil3/core/modules/module_content_creator/interface/ModuleConfig.interface.php';
include_once 'library/facil3/core/modules/module_content_creator/interface/BaseInfoParamether.interface.php';
class ModuleConfigVO implements ModuleConfig{
	protected $arrayParamethers = array();
	protected $moduleName;
	protected $description;
	protected $entity_name;
	protected $src_folder;
	public function __construct(){
		//
	}
	//SETS e ADDs
	public function setModuleName($name){
		$this->moduleName = $name;
	}
	public function setDescription($value){
		$this->description = $value;
	}
	public function setEntityName($name){
		$this->entity_name = $name;
	}
	public function setFolderModule($src_folder){
		$this->src_folder = $src_folder;
	}
	
	/**
	 * Vai adicionar na array, um parametro (atributo) que esse módulo precisa
	 * @param BaseInfoParamether $Paramether
	 * @return bool só da false se enviar parametro com nome repetido
	 */
	public function addParamether(BaseInfoParamether $Paramether){
		$parametro_repetido = FALSE;
		foreach($this->arrayParamethers as $ParametherItem){
			if($ParametherItem->getVariableName() == $Paramether){
				$parametro_repetido = TRUE;
				throw new ErrorException("Dois parametros não podem ter o mesmo nome.", $Paramether->getOrderInClass());
				return FALSE;
			}
		}
		$this->arrayParamethers[] = $Paramether;
		return TRUE;
	}
	//GETS
	/**
	 * O nome da entidade, possivelmente o nome da tabela no banco, caso haja
	 * @return string
	 */
	function getEntityName(){
		return $this->entity_name;
	}
	/**
	 * O nome do módulo, teoricamente seria o mesmo que a entidade, mas pode ser diferente
	 * 	@return string
	 */
	function getModuleName(){
		return $this->moduleName;
	}
	
	/**
	 * array de atributos da entidade, cada objeto possui informações sobre o tipo de atributo e comentário explicativo*
	 * @return array de BaseInfoParametherVO
	 */
	function getArrayParamethers(){
		return $this->arrayParamethers;
	}
	/**
	 * (non-PHPdoc)
	 * @see ModuleConfig::getFolderModule()
	 * Local onde os arquivos serão salvos
	 */
	public function getFolderModule(){
		return $this->src_folder;
	}
	/**
	 * O modo como o módulo será criado.
	 * Inicialmente apenas o modo utilizando parametros
	 * Aqui se trata de uma convensão de nomes para ser utilizado no módulo criador de módulos
	 * 	Futuramente pode ter o modo:
	 * 				utilizando tabelas (criando todas as tabelas (N>N e 1>N) e todos os campos das tabelas)
	 * @return string	
	 */
	function getCreateMode(){
		//por enquanto só aceita esse módo, então nem precisa ter set, futuramente vai aceitar mais tipos
		return ModuleConfig::CREATE_ENTITY_TABLE_AND_USE_PARAMETHER;
	}
	/**
	 * A descrição e comentários sobre a classe
	 * @return string
	 */
	function getModuleDescription(){
		return $this->description;
	}
	function validate(){
		$ReturnResultVO = new ReturnResultVO();
		
		return $ReturnResultVO;
	}
}