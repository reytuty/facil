<?php

class HtmlHeader{
    
    private $js = array();
    private $css = array();
    
    private $description;
    private $keywords;
    private $title;
    
    public function addJS($js, $assetFilePath = NULL){
        if(is_array($js)){
            foreach ($js as $j)
                $this->js[$j] = $j;
        }else{
            $this->js[$js] = $assetFilePath;
        }
    }
    
    public function addCSS($css,  $assetFilePath = NULL, $media = 'all', $ieOnly = FALSE){
        if(is_array($css)){
            foreach ($css as $c)
                $this->css[$c] = (object)array('media'=>$media, 'path'=>$c, 'ieOnly'=>$ieOnly);
        }else{
            $this->css[$css] = (object)array('media'=>$media, 'path'=>$assetFilePath, 'ieOnly'=>$ieOnly);
        }            
    }
    
	public function set_title($title){
		$this->title = $title ;
	}
	
    public function __construct($data){
        $this->description  = $data->getDescription();
        $this->keywords  = $data->getKeywords();
        $this->title  = $data->getTitle();
    }
	

    public function show($_return = FALSE){
        $url_config = Config::getRootPath("config");
        $head = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n" ;
        $head.= '<html xmlns="http://www.w3.org/1999/xhtml">'. "\n";
        $head.= '<head>' . "\n";
        $head.= '<meta http-equiv="X-UA-Compatible" content="IE=edge" >' . "\n";
        $head.= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'. "\n";
        $head.= '<meta name="description" content="' . $this->description . '" />' . "\n";
        $head.= '<meta name="keywords" content="' . $this->keywords . '" />' . "\n";
        $head.= '<title>' . $this->title . '</title>' . "\n";
        $head.= '<link rel="shortcut icon" type="image/x-icon" href="' . Config::getImagePath("favicon.ico") .'" />';
        $head.= "\n<script type=\"text/javascript\" >";
	        $head.= "var ROOT_PATH = '".Config::getRootPath()."';";
			$head.= "var NEXT_URL = '".Config::getNextUrl()."';";
			$head.= "var LAST_URL = '".Config::getLastUrl()."';";
			$head.= "var LOCALE = '".Config::getLocale()."';";
        $head.= "</script>\n";
        $head.= '<link rel="apple-touch-icon" href="' . Config::getImagePath("icon-ipad.jpg") . '" />';
        foreach ($this->css as $slug =>$data){
            $file_path = $slug . '.css';
            $uri_path = Config::getAsset('assets/css/'. $file_path );
            $sys_path = Config::getFolderView( 'assets/css/' . $file_path);
            if(!file_exists($sys_path)){
                $file_path 	= (strpos('.css', $data->path) === FALSE ? $data->path . '.css' : $data->path);
                $uri_path 	= Config::getAsset('assets/'. $file_path );
                $sys_path 	= Config::getFolderView( 'assets/' . $file_path);
                if(!file_exists($sys_path)){
                	var_dump($slug, '---' ,  $data, $file_path);
                    echo ("<!-- arquivo CSS {$uri_path} não encontrado (tentando no file_system : {$sys_path}  )-->\n");
                    exit();
                    continue;
                } 
            }
            if($data->ieOnly)
                $head.="\n<!--[if IE]>";    
                
            $head.= "\n<link type=\"text/css\" media=\"" . $data->media . "\" rel=\"stylesheet\" href=\"" . $uri_path . "\" />";
            
            if($data->ieOnly)
                $head.="\n<![endif]-->"; 
        }
        
        foreach ($this->js as $slug =>$path){
            $file_path = $slug . '.js';
            $uri_path = Config::getAsset('assets/js/'. $file_path );
            $sys_path = Config::getFolderView( 'assets/js/' . $file_path);
            if(!file_exists($sys_path)){
                $file_path = (strpos('.js', $path) === FALSE ? $path . '.js' : $path);
                $uri_path = Config::getAsset('assets/'. $file_path );
                $sys_path = Config::getFolderView( 'assets/' . $file_path);
                if(!file_exists($sys_path)){
                    echo ("<!-- arquivo JS {$uri_path} não encontrado (tentando no file_system : {$sys_path}  )-->\n");
                    continue;
                } 
            }
            $head.= "\n<script type=\"text/javascript\" src=\"" . $uri_path . "\"></script>"; 
        }

        $head.="\n</head>";
		if($_return) return $head;
		 
        echo $head;
    }
    
}
