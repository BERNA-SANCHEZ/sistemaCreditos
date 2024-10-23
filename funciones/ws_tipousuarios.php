<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors",0);
require_once("classSQL.php");

$accion =$_REQUEST['accion'];

switch ($accion) {
	case 'mostrarTU':
			mostrarTipoUsuarios();
		break;
	case 'mostrarPU':
			mostrarPermisosUsuarios();
		break;
	case 'actializarPermiso':
			actializarPermiso();
		break;
	case 'mostrarPM':
			mostrarPermisosMeseros();
		break;
	case 'editarPermiso':
			editarPermisoMeseros();
		break;
	
	
}


function mostrarTipoUsuarios()
{
	try
	{	
		$conexion = new conexion();
		if ($_REQUEST['id']) {
			$sql="SELECT * FROM tiposusuarios  WHERE id = {$_REQUEST['id']}";			
		}else{
			$sql="SELECT * FROM tiposusuarios";			
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



function mostrarPermisosUsuarios()
{
	try
	{	
		$conexion = new conexion();
		$regModulos = $conexion->sql("SELECT * FROM modulos WHERE id NOT IN (SELECT idModulo FROM permisos WHERE idTipoUsuario = {$_REQUEST['id']} ) ");
		foreach ($regModulos as $key => $value) {
			mysql_query("INSERT INTO permisos (idTipoUsuario, idModulo, acceso, crear, modificar, eliminar, consultar) VALUES ( '{$_REQUEST['id']}', '{$value['id']}', '0', '0', '0', '0', '0');");
		}

		$sql="SELECT p.Id, p.idTipoUsuario, p.idModulo, 
		IF(p.acceso = 1,'checked', '') as acceso,
		IF(p.crear = 1,'checked', '') as crear,
		IF(p.modificar = 1,'checked', '') as modificar,
		IF(p.eliminar = 1,'checked', '') as eliminar,
		IF(p.consultar = 1,'checked', '') as consultar,
		tu.descripcion as tipousuario, m.descripcion as modulo
		FROM permisos p
		INNER JOIN tiposusuarios tu ON tu.id = p.idTipoUsuario
		INNER JOIN modulos m ON m.Id = p.idModulo
		WHERE p.idTipoUsuario  = {$_REQUEST['id']}";


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

function actializarPermiso()
{
	try
	{
		$conexion = new conexion();
		$sql="UPDATE permisos SET ".$_REQUEST['campo']."=".$_REQUEST['valor']." WHERE id = ".$_REQUEST['id'];
		
		$respuesta = $conexion->sqlOperacion($sql);
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

function mostrarPermisosMeseros()
{
	try
	{	
		$conexion = new conexion();
		$regModulos = $conexion->sql("SELECT * FROM permisosmeseros WHERE idmesero = {$_REQUEST['id']}");
	
		if ( count($regModulos) == 0) {
			mysql_query("INSERT INTO permisosmeseros (idmesero, agregar, cobrar, mesas, unir, traspasar, agregarmesa, anular, imprimir, finalizar) 
				VALUES ( '{$_REQUEST['id']}', '0', '0', '0', '0', '0', '0', '0', '0', '0');");
		}


		$sql="SELECT p.id,p.agregar,p.cobrar,p.mesas,p.unir,p.traspasar,p.agregarmesa,p.anular,p.imprimir,p.finalizar,p.idmesero,u.nombre
		FROM permisosmeseros p
		INNER JOIN usuarios u on u.id = p.idmesero
		WHERE p.idmesero  = {$_REQUEST['id']}";


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


function editarPermisoMeseros()
{
	try
	{
		$conexion = new conexion();
		$sql="UPDATE permisosmeseros SET ".$_REQUEST['campo']."=".$_REQUEST['valor']." WHERE id = ".$_REQUEST['id'];
		
		$respuesta = $conexion->sqlOperacion($sql);
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