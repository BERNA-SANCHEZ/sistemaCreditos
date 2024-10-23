<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors",0);
require_once("classSQL.php");

$accion =$_REQUEST['accion'];

switch ($accion) {
	case 'nuevo':
			nuevoRepresentante();
		break;
	case 'editar':
			editarRepresentante();
		break;
	case 'eliminar':
			eliminarRepresentante();
		break;
	case 'mostrar':
			mostrarRepresentante();
		break;
        	
	
}


function nuevoRepresentante()
{
	try
	{	
		$conexion = new conexion();
            
        $sql = "INSERT INTO representantelegal (nombre, nacimiento, estadocivil, nacionalidad, oficio, dpi)
        VALUES ('".utf8_decode($_REQUEST['newNameRL'])."',
        '".utf8_decode($_REQUEST['newNacimiento'])."',
        '".utf8_decode($_REQUEST['newEstadoCivil'])."',
        '".utf8_decode($_REQUEST['newNacionalidad'])."',
        '".utf8_decode($_REQUEST['newOficio'])."',
        '".utf8_decode($_REQUEST['newDPI'])."')";

        $regRepresentante = $conexion->sqlOperacion($sql);

        if ($regRepresentante["resultado"] == true ) {
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

function editarRepresentante()
{
	try
	{	
		$conexion = new conexion();
		
        $sql = "UPDATE representantelegal SET nombre= '".utf8_decode($_REQUEST['editNameRL'])."',
        nacimiento= '".utf8_decode($_REQUEST['editNacimiento'])."',
        estadocivil= '".utf8_decode($_REQUEST['editEstadoCivil'])."',
        nacionalidad= '".utf8_decode($_REQUEST['editNacionalidad'])."',
        oficio= '".utf8_decode($_REQUEST['editOficio'])."',
        dpi= '".utf8_decode($_REQUEST['editDPI'])."'
       
        WHERE id = ".$_REQUEST['idrepresentantelegal'];

        $resRepresentante = $conexion->sqlOperacion($sql);

        if($resRepresentante){
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

function eliminarRepresentante()
{
	try
	{	
		$conexion = new conexion();
		$sql = "DELETE FROM representantelegal WHERE id=".$_REQUEST['idrepresentantelegal'];

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


function mostrarRepresentante()
{
	try
	{	
		$conexion = new conexion();
		
		if ($_REQUEST['idrepresentantelegal']) {
			$sql="SELECT * FROM representantelegal WHERE id = {$_REQUEST['idrepresentantelegal']}  ";
		}else{
			$sql="SELECT * FROM representantelegal ";			
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