<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors",0);
require_once("classSQL.php");
$conexion = new conexion();
try
{		
	
	$username = stripslashes(mysql_real_escape_string($_REQUEST['usuario']));
	$password= sha1(strtolower($username).strip_tags(stripslashes($_REQUEST['clave'])));

	$query="SELECT u.id, u.idtipousuario, u.usuario, u.clave, u.activado, u.nombre, 
	u.aleatorio, u.ultimafechaingreso,u.restringirhorario,u.horainicio,u.horafin, tp.descripcion as tipousuario			
			FROM usuarios u
			INNER JOIN tiposusuarios tp ON tp.id = u.idtipousuario
			WHERE u.usuario = '{$_REQUEST['usuario']}' AND u.clave = '{$password}' AND u.activado = 1";
	$resConsulta =  $conexion->sql($query);
	$_SESSION = array();




	$accesar = 0;


	function hourIsBetween($from, $to, $input) {
		$dateFrom = DateTime::createFromFormat('!H:i', $from);
		$dateTo = DateTime::createFromFormat('!H:i', $to);
		$dateInput = DateTime::createFromFormat('!H:i', $input);
		if ($dateFrom > $dateTo) $dateTo->modify('+1 day');
		return ($dateFrom <= $dateInput && $dateInput <= $dateTo) || ($dateFrom <= $dateInput->modify('+1 day') && $dateInput <= $dateTo);
	}


	$hoy = date("H:i:s");  


	if ($resConsulta[0]['restringirhorario'] == 1) {
		$accesar = 1;
	}else{
		if (hourIsBetween(substr($resConsulta[0]['horainicio'],0,5), substr($resConsulta[0]['horafin'],0,5), substr($hoy,0,5))) {			
			$accesar = 1;
		}
	}



	if ( count($resConsulta) > 0 && $accesar == 1) {
		
		$_SESSION['idusuario'] = $resConsulta[0]['id'];
		$_SESSION['usuario'] = $resConsulta[0]['usuario'];
		$_SESSION['nombre'] = $resConsulta[0]['nombre'];
		$_SESSION['idtipousuario'] = $resConsulta[0]['idtipousuario'];
		$_SESSION['tipousuario'] = $resConsulta[0]['tipousuario'];

		$fechaActual=date("Y-m-d H:i:s");
		mysql_query("UPDATE  usuarios SET  ultimafechaingreso = NOW() WHERE  id =".$resConsulta[0]['id']);
		$respuesta['mensaje']="Bienvenidos ";
		$respuesta['resultado']=true;
		
	}else{
		$respuesta['mensaje']="Verifique usuario o contraseña";
		$respuesta['resultado']=false;
	}


}
catch (Exception $e)
{
	$respuesta['registros']=array();
	$respuesta['resultado']=false;
}

echo json_encode( $respuesta );
$conexion->respuestaTrans("COMMIT");


?>