<?php

include_once "library/site/controller/admin/default/Content.php" ;
include_once "library/facil3/core/modules/category/dao/CategoryDAO.class.php" ;
include_once "library/site/modules/content/vo/ContentSiteVO.class.php" ;

/**
 * @author 			: Renato Miawaki
 * @desc			: para admin category_id 12
 * @date			: 24/01/2011
 */
class Banner extends Content {

    private $my_action = "admin/home/banner/commit" ;
    //private $my_redirect = "admin/home/banner/select" ;

    /**
     * @param $arrayRest
     * @return void
     */
    public function __construct( $arrayRest = NULL ) {
        parent::__construct( $arrayRest ) ;
        $this->category_id = 46 ;
        $this->my_redirect  = "admin/home/banner/select";
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

            $CategoryVO->setId( 46 , TRUE ) ;

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
        $HttpContentResult->url_insert = Config::getRootPath( "admin/home/banner/insert/" ) ;
        //include (Config::getFolderView( 'admin/home/index.php' )) ;
        //exit() ;
        //print_r($returnResult);exit();
        return $returnResult ;
    }

    /**
     * @return para poder inserir
     */
    public function insert() {
//		echo "cocococo";exit();
        return $this->createFormData() ;
    }

    public function edit( $ReturnResultVO = NULL ) {
        return $this->createFormData( $ReturnResultVO ) ;
    }

    public function delete() {
        parent::delete() ;
        Navigation::redirect( "admin/home/banner" ) ;
        exit() ;
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
        $this->my_redirect = "admin/home/banner/" ;

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

    /**
     * Funcao chamado via ajax para salvar as configurações do novo Banner
     * @author: Gilmar Soares <professorgilmagro@gmail.com>
     * @since 30/08/2011
     * @access public
     */
    public function save() {
        if ( isset( $_POST["auto"] ) ) {
            extract( $_POST ) ;

            $data = array(
                "config" => array(
                    "auto" => ( bool ) $auto ,
                    "delay" => $delay ,
                    "speed" => $speed ,
                    "timer_align" => $timer_align ,
                    "transition" => $transition ,
                    "easing" => $easing ,
                    "textboxEffect" => $textboxEffect ,
                    "tooltip" => $tooltip ,
                    "cpanel_align" => $cpanel_align ,
                    "type" => $type ,
                    "timer_align" => $timer_align ,
                ) ,
                "display" => array(
                    "buttons" => ( bool ) $buttons ,
                    "numbers" => ( bool ) $numbers ,
                    "thumbs" => ( bool ) $thumbs ,
                    "play" => ( bool ) $play ,
                    "back_forward" => ( bool ) $back_forward ,
                    "timer_bar" => ( bool ) $timer_bar
                ) ,
                "mouseover" => array(
                    "pause" => ( bool ) $pause ,
                    "control_panel" => ( bool ) $control_panel ,
                    "text_panel" => ( bool ) $text_panel ,
                    "text_effect" => ( bool ) $text_effect
                )
            ) ;
        }

        $data = json_encode( $data ) ; //Dados para salvar no banco
        // FALTA CHAMAR O METODO DA MODEL PARA SALVAR OS DADOS NO BANCO


        $success = true ;
        $msg = "Salvo com sucesso!" ;
        $response = NULL ;

        $info = array("success" => $success , "message" => $msg , "response" => $response) ;

        print json_encode( $info ) ;

        exit() ;
    }

}