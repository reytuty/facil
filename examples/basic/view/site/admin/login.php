<?php
$HttpContentResult = $HttpResult->getHttpContentResult();
//de onde veio o usuário $to
$to = (isset($HttpContentResult->to))?$HttpContentResult->to:"";
?>
<?php include(Config::getFolderView('/backend/head.php')); ?>
<body style="background:#fff;">
<div class="loginlogo">
	<img src="images/logo.png" alt="Logo" />
</div><!--loginlogo-->

<div class="notification notifyError loginNotify">Invalid username or password. (Type anything)</div>

<?php
	//Retorna o erro do login
	if($HttpContentResult->returnResult!=NULL){
		if(!($HttpContentResult->returnResult->success)){
			?>
			<div class="notification notifyError loginNotify" style="display:block;">
			<?php
			echo $HttpContentResult->returnResult->message;
			?>
			</div>
			<?php
		}
	}
?>

<form id="loginform" action="<?php echo Config::getRootPath('backend/login/check/to/'.$to); ?>" method="post">
<div class="loginbox">
	<div class="loginbox_inner">
    	<div class="loginbox_content">
            <input type="text" name="login" class="username" />
            <input type="password" name="password" class="password" />
            <button name="submit" class="submit">Login</button>
        </div><!--loginbox_content-->
    </div><!--loginbox_inner-->
</div><!--loginbox-->

<div class="loginoption">
	<a href="" class="cant">&nbsp;</a>
    <!--<input type="checkbox" name="remember" />-->&nbsp;
</div><!--loginoption-->
</form>

</body>
</html>