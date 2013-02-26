<?php
include_once Config::FOLDER_APPLICATION."controller/admin/default/Content.php";
include_once "library/facil3/core/modules/category/dao/CategoryDAO.class.php";
include_once Config::FOLDER_APPLICATION."modules/content/vo/ContentSiteVO.class.php";
/**
 * @author 			: Renato Miawaki
 * @desc			: para admin category_id 12
 * @date			: 24/01/2011
 */



class Home extends Content {
	private $my_action 		= "admin/home/commit";
	//private $my_redirect 	= "admin/home";
	/**
	 * @param $arrayRest
	 * @return void
	 */
	 public function __construct( $arrayRest = NULL ) {
        parent::__construct( $arrayRest ) ;
        $this->category_id = 47;
        $this->my_redirect  = "admin/home"; 
    }

    public function init() {
        //se for postado algo ta tentando atualizar... atualiza em silencio
        if ( $_POST ) {
            include_once "library/site/modules/content/dao/ContentDemoDAO.class.php" ;
            include_once "library/facil3/core/modules/category/dao/CategoryDAO.class.php" ;
            include_once "library/site/modules/banner/vo/BannerConfigVO.class.php" ;
            $ContentDemoDAO = ContentSiteDAO::getInstance() ;
            $CategoryDAO = CategoryDAO::getInstance() ;

            $CategoryVO = new BannerConfigVO() ;

            $CategoryVO->setId( 47 , TRUE ) ;

            /*
             * Array(
              [transitionType] => 1
              [transitionDelayTimeFixed] => 2
              )
             */
            //exit();
            $CategoryVO->commit() ;
            //exit();
        }
        $returnResult = parent::init() ;
        $novo_content = array() ;
        foreach ( $this->arrayContentsVO as $ContentsVO ) {
            if ( $ContentsVO->active < 2 ) {
                $novo_content[] = $ContentsVO ;
            }
        }
        $this->arrayContentsVO = $novo_content ;
        //$ContentVO->toStdClass(Config::getLocale());
        $SelectData = new ContentSelectView( $this->arrayContentsVO ) ;
        $SelectData->setGoToLocation( array("value" => "admin/home/") ) ;
		
        $SelectData->gerate() ;
        $SelectData->hat["label"] = "Url:" ;


        $SelectData->setMassiveAttr( 'visible' , FALSE , array(
//																'Title',
            'Name' ,
//																'Hat',
            'Description' ,
            'Content' ,
            'Author' ,
            'TemplateUrl' ,
            'Slug' ,
            'KeyWords' ,
            'Date' ,
            'DateIn' ,
            'DateOut' ,
            'Order'
        ) ) ;

        $SelectData = $SelectData->getFormData() ;

        $HttpContentResult = $returnResult->getHttpContentResult() ;

        $HttpContentResult->selectData = $SelectData ;
		$HttpContentResult->category_id = $this->category_id;
        $returnResult->setHttpContentResult( $HttpContentResult ) ;
        $HttpResult = $returnResult ;
        //para o botao de inserir
        $HttpContentResult->url_insert = Config::getRootPath( "admin/home/insert/" ) ;
        //include (Config::getFolderView( 'admin/home/index.php' )) ;
        //exit() ;
        //print_r($returnResult);exit();
        return $returnResult ;
    }

	/**
	 * @return para poder inserir
	 */
	public function insert(){
//		echo "cocococo";exit();
		if(UserClient::getTypeId() == Config::DEVELOPER_USER_TYPE_ID){
			return $this->createFormData();
		} else {
			Navigation::redirect("admin/");
		}
		
	}
	public function edit( $ReturnResultVO = NULL ) {
        return $this->createFormData( $ReturnResultVO ) ;
    }

	public function delete(){
		parent::delete();
		Navigation::redirect("admin/page");
		exit();
	}		
	private function createFormData( $ReturnResultVO = NULL ) {
        //adiciona o content na url de envio do formulario
        if ( $this->content_id > 0 ) {
            $this->my_action .= "/id.$this->content_id/" ;
        }
		//Debug::print_r($this->ContentSiteVO->getImages());exit();
        $formData = new ContentFormView( $this->ContentSiteVO, Config::getRootPath( $this->my_action ) ) ;

        $ImageFormView = new ImageFormView() ;
        $ImageFormView->setFormLabel( "Selecionar Imagem" ) ;
        $ImageFormView->setQuantity( 1 ) ;

//		$ImageFormView->setDescription(array('label'=>Translation::text('Link'), 'visible'=>false, 'type'=>'simpleText'));

        $FileFormView = new FileFormView() ;
        $FileFormView->setFormLabel( "Selecionar Arquivo" ) ;
        $FileFormView->setQuantity( 0 ) ;

//		$formData->setPersonalInput(array("name"=>"destaque[]","label"=>"Mostrar na Home?", "options"=>array((object) array("name"=>"Palestrantes", "id"=>"33"), (object) array("name"=>"Outros Palestrantes", "id"=>"34"))));
        //trocando o rótulo para Content
//		$formData->setContent(array("label"=>"Descrição:"));
        //trocando o rótulo para Title
//		$formData->setTitle(array("label"=>"Titulo:"));
        $formData->setCategory( array("visible" => FALSE , "name" => "category[]" , "selected" => array($this->category_id)) ) ;

        $formData->setImage( $ImageFormView ) ;
        $formData->setFile( $FileFormView ) ;
        $formData->setHat( array("label" => "Url:") ) ;
        $formData->setMassiveAttr( 'visible' , FALSE , array(
            'Name' ,
//														'Title',
//														'Hat',
            'Description' ,
            'Content' ,
            'Author' ,
            'TemplateUrl' ,
            'Slug' ,
            'KeyWords' ,
            'Date' ,
            'DateIn' ,
            'DateOut' ,
            'Order'
        ) ) ;

        $formData->setActive( array("value" => "1" , "visible" => FALSE) ) ;
        $this->my_redirect = "admin/home/" ;

        parent::edit( $formData , TRUE , NULL , $this->my_redirect , NULL , $ReturnResultVO ) ;
    }


	
    public function commit() {
//				echo "cococ";exit();
        $this->redirect_with_id = FALSE ;
        if ( DataHandler::getValueByArrayIndex( $_POST , "destaque" ) ) {
            if ( is_array( $_POST["category"] ) ) {
                $_POST["category"] = DataHandler::appendArray( $_POST["category"] , $_POST["destaque"] ) ;
            }
            else {
                throw new Exception( "envie a array de categoria. Sem isso a content nao funciona." ) ;
            }
        }
        parent::commit() ;
    }
	
	
}