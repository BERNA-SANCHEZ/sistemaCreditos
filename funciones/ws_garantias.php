<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors",0);
require_once("classSQL.php");

$accion =$_REQUEST['accion'];

switch ($accion) {
	case 'nuevo':
			nuevaGarantia();
		break;
	case 'editar':
			editarGarantia();
		break;
	case 'eliminar':
			eliminarGarantia();
		break;
	case 'mostrar':
			mostrarGarantias();
		break;
	case 'quitarImagen':
			quitarImagen();
		break;		
	
}


function nuevaGarantia()
{
	try
	{	
		$conexion = new conexion();
    
        $sql = "INSERT INTO garantias (idusuario, idprestamo, nombre, valuacion, estado, descripcion,  serie, modelo, marca)
            VALUES ('".$_SESSION['idusuario']."', '".$_REQUEST['id_prestamo']."', 
            '".utf8_decode($_REQUEST['new_Nombre'])."', '".$_REQUEST['newValuacion']."', '".$_REQUEST['newEstado']."', 
            '".utf8_decode($_REQUEST['newDescripcion'])."', '".utf8_decode($_REQUEST['new_Serie'])."',
			'".utf8_decode($_REQUEST['new_Modelo'])."','".utf8_decode($_REQUEST['new_Marca'])."' )";
				
        $respuesta = $conexion->sqlOperacion($sql);

        if ($_REQUEST['ruta'] != '') {			
            $sql2 = "INSERT INTO rutas_garantias (idgarantias,ruta) VALUES ('".$respuesta["ultimoId"]."','".$_REQUEST['ruta']."')";
            $regRutasProductos = $conexion->sqlOperacion($sql2);
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

function editarGarantia()
{
	try
	{	
		$conexion = new conexion();
		
        $sql = "UPDATE garantias SET idusuario= '".$_SESSION['idusuario']."' ,nombre= '".utf8_decode($_REQUEST['edit_Nombre'])."',
        valuacion= '".$_REQUEST['editValuacion']."', estado= '".$_REQUEST['editEstado']."' ,
		descripcion= '".utf8_decode($_REQUEST['editDescripcion'])."',
		serie= '".utf8_decode($_REQUEST['edit_Serie'])."',
		modelo= '".utf8_decode($_REQUEST['edit_Modelo'])."',
		marca= '".utf8_decode($_REQUEST['edit_Marca'])."'
        WHERE id = ".$_REQUEST['idgarantias'];

        $resGarantias = $conexion->sqlOperacion($sql);

        if ($_REQUEST['ruta'] != '') {			
            $consultaImagen = $conexion->sql("SELECT * FROM rutas_garantias WHERE idgarantias = {$_REQUEST['idgarantias']}");
            if(count($consultaImagen) > 0){				
                unlink('../'.$consultaImagen[0]["ruta"]);
                $resrutas_garantias = mysql_query("DELETE FROM rutas_garantias WHERE idgarantias ={$_REQUEST['idgarantias']}");
            }

            $sql2 = "INSERT INTO rutas_garantias (idgarantias,ruta) VALUES ('".$_REQUEST['idgarantias']."','".$_REQUEST['ruta']."')";
            $regRutasProductos = $conexion->sqlOperacion($sql2);						
        }


        if($resGarantias){
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

function eliminarGarantia()
{
	try
	{	
		$conexion = new conexion();

		$consultaImagen = $conexion->sql("SELECT * FROM rutas_garantias WHERE idgarantias = {$_REQUEST['idgarantia']}");
		if(count($consultaImagen) > 0){				
			unlink('../'.$consultaImagen[0]["ruta"]);
			$resrutas_garantias = mysql_query("DELETE FROM rutas_garantias WHERE idgarantias ={$_REQUEST['idgarantia']}");
		}
		
		$sql = "DELETE FROM garantias WHERE id=".$_REQUEST['idgarantia'];

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


function mostrarGarantias()
{
	try
	{	
		$conexion = new conexion();
		
		if ($_REQUEST['id']) {
			$sql="SELECT c.*, rc.ruta FROM garantias c
			LEFT JOIN rutas_garantias rc ON c.id = rc.idgarantias
			WHERE c.id = {$_REQUEST['id']}  ";
		}else{
			$sql="SELECT *, 
            IFNULL( (SELECT ruta FROM rutas_garantias WHERE idgarantias = garantias.id)  ,'upload/opcion.png')  as foto
            FROM garantias WHERE idprestamo = {$_REQUEST['id_prestamo']}  ";			
		}




		$result = $conexion->sql($sql);

        if ($_REQUEST['id']) {
            $respuesta["refC1"] = explode("**", $result[0]['referenciacredito1'] );
            $respuesta["refP1"] = explode("**", $result[0]['referenciapersonal1'] );
        }

		$respuesta["registros"] = $result;
		$respuesta["data"] = $result;
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


function quitarImagen(){

	try
	{		
		$conexion = new conexion();

		$consultaImagen = $conexion->sql("SELECT * FROM rutas_garantias WHERE idgarantias = {$_REQUEST['idgarantias']}");
		if(count($consultaImagen) > 0){				
			unlink('../'.$consultaImagen[0]["ruta"]);
			$resrutas_garantias = mysql_query("DELETE FROM rutas_garantias WHERE idgarantias ={$_REQUEST['idgarantias']}");
		}

		$respuesta["resultado"] = true;
		$respuesta["mensaje"] = "Registro eliminado";
		
		
	}
	catch (Exception $e)
	{
		$respuesta['resultado']=false;
		$respuesta['mensaje']=$e;
	}

	echo json_encode( $respuesta );
	$conexion->respuestaTrans("COMMIT");

}




?>