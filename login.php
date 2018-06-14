<?php
session_start();
	include('funciones.php');
	sesion();?>
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
	<script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script>
	<script type="text/javascript" src="assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/pages/login.js"></script>
</head>
<body class="navbar-bottom login-container">
	<div class="navbar navbar-inverse">
		<div class="navbar-header">
			<a class="navbar-brand" href="#SAE">SAE {licence}</a>
				<ul class="nav navbar-nav pull-right visible-xs-block">
					<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
				</ul>
		</div>
	</div>
	<div class="page-container">
		<div class="page-content">
			<div class="content-wrapper">
				<form action="core.php?l=login" method="POST" id="login" autocomplete="off">
					<div class="panel panel-body login-form">
						<div class="text-center">
							<div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
							<h5 class="content-group">Ingrese a su Cuenta <small class="display-block">Iniciar Sesión</small></h5>
						</div>
						<div class="form-group has-feedback has-feedback-left">
							<input type="text" name="codigo" class="form-control" placeholder="Codigo" required="required" />
							<div class="form-control-feedback">
								<i class="icon-user text-muted"></i>
							</div>
						</div>
						<div class="form-group has-feedback has-feedback-left">
							<input type="password" name="pass" class="form-control" placeholder="Contraseña" required="required" />
							<div class="form-control-feedback">
								<i class="icon-lock2 text-muted"></i>
							</div>
						</div>
						<div class="form-group">
							<button type="submit" id="blogin" class="btn btn-primary btn-block">Ingresar<i class="icon-circle-right2 position-right"></i></button>
						</div>
						<div class="text-center">
							<a href="password.php">¿Olvido su contraseña?</a>
						</div>
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