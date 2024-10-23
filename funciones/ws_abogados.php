<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors",0);
require_once("classSQL.php");

$accion =$_REQUEST['accion'];

switch ($accion) {
	case 'nuevo':
			nuevoAbogado();
		break;
	case 'editar':
			editarAbogado();
		break;
	case 'eliminar':
			eliminarAbogado();
		break;
	case 'mostrar':
			mostrarAbogado();
		break;
        	
	
}


function nuevoAbogado()
{
	try
	{	
		$conexion = new conexion();
            
        $sql = "INSERT INTO abogado (nombre, colegiado)
        VALUES ('".utf8_decode($_REQUEST['nuevo_nombre'])."',
        '".utf8_decode($_REQUEST['nuevo_colegiado'])."')";

        $regAbogado = $conexion->sqlOperacion($sql);

        if ($regAbogado["resultado"] == true ) {
            $respuesta["resultado"] = true;
            $respuesta["mensaje"] = "Datos ingresados correctamente";
        }else{
            $respuesta["resultado"] = false;
            $respuesta["mensaje"] = "Datos No ingresados ";
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

function editarAbogado()
{
	try
	{	
		$conexion = new conexion();
		
        $sql = "UPDATE abogado SET nombre= '".utf8_decode($_REQUEST['editar_nombre'])."',
        colegiado= '".utf8_decode($_REQUEST['editar_colegiado'])."'       
        WHERE id = ".$_REQUEST['idabogado'];

        $resAbogado = $conexion->sqlOperacion($sql);

        if($resAbogado){
            $respuesta["resultado"] = true;
            $respuesta["mensaje"] = "Registro modificado";
        }else{
            $respuesta["resultado"] = false;
            $respuesta["mensaje"] = "Registro NO modificado ";
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

function eliminarAbogado()
{
	try
	{	
		$conexion = new conexion();
		$sql = "DELETE FROM abogado WHERE id=".$_REQUEST['idabogado'];

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


function mostrarAbogado()
{
	try
	{	
		$conexion = new conexion();
		
		if ($_REQUEST['idabogado']) {
			$sql="SELECT * FROM abogado WHERE id = {$_REQUEST['idabogado']}  ";
		}else{
			$sql="SELECT * FROM abogado ";			
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



?>