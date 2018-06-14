<?php
session_start();
	include('funciones.php');?>
<!DOCTYPE html>
<html lang="es_GT">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SAE - Sistema Administrativo Educativo</title>
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="assets/css/colors.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="assets/js/core/libraries/bootstrap.min.js"></script>
</head>

<body class="navbar-bottom login-container">
	<div class="navbar navbar-inverse">
		<div class="navbar-header">
			<a class="navbar-brand" href="login.php#SAE">SAE {licence}</a>
		</div>
	</div>
	<div class="page-container">
		<div class="page-content">
			<div class="content-wrapper">
				<form action="core.php?l=login" method="POST" id="pass" autocomplete="off">
					<div class="panel panel-body login-form">
						<div class="text-center">
							<div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
							<h5 class="content-group">Recuperar Contraseña <small class="display-block">Enviaremos su contraseña al siguiente correo</small></h5>
						</div>

						<div class="form-group has-feedback">
							<input type="email" name="email" class="form-control" placeholder="Su correo">
							<div class="form-control-feedback">
								<i class="icon-mail5 text-muted"></i>
							</div>
						</div>
						<div class="text-center">
							<h5 class="content-group"><small class="display-block">Ingrese algún correo registrado en sistema, puede ser el de encargado o bien el de alumno, si se encuentra alguna coincidencia se enviara la contraseña al correo proporcionado.</small></h5>
						</div>
						<button type="submit" class="btn bg-blue btn-block">Enviar Contraseña <i class="icon-arrow-right14 position-right"></i></button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="navbar navbar-default navbar-fixed-bottom footer">
		<ul class="nav navbar-nav visible-xs-block">
			<li><a class="text-center collapsed" data-toggle="collapse" data-target="#footer"><i class="icon-circle-up2"></i></a></li>
		</ul>

		<div class="navbar-collapse collapse" id="footer">
			<div class="navbar-text">
				&copy; <?php echo date('Y');?>. <a href="#" class="navbar-link">Desarrolla: Marvin Lopez</a>
			</div>
			<div class="navbar-right">
				<ul class="nav navbar-nav">
					<li><a href="#">Dev: <?php version();?></a></li>
				</ul>
			</div>
		</div>
	</div>

</body>
</html>