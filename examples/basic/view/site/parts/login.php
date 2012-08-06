<div id="login_content">

	<span id="close-form" class="close">fechar</span>

	<div id="form-login">
		<div class="header-info">
		<h2>login</h2>
		</div>

		<form id="frmLogin" method="post" action="<?php echo Config::getRootPath("login/check/") ?>" >
			<ul>
				<li>
					<input type="text" name="login" id="login" /><label for="login" >email *</label>
				</li>
				<li>
					<input type="password" name="password" id="pass" /><label for="pass" >senha *</label>
				</li>
			</ul>
			<span class="msg">&nbsp;</span>
			<a class="pass-recovery" href="" >esqueci minha senha</a>
			<!-- TODO: Fazer um exemplo de cadastro  -->
			<button id="btn-register" type="button" >cadastre-se</button>
			<button id="btn-login" type="submit" >login</button>
		</form>

	</div>
</div>