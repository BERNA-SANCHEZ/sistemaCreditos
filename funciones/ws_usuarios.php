<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors",0);
require_once("classSQL.php");

$accion =$_REQUEST['accion'];

switch ($accion) {
	case 'nuevo':
			nuevoUsuario();
		break;
	case 'editar':
			editarUsuario();
		break;
	case 'eliminar':
			eliminarUsuario();
		break;
	case 'mostrar':
			mostrarUsuarios();
		break;
	case 'probarPermisos':
			probarPermisos();
		break;
	case 'consultarPermisos':
			consultarPermisos();
		break;
	case 'cambiarPass':
			cambiarPass();
		break;
	case 'sucursales':
			mostrarSucursales();
		break;	
	case 'restringir_Horario':
			restringir_Horario();
		break;	

	
}


function nuevoUsuario()
{
	try
	{	
		$conexion = new conexion();
		$clave=encriptar_pwd($_REQUEST['usuario'], $_REQUEST['clave']);

		$buscarUser = $conexion->sql("SELECT * FROM usuarios WHERE usuario = '{$_REQUEST['usuario']}'  AND clave = '{$clave}' ");
		if (count($buscarUser) == 0) {


			if ($_REQUEST["newRestringirHorario"] == 1) {
				$_REQUEST["newhorainicio"] = '';
				$_REQUEST["newhorafin"] = '';
			}

			$sql = "INSERT INTO usuarios (idtipousuario,idsucursal,usuario,clave,activado,aleatorio,
						ultimaFechaIngreso,nombre,restringirhorario, horainicio, horafin, accesocaja) 
						VALUES ('".$_REQUEST['idtipousuario']."','".$_REQUEST['idsucursal']."',
						'".$_REQUEST['usuario']."','".$clave."','".$_REQUEST["activado"]."',
						'111222',now(),'".utf8_decode($_REQUEST['nombre'])."',
						'".$_REQUEST["newRestringirHorario"]."','".$_REQUEST["newhorainicio"]."',
						'".$_REQUEST["newhorafin"]."','".$_REQUEST["newaccesocaja"]."')";

						
			$respuesta = $conexion->sqlOperacion($sql);

			if ( $_REQUEST["newaccesocaja"] == 1) {

				$nuevaCaja = $conexion->sqlOperacion(" INSERT INTO cajas (descripcion, estado, idsucursal, idusuario) 
				VALUES ( 'Caja Cobrador', 0, 3, '".$respuesta["ultimoId"]."') ");

				$conexion->sqlOperacion("INSERT INTO cajasaperturas (idcaja, idusuarioinicio, 
				fechainicio, efectivoinicial, ventaefectivo, retiros, anulaciones, totalcierre, 
				ingresocaja, diferencia, idusuariocerro, fechacierre, ingresos, prestamos, pagoscapital) 
				VALUES ('".$nuevaCaja["ultimoId"]."', '".$respuesta["ultimoId"]."', 
				now(), '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', '0.00', '0.00')");

			}


		}else{
			$respuesta['resultado']=false;
			$respuesta['mensaje']="ya existe un usuario con las credenciales ingresadas, pruebe con otros datos";
		}
	}
	catch (Exception $e)
	{
		$respuesta['resultado']=false;
		$respuesta['mensaje']=$e;
	}

	echo json_encode( $respuesta );
	$conexion->respuestaTrans("COMMIT");	
}

function editarUsuario()
{
	try
	{	
		$conexion = new conexion();
		$clave=encriptar_pwd($_REQUEST['usuario'], $_REQUEST['clave']);
		$buscarUser = $conexion->sql("SELECT * FROM usuarios WHERE usuario = '{$_REQUEST['usuario']}'  AND clave = '{$clave}' AND id <> ".$_REQUEST['idusuario']);
		if (count($buscarUser) == 0) {

			if ($_REQUEST["editRestringirHorario"] == 1) {
				$_REQUEST["edithorainicio"] = '';
				$_REQUEST["edithorafin"] = '';
			}


			$sql = "UPDATE usuarios SET idtipousuario='".$_REQUEST['id_tipousuario']."', 
			usuario='".$_REQUEST['usuario']."',clave='".$clave."',nombre='".utf8_decode($_REQUEST['nombre'])."', 
			activado='".$_REQUEST["activado"]."',
			restringirhorario='".$_REQUEST["editRestringirHorario"]."', horainicio='".$_REQUEST["edithorainicio"]."', 
			horafin='".$_REQUEST["edithorafin"]."' , accesocaja = '".$_REQUEST["editaccesocaja"]."'
			WHERE id = ".$_REQUEST['idusuario'];
			$respuesta = $conexion->sqlOperacion($sql);


			if ( $_REQUEST["editaccesocaja"] == 1 ) {

				$buscarCaja = $conexion->sql("SELECT * FROM cajas WHERE idusuario = '{$_REQUEST['idusuario']}' ");

				if (count($buscarCaja) == 0) {

					$nuevaCaja = $conexion->sqlOperacion(" INSERT INTO cajas (descripcion, estado, idsucursal, idusuario) 
					VALUES ( 'Caja Cobrador', 0, 3, '".$_REQUEST['idusuario']."') ");
	
					$conexion->sqlOperacion("INSERT INTO cajasaperturas (idcaja, idusuarioinicio, 
					fechainicio, efectivoinicial, ventaefectivo, retiros, anulaciones, totalcierre, 
					ingresocaja, diferencia, idusuariocerro, fechacierre, ingresos, prestamos, pagoscapital) 
					VALUES ('".$nuevaCaja["ultimoId"]."', '".$_REQUEST['idusuario']."', 
					now(), '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', '0.00', '0.00')");

				}

			}


		}else{
			$respuesta['resultado']=false;
			$respuesta['mensaje']="ya existe un usuario con las credenciales ingresadas, pruebe con otros datos";
		}
	}
	catch (Exception $e)
	{
		$respuesta['resultado']=false;
		$respuesta['mensaje']=$e;
	}

	echo json_encode( $respuesta );
	$conexion->respuestaTrans("COMMIT");
}

function cambiarPass()
{
	try
	{	
		$conexion = new conexion();
		$clave=encriptar_pwd($_REQUEST['usuario'], $_REQUEST['clave']);
		$sql = "UPDATE usuarios SET usuario='".$_REQUEST['usuario']."',clave='".$clave."' WHERE id = ".$_SESSION["idusuario"];

		$respuesta = $conexion->sqlOperacion($sql);
	}
	catch (Exception $e)
	{
		$respuesta['resultado']=false;
		$respuesta['mensaje']=$e;
	}

	echo json_encode( $respuesta );
	$conexion->respuestaTrans("COMMIT");
}

function eliminarUsuario()
{
	try
	{	
		$conexion = new conexion();
		$sql = "DELETE FROM usuarios WHERE id=".$_REQUEST['idusuario'];

		$respuesta = $conexion->sqlOperacion($sql);
	}
	catch (Exception $e)
	{
		$respuesta['resultado']=false;
		$respuesta['mensaje']=$e;
	}

	echo json_encode( $respuesta );
	$conexion->respuestaTrans("COMMIT");
}


function mostrarUsuarios()
{
	try
	{	
		$conexion = new conexion();
		


		if ($_REQUEST['id']) {
			$sql="SELECT u.id, u.idtipousuario,u.idsucursal,u.accesocaja, t.descripcion as tipousuario, u.usuario, 
				u.activado, u.nombre,u.restringirhorario,u.horainicio,u.horafin, s.nombre as sucursal
				FROM usuarios u
				INNER JOIN tiposusuarios t ON t.id = u.idtipousuario 
				INNER JOIN sucursales s ON s.id = u.idsucursal 
				WHERE u.id = {$_REQUEST['id']}  AND u.Id > 1
				ORDER BY u.nombre ";			
		}else{
			$sql="SELECT u.id, u.idtipousuario, u.idsucursal , t.descripcion as tipousuario, u.usuario, u.activado, u.nombre, s.nombre as sucursal
				FROM usuarios u
				INNER JOIN tiposusuarios t ON t.id = u.idtipousuario
				INNER JOIN sucursales s ON s.id = u.idsucursal 
				WHERE u.Id > 1 
				ORDER BY u.nombre ";			
		}

		$result = $conexion->sql($sql);
		$respuesta["registros"] = $result;
		$respuesta["mensaje"] = "Datos consultados Exitosamente";
		$respuesta["resultado"] = true;

		
	}
	catch (Exception $e)
	{
		$respuesta['registros']=array();
		$respuesta['resultado']=false;
		$respuesta['mensaje']=$e;
	}

	echo json_encode( $respuesta );
	$conexion->respuestaTrans("COMMIT");
}



function mostrarSucursales()
{
	try
	{	
		$conexion = new conexion();
		if ($_REQUEST['id']) {
			$sql="SELECT * FROM sucursales WHERE id = ".$_REQUEST["id"];			
		}else{
			$sql="SELECT * FROM sucursales ";
		}

		$result = $conexion->sql($sql);
		$respuesta["registros"] = $result;
		$respuesta["mensaje"] = "Datos consultados Exitosamente";
		$respuesta["resultado"] = true;

		
	}
	catch (Exception $e)
	{
		$respuesta['registros']=array();
		$respuesta['resultado']=false;
		$respuesta['mensaje']=$e;
	}

	echo json_encode( $respuesta );
	$conexion->respuestaTrans("COMMIT");
}



function probarPermisos()
{
	$conexion = new conexion();
	$respuesta = $conexion->permisos($_SESSION['idtipousuario'],"8","acceso");
	var_dump($respuesta);
}


function consultarPermisos()
{
	try
	{	
		$conexion = new conexion();	
		$sql="SELECT * FROM permisos WHERE idtipousuario = {$_SESSION['idtipousuario']} AND idModulo = {$_REQUEST['idmodulo']} ";

		$result = $conexion->sql($sql);
		$respuesta["registros"] = $result;
		$respuesta["mensaje"] = "Datos consultados Exitosamente";
		$respuesta["resultado"] = true;

		
	}
	catch (Exception $e)
	{
		$respuesta['registros']=array();
		$respuesta['resultado']=false;
		$respuesta['mensaje']=$e;
	}

	echo json_encode( $respuesta );
	$conexion->respuestaTrans("COMMIT");
}


function encriptar_pwd($u,$pw) {
	$username = stripslashes(mysql_real_escape_string($u));
	$password= sha1(strtolower($username).strip_tags(stripslashes($pw)));

  	return $password;
}



function restringir_Horario(){
	try
	{	
		$conexion = new conexion();			
		
		$query="SELECT * FROM usuarios WHERE id = ".$_SESSION["idusuario"];
		$resConsulta =  $conexion->sql($query);	
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



		$respuesta['resultado']=true;
		$respuesta['accesar']=$accesar;


		
	}
	catch (Exception $e)
	{
		$respuesta['registros']=array();
		$respuesta['resultado']=false;
		$respuesta['mensaje']=$e;
	}

	echo json_encode( $respuesta );
	$conexion->respuestaTrans("COMMIT");
}


?>