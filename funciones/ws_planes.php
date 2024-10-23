<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors",0);
require_once("classSQL.php");

$accion =$_REQUEST['accion'];

switch ($accion) {
	case 'nuevo':
			nuevoPlan();
		break;
	case 'editar':
			editarPlan();
		break;
	case 'eliminar':
			eliminarPlan();
		break;
	case 'mostrar':
			mostrarPlanes();
		break;
        	
	
}


function nuevoPlan()
{
	try
	{	
		$conexion = new conexion();
            
        $sql = "INSERT INTO planes (nombre, cuotas, interes, tipo, dias, idusuario,n, m) 
        VALUES ('".utf8_decode($_REQUEST['newNombre'])."',
        '".$_REQUEST['newCantidadCuotas']."',
        '".$_REQUEST['newTasaInteres']."',
        '".$_REQUEST["newTipoPlan"]."',
        '".$_REQUEST["cadenaDias"]."',
        '".$_SESSION["idusuario"]."',
		'".$_REQUEST["newn"]."',
		'".$_REQUEST["newm"]."')";

        $regPlan = $conexion->sqlOperacion($sql);

        if ($regPlan["resultado"] == true ) {
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

function editarPlan()
{
	try
	{	
		$conexion = new conexion();
		
        $sql = "UPDATE planes SET nombre= '".utf8_decode($_REQUEST['editNombre'])."' ,
        cuotas= '".$_REQUEST['editCantidadCuotas']."' ,
        interes= '".$_REQUEST['editTasaInteres']."',
        tipo= '".$_REQUEST['editTipoPlan']."',
        dias= '".$_REQUEST['cadenaDias']."',
        idusuario= '".$_SESSION["idusuario"]."',
		n = '".$_REQUEST['editn']."',
		m = '".$_REQUEST['editm']."'
        WHERE id = ".$_REQUEST['idplan'];

        $resPlanes = $conexion->sqlOperacion($sql);

        if($resPlanes){
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

function eliminarPlan()
{
	try
	{	
		$conexion = new conexion();
		$sql = "DELETE FROM planes WHERE id=".$_REQUEST['idplan'];

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


function mostrarPlanes()
{
	try
	{	
		$conexion = new conexion();
		
		if ($_REQUEST['id']) {
			$sql="SELECT * FROM planes WHERE id = {$_REQUEST['id']}  ";
		}else{
			$sql="SELECT * FROM planes ";			
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