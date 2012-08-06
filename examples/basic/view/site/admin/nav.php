<?php
$array_folders = Navigation::getURI(Config::$URL_ROOT_APPLICATION);
?>

<div class="headerspace"></div>

<div class="header">
	<!--
    <form id="search" action="" method="post">
    	<input type="text" name="keyword" /> <button class="searchbutton"></button>
    </form>-->
    
    <div class="topheader">
    	<!--
        <ul class="notebutton">
            <li class="note">
                <a href="pages/message.html" class="messagenotify">
                    <span class="wrap">
                        <span class="thicon msgicon"></span>
                        <span class="count">1</span>
                    </span>
                </a>
            </li>
            <li class="note">
            	<a href="pages/info.html" class="alertnotify">
                	<span class="wrap">
                    	<span class="thicon infoicon"></span>
                        <span class="count">5</span>
                    </span>
                </a>
            </li>
        </ul>-->
    </div><!-- topheader -->


    <!-- logo -->
    <div style="height:54px; width:100px;">
	<a href=""></a>
    </div>
    
    <div class="tabmenu">
    	<ul>
        	<li><a href="<?php echo Config::getRootPath('backend'); ?>" class="dashboard"><span>Dashboard</span></a></li>
            <li <?php if(count($array_folders) > 1 && $array_folders[1]=='category'){echo(' class="current"');}?> > <a href="<?php echo Config::getRootPath('backend/category/show'); ?>" class="reports"><span>Categorias</span></a></li>
            <li <?php if(count($array_folders) > 1 && $array_folders[1]=='product'){echo(' class="current"');}?>><a href="<?php echo Config::getRootPath('backend/product/show'); ?>" class="elements"><span>Produtos</span></a></li>
            <li <?php if(count($array_folders) > 1 && $array_folders[1]=='client'){echo(' class="current"');}?>><a href="<?php echo Config::getRootPath('backend/client/show'); ?>" class="users"><span>Clientes</span></a></li>
            <li <?php if(count($array_folders) > 1 && $array_folders[1]=='equipe'){echo(' class="current"');}?>><a href="<?php echo Config::getRootPath('backend/equipe/show'); ?>" class="users"><span>Equipe</span></a></li>
            <li <?php if(count($array_folders) > 1 && ($array_folders[1]=='info'||$array_folders[1]=='info_segmento')){echo(' class="current"');}?>><a href="<?php echo Config::getRootPath('backend/info_segmento/show'); ?>" class="elements"><span>Infos</span></a></li>
            
        </ul>
    </div><!-- tabmenu -->
    
    <div class="accountinfo" style="width:200px;">
    	<img src="<?php echo Config::getAsset('backend/assets/images/avatar.png'); ?>" alt="Avatar" />
        <div class="info" style="float:right;">
        	<h3>Sérgio Fix</h3>
            <p>
            	<a href=""></a> <a href="<?php echo Config::getRootPath("backend/login/logout/")?>">Logout</a>
            </p>
        </div><!-- info -->
    </div><!-- accountinfo -->
</div><!-- header -->
