<?php
$array_folders = Navigation::getURI(Config::$URL_ROOT_APPLICATION);
?>
<div class="sidebar">
	<div id="accordion">
        <h3 class="open"><?php echo $HttpContentResult->vo->title; ?></h3>
        <div class="content" style="display: block;">
        	<ul class="leftmenu">
            	<li <?php if(count($array_folders) > 2 && $array_folders[2]=='change'){echo(' class="current"');}?>><a href="<?php echo Config::getRootPath('backend/product/change/id.'.$HttpContentResult->vo->id); ?>" class="table">Infos</a></li>
                <li <?php if(count($array_folders) > 2 && $array_folders[2]=='link'){echo(' class="current"');}?>><a href="<?php echo Config::getRootPath('backend/product/link/id.'.$HttpContentResult->vo->id); ?>" class="form">Categorias</a></li>
                <li <?php if(count($array_folders) > 2 && $array_folders[3]=='tagged'){echo(' class="current"');}?>><a href="<?php echo Config::getRootPath('backend/product/gallery/type.tagged/id.'.$HttpContentResult->vo->id); ?>" class="form">Destaque</a></li>
                <li <?php if(count($array_folders) > 2 && $array_folders[3]=='gallery'){echo(' class="current"');}?>><a href="<?php echo Config::getRootPath('backend/product/gallery/type.gallery/id.'.$HttpContentResult->vo->id); ?>" class="form">Galeria</a></li>
                <li <?php if(count($array_folders) > 2 && $array_folders[3]=='dimensions'){echo(' class="current"');}?>><a href="<?php echo Config::getRootPath('backend/product/gallery/type.dimensions/id.'.$HttpContentResult->vo->id); ?>" class="form">Dimensões</a></li>
                <li <?php if(count($array_folders) > 2 && $array_folders[3]=='video'){echo(' class="current"');}?>><a href="<?php echo Config::getRootPath('backend/product/gallery/type.video/id.'.$HttpContentResult->vo->id); ?>" class="form">Video</a></li>
                <li <?php if(count($array_folders) > 2 && $array_folders[3]=='360'){echo(' class="current"');}?>><a href="<?php echo Config::getRootPath('backend/product/gallery/type.360/id.'.$HttpContentResult->vo->id); ?>" class="form">360&deg;</a></li>
                <li <?php if(count($array_folders) > 2 && $array_folders[2]=='file'){echo(' class="current"');}?>><a href="<?php echo Config::getRootPath('backend/product/file/id.'.$HttpContentResult->vo->id); ?>" class="form">Arquivos</a></li>
            </ul>
        </div>
        <h3 class="open"><!--Custom Text--></h3>
        <div class="content" style="display: block;"><!--Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.--></div>
	</div>
	
</div><!-- leftmenu -->
