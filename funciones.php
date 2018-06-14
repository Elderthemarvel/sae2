<?php
	date_default_timezone_set('America/Guatemala');
	ini_set('memory_limit', '1G');
  	$sid = 1;
  	$datos['y'] = date('Y');

  	function sesion(){
		if(!isset($_COOKIE['perfil']) && (isset($_SESSION['codigo']) && isset($_SESSION['tipo'])) ){
			datosG($_SESSION['codigo'], $_SESSION['tipo']);
		}	
		if((isset($_SESSION['codigo']) && isset($_SESSION['tipo'])) and lugar()=="login" ){
			header('Location: index.php');
			exit();
		}
		if(!(isset($_SESSION['codigo']) && isset($_SESSION['tipo'])) and lugar()!="login" ){
			header('Location: login.php');
		exit();
		}

	}


  	function crearObj($user,$ciclo=""){
  		global $mysqli;
  		if($ciclo == "") $ciclo = date('Y');
  		$usuario 		= 	db("SELECT * FROM usuarios WHERE codigo LIKE '{$user}' limit 0,1",$mysqli);
  		$archivos 		= 	db("SELECT * FROM archivos WHERE docente LIKE '{$user}' ",$mysqli);
		$asignaciones 	= 	db("SELECT * FROM asignaciones WHERE maestro LIKE '{$user}' ",$mysqli);
		$config 		= 	db("SELECT * FROM configuracion",$mysqli);		
		foreach ($usuario[0] as $key => $value) {
			$datos['user'][$key]	=		$value;
		}		
		foreach ($archivos[0] as $key => $value) {
			$datos['file'][$key]	=		$value;
		}
		foreach ($asignaciones as $key => $value) {
			$datos['assi'][$key]	=		$value;
		}
		foreach ($config as $key => $value) {
			$datos['conf'][$key]	=		$value;
		}		
		if(isset($_SESSION['data'])) unset($_SESSION['data']);
		$_SESSION['data'] = $datos;
		
  	}






	if(isset($_COOKIE["UNIDAD"])){
		$unidad = $_COOKIE["UNIDAD"];
	}else{
		$unidad = 0;
	}

	function eliminar_tildes($cadena){
	    $cadena = str_replace(
	        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
	        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
	        $cadena);
	    $cadena = str_replace(
	        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
	        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
	        $cadena );
	    $cadena = str_replace(
	        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
	        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
	        $cadena );
	    $cadena = str_replace(
	        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
	        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
	        $cadena );
	    $cadena = str_replace(
	        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
	        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
	        $cadena );
	    $cadena = str_replace(
	        array('ñ', 'Ñ', 'ç', 'Ç'),
	        array('n', 'N', 'c', 'C'),
	        $cadena
	    );
	    return $cadena;
	}

	function version(){
		$ver = '3.0';
		return $ver;
	}

	function lugar(){
		$nombre_archivo = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
			if ( strpos($nombre_archivo, '/') !== FALSE ){
			    //de ser asi, lo eliminamos, y solamente nos quedamos con el nombre sin su extension
			    @$nombre_archivo = preg_replace('/\.php$/', '' ,array_pop(explode('/', $nombre_archivo)));
			}
		return $nombre_archivo;
	}

	function conectar(){
		global $mysqli;	
		define("HOST", "saerds.cjotq8aa2kgw.us-east-2.rds.amazonaws.com");
		define("USER", "sae_system"); 
		define("PASSWORD", "Sae5621*");
		define("DATABASE", "sae");
		@$mysqli = mysqli_connect(HOST, USER, PASSWORD) or die ("No se ha podido conectar al servidor. <br />Error: ".mysqli_connect_error());
		@$db = mysqli_select_db( $mysqli, DATABASE ) or die ( "Upps! No es posible conectar con la base de datos." );
		$acentos = $mysqli->query("SET NAMES 'utf8'");
	}

	function db ($sql, $c) {
		$res = false;
		$q = ($c === null)?@mysqli_query($sql):@mysqli_query($c,$sql);

		if($q) {
			if(strpos(strtolower($sql),'select') === 0) {
				$res = array();
				while($r = mysqli_fetch_assoc($q)) {
					$res[] = $r;
				}
			} else {
				$res = ($c === null)?mysqli_affected_rows():mysqli_affected_rows($c);
			}
		}
		return $res;
		mysqli_close($c);
	}

	function cerrar_conex(){
		global $mysqli;
		mysqli_close ($mysqli);
	}

	function salir(){
      session_start();
      $_SESSION = array();
      if (ini_get("session.use_cookies")) {
          $params = session_get_cookie_params();
          setcookie(session_name(), '', time() - 420000,
              $params["path"], $params["domain"],
              $params["secure"], $params["httponly"]
          );
      session_destroy();
      setcookie("perfil", 0,time() - 3600);
      }
      header("Location: login.php");
      exit;
	}

	function datosG($codigo, $tipo){
		$datosGE = array(); 	
		global $mysqli;
		    if($codigo && $tipo){
		    	$datosGE['hora'] = date('1998');
		    	if($user = db("select codigo,tipo,nombre,apellido from usuarios where codigo = '{$codigo}' and tipo = '{$tipo}' limit 0,1 ",$mysqli)){
		   		 	$datosGE['nombres'] = $user[0]['nombre'];
		    		$datosGE['apellidos'] = $user[0]['apellido'];
		    		$datosGE['hora'] = date('YmdHis');
		    		$datosGE['codigo'] = $codigo;
		    	}
		    		$perfil = json_encode($datosGE);
		    		setcookie("perfil", $perfil);
		    }
	}

	function tabla_archivos($codigo,$tipo=""){
		global $mysqli;
		$tabla = "";
		$n = 0;
		if($archivos = db("select * from archivos where docente like '{$codigo}' ", $mysqli)){
			$enlace = "";
				$tabla = '
				<table id="tblarchivos" class="table table-bordered table-xxs">
					<thead>
						<tr>
							<th width="5%">#</th>
							<th>Título de Archivo</th>';
			if($tipo == ""){
				$tabla .= '<th width="15%">Configuración</th>';
			}				
				$tabla .= '							
						</tr>
					</thead>
					<tbody>';
			foreach ($archivos as $archivo) {
				$n++;
				if($tipo == ""){
					$enlace = microsoft_view($archivo['archivo']);
				}
				$tabla .= "
				<tr>
	                <td>{$n}</td>
	                <td>{$archivo['nombre']}</td>
	                <!--<td>{conteo}</td>-->";




	            if($tipo == ""){
	            $tabla .= "    <td>
						<div class='btn-group'>
	                    	<a data-toggle='dropdown' aria-expanded='false' > <i class='icon-gear'></i> &nbsp;<span class='caret'></span> </a>
	                    	<ul class='dropdown-menu dropdown-menu-right'>
	                    		<li><a class='verDOC' href='#verDOC' rel='{$enlace}'><i class='icon-file-eye'></i> Ver</a></li>
								<li><a class='editar' anterior='{$archivo['archivo']}' href='#Editar' titulo='{$archivo['nombre']}' idb='{$archivo['idfile']}'><i class='icon-pencil7'></i> Editar</a></li>
								<li class='divider'></li>
								<li><a source='del_file' idb='{$archivo['idfile']}' class='borrar' href='#borrar'><i class='icon-trash'></i> Borrar</a></li>
							</ul>
						</div>
	                </td>";
	            }

	           $tabla .="</tr>";
			}
			$tabla .= '</tbody></table>';
		}else{
			$tabla = "<h3>No se encontro archivos con su codigo.</h3>";
		}
		return $tabla;
	}

	function ext($ext){
		$info = new SplFileInfo($ext);
		$ext = $info->getExtension();
		return $ext;
	}

	function microsoft_view($archivo){
		if( ext($archivo) == "pdf"){
			$enlace = "files/";
		}else{
			$enlace = "https://view.officeapps.live.com/op/view.aspx?src=";
		}
		return $enlace.$archivo;
	}

	function color_mis_cursos($c){
		if($c==0){
			$r = 'fila';
		}elseif($c==1){
			$r = 'success';
		}elseif($c==2){
			$r = 'warning';
		}elseif($c==3){
			$r = 'danger';
		}elseif($c==4){
			$r = 'info';
		}
		return $r;
	}

	function vermiscursos($codigo,$datos=""){
		global $mysqli, $unidad;	
		$tabla = "";
		$n = 0;		
		if($asignaciones = db("SELECT asignaciones.maestro, asignaciones.seccion, cursos_grado.codigo, cursos_grado.perfil, cursos_grado.grado, cursos_grado.nivel, cursos_grado.carrera, cursos_grado.jornada, cursos.nombre, cursos_grado.id FROM 			asignaciones  INNER JOIN cursos_grado ON asignaciones.curso = cursos_grado.id INNER JOIN cursos ON cursos_grado.codigo = cursos.codigo WHERE asignaciones.maestro LIKE '{$codigo}'",$mysqli)){
		$tabla = '
		<table class="table datatable-column-search-selects table-bordered">
			<thead>
				<tr>
					<th width="10">#</th>
					<th width="50">Codigo</th>
					<th>Curso</th>
					<th>Grado</th>
					<th width="40">Sección</th>
					<td width="150"></td>
				</tr>
			</thead>
			<tbody>';
		foreach ($asignaciones as $curso) {
			$clase 		=		'fila';
			$n++;			
			if($unidad > 0){
				$crono 		=		buscaCrono($curso['id'],$curso['seccion'],$unidad);
				$clase 		=		color_mis_cursos($crono['estado']);
			}else{
				$crono 		= 		0;
			}
			if($crono==0){
				$clase_action 	= "crear";
			}else {
				$clase_action 	= "edit";
			}

			$grado = grado($curso['grado'],$curso['nivel'],$curso['carrera']);
			$tabla .= "
			<tr class='{$clase} '>
                <td>{$n}</td>
                <td>{$curso['codigo']}</td>
                <td>{$curso['nombre']}</td>
                <td><span style='font-size:12px;'>{$grado}</span> </td>
                <td>{$curso['seccion']}</td>";
            if($datos==""){
            $data['codigo'] = $curso['id'];
            $data['curso_t'] = $curso["nombre"];
            $data['grado'] = $grado;
            $data['seccion'] = $curso["seccion"];
            $dato 	= base64_encode(json_encode($data));
            $tabla .= "<td>
					<ul class='icons-list'>
						<li>
							<a class='{$clase_action}' href='?crear' data-crono='{$dato}' title='Cronograma'>
								<i class='icon-pencil7'></i>
							</a>
						</li>
						<li>
							<a data-toggle='modal' data-target='#fullzona' class='btn bg-info btn-icon btn-xs permiso_unidad' href='notas.php?data={$dato}' >
								<i class='icon-table2'></i>
							</a>
						</li>
					</ul>
				</td>";
			}elseif($datos == "clonar"){
				$tabla .= '<td> <input type="checkbox" class="styled" > </td>';
			}
			$tabla .= '</tr>';
		}

		$tabla .= '
		</tbody>
			<tfoot>
				<tr>
					<th width="10">#</th>
					<td width="50">Codigo</th>
					<td>Curso</td>
					<td>Grado</td>
					<td width="40">Sección</td>
					<th width="150"></td>
				</tr>
			</tfoot>
		</table>';

		}else{

			$tabla = "<h3>No tiene cursos asignados, puede asignarse en cualquier momento.</h3>";
		}
		return $tabla;
	}

	function grado_t($g){
		if($g==1){
			$g = "Primero";
		}elseif ($g==2) {
			$g = "Segundo";
		}elseif ($g==3) {
			$g = "Tercero";
		}elseif ($g==4) {
			$g = "Cuarto";
		}elseif ($g==5) {
			$g = "Quinto";
		}elseif ($g==6) {
			$g = "Sexto";
		}else{
			$g = 0;
		}
		return $g;
	}

	function grado($g,$n,$c){
		@session_start();
		global $mysqli;
		if($c==""){
			$ret 	=	json_decode($_SESSION['data']['conf'][6]['valor'],TRUE);
			$dat 	= 	$ret[$n];
		}else{
			$ret 	=	json_decode($_SESSION['data']['conf'][4]['valor'],TRUE);
			$dat 	= 	$ret[$c];
		}
		$g = grado_t($g).' <br> <small style="font-weight: bold;">'.$dat.'</small>';
		return $g;
	}

	function Sgrado($limite){
		$select = '';
		//Select para grados
		if ($limite == 0 || $limite > 6) {
			$limite = 6;
		}
		$select = '
		<select name="grado" class="form-control" required>
        <option value="">Grado</option>';
        for ($i = 1; $i <= $limite; $i++) {
        	$select .= "<option value=\"{$i}\">".grado_t($i).'</option>';
		}
		$select .= '</select>';
		return $select;
	}

	function Snivel($name,$r=FALSE){
		global $mysqli, $sid;
		$select = '';
		if($name == 'nivel'){
			$n = db("SELECT valor FROM configuracion WHERE opcion LIKE 'NIVELES' and sid LIKE '{$sid}' LIMIT 0,1",$mysqli);
			$c = "";
		}else{
			$n = db("SELECT valor FROM configuracion WHERE opcion LIKE 'CARRERAS' and sid LIKE '{$sid}' LIMIT 0,1",$mysqli);
			$c = 'Sinput';
		}
			$datos = json_decode($n[0]['valor']);
			$rq = '';
			if($r){$rq = 'required'; }
			$op = ucwords($name);
			$select .= "<select class='form-control {$c}' name='{$name}' {$rq} >
						<option value=''>{$op}</option>";
			foreach ($datos as $key => $value) {
				$select .= "<option value='{$key}'>{$value}</option>";
			}
			$select .= '</select>';
        return $select;
	}

	function Sjornada(){
		global $mysqli;
		$i = 0;
		$jornadas 	=		db("select valor from configuracion where opcion LIKE 'JORNADAS' limit 0,1",$mysqli);
		$data 		=		$jornadas[0]['valor'];
		$data 		=		json_decode($data,TRUE);

		$select = '
        <select name="jornada" class="form-control" required>
            <option value="">Jornada</option>';
	        foreach ($data as $key => $value) {
	        	$select .= "<option value='{$key}'>{$value}</option>";
	        }

        $select .= '</select>';
        return $select;
	}

	function cursos($datos){
		global $mysqli;
		$tabla = "";
		if($datos['p'] == "" ){
          $carreras = "";
        }else{
          $carreras = " and carrera = ${datos['p']} ";
        }
		if($cursos = db("SELECT cursos_grado.id, cursos.codigo, nombre FROM cursos_grado INNER JOIN cursos ON cursos_grado.codigo = cursos.codigo WHERE cursos_grado.grado = ${datos['g']} AND cursos_grado.nivel = ${datos['n']} AND cursos_grado.jornada = ${datos['j']}  ",$mysqli)){
		$n=0;
		$tabla = '
			<table class="table table-xxs table-bordered">
			<thead>
				<tr>
					<th width="2">#</th>
					<th width="50">Codigo</th>
					<th>Nombre</th>
					<th width="5">P</th>
					<td width="15"> </td>
				</tr>
			</thead>
			<tbody>';
		foreach ($cursos as $curso) {
			$datos['c'] = $curso['codigo'];
			$clase = 'fila';
			$bclase	=	'asignar label-info';
			$label = 'Asignarme';
			if($datos['d'] != ""){
				$asignado = buscar_asignacion($datos); 
				if($asignado){
					$clase 	= 	'success';
					$bclase	=	'desasignar label-danger';
					$label = 'Desasignar';
				}
			}
			$datos['i'] = $curso['id'];
			$n++;
			$dato = base64_encode(json_encode($datos));
			$tabla .="
			<tr class='{$clase}'>
                <td>{$n}</td>
                <td>{$curso['codigo']}</td>
                <td>{$curso['nombre']}</td>
                <td>
                <span class='badge badge-success pull-right'> <i class='icon-checkmark2'></i>  </span>
                </td>
                <td>
					<a data-datos='{$dato}' class='{$bclase} label  pull-right'> {$label} </a>
                </td>
            </tr>";
		}

		$tabla .= '
		</tbody>
		</table>';



		}else{
			$tabla 	=	'<h3>No se encontró ningún curso para este grado, verifique la información ingresada.</h3>';
		}

		return $tabla;
	}

	function Sseccion(){
		global $mysqli;
		$list = '
        <select name="seccion" class="form-control" required>
            <option value="">Sección</option>';
			if($limite = db('select valor from configuracion where opcion like "SECCIONES" limit 0,1',$mysqli)){
				$g = $limite['0']['valor'] + 65;
				for ($i = 65; $i < $g; $i++) {
					$s = chr($i);
				    $list .= "<option value=\"{$s}\">{$s}</option>";
				}
			}else{
				$list .= '<option value="A">A</option>';
			}

        $list .= '</select>';
        return $list;
	}

	function buscar_asignacion($datos){
		global $mysqli;
		if($busca = db("SELECT asignaciones.id FROM asignaciones INNER JOIN cursos_grado ON asignaciones.curso = cursos_grado.id WHERE asignaciones.maestro LIKE '${datos['d']}' AND asignaciones.seccion LIKE '${datos['s']}' AND cursos_grado.codigo = ${datos['c']} AND cursos_grado.grado = ${datos['g']} AND cursos_grado.nivel = ${datos['n']} AND cursos_grado.jornada = ${datos['j']} limit 0,1",$mysqli)){
			$a = TRUE;
		}else{
			$a = FALSE;
		}
		return $a;
	}

	function saveAsignacion($datos){
		global $mysqli;
		$ciclo = date('Y');
		if ($guardar = $mysqli->prepare("INSERT INTO asignaciones (maestro, curso, seccion, ciclo) VALUES (?,?,?,?)")) {
			$guardar->bind_param('sssi', $datos['d'], $datos['i'], $datos['s'], $ciclo);
	    	$guardar->execute();
			$id	=	$mysqli->insert_id;
		}
		if(isset($id)){
			$id = $id;
		}else{
			$id = 0;
		}
		return $id;
	}

	function borrarAsignacion($datos){
		global $mysqli;
		$ciclo = date('Y');
		$borrar = "DELETE FROM asignaciones WHERE maestro like '${datos['d']}' and curso like '${datos['i']}' and seccion like '${datos['s']}' and ciclo = ".$ciclo;
        if($mysqli->query($borrar)){
        	return TRUE;
        }else{
        	return FALSE;
        }
	}

	function buscaCrono($c,$s,$b){
		global $mysqli;
		if($busca = db("select * from crono_key where curso like '{$c}' and seccion like '{$s}' and unidad = {$b} limit 0,1",$mysqli)){
			$r['estado'] = $busca['0']['estado'];
			$r['id'] = $busca['0']['id'];
			$r['save'] = $busca['0']['save'];
			$r['update'] = $busca['0']['update'];
			$r['llave'] = $busca['0']['llave'];
		}else{
			$r = 0;
		}
		return $r;
	}

	function cronograma($datos){
		global $mysqli;
		$datos['perfil']    =   json_decode($_COOKIE["perfil"],TRUE); 
		if($datos['tipo'] == "crear"){
			$responde['html'] = cronoForm($datos);
			$responde['estado'] = 0;
		}else{
			$estado = buscaCrono($datos['codigo'],$datos['seccion'],$datos['unidad']);
			if($estado['estado'] == 2){
				$responde['html'] = '
				<div class="alert alert-warning no-border">
					
					<span class="text-semibold">Enviado!</span> <br />Este cronograma ya fue enviado, esta esperando revisión, por favor espere, si este mensaje tarda mucho, verifique con el auxiliar de nivel. <br /><br /> ID: ';
				$responde['html'] .= $estado['id'].' <br /> SAVE: ';
				$responde['html'] .= $estado['save'].' <br /> UPDATE: ';
				$responde['html'] .= $estado['update'].'</div>';
				$responde['estado'] = 2;
			}elseif ($estado['estado'] == 3 || $estado['estado'] == 4) {
				$responde['html']	=	cronoedit($estado['id'],$estado['llave'],$datos);
				$responde['estado'] = 	3;
				$responde['idK']	=	$estado['id'];
				$responde['idD']	=	$estado['llave'];
			}else{
				$responde['html']	=	cronoview($estado['id'],$estado['llave'],$datos);
				$responde['estado'] = 1;
				$responde['idK']	=	$estado['id'];
				$responde['idD']	=	$estado['llave'];
			}
		}
		return $responde;
	}

	function cronoview($idK,$idD,$datos){
		global $mysqli,$version;
		$generado 		= 		date('Y-m-d H:i:s');
		$key 			=		db("SELECT * from crono_key where id = {$idK} LIMIT 0,1",$mysqli);
		$busca 			= 		db("select valor from configuracion where opcion like 'CRONOGRAMAS' limit 0,1",$mysqli);
		$data  			= 		db("select * from crono_data where llave like '{$idD}' limit 0,1",$mysqli);
		$enviado 		=		$key[0]['save'];
		$actualizado	=		$data[0]['save'];
		$autorizado 	=		$key[0]['update'];
		$cronodatos 	= 		json_decode($data[0]['data'], TRUE);
		$titulos = array();
		$celda = 0;
			$C 		= json_decode($data[0]['data'], true);
			$crono = json_decode($busca['0']['valor'], TRUE);
			$col   = count($crono['titulos']);
			$tabla = '
			<table class="table table-xxs table-bordered"><thead><tr><th width="30">#</th>';
			    for ($i = 0; $i < $col; $i++) {
			    	$tabla .= "<th>{$crono['titulos'][$i]}</th>";
	    		}
			$tabla .= '<th width="110">Fecha</th></tr></thead><tbody>';
				for ($i = 1; $i <= $crono['maximo']; $i++) {
	    			$tabla .= '<tr><td>'.($i).'</td>';
					for ($ii = 0; $ii < $col; $ii++) {
						$celda++;
						$t = $crono['titulos'][$ii]; 
				    	$tabla .= "<td><p style='font-size:10px;' class='small'>{$C['C'.$celda]} </p></td>";
		    		}
	    			$tabla .= "<td><p style='font-size:10px;' class='small'>{$C['f'.$i]}</p></td></tr>";
				}
				$tabla .= '</tbody></table>';
		$responde = <<<EOD
			<div id="imprimir" class="panel panel-white">
				<div class="panel-heading">
					<h6 class="panel-title">Cronograma de Tareas</h6>
					<div class="heading-elements">
						<button type="button"  data-show="miscursos" data-hide="cronograma" class="btn btn-default btn-xs heading-btn cancelar"><i class="icon-file-check position-left"></i> Regresar </button>
						<button type="button" class="print btn btn-default btn-xs heading-btn"><i class="icon-printer position-left"></i> imprimir</button>
                	</div>
				</div>
				<div id="printArea">
				<div class="panel-body no-padding-bottom">
					<div class="row">
						<div class="col-sm-6 content-group">
							<b>{$datos['curso_t']}</b>
 							<ul class="list-condensed list-unstyled">
								<li> Código: {$datos['codigo']} </li>
								<li> Unidad: {$datos['unidad']} </li>
								<li> Grado: {$datos['grado']} </li>
								<li> Sección: {$datos['seccion']} </li>
							</ul>
						</div>
					</div>
				</div>

				<div class="table-responsive">
				    {$tabla}
				</div>

				<div class="panel-body">
					<div class="row invoice-payment">
						<div class="col-sm-7">
							<div class="content-group">
								<h6 class="small">Curso impartido por: {$datos['perfil']['nombres']} {$datos['perfil']['apellidos']}</h6>
							</div>
						</div>
					</div>
				</div>
				</div>
				<!-- Adjunto -->
				<div class="mail-attachments-container">
					<h6 class="mail-attachments-heading">1 Archivo(s) Adjunto(s)</h6>

					<ul class="mail-attachments">
						
						<li>
							<span class="mail-attachments-preview">
								<i class="icon-file-pdf icon-2x"></i>
							</span>

							<div class="mail-attachments-content">
								<span class="text-semibold">new_december_offers.pdf</span>

								<ul class="list-inline list-inline-condensed no-margin">
									<li class="text-muted">174 KB</li>
									<li><a href="#">Ver</a></li>
									<li><a href="#">Descargar</a></li>
								</ul>
							</div>
						</li>

					</ul>
				</div>
				<!-- /attachments -->
			</div>
EOD;
	return $responde;
	}

	function cronoedit($idK,$idD="",$datos=""){
		global $mysqli;
			$titulos = array();
			$celda = 0;
			$busca = db("select valor from configuracion where opcion like 'CRONOGRAMAS' limit 0,1",$mysqli);
			if($busca){
			$data  	= db("select * from crono_data where llave like '{$idD}' limit 0,1",$mysqli);
			$C 		= json_decode($data[0]['data'], true);
			$crono = json_decode($busca['0']['valor'], TRUE);
			$col   = count($crono['titulos']);
			$tabla = "<form id='cronoForm' autocomplete='off'>
			<input class='datos' id='idK' type='hidden' name='idK' value='{$idK}'>
			<input class='datos' id='idD' type='hidden' name='idD' value='{$idD}'>
			<input class='datos' id='seccion' type='hidden' name='seccion' value='{$datos['seccion']}'>
			<input class='datos' id='docente' type='hidden' name='docente' value='{$datos['docente']}'>
			<input class='datos' id='curso' type='hidden' name='curso' value='{$datos['codigo']}'>
			<input class='datos' id='unidad' type='hidden' name='unidad' value='{$datos['unidad']}'>
			<input class='datos' id='id' type='hidden' name='id' value='{$idK}'>
			<input class='datos' id='estado' type='hidden' name='estado' value='0'>
			<input class='datos' id='fila' type='hidden' name='fila' value='{$col}'>			
			<table class='table table-bordered table-framed crono'><thead><tr><th width='50px'>#</th>";
		    for ($i = 0; $i < $col; $i++) {
		    	$tabla .= "<th>{$crono['titulos'][$i]}</th>";
    		}
			$tabla .= '<th>Fecha</th></tr></thead><tbody>';
							for ($i = 1; $i <= $crono['maximo']; $i++) {
				    		$tabla .= '<tr class="numero"><td style="margin: 0px; padding: 3px;">'.($i).'</td>';
								for ($ii = 0; $ii < $col; $ii++) {
									$celda++;
									$t = eliminar_tildes(strtolower($crono['titulos'][$ii])); 
							    	$tabla .= '<td style="margin: 0px; padding: 3px;">';
									$tabla .= "<input name='{$t}[]' id='C{$celda}' data-id='{$celda}|{$col}|' type='text' class='move datos' value='{$C['C'.$celda]}' /> </td>";
					    		}
				    		$tabla .= '<td style="margin: 0px; padding: 3px;"><input id="f'.$i.'" class="datos" style="border:none;" type="date" value="'.$C['f'.$i].'" name="fecha[]"/></td>
				    				</tr>';
				}
				$tabla .= '</tbody></table></form>';
		}else{
			$tabla 	=	'<div class="alert alert-danger no-border"><button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button><span class="text-semibold">Oh snap!</span>No se ha configurado los cronogramas, por favor realice esta configuración, para poder realizar este proceso. CODE: CRONOGRAMAS</div>';
		}
		return $tabla;
	}

	function cronoForm($datos){
		session_start();
		global $mysqli;
			$titulos = array();
			$celda = 0;
			$busca = $_SESSION['data']['conf'][0];
		if($busca){
			$crono = json_decode($busca['valor'], TRUE);
			$col   = count($crono['titulos']);
			$tabla = "<form id='cronoForm' autocomplete='off'>
			<input class='datos' id='seccion' type='hidden' name='seccion' value='{$datos['seccion']}'>
			<input class='datos' id='docente' type='hidden' name='docente' value='{$datos['docente']}'>
			<input class='datos' id='curso' type='hidden' name='curso' value='{$datos['codigo']}'>
			<input class='datos' id='unidad' type='hidden' name='unidad' value='{$datos['unidad']}'>
			<input class='datos' id='id' type='hidden' name='id' value='0'>
			<input class='datos' id='estado' type='hidden' name='estado' value='0'>
			<input class='datos' id='fila' type='hidden' name='fila' value='{$col}'>
			<table class='table table-bordered table-framed crono'><thead><tr><th width='50px'>#</th>";
			    for ($i = 0; $i < $col; $i++) {
			    	$tabla .= "<th>{$crono['titulos'][$i]}</th>";
	    		}
			$tabla .= '<th>Fecha</th></tr></thead><tbody>';
				for ($i = 1; $i <= $crono['maximo']; $i++) {
	    		$tabla .= '<tr class="numero"><td style="margin: 0px; padding: 3px;">'.($i).'</td>';
					for ($ii = 0; $ii < $col; $ii++) {
						$celda++;
						$t = eliminar_tildes(strtolower($crono['titulos'][$ii])); 
				    	$tabla .= '<td style="margin: 0px; padding: 3px;">';
						$tabla .= "<input name=\"{$t}[]\" id=\"C{$celda}\" data-id=\"{$celda}|{$col}|\" type=\"text\" class=\"move datos\" /> </td>";
		    		}
	    		$tabla .= '<td style="margin: 0px; padding: 3px;"><input id="f'.$i.'" class="datos" style="border:none;" type="date" name="fecha[]"/></td>
	    				</tr>';
				}
				$tabla .= '</tbody></table></form>';
		}else{
			$tabla 	=	'<div class="alert alert-danger no-border"><button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button><span class="text-semibold">Oh snap!</span>No se ha configurado los cronogramas, por favor realice esta configuración, para poder realizar este proceso. CODE: CRONOGRAMAS</div>';
		}
		return $tabla;
	}

	function tabla_usuarios($tipo,$datos=""){
		global $mysqli;
		$tabla = "";
		if($datos){$filtro = "and estado = 1";}else {$filtro = "";}
		if($usuarios = db("select * from usuarios where tipo = {$tipo} {$filtro}", $mysqli)){
			$tabla = '
				<table class="table datatable-save-state" >
					<thead>
						<tr>
							<th>Codigo</th>
							<th>Nombres</th>
							<th>Apellidos</th>
							<th>Correo</th>
							<th>Contraseña</th>
							<th class="text-center">Opciones</th>
						</tr>
					</thead>
					<tbody>';
						foreach ($usuarios as $usuario) {
							if ($usuario['estado']==0) {
								$estado = 'icon-eye-blocked2';
							}else{
								$estado = 'icon-eye';
							}
							$pass= base64_decode($usuario['password']);
							$tabla .="<tr>
								<td>
									<a href='/codigo' class='edit' data-title='Codigo' data-pk='{$usuario['id']}' data-name='codigo'>{$usuario['codigo']}</a></td>
								<td>{$usuario['nombre']}</td>
								<td>{$usuario['apellido']}</td>
								<td><a href='/correo' class='edit' data-title='Correo' data-pk='{$usuario['id']}' data-name='nick'>{$usuario['correo']}</a></td>
								<td><a href='/pass' class='edit' data-title='Contraseña' data-pk='{$usuario['id']}' data-name='pass'>{$pass}</a></td>
								<td class='text-center'>
									<ul class='icons-list'>
										<li class='cambiar' value='{$usuario['id']}'><a href='#''><i class={$estado}></i></a></li>
										<li class='text-danger-600 eliminar' value='{$usuario['id']}'><a href='#'><i class='icon-x'></i></a></li>
									</ul>
								</td>
							</tr>";
					}		
					$tabla .='</tbody>
					</table>';
				
		}else{
			$tabla = "<h3>No se encontraron registros, verifique con el adimistrador.</h3>";
		}
					
		return $tabla;
		cerrar_conex();
	}

	function Sactivo(){
		global $mysqli;

		$key[0] = 0;
		if($db=db("select valor from configuracion where opcion like 'LICENSE' limit 0,1",$mysqli)){
			$key[1] = $db[0]['valor'];
			if($db=db("select valor from configuracion where opcion like 'SECRET_KEY' limit 0,1",$mysqli)){
				$key[2] = $db[0]['valor'];
				if($db=db("select valor,fecha from configuracion where opcion like 'LOCAL_KEY' limit 0,1",$mysqli)){
					$key[3] = $db[0]['valor'];
					$key[4] = $db[0]['fecha'];
					$key[0] = 1;
				}
			}
		}
		return $key;
	}

	function server_status($licensekey, $localkey='',$secret='') {
	    $whmcsurl = base64_decode('aHR0cHM6Ly93d3cuZ3RkZXNhcnJvbGxvLmNvbS8=');
	    $licensing_secret_key = $secret;
	    $localkeydays = 7;
	    $allowcheckfaildays = 5;
	    @$check_token = time() . md5(mt_rand(1000000000, 9999999999) . $licensekey);
	    $checkdate = date("Ymd");
	    $domain = $_SERVER['SERVER_NAME'];
	    $usersip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];
	    $dirpath = dirname(__FILE__);
	    $verifyfilepath = base64_decode('bW9kdWxlcy9zZXJ2ZXJzL2xpY2Vuc2luZy92ZXJpZnkucGhw');
	    $localkeyvalid = false;
	    if ($localkey) {
	        $localkey = str_replace("\n", '', $localkey); # Remove the line breaks
	        $localdata = substr($localkey, 0, strlen($localkey) - 32); # Extract License Data
	        $md5hash = substr($localkey, strlen($localkey) - 32); # Extract MD5 Hash
	        if ($md5hash == md5($localdata . $licensing_secret_key)) {
	            $localdata = strrev($localdata); # Reverse the string
	            $md5hash = substr($localdata, 0, 32); # Extract MD5 Hash
	            $localdata = substr($localdata, 32); # Extract License Data
	            $localdata = base64_decode($localdata);
	            $localkeyresults = unserialize($localdata);
	            $originalcheckdate = $localkeyresults['checkdate'];
	            if ($md5hash == md5($originalcheckdate . $licensing_secret_key)) {
	                $localexpiry = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - $localkeydays, date("Y")));
	                if ($originalcheckdate > $localexpiry) {
	                    $localkeyvalid = true;
	                    $results = $localkeyresults;
	                    $validdomains = explode(',', $results['validdomain']);
	                    if (!in_array($_SERVER['SERVER_NAME'], $validdomains)) {
	                        $localkeyvalid = false;
	                        $localkeyresults['status'] = "Invalid";
	                        $results = array();
	                    }
	                    $validips = explode(',', $results['validip']);
	                    if (!in_array($usersip, $validips)) {
	                        $localkeyvalid = false;
	                        $localkeyresults['status'] = "Invalid";
	                        $results = array();
	                    }
	                    $validdirs = explode(',', $results['validdirectory']);
	                    if (!in_array($dirpath, $validdirs)) {
	                        $localkeyvalid = false;
	                        $localkeyresults['status'] = "Invalid";
	                        $results = array();
	                    }
	                }
	            }
	        }
	    }
	    if (!$localkeyvalid) {
	        $responseCode = 0;
	        $postfields = array(
	            'licensekey' => $licensekey,
	            'domain' => $domain,
	            'ip' => $usersip,
	            'dir' => $dirpath,
	        );
	        if ($check_token) $postfields['check_token'] = $check_token;
	        $query_string = '';
	        foreach ($postfields AS $k=>$v) {
	            $query_string .= $k.'='.urlencode($v).'&';
	        }
	        if (function_exists('curl_exec')) {
	            $ch = curl_init();
	            curl_setopt($ch, CURLOPT_URL, $whmcsurl . $verifyfilepath);
	            curl_setopt($ch, CURLOPT_POST, 1);
	            curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
	            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	            $data = curl_exec($ch);
	            $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	            curl_close($ch);
	        } else {
	            $responseCodePattern = '/^HTTP\/\d+\.\d+\s+(\d+)/';
	            $fp = @fsockopen($whmcsurl, 80, $errno, $errstr, 5);
	            if ($fp) {
	                $newlinefeed = "\r\n";
	                $header = "POST ".$whmcsurl . $verifyfilepath . " HTTP/1.0" . $newlinefeed;
	                $header .= "Host: ".$whmcsurl . $newlinefeed;
	                $header .= "Content-type: application/x-www-form-urlencoded" . $newlinefeed;
	                $header .= "Content-length: ".@strlen($query_string) . $newlinefeed;
	                $header .= "Connection: close" . $newlinefeed . $newlinefeed;
	                $header .= $query_string;
	                $data = $line = '';
	                @stream_set_timeout($fp, 20);
	                @fputs($fp, $header);
	                $status = @socket_get_status($fp);
	                while (!@feof($fp)&&$status) {
	                    $line = @fgets($fp, 1024);
	                    $patternMatches = array();
	                    if (!$responseCode
	                        && preg_match($responseCodePattern, trim($line), $patternMatches)
	                    ) {
	                        $responseCode = (empty($patternMatches[1])) ? 0 : $patternMatches[1];
	                    }
	                    $data .= $line;
	                    $status = @socket_get_status($fp);
	                }
	                @fclose ($fp);
	            }
	        }
	        if ($responseCode != 200) {
	            $localexpiry = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - ($localkeydays + $allowcheckfaildays), date("Y")));
	            if ($originalcheckdate > $localexpiry) {
	                $results = $localkeyresults;
	            } else {
	                $results = array();
	                $results['status'] = "Invalid";
	                $results['description'] = "Remote Check Failed";
	                return $results;
	            }
	        } else {
	            preg_match_all('/<(.*?)>([^<]+)<\/\\1>/i', $data, $matches);
	            $results = array();
	            foreach ($matches[1] AS $k=>$v) {
	                $results[$v] = $matches[2][$k];
	            }
	        }
	        if (!is_array($results)) {
	            die("Invalid License Server Response");
	        }
	        if (@$results['md5hash']) {
	            if ($results['md5hash'] != md5($licensing_secret_key . $check_token)) {
	                $results['status'] = "Invalid";
	                $results['description'] = "MD5 Checksum Verification Failed";
	                return $results;
	            }
	        }
	        if ($results['status'] == "Active") {
	            $results['checkdate'] = $checkdate;
	            $data_encoded = serialize($results);
	            $data_encoded = base64_encode($data_encoded);
	            $data_encoded = md5($checkdate . $licensing_secret_key) . $data_encoded;
	            $data_encoded = strrev($data_encoded);
	            $data_encoded = $data_encoded . md5($data_encoded . $licensing_secret_key);
	            $data_encoded = wordwrap($data_encoded, 80, "\n", true);
	            $results['localkey'] = $data_encoded;
	        }
	        $results['remotecheck'] = true;
	    }
	    unset($postfields,$data,$matches,$whmcsurl,$licensing_secret_key,$checkdate,$usersip,$localkeydays,$allowcheckfaildays,$md5hash);
	    return $results;
	}

	function saveOPkey($license,$secret,$localkey,$tipo=""){
		global $mysqli;
		$datos = Sactivo();
		if($datos[0]==0){
			if ($guardar = $mysqli->prepare("INSERT INTO configuracion (opcion, valor) VALUES (?,?)")) {
				$guardar->bind_param('ss', $opcion, $valor);
				$opcion = 	'LICENSE';
				$valor 	=	$license;
		    	$guardar->execute();
				$opcion = 	'SECRET_KEY';
				$valor 	=	$secret;
		    	$guardar->execute();
				$opcion = 	'LOCAL_KEY';
				$valor 	=	$localkey;
		    	$guardar->execute();
			}
		}else{
			$vence 	= 	date ('Y-m-j H:i:s',strtotime('+7 day',strtotime($tipo)));
			$hoy 	=	date('Y-m-j H:i:s');
			if($hoy >= $vence){
				if ($guardar = $mysqli->prepare(" UPDATE configuracion SET valor = ?, fecha = ? WHERE opcion = ?")) {
					$guardar->bind_param('sss', $valor, $fecha,$opcion);
					$opcion = 	'LOCAL_KEY';
					$valor 	=	$localkey;
					$fecha 	=	date('Y-m-d H:i:s');
					$guardar->execute();
				}
			}


		}
	}

	function editFileForm($data){
		$formulario = <<<EOT
			<form  class="form-horizontal formulario" enctype="multipart/form-data" action="#">
				<div class="form-group">
					<div class="col-lg-12">
						<input type="hidden" name="id" value="{$data['id']}" />
						<input type="hidden" name="anterior" value="{$data['anterior']}" />
						<input type="text" name="nombre" value="{$data['nombre']}" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-12">
						<input class="file-styled" name="archivo" type="file" id="imagen" />
					</div>
				</div>
			</form>
EOT;
		return $formulario;
	}

	function modal_usuarios($a){
		$divmodal = "";
		$tipo= $a;
		//Cualquier cambio aqui debe hacerce en CORE id VerArchivos
		$divmodal="
		<div id='Formdocentes' class='modal fade'>
					<div class='modal-dialog'>
						<div class='modal-content'>
							<div class='modal-header'>
								<button type='button' class='close' data-dismiss='modal'>&times;</button>
								<h5 class='modal-title'>Nuevo Usuario</h5>
							</div>
							<form action='/nuevoUsuario' id='formuser' method='post' autocomplete='off'>
								<div class='modal-body'>
									<div class='form-group'>
										<div class='row'>
											<div class='col-sm-6'>
												<label>Nombres</label>
												<input type='text' placeholder='Nombres' name='nombre' class='form-control' required>
											</div>
											<div class='col-sm-6'>
												<label>Apellidos</label>
												<input type='text' placeholder='Apellidos' name='apellido' class='form-control' required>
											</div>
										</div>
									</div>
									<div class='form-group'>
										<div class='row'>
											<div class='col-sm-8'>
												<label>Email</label>
												<input type='email' placeholder='info@sae.com' class='form-control' name='correo' required>
											</div>
											<div class='col-sm-4'>
												<label>Fecha de Nacimiento:</label>
												<input type='date' class='form-control' name='fecha' required>
											</div>
										</div>
									</div>
									<div class='form-group'>

										<div class='row'>
											<div class='col-sm-6'>
												<label class='display-block text-semibold'>Genero</label>
												<label class='radio-inline'>
												<input type='radio' name='genero' class='styled' checked='checked' value='M' required> Masculino
												</label>
												<label class='radio-inline'>
												<input type='radio' name='genero' value='F' class='styled' required> Femenino
												</label>
											</div>
											<div class='col-sm-6'>
												<label class='display-block text-semibold'>Tipo</label>
												<select class='form-control' data-width='100%' name='tipo' required>
													<option value='1'>Maestro</option>
													<option value='3'>Secretaría</option>
													<option value='4'>Contabilidad</option>
													<option value='9'>Administrador</option>
												</select>
											</div>
										</div>
									</div>	
								</div>
								<div class='modal-footer'>
									<button type='button' class='btn btn-link' data-dismiss='modal'>Cerrar</button>
									<button type='submit' class='btn btn-primary'>Guardar</button>
								</div>
							</form>
						</div>
					</div>
				</div>";
					
		return $divmodal;
	}
  
  
  
	function cuadro_zona($codigo="",$datos=""){
	  	global $mysqli, $sid;
	  	if($codigo == ""){
	  		$datos = "<h3>Seleccione docente a verificar.</h3>";
		}
  		return $datos;
  	}
  
  	function list_jornadas(){
	  	global $mysqli, $sid;
	  	$res = "";
	  	if($jornada = db("SELECT valor FROM configuracion WHERE opcion LIKE 'JORNADAS' and sid LIKE '{$sid}' LIMIT 0,1",$mysqli)){
	  		$data = json_decode($jornada[0]['valor'], TRUE);
		    foreach ($data as $valor) {
		    	$res .= input_textConf ("jornada[]",$valor);
		    }
	  	}else{
	  		$res = input_textConf ("jornada[]",$value = "");
	  	}
  		return $res;
  	}

	function list_niveles($tipo=""){
		global $mysqli;
		$res="";
		$sid = 1;
		$jornada = db("SELECT valor FROM configuracion WHERE opcion LIKE 'NIVELES' and sid LIKE '{$sid}' LIMIT 0,1",$mysqli);


		if($jornada){
			$data = json_decode($jornada[0]['valor'], TRUE);
	    foreach ($data as $valor) {
	    	$res .= input_textConf ("nivel[]",$valor);
	    }
		}else{
			$res = input_textConf ("nivel[]",$value = "");
		}
		return $res;
	}

	function list_secciones (){
		global $mysqli, $sid;
		if($jornada = db("SELECT valor FROM configuracion WHERE opcion LIKE 'SECCIONES' and sid LIKE '{$sid}' LIMIT 0,1",$mysqli)){
			$value = $jornada[0]['valor'];
		}else{
			$value = "";
		}
		$html = "<div class='input-group'>
					<input name='seccion' type='text' class='form-control' value='{$value}' />
					<span style='cursor: pointer;' class='btn_seccion input-group-addon bg-success'>
						<i class='icon-add'></i>
					</span>
				</div>";
		return $html;
	}

	function input_textConf ($tipo,$value = ""){
	  	$html = "
  			<div class='field_wrapper'>
				<div class='input-group'>
					<input name='{$tipo}' type='text' class='form-control' value='{$value}' />
					<span inp='{$tipo}' style='cursor: pointer;' class='input-group-addon bg-success add_button'>
						<i class='icon-add'></i>
					</span>
					<span style='cursor: pointer;' class='input-group-addon bg-pink remove_button'>
						<i class='icon-subtract'></i>
					</span>
				</div>
			</div>";
		return $html;
	}

	function list_carreras(){
		global $mysqli;
		$res="";
		$sid = 1;
		$jornada = db("SELECT valor FROM configuracion WHERE opcion LIKE 'CARRERAS' and sid LIKE '{$sid}' LIMIT 0,1",$mysqli);
		if($jornada){
			$data = json_decode($jornada[0]['valor'], TRUE);
	    foreach ($data as $valor) {
	    	$res .= input_textConf ("carrera[]",$valor);
	    }
		}else{
			$res = input_textConf ("carrera[]",$value = "");
		}
		return $res;
	}

