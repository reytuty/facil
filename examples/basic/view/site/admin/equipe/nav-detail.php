<?php
$array_folders = Navigation::getURI(Config::$URL_ROOT_APPLICATION);
?>
<div class="sidebar">
	<div id="accordion">
        <h3 class="open"><?php echo $HttpContentResult->vo->title; ?></h3>
        <div class="content" style="display: block;">
        	<ul class="leftmenu">
            	<li <?php if(count($array_folders) > 2 && $array_folders[2]=='change'){echo(' class="current"');}?>><a href="<?php echo Config::getRootPath('backend/equipe/change/id.'.$HttpContentResult->vo->id); ?>" class="table">Infos</a></li>
                <li <?php if(count($array_folders) > 2 && $array_folders[2]=='gallery'){echo(' class="current"');}?>><a href="<?php echo Config::getRootPath('backend/equipe/gallery/type.gallery/id.'.$HttpContentResult->vo->id); ?>" class="form">Galeria</a></li>
            </ul>
        </div>
        <h3 class="open"><!--Custom Text--></h3>
        <div class="content" style="display: block;"><!--Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.--></div>
	</div>
	
</div><!-- leftmenu -->
