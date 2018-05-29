<?php
include("funciones.php");
//Multi-proceso
//Variable de control Global
	if(isset($_GET['l'])){
		$proceso = $_GET['l'];
	}else{
		$proceso = "";
	}
    $data = array();
	//Procesos
	if($proceso == "upload_file"){
		if (!empty($_FILES)) {
      $codigo = $_POST['codigo'];
			if(!is_dir("files/"))
		    mkdir("files/", 0777);	
		  	$tempFile = $_FILES['file']['tmp_name']; 
        $namefile = $_FILES['file']['name']; 
		  	$targetFile = basename(urlencode(uniqid().$_FILES['file']['name']));
		  	$saveFile = 'files/'.$targetFile;
		  	if(move_uploaded_file($tempFile, $saveFile)){
          conectar();
            //Guardamos registro en base
            $idfile = uniqid();
            $sql = "INSERT into archivos (idfile, nombre, archivo, codocente) VALUES ('$idfile', '$namefile',  '$targetFile', '$codigo')";
            if(mysqli_query($mysqli, $sql)){
              echo "Se guardo bien";
            }else{
              echo "algo no anda bien ".mysqli_error($mysqli);
            }
          cerrar_conex();
        }else{
          echo "Error al guardar";
        }

		}
		exit;
	}





  elseif ($proceso == "SaveIndividualCrono") {
    $curso    =   $_POST['curso'];
    $docente  =   $_POST['docente'];
    $seccion  =   $_POST['seccion'];
    $unidad   =   $_POST['unidad'];
    $id       =   $_POST['id'];
    $estado   =   $_POST['estado'];
    $key      =   uniqid();
	  $crono    =   json_encode($_POST);
    $fecha    =   date('Y-m-d H:i:s');
    conectar();
  if($id==0){
    if ($guardar = $mysqli->prepare("INSERT into crono_key (curso, seccion, unidad, estado, llave) VALUES (?,?,?,?,?)")) {
        $guardar->bind_param('ssiis', $curso, $seccion, $unidad, $estado, $key);
        $guardar->execute();
        $idK = $mysqli->insert_id;
        $data['idK'] = $idK;
    }
    if(isset($idK)){
      if ($guardar = $mysqli->prepare(" INSERT into crono_data (llave, data) VALUES (?,?) ")) {
          $guardar->bind_param('ss', $key, $crono);
          $guardar->execute();
          $idD = $mysqli->insert_id;
          $data['idD'] = $idD;
      }
      if(isset($idD)){
        $data['code']   =   "1";
        $data['alert']  =   "success";
        $data['msg']    =   "Cronograma Guardado.";
      }else{
        $data['code']   =   "0";
        $data['alert']  =   "error";
        $data['msg']    =   "No fue posible crear la DATA para este cronograma. Por favor copie el siguiente numero y entregue al administrador de sistema. \n ID error:".$idK." \n Code: ".mysqli_error($mysqli);
      }

    }else{
      $data['code']     =   "0";
      $data['alert']    =   "error";
      $data['msg']      =   "No fue posible crear el KEY para este cronograma. \n Error: ".mysqli_error($mysqli);
    }

  }else{
    $data['idD'] = $id;
    //Si id esta definido, actualizamos registro
      if ($guardar = $mysqli->prepare(" UPDATE crono_data SET data = ? WHERE id = ?")) {
        $guardar->bind_param('si', $crono, $id);
        $guardar->execute();
        $data['code']  = "2";
        $data['alert']  = "success";
        $data['msg'] = "Se actualizo correctamente. ";
      }else{
      $data['code']  = "0";
      $data['alert']  = "error";
      $data['msg'] = "No se logro actualizar este registro. "; 
    }
  }


    cerrar_conex();

print json_encode($data);
    

  exit;












	}elseif ($proceso == "login"){

		if(isset($_POST['codigo']) && isset($_POST['pass'])){
			conectar();
        if($datos = db("select codigo,password,tipo FROM usuarios WHERE codigo = '{$_POST['codigo']}' and password = '".base64_encode($_POST['pass'])."' LIMIT 0, 1",$mysqli)){
            session_start();
            $_SESSION['codigo']   =   $_POST['codigo'];
            $_SESSION['tipo']     =   $datos[0]['tipo'];
            $data['id']     =   1;
            $data['codigo'] =   $_SESSION['codigo'];
            $data['tipo']   =   $_SESSION['tipo'];
            $data['error']  = "Sin error, acceso correcto.";
            datosG($_SESSION['codigo'], $_SESSION['tipo']);
    			}else{
            $data['id']     =   0;
            $data['error']  =   "Error en codigo o contraseña.";
          }
      cerrar_conex();
      print json_encode($data);
		}else{
			$msg = "Ups, estas intentando hacer trampa, por favor inicia sesión.";
		}
		exit;



	}elseif ($proceso == "logout") {
    salir();
  }

elseif ($proceso == "verarchivos") {
  //Cambios de tabla en funcion archivos
    $codigo = $_POST['codigo'];
    $data['html'] = tabla_archivos($codigo);
    print json_encode($data);
    cerrar_conex();
exit;
}

elseif ($proceso == "borrar_archivo") {
    conectar();
    $archivo = db("select archivo FROM archivos where id = '{$_POST['id']}' LIMIT 0,1",$mysqli);
    $nombre_fichero = './files/'.$archivo[0]['archivo'];

  if(file_exists($nombre_fichero)){
    if (unlink($nombre_fichero)){
       $sql = 'DELETE FROM archivos WHERE id = '.$_POST['id'];
       if (mysqli_query($mysqli, $sql)) {
          $data['msg'] = "Record deleted successfully";
       } else {
          $data['msg'] = "Error deleting record: " . mysqli_error($mysqli);
       }
      cerrar_conex();
    }


  }else {
     $data['msg'] = "algo no esta bien el archivo no existe, se borrara registro\n";

       $sql = 'DELETE FROM archivos WHERE id = '.$_POST['id'];
       if (mysqli_query($mysqli, $sql)) {
          $data['msg'] .= "Record deleted successfully";
       } else {
          $data['msg'] .= "Error deleting record: " . mysqli_error($mysqli);
       }

  }   
  print json_encode($data);


 
exit;
}

elseif($proceso == "ver_DOC"){
  $url = $_POST['url'];
  $data['html'] = verDOC($url);
  print json_encode($data);
exit;
}


elseif($proceso == "refreshMisCursos"){


  //Cambios de tabla en funcion archivos
    $codigo = $_POST['codigo'];
    $data['html'] = vermiscursos($codigo);
    print json_encode($data);
    cerrar_conex();



  exit;
}

elseif($proceso == "bCurso"){
    $grado    =   $_POST['grado'];
    $nivel    =   $_POST['nivel'];
    $carrera  =   $_POST['carrera'];
    $jornada  =   $_POST['jornada'];
    $seccion  =   $_POST['seccion'];
    $docente  =   $_POST['docente'];
    conectar();
    $t="";
      if($grado !="" and $nivel !="" and $jornada !=""){
        $data['html'] = cursos($grado,$nivel,$carrera,$jornada,$t,$docente,$seccion);
        $data['seccion'] = $seccion;
        $data['docente'] = $docente;

      }else{
        $data['error']  = "Algo raro pasa aquí, se esta intentando saltar las politicas de seguridad.";
      }
    cerrar_conex();
    print json_encode($data);

  exit;
}

elseif($proceso == "Asignarme"){
    $data     =   array(); 
    $docente  =   $_POST['docente'];
    $seccion  =   $_POST['seccion'];
    $codigo   =   $_POST['codigo'];
    $ciclo    =   date('Y');
    if($docente !="" and $seccion !="" and $codigo !=""){
    conectar();
    $buscar_asignacion = buscar_asignacion($codigo,$docente,$seccion);
      if($buscar_asignacion){
          $data['error_code']   =     1;
          $data['error']        =     "Ya tiene asignado este curso, no es posible asignar el mismo curso más de una vez.";
      }else{
          $guardar = saveAsignacion($codigo,$docente,$seccion);
          if($guardar == 0){
            $data['error_code']   =     1;
            $data['error']        =     "Algo raro pasó cuando se intentaba guardar está asignación, por favor repórtelo al departamento de desarrollo. \n Data: ".mysqli_error($mysqli);
          }else{
            $data['error_code']   =     0;
            $data['error']        =     "Asignación Correcta, numero de seguimiento ".$guardar;          
          }
      }
    }else{
      $data['error_code']   =     1;
      $data['error']  = "Algo raro pasa aquí, se esta intentando saltar las politicas de seguridad.";
    }
    cerrar_conex();
    print json_encode($data);
  exit;
}

elseif($proceso == "Desasignarme"){
    $data     =   array(); 
    $docente  =   $_POST['docente'];
    $seccion  =   $_POST['seccion'];
    $codigo   =   $_POST['codigo'];
    $ciclo    =   date('Y');
    if($docente !="" and $seccion !="" and $codigo !=""){
    conectar();
    $buscar_asignacion = buscar_asignacion($codigo,$docente,$seccion);
      if($buscar_asignacion){
          $borrar = borrarAsignacion($docente,$codigo,$seccion);
          if($borrar){
            $data['error_code']   =     0;
            $data['error']        =     "Se ha borrado el curso correctamente.";
          }else{
            $data['error_code']   =     1;
            $data['error']        =     "No fue posible eliminar la asignación.";           
          }


      }else{
          $data['error_code']   =     1;
          $data['error']        =     "No puede eliminar un curso que no tiene asignado, este debe ser un error.";
      }
    }else{
      $data['error_code']   =     1;
      $data['error']  = "Algo raro pasa aquí, se esta intentando saltar las politicas de seguridad.";
    }
    cerrar_conex();
    print json_encode($data);
  exit;
}


elseif($proceso == "cronoForm"){
    $datos    =   $_POST['docente'].'|';
    $datos    .=   $_POST['curso'].'|';
    $datos    .=   $_POST['seccion'].'|';
    $datos    .=   $_POST['unidad'];
    $data     =   array(); 
    conectar();

      



      $data['html']   =   cronograma($datos);




    cerrar_conex();
    print json_encode($data);
  exit;
}






elseif($proceso == "borrarCrono"){
  $idK  =   $_POST['idK'];
  $idD  =   $_POST['idD'];



  exit;
}




elseif($proceso == "clonar"){
  $codigo   =   $_POST['codigo'];
  $tipo     =   $_POST['tipo'];
    

      $data['html']   =   vermiscursos($codigo,$tipo);

    


  print json_encode($data);
  exit;
}




//Actualiza la tabla de docentes
elseif($proceso == "verdocentes"){
$nombre=$_POST['name'];
$valor=$_POST['value'];
$pk=$_POST['pk'];
conectar();
$sql ="UPDATE `usuarios` SET {$nombre} = '{$valor}' where `id`='{$pk}'";
if(mysqli_query($mysqli, $sql)){
  echo "0";
}else{
  echo "1";
}
cerrar_conex();
exit;
}



//desactivar docentes
elseif($proceso == "desactivar_docente"){
$pk=$_POST['usuario'];
conectar();
if ($usuarios = db("select estado from usuarios where id = {$pk}", $mysqli)) {
    if ($usuarios[0]['estado']== 1) {
      $sql ="UPDATE `usuarios` SET estado = 0 where `id`='{$pk}'";
        if(mysqli_query($mysqli, $sql)){
          $info['error']=false;
        }else{
          $info['error']=true;
        }
    }else{
      $sql ="UPDATE `usuarios` SET estado = 1 where `id`='{$pk}'";
        if(mysqli_query($mysqli, $sql)){
          $info['error']=false;
      }else{
          $info['error']=true;
      }
    }
}else{
    $info['error']="SN";
} 
echo  json_encode($info);
cerrar_conex();
exit;
}


//eliminar docentes
elseif($proceso == "eliminar_docente"){
$pk=$_POST['usuario'];
conectar();
if($users= db("delete from `usuarios` where id ={$pk}",$mysqli)){
  $info['error']= false;
}else{
  $info['error']= true;
}
cerrar_conex();
echo json_encode($info);
exit;
}


//bloquear docentes
elseif($proceso == "bloquear_docentes"){
conectar();
$peticion="UPDATE usuarios set password=? where tipo= ?";
$cambios=$mysqli-> prepare($peticion);
$clave= base64_encode(date('Ymd'));
$tipo=1;
$cambios-> bind_param("si",$clave,$tipo);

if ($cambios-> execute()) {
  $info['error']=false;
}else{
  $info['error']=true;
}
cerrar_conex();

echo json_encode($info);
exit;
}



//Guardar usuario
elseif($proceso == "guardar_user"){
$nom=$_POST['nombre'];
$ap=$_POST['apellido'];
$correo=$_POST['correo'];
$fecha=$_POST['fecha'];
$genero=$_POST['genero'];
$tipo= $_POST['tipo'];
$estado=1;
$pass= base64_encode(substr( md5(microtime()), 1, 8));
if($tipo==1){
  $codigo='M'.date('His');
}elseif($tipo==3){
  $codigo='S'.date('His');
}elseif($tipo==4){
  $codigo='C'.date('His');
}elseif($tipo==9){
  $codigo='A'.date('His');
}else{
  $codigo=date('His');
}
conectar();
    if ($guardar = $mysqli->prepare("INSERT INTO `usuarios` (`codigo`, `nombre`, `apellido`, `genero`, `correo`, `password`, `tipo`, `estado`, `nacimiento`) VALUES (?,?,?,?,?,?,?,?,?)")){
        $guardar->bind_param('ssssssiis', $codigo,$nom,$ap,$genero,$correo,$pass,$tipo,$estado,$fecha);
        $guardar->execute();
        $info['error']=false;  
        }else{
        $info['error']=true;
        }
cerrar_conex();
echo json_encode($info);
exit;
}



elseif($proceso == "RefreshUser"){

    $tipo   = $_POST['tipo'];
    $tabla  = $_POST['tabla'];

    $data['html'] = tabla_usuarios($tabla,$tipo);

    print json_encode($data);
  exit;
}















elseif($proceso == "conf"){
    $data     =   array(); 

    $data['opciones']   =   $_POST['opciones'];
    $data['minimo']     =   $_POST['minimo'];
    $data['maximo']     =   $_POST['maximo'];
    $datos              =   $_POST['titulo'];
    


    for ($i = 0; $i < count($datos); $i++) {
      $data['titulos'][] = $datos[$i];
    }



    print json_encode($data);
  exit;
}





