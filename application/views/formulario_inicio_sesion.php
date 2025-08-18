<?php
    require("head.php");
?>
<link rel="stylesheet" type="text/css" href="<?= base_url("css/login.css")?>">
<body class="hold-transition login-page">
	<div id="loginBox" class="login-box">
		<div id="blkblur" class="login-box-body">
			<div class="login-logo">
				<center><a href="#"><img id="profile-img" class="img-responsive" src="<?= base_url("img/logo_blanco_cdm.png")?>" /></a></center>
				CUENTAS POR PAGAR
				<hr/>
			</div>
			<form id="formulario_login" action="<?= site_url("Login/Verificar")?>" method="post">
				<?= $this->session->flashdata('error_usuario') ?>
				<div style="white-space:nowrap">
                        <label for="user"><i class="fas fa-user"></i></label>
                        <input type="text" id="login_usuario" name="login_usuario" placeholder="Usuario" required/>
                    </div>
                    <div style="white-space:nowrap">
                        <label for="pass"><i class="fas fa-key"></i></label>
                        <input type="password" id="login_password" name="login_password" placeholder="Contraseña" required/>
                    </div>
				<div class="row">
					<div class="col-xs-12">
						<button type="submit" class="btn btn-default btn-block">ENTRAR</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</body>