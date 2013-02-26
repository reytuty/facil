<?php
include_once "library/facil3/core/controller/interface/AbstractController.class.php" ;
include_once "library/facil3/utils/Navigation.class.php";
include_once "library/facil3/core/modules/content/dao/ContentDAO.class.php";
include_once "library/facil3/core/controller/content/vo/ContentInfoPostVO.class.php";
include_once "library/facil3/core/dao/LinkDAO.class.php";

include_once "library/forum/controller/admin/default/GenericAdminController.class.php";


//para retorno
include_once "library/facil3/core/vo/ReturnResultVO.class.php";

/**
 * @author 		: Mauricio Amorim
 * @date		: 05/12/2010
 * @desc		: controller a ser extendida ou usada por outra controller para manipular a content
 */
/**
 * @author 		: Renato Miawaki
 * @date		: 13/01/2011 - 17/01/2001
 * @desc		: 
 */
class FacilContent extends GenericAdminController implements AbstractController{
	/**
	 * variaveis de configuração, modifique-as antes de chamar o metodo para ter resultados diferentes
	 */
	
	public $ContentInfoPostVO;
	
	/**
	 * @var array de plugins que siga a interface FacilPlugin
	 */
	private $array_plugins = array();
	public function addPlugin(FacilPlugin $PluginItem){
		$this->array_plugins[] = $PluginItem;
	}
	public function getPlugins(){
		return $this->array_plugins;
	}
	
	protected $arrayVariable;
	protected $arrayRestFolder;
	
	/**
	 * na construct da facil content pode-se configurar como essa content vai se comportar
	 * nisso também pode-se iniciar os plugins que serão aceitos e que respeitem o padrão FacilPlugin
	 * @param $arrayRestFolder
	 * @return unknown_type
	 */
	public function __construct($arrayRestFolder = NULL){
		if($arrayRestFolder != NULL){
			$this->arrayVariable   = Navigation::getVariableArraySlug($arrayRestFolder);
            $this->arrayRestFolder = $arrayRestFolder;
        }
        $this->moduleName					= "content";
        
		//$this->ContentInfoPostVO = new ContentInfoPostVO($_POST, $_FILE);
	}
	/** 
	 * Envie o ContentInfoPost
	 */
	public function resetInfoPost($ContentInfoPostVO){
		$this->ContentInfoPostVO = $ContentInfoPostVO;
	}
    /**
     * @return ReturnResultVO
     */
    public function init(){
    	//por padrÃ£o nÃ£o faz nada
    	return new ReturnResultVO(FALSE, array("nenhuma ação padrão"));
    }
    
 	/**
 	 * busca todos os contents conforme requisições na ContentInfoPostVO
	 * @return ReturnResultVO
	 */
	public function select(){
    	$table		 		= DataHandler::forceString($this->ContentInfoPostVO->request_table);
    	$table_id			= DataHandler::forceInt($this->ContentInfoPostVO->request_table_id);
		$quant_start		= DataHandler::forceInt($this->ContentInfoPostVO->request_quant_start);
		$quant_limit		= DataHandler::forceInt($this->ContentInfoPostVO->request_quant_limit);
		$type_of_return		= DataHandler::forceString($this->ContentInfoPostVO->request_type_of_return);
		$locale				= DataHandler::forceString($this->ContentInfoPostVO->request_locale);
		
		// se nao foi passado table e table id naum da pra procurar
    	if($table == NULL && $table_id < 1){
    		$ReturnResultVO->success = FALSE;
			$ReturnResultVO->result = $this->array_content;
			$ReturnResultVO->array_messages[] = Translation::text(LibraryLanguage::ERROR_CONTENT_NOT_FOUND);
			return $ReturnResultVO;
		}
		//inicia a LinkDAO para fazer a consulta
    	$DAO = ContentDAO::getInstance();		
	
		//busca contents vinculados a tabela e tabela id passados
		$ReturnDataVO = $DAO->selectByTableId($table, $table_id, $type_of_return, "content", $quant_limit, $quant_start);
		if($ReturnDataVO->success){
			//Debug::print_r($ReturnDataVO);exit();
			$ReturnResultVO->success = $ReturnDataVO->success;
			$ReturnResultVO->result = $ReturnDataVO->result;
			return $ReturnResultVO;
		}else{
			$ReturnResultVO->success = $ReturnDataVO->success;
			$ReturnResultVO->result = $this->array_content;
			$ReturnResultVO->array_messages[] = Translation::text("LibraryLanguage::ERROR_CONTENT_NOT_FOUND");
			return $ReturnResultVO;
		}
	}
    
    /**
     * serve para buscar um unico item especifico, baseado no id
     * @return ReturnResultVO
     */
    public function get(){
    	//inicia um retorno de ReturnResultVO
    	$ReturnResultVO = new ReturnResultVO();
    	$table		 	= DataHandler::forceString($this->ContentInfoPostVO->request_table);
    	$table_id	 	= DataHandler::forceInt($this->ContentInfoPostVO->request_table_id);
    	$content_id 	= DataHandler::forceInt($this->ContentInfoPostVO->request_content_id);
    	$slug		 	= DataHandler::forceString($this->ContentInfoPostVO->request_slug);
    	$locale		 	= DataHandler::forceString($this->ContentInfoPostVO->request_locale);
		$type_of_return	= DataHandler::forceString($this->ContentInfoPostVO->request_type_of_return);
    	
		//verifica se foram passados os parametros necessarios para o slect
    	if($table_id > 0 && $slug != NULL){
    		$DAO = ContentDAO::getInstance();
    		//se foi passado locale o retorno vai ser em stClassVO
    		//se nao o retorno vem em VO para depois buscar os locales
   			$ReturnDataVO = $DAO->selectBySlugAndTableId($slug, $table, $table_id, $locale, $type_of_return);
    		//print_r($ReturnDataVO);
    		//se deu erro retorna
			if(!$ReturnDataVO->success || count($ReturnDataVO->result) <= 0){
				//deu erro ao vincular
				$ReturnResultVO->success = false;
				$ReturnResultVO->array_messages[] = Translation::text("LibraryLanguage::ERROR_CONTENT_NOT_FOUND");
				return $ReturnResultVO;
			}
			
			//se foi passado o locale vei pegar os campos com locale
			if($locale != NULL){
				//faz o get com locale
				$tempResult = array();
				foreach($ReturnDataVO->result as $vo){
					$tempResult = $vo->toStdClass($locale);
				}
				$ReturnResultVO->success = $ReturnDataVO->success;
				$ReturnResultVO->result = $ReturnDataVO->result;
				return $ReturnResultVO;
			}
			
			//se estiver tudo certo prepara o retorno no objeto ReturnResultVO
			$ReturnResultVO->success = $ReturnDataVO->success;
			$ReturnResultVO->result = $ReturnDataVO->result;
			return $ReturnResultVO;			
    	}else{
    		//Debug::print_r($this->ContentInfoPostVO);
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->result = array();
			$ReturnResultVO->array_messages[] = Translation::text("LibraryLanguage::ERROR_CONTENT_NOT_FOUND");
			return $ReturnResultVO;    		
    	}
    }
  
     /*
     * recebe o content por post e insere, observação para q deve ser passado table e table_id para que 
     * o conteudo seja vinculado
     * @return ReturnResultVO
     */
    public function insert(){
    	//inicia um retorno de ReturnResultVO
    	$ReturnResultVO = new ReturnResultVO();
    	
    	//pega os dados baseado na ContentInfoPostVO
	    $VO = new ContentVO();
		//popula no objeto
		if($this->ContentInfoPostVO->id){
			$VO->setId($this->ContentInfoPostVO->id, TRUE);
		} 
		$VO->setActive($this->ContentInfoPostVO->active); 
		$VO->setName($this->ContentInfoPostVO->name);
		$VO->setTitle($this->ContentInfoPostVO->title, $this->ContentInfoPostVO->request_locale);
		$VO->setHat($this->ContentInfoPostVO->hat, $this->ContentInfoPostVO->request_locale);
		$VO->setDescription($this->ContentInfoPostVO->description, $this->ContentInfoPostVO->request_locale);
		$VO->setContent($this->ContentInfoPostVO->content, $this->ContentInfoPostVO->request_locale);
		$VO->setAuthor($this->ContentInfoPostVO->author);
		$VO->setTemplateUrl($this->ContentInfoPostVO->template_url, $this->ContentInfoPostVO->request_locale);
		$VO->setSlug($this->ContentInfoPostVO->slug, $this->ContentInfoPostVO->request_locale);
		$VO->setKeyWords($this->ContentInfoPostVO->key_words, $this->ContentInfoPostVO->request_locale);
		$VO->setDate($this->ContentInfoPostVO->date);
		$VO->setDateIn($this->ContentInfoPostVO->date_in);
		$VO->setDateOut($this->ContentInfoPostVO->date_out);
		$VO->setOrder($this->ContentInfoPostVO->order);
		include "";
		
		//("Ja") gera id para criar pasta onde vai ser guardado o arquivo
		$ReturnResultContentVO = $VO->commit();
		
		if($ReturnResultContentVO->success){
			$ReturnResultContentVO->result = $VO->getId();
			
			
			// TODO: AQ ADD IMGAGE  e/ou FILE - $arr_uploaded_files
			
			// $this->ContentInfoPostVO->arr_uploaded_files['image'][0]->table 		= 'content';
			// $this->ContentInfoPostVO->arr_uploaded_files['image'][0]->table_id 	= $ReturnResultContentVO->result ;
			
			// $facilFile = new FacilImage( $this->ContentInfoPostVO->arr_uploaded_files['image'][0])
			
			// $facilFile = new FacilFile( $this->ContentInfoPostVO->arr_uploaded_files['file'][0])
			
		}
		
		
		if($ReturnResultContentVO->success){
			//incluir o vinculo com a linked_table e linked_table_id
			//receber 	table
			//			table_id
			if($this->ContentInfoPostVO->request_table != NULL && $this->ContentInfoPostVO->request_table_id > 0){
				$table 		= 	$this->ContentInfoPostVO->request_table;
				$table_id	= 	$this->ContentInfoPostVO->request_table_id;
				
				include_once "library/facil3/core/dao/LinkDAO.class.php";
				
				$LinkDAO = new LinkDAO();
				//vincula a foto ao table e table_id enviado
				$ReturnResultVinculoVO = $LinkDAO->insert($table, $table_id, $this->moduleName, $VO->getId(), 1);
				if(!$ReturnResultVinculoVO->success){
					//deu erro ao vincular
					$ReturnResultContentVO->appendMessage($ReturnResultVinculoVO->array_messages);
				}
			}
		}
		return $ReturnResultContentVO;
    }
}
