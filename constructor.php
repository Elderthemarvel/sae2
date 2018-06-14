<?php
$version = '3.0';
$build='022018';
$head = "";
$perfil = json_decode($_COOKIE['perfil'], TRUE);
$year = date('Y');
include("funciones.php");
$foother = <<<EOT
	<div class="navbar navbar-default navbar-fixed-bottom footer">
		<ul class="nav navbar-nav visible-xs-block">
			<li><a class="text-center collapsed" data-toggle="collapse" data-target="#footer"><i class="icon-circle-up2"></i></a></li>
		</ul>

		<div class="navbar-collapse collapse" id="footer">
			<div class="navbar-text">
				&copy; 2013 - {$year}. <a href="#" class="navbar-link">Desarrolla: Marvin Lopez</a>  <a href="http://gtdesarrollo.net" class="navbar-link" target="_blank">GT Desarrollo</a>
			</div>

			<div class="navbar-right">
				<ul class="nav navbar-nav">
					<li><a href="#">Build: {$build}</a></li>
				</ul>
			</div>

		</div>
	</div>
EOT;



$msglist = <<<EOT
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-bubbles4"></i>
						<span class="visible-xs-inline-block position-right">Mensajes</span>
						<span class="badge bg-warning-400">2</span>
					</a>
					
					<div class="dropdown-menu dropdown-content width-350">
						<div class="dropdown-content-heading">
							Mensajes
							<ul class="icons-list">
								<li><a href="#"><i class="icon-compose"></i></a></li>
							</ul>
						</div>

						<ul class="media-list dropdown-content-body">
							<li class="media">
								<div class="media-left">
									<img src="assets/images/placeholder.jpg" class="img-circle img-sm" alt="">
									<span class="badge bg-danger-400 media-badge">5</span>
								</div>

								<div class="media-body">
									<a href="#" class="media-heading">
										<span class="text-semibold">James Alexander</span>
										<span class="media-annotation pull-right">04:58</span>
									</a>

									<span class="text-muted">who knows, maybe that would be the best thing for me...</span>
								</div>
							</li>

							<li class="media">
								<div class="media-left">
									<img src="assets/images/placeholder.jpg" class="img-circle img-sm" alt="">
									<span class="badge bg-danger-400 media-badge">4</span>
								</div>

								<div class="media-body">
									<a href="#" class="media-heading">
										<span class="text-semibold">Margo Baker</span>
										<span class="media-annotation pull-right">12:16</span>
									</a>

									<span class="text-muted">That was something he was unable to do because...</span>
								</div>
							</li>

							<li class="media">
								<div class="media-left"><img src="assets/images/placeholder.jpg" class="img-circle img-sm" alt=""></div>
								<div class="media-body">
									<a href="#" class="media-heading">
										<span class="text-semibold">Jeremy Victorino</span>
										<span class="media-annotation pull-right">22:48</span>
									</a>

									<span class="text-muted">But that would be extremely strained and suspicious...</span>
								</div>
							</li>

							<li class="media">
								<div class="media-left"><img src="assets/images/placeholder.jpg" class="img-circle img-sm" alt=""></div>
								<div class="media-body">
									<a href="#" class="media-heading">
										<span class="text-semibold">Beatrix Diaz</span>
										<span class="media-annotation pull-right">Tue</span>
									</a>

									<span class="text-muted">What a strenuous career it is that I've chosen...</span>
								</div>
							</li>

							<li class="media">
								<div class="media-left"><img src="assets/images/placeholder.jpg" class="img-circle img-sm" alt=""></div>
								<div class="media-body">
									<a href="#" class="media-heading">
										<span class="text-semibold">Richard Vango</span>
										<span class="media-annotation pull-right">Mon</span>
									</a>
									
									<span class="text-muted">Other travelling salesmen live a life of luxury...</span>
								</div>
							</li>
						</ul>

						<div class="dropdown-content-footer">
							<a href="#" data-popup="tooltip" title="All messages"><i class="icon-menu display-block"></i></a>
						</div>
					</div>
				</li>
EOT;


$user = <<<EOT
				<li class="dropdown dropdown-user">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<img src="assets/images/placeholder.jpg" alt="">
						<span> {$perfil['nombres']} </span>
						<i class="caret"></i>
					</a>

					<ul class="dropdown-menu dropdown-menu-right">
						<li><a href=""><i class="icon-user-plus"></i> Mi perfil</a></li>
						<li><a href="./miscursos.php"><i class="icon-coins"></i> Mis cursos</a></li>
						<li><a href="#"><span class="badge bg-teal-400 pull-right">58</span> <i class="icon-comment-discussion"></i> Alertas</a></li>
						<li class="divider"></li>
						<li><a href="#"><i class="icon-cog5"></i> Editar cuenta</a></li>
						<li><a href="core.php?l=logout"><i class="icon-switch2"></i> Salir</a></li>
					</ul>
				</li>
EOT;

$user2 = <<<EOT
			<div class="category-content sidebar-user">
				<div class="media">
					<a href="#" class="media-left"><img src="assets/images/placeholder.jpg" class="img-circle img-sm" alt=""></a>
					<div class="media-body">
						<span class="media-heading text-semibold"> {$perfil['nombres']} </span>
						<div class="text-size-mini text-muted">
							<i class="icon-pin text-size-small"></i> &nbsp;{$perfil['apellidos']}
						</div>
					</div>

					<div class="media-right media-middle">
						<ul class="icons-list">
							<li>
								<a href="#"><i class="icon-cog3"></i></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
EOT;





$menu_s = <<<EOT
	<div class="navbar navbar-inverse">
		<div class="navbar-header">
			<a class="navbar-brand" href="/">SAE <small> {$version}</small></a>
			<ul class="nav navbar-nav visible-xs-block">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
				<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
			</ul>
		</div>
			<p id="ver" class="navbar-text"><span id="verUNIDAD" class="label bg-success-400 ">Seleccione Unidad</span></p>
		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav navbar-right">{$msglist} {$user}</ul>
		</div>
	</div>
EOT;


$menu2 = <<<EOT
		<div class="breadcrumb-line">
			<ul class="breadcrumb-elements">
				<li><a href="#"><i class="icon-comment-discussion position-left"></i> Soporte</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-gear position-left"></i>
						<span id="unidad">Unidad a Trebajar</span>
						<span class="caret"></span>
					</a>

					<ul class="dropdown-menu dropdown-menu-right">
						<li><a class="unidad" unidad="1" href="#"><i class="icon-statistics"></i> Primera Unidad</a></li>
						<li><a class="unidad" unidad="2" href="#"><i class="icon-statistics"></i> Segunda Unidad</a></li>
						<li><a class="unidad" unidad="3" href="#"><i class="icon-statistics"></i> Tercera Unidad</a></li>
						<li><a class="unidad" unidad="4" href="#"><i class="icon-statistics"></i> Cuarta Unidad</a></li>
					</ul>
				</li>
			</ul>
		</div>
EOT;


$pageheader = <<<EOT
	<div class="page-header">
		$menu2;
		<!--
		<div class="page-header-content">
			<div class="page-title">
				<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> - Dashboard <small>Hello, {$perfil['nombres']}!</small></h4>
			</div>
			<div class="heading-elements">
				<div class="heading-btn-group">
					<a href="#" class="btn btn-link btn-float has-text"><i class="icon-bars-alt text-primary"></i><span>Statistics</span></a>
					<a href="#" class="btn btn-link btn-float has-text"><i class="icon-calculator text-primary"></i> <span>Invoices</span></a>
					<a href="#" class="btn btn-link btn-float has-text"><i class="icon-calendar5 text-primary"></i> <span>Schedule</span></a>
				</div>
			</div>
		</div>-->

	</div>
EOT;

$este_lugar = lugar();
function menu(){
	global $este_lugar;
	$data = '
	<div class="category-content no-padding">
	<ul class="navigation navigation-main navigation-accordion">
		<!-- Main -->
		<li class="navigation-header"><span>Sistema Administrativo Educativo</span> <i class="icon-menu" title="Main pages"></i></li>
		<li '; if( $este_lugar == "index" || $este_lugar == ""){$data .= "class='active'";}; $data .= '><a href="index.php"><i class="icon-home4"></i> <span>Inicio</span></a></li>
		<li>
			<a href="#"><i class="icon-stack2"></i> <span>Mis opciones</span></a>
			<ul>
				<li '; if($este_lugar == "miscursos"){$data .= "class='active'";}; $data .= '><a href="miscursos.php">Mis Cursos</a></li>
				<li '; if($este_lugar == "archivos"){$data .= "class='active'";}; $data .= '><a href="archivos.php">Archivos</a></li>

				<li '; if($este_lugar == "docentes"){$data .= "class='active'";}; $data .= '><a href="docentes.php">Docentes</a></li>
				<li '; if($este_lugar == "zona"){$data .= "class='active'";}; $data .= '><a href="zona.php">Notas</a></li>




				<li class="cosa "><a href="layout_navbar_secondary_hideable.html">Hideable secondary navbar</a></li>
				<li class="cosa "><a href="layout_sidebar_sticky_custom.html">Sticky sidebar (custom scroll)</a></li>
				<li class="cosa "><a href="layout_sidebar_sticky_native.html">Sticky sidebar (native scroll)</a></li>
				<li class="cosa "><a href="layout_footer_fixed.html">Fixed footer</a></li>
				<li class="navigation-divider"></li>
				<li class="cosa "><a href="boxed_default.html">Boxed with default sidebar</a></li>
				<li class="cosa "><a href="boxed_mini.html">Boxed with mini sidebar</a></li>
				<li class="cosa "><a href="boxed_full.html">Boxed full width</a></li>
			</ul>
		</li>
		<li>
			<a href="#"><i class="icon-copy"></i> <span>Configuración</span></a>
			<ul>
				<li '; if($este_lugar == "configuracion"){$data .= "class='active'";}; $data .= '><a href="configuracion.php">Institucional</a></li>
				<li '; if($este_lugar == "academico"){$data .= "class='active'";}; $data .= '><a href="academico.php">Académico</a></li>
				<li><a href="index.html" id="layout3">Layout 3 <span class="label bg-warning-400">Current</span></a></li>
				<li><a href="../../../layout_4/LTR/default/index.html" id="layout4">Layout 4</a></li>
				<li><a href="../../../layout_5/LTR/default/index.html" id="layout5">Layout 5</a></li>
				<li class="disabled"><a href="../../../layout_6/LTR/default/index.html" id="layout6">Layout 6 <span class="label label-transparent">Coming soon</span></a></li>
			</ul>
		</li>
		<li>
			<a href="#"><i class="icon-droplet2"></i> <span>Color system</span></a>
			<ul>
				<li><a href="colors_primary.html">Primary palette</a></li>
				<li><a href="colors_danger.html">Danger palette</a></li>
				<li><a href="colors_success.html">Success palette</a></li>
				<li><a href="colors_warning.html">Warning palette</a></li>
				<li><a href="colors_info.html">Info palette</a></li>
				<li class="navigation-divider"></li>
				<li><a href="colors_pink.html">Pink palette</a></li>
				<li><a href="colors_violet.html">Violet palette</a></li>
				<li><a href="colors_purple.html">Purple palette</a></li>
				<li><a href="colors_indigo.html">Indigo palette</a></li>
				<li><a href="colors_blue.html">Blue palette</a></li>
				<li><a href="colors_teal.html">Teal palette</a></li>
				<li><a href="colors_green.html">Green palette</a></li>
				<li><a href="colors_orange.html">Orange palette</a></li>
				<li><a href="colors_brown.html">Brown palette</a></li>
				<li><a href="colors_grey.html">Grey palette</a></li>
				<li><a href="colors_slate.html">Slate palette</a></li>
			</ul>
		</li>
		<li>
			<a href="#"><i class="icon-stack"></i> <span>Starter kit</span></a>
			<ul>
				<li><a href="starters/horizontal_nav.html">Horizontal navigation</a></li>
				<li><a href="starters/1_col.html">1 column</a></li>
				<li><a href="starters/2_col.html">2 columns</a></li>
				<li>
					<a href="#">3 columns</a>
					<ul>
						<li><a href="starters/3_col_dual.html">Dual sidebars</a></li>
						<li><a href="starters/3_col_double.html">Double sidebars</a></li>
					</ul>
				</li>
				<li><a href="starters/4_col.html">4 columns</a></li>
				<li><a href="starters/layout_boxed.html">Boxed layout</a></li>
				<li class="navigation-divider"></li>
				<li><a href="starters/layout_navbar_fixed_main.html">Fixed main navbar</a></li>
				<li><a href="starters/layout_navbar_fixed_secondary.html">Fixed secondary navbar</a></li>
				<li><a href="starters/layout_navbar_fixed_both.html">Both navbars fixed</a></li>
				<li><a href="starters/layout_sidebar_sticky.html">Sticky sidebar</a></li>
			</ul>
		</li>
		<li><a href="changelog.html"><i class="icon-list-unordered"></i> <span>Changelog <span class="label bg-blue-400">1.6</span></span></a></li>
		<li><a href="../../RTL/default/index.html"><i class="icon-width"></i> <span>RTL version</span></a></li>
		<!-- /main -->
	</ul>
	</div>';
return $data;
}


$menu = menu();



$sidebar = <<<EOT
			<div class="sidebar sidebar-main sidebar-default">
				<div class="sidebar-fixed">
					<div class="sidebar-content">
						<div class="sidebar-category sidebar-category-visible">
							<div class="category-title h6">
								<span>Opciones</span>
								<ul class="icons-list">
									<li><a href="#" data-action="collapse"></a></li>
								</ul>
							</div>
								$user2 $menu
						</div>
					</div>
				</div>
			</div>
EOT;



$head = '
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta id="codigo" source="SAE2" code="" />
		<meta id="verUNIDAD1" source="SAE2" code="0" />
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
		<script type="text/javascript" src="assets/js/plugins/loaders/blockui.min.js"></script>
		<script type="text/javascript" src="assets/js/pages/print.js"></script>
		<script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
		
		';


define('FOOTHER',$foother);
define('MENU',$menu_s);

	$head .= '
			<script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script>
			<script type="text/javascript" src="assets/js.cookie.js"></script>
			';

	if(lugar()=="index" || lugar()==""){
		//js req
		$head .= '
			<script type="text/javascript" src="assets/js/plugins/visualization/d3/d3.min.js"></script>
			<script type="text/javascript" src="assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
			<script type="text/javascript" src="assets/js/plugins/forms/styling/switchery.min.js"></script>
			<script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
			<script type="text/javascript" src="assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
			<script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>
			<script type="text/javascript" src="assets/js/plugins/pickers/daterangepicker.js"></script>
			<script type="text/javascript" src="assets/js/plugins/ui/nicescroll.min.js"></script>
			<script type="text/javascript" src="assets/js/core/app.js"></script>
			<script type="text/javascript" src="assets/js/pages/dashboard.js"></script>
			<script type="text/javascript" src="assets/js/pages/layout_sidebar_sticky_custom.js"></script>
			</head>';
			include("dashboard.php");
	}elseif(lugar()=="newcrono"){
		$head .= '
			<script type="text/javascript" src="assets/js/plugins/uploaders/dropzone.min.js"></script>
			<script type="text/javascript" src="assets/js/plugins/tables/handsontable/handsontable.min.js"></script>
			<script type="text/javascript" src="assets/js/plugins/tables/handsontable/languages.js"></script>
			<script type="text/javascript" src="assets/js/core/app.js"></script>
			<script type="text/javascript" src="assets/js/pages/newcrono.js"></script>
			</head>';
	}elseif(lugar()=="listCrono"){
		$head .= '
			<script type="text/javascript" src="assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
			<script type="text/javascript" src="assets/js/core/app.js"></script>
			</head>
			';
	}elseif(lugar()=="archivos"){
		$head .= '
			<script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
			<script type="text/javascript" src="assets/js/plugins/uploaders/dropzone.min.js"></script>
			<script type="text/javascript" src="assets/js/core/app.js"></script>
			<script type="text/javascript" src="assets/js/pages/archivos.js"></script>
			</head>
			';
	}elseif(lugar()=="miscursos"){
		$head .= '
			<script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
			<script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
			<script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
			<script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
			<script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
			<script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
			<script type="text/javascript" src="assets/js/plugins/tables/handsontable/handsontable.min.js"></script>


			<script type="text/javascript" src="assets/js/plugins/loaders/blockui.min.js"></script>
			<script type="text/javascript" src="assets/js/core/app.js"></script>
			<script type="text/javascript" src="assets/js/pages/miscursos.js"></script>
			</head>
			';
	}elseif(lugar()=="configuracion"){
		$head .= '
			<script type="text/javascript" src="assets/js/plugins/uploaders/dropzone.min.js"></script>
			<script type="text/javascript" src="assets/js/core/app.js"></script>
			<script type="text/javascript" src="assets/js/pages/configuracion.js"></script>
			</head>
			';
		

	}elseif(lugar()=="academico"){
		$head .= '
			<script type="text/javascript" src="assets/js/plugins/uploaders/dropzone.min.js"></script>
			<script type="text/javascript" src="assets/js/core/app.js"></script>
			<script type="text/javascript" src="assets/js/pages/academico.js"></script>
			</head>
			';
		}


	elseif(lugar()=="docentes"){
		$head .= '
			<script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
			<script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
			<script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
			<script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
			<script type="text/javascript" src="assets/js/plugins/forms/editable/editable.min.js"></script>			
			<script type="text/javascript" src="assets/js/core/app.js"></script>
			<script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
			<script type="text/javascript" src="assets/js/pages/docentes.js"></script>
			</head>
			';
	}
	


	define('HEAD',$head);