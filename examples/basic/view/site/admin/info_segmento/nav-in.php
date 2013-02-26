<?php
//$array_folders = Navigation::getURI(Config::$URL_ROOT_APPLICATION);
$array_folders = Navigation::getURI(Config::getAliasFolder(), Navigation::URI_RETURN_TYPE_ARRAY);
//Debug::print_r($array_folders);
?>
<div class="sidebar">
	<div id="accordion">
        <h3 class="open"><!--Seja bem-vindo--></h3>
        <div class="content" style="display: block;">
        	<ul class="leftmenu">
            	<li <?php if(count($array_folders) > 2 && $array_folders[1]=='info_segmento' && $array_folders[2]=='show'){echo(' class="current"');}?>><a href="<?php echo Config::getRootPath('backend/info_segmento/show/'); ?>" class="table">Listagem Segmentos</a></li>
                <li <?php if(count($array_folders) > 2 && $array_folders[1]=='info_segmento' && $array_folders[2]=='add'){echo(' class="current"');}?>><a href="<?php echo Config::getRootPath('backend/info_segmento/add/'); ?>" class="form">Inserir Info Segmento</a></li>
                <li <?php if(count($array_folders) > 2 && $array_folders[1]=='info_estrutura' && $array_folders[2]=='show'){echo(' class="current"');}?>><a href="<?php echo Config::getRootPath('backend/info_estrutura/show/'); ?>" class="table">Listagem Estrutura</a></li>
                <li <?php if(count($array_folders) > 2 && $array_folders[1]=='info_estrutura' && $array_folders[2]=='add'){echo(' class="current"');}?>><a href="<?php echo Config::getRootPath('backend/info_estrutura/add/'); ?>" class="form">Inserir Info Estrutura</a></li>
                	<li <?php if(count($array_folders) > 2 && $array_folders[1]=='info' && $array_folders[2]=='show'){echo(' class="current"');}?>><a href="<?php echo Config::getRootPath('backend/info/show/'); ?>" class="table">Outras Infos</a></li>
            </ul>
        </div>
        <h3 class="open"><!--Custom Text--></h3>
        <div class="content" style="display: block;"><!--Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.--></div>
	</div>
	
</div><!-- leftmenu -->
