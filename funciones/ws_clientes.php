<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors",0);
require_once("classSQL.php");

$accion =$_REQUEST['accion'];

switch ($accion) {
	case 'nuevo':
			nuevoCliente();
		break;
	case 'editar':
			editarCliente();
		break;
	case 'editarFoto':
			editarFoto();
		break;		
	case 'eliminar':
			eliminarCliente();
		break;
	case 'mostrar':
			mostrarClientes();
		break;
	case 'quitarImagen':
			quitarImagen();
		break;		
	case 'eliminarImagen':
			eliminarImagen();
		break;
	case 'codigosiguiente':
			codigosiguiente();
		break;

		
}


function nuevoCliente()
{
	try
	{	
		$conexion = new conexion();
    
		$buscarUser = $conexion->sql("SELECT * FROM clientes WHERE codigo = '{$_REQUEST['newCodigo']}' ");


        $refCredito1  = utf8_decode($_REQUEST['newReferenciacredito1'])."**".$_REQUEST['newTelefono1'];
        $refPersonal1  = utf8_decode($_REQUEST['newReferenciapersonal1'])."**".$_REQUEST['newTelefono2'];


		if (count($buscarUser) == 0) {
			$sql = "INSERT INTO clientes (codigo, nombre, dpi, telefono, direccionvive, alquila, direccionnegocio, 
            tiponegocio, referenciacredito1, referenciapersonal1, multprestamos, prestamosanteriores, tipoCliente, permitirprestamos) 
            VALUES ('".utf8_decode($_REQUEST['newCodigo'])."', '".utf8_decode($_REQUEST['newNombre'])."', 
            '".$_REQUEST['newDPI']."', '".$_REQUEST['newTelefono']."', '".utf8_decode($_REQUEST['newDireccion'])."', 
            '".$_REQUEST['newAlquila']."', '".utf8_decode($_REQUEST['newDireccionNegocio'])."', 
            '".utf8_decode($_REQUEST['newTipoNegocio'])."', '".$refCredito1."', '".$refPersonal1."', 
			'".$_REQUEST['newMultiple']."', '".$_REQUEST['newprestamosanteriores']."', '".$_REQUEST['newtipoCliente']."', '".$_REQUEST['newpermitirprestamos']."' )";
				
			$respuesta = $conexion->sqlOperacion($sql);

			if ($_REQUEST['ruta'] != '') {			
				$sql2 = "INSERT INTO rutas_clientes (idClientes,ruta) VALUES ('".$respuesta["ultimoId"]."','".$_REQUEST['ruta']."')";
				$regRutasProductos = $conexion->sqlOperacion($sql2);
			}



		}else{
			$respuesta['resultado']=false;
			$respuesta['mensaje']="Codigo Repetido";
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


function editarFoto(){

	try
	{	
		$conexion = new conexion();			
        $conexion->transaccion();


		if ($_REQUEST['ruta'] != '') {			
			$consultaImagen = $conexion->sql("SELECT * FROM rutas_clientes WHERE idClientes = {$_REQUEST['idcliente']}");
			if(count($consultaImagen) > 0){				
				unlink('../'.$consultaImagen[0]["ruta"]);
				$resrutas_clientes = mysql_query("DELETE FROM rutas_clientes WHERE idClientes ={$_REQUEST['idcliente']}");
			}

			$sql2 = "INSERT INTO rutas_clientes (idClientes,ruta) VALUES ('".$_REQUEST['idcliente']."','".$_REQUEST['ruta']."')";
			$regRutasProductos = $conexion->sqlOperacion($sql2);				
			

			if (file_exists('../'.$_REQUEST['ruta'])) {
				$respuesta["resultado"] = true;
				$conexion->respuestaTrans("COMMIT");
				$respuesta["mensaje"] = "Imagen modificada";			
			}else{
				$respuesta["resultado"] = false;
				$conexion->respuestaTrans("ROLLBACK");
				$respuesta["mensaje"] = "Imagen no modificada";
			}

		}else{
			$respuesta["resultado"] = false;
			$respuesta["mensaje"] = "Imagen no modificada";
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

function editarCliente()
{
	try
	{	
		$conexion = new conexion();
		
		$buscarUser = $conexion->sql("SELECT * FROM clientes WHERE codigo = '{$_REQUEST['editCodigo']}' AND id <> ".$_REQUEST['idcliente']);
        
		if (count($buscarUser) == 0) {

            $refCredito1  = utf8_decode($_REQUEST['editReferenciacredito1'])."**".$_REQUEST['editTelefono1'];
            $refPersonal1  = utf8_decode($_REQUEST['editReferenciapersonal1'])."**".$_REQUEST['editTelefono2'];

			$sql = "UPDATE clientes SET codigo= '".utf8_decode($_REQUEST['editCodigo'])."' ,nombre= '".utf8_decode($_REQUEST['editNombre'])."',
            dpi= '".$_REQUEST['editDPI']."', telefono= '".$_REQUEST['editTelefono']."' ,direccionvive= '".utf8_decode($_REQUEST['editDireccion'])."',
            alquila= '".$_REQUEST['editAlquila']."',direccionnegocio= '".utf8_decode($_REQUEST['editDireccionNegocio'])."',
            tiponegocio= '".utf8_decode($_REQUEST['editTipoNegocio'])."',referenciacredito1='".$refCredito1."' ,
            referenciapersonal1= '".$refPersonal1."', multprestamos = '".$_REQUEST['editMultiple']."', 
			prestamosanteriores = '".$_REQUEST['editprestamosanteriores']."', 
			tipoCliente = '".$_REQUEST['edittipoCliente']."', permitirprestamos = '".$_REQUEST['editpermitirprestamos']."'  WHERE id = ".$_REQUEST['idcliente'];

			$resClientes = $conexion->sqlOperacion($sql);

			if ($_REQUEST['ruta'] != '') {			
				$consultaImagen = $conexion->sql("SELECT * FROM rutas_clientes WHERE idClientes = {$_REQUEST['idcliente']}");
				if(count($consultaImagen) > 0){				
					unlink('../'.$consultaImagen[0]["ruta"]);
					$resrutas_clientes = mysql_query("DELETE FROM rutas_clientes WHERE idClientes ={$_REQUEST['idcliente']}");
				}
	
				$sql2 = "INSERT INTO rutas_clientes (idClientes,ruta) VALUES ('".$_REQUEST['idcliente']."','".$_REQUEST['ruta']."')";
				$regRutasProductos = $conexion->sqlOperacion($sql2);						
			}


			if($resClientes){
				$respuesta["resultado"] = true;
				$respuesta["mensaje"] = "Registro modificado";
			}else{
				$respuesta["resultado"] = false;
				$respuesta["mensaje"] = "Registro NO modificado ";
			}


		}else{
			$respuesta['resultado']=false;
			$respuesta['mensaje']="Codigo Repetido";
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

function eliminarCliente()
{
	try
	{	
		$conexion = new conexion();

		$consultaImagen = $conexion->sql("SELECT * FROM rutas_clientes WHERE idClientes = {$_REQUEST['idcliente']}");
		if(count($consultaImagen) > 0){				
			unlink('../'.$consultaImagen[0]["ruta"]);
			$resrutas_clientes = mysql_query("DELETE FROM rutas_clientes WHERE idClientes ={$_REQUEST['idcliente']}");
		}
		
		$sql = "DELETE FROM clientes WHERE id=".$_REQUEST['idcliente'];

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


function mostrarClientes()
{
	try
	{	
		$conexion = new conexion();
		
		if ($_REQUEST['id']) {
			$sql="SELECT c.*, rc.ruta FROM clientes c
			LEFT JOIN rutas_clientes rc ON c.id = rc.idClientes
			WHERE c.id = {$_REQUEST['id']}  ";
		}else{
			$sql="SELECT *,
			IFNULL( (SELECT ruta FROM rutas_clientes WHERE idClientes = clientes.id)  ,'upload/user.png')  as foto
			,IFNULL( (SELECT COUNT(*) FROM prestamos WHERE idcliente = clientes.id AND estado = 1)  ,0)  as prestamosActivos
			FROM clientes";			
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



function codigosiguiente()
{
	try
	{	
		$conexion = new conexion();		
		$sql="SELECT MAX(codigo)+1 as codigosiguiente FROM clientes";	
		$result = $conexion->sql($sql);
		$respuesta["codigosiguiente"] = $result[0]["codigosiguiente"];
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

		$consultaImagen = $conexion->sql("SELECT * FROM rutas_clientes WHERE idClientes = {$_REQUEST['idcliente']}");
		if(count($consultaImagen) > 0){				
			unlink('../'.$consultaImagen[0]["ruta"]);
			$resrutas_clientes = mysql_query("DELETE FROM rutas_clientes WHERE idClientes ={$_REQUEST['idcliente']}");
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

function eliminarImagen(){

	try
	{		
		if(unlink('../'.$_REQUEST['ruta'])){
			$respuesta["resultado"] = true;
			$respuesta["mensaje"] = "Registro eliminado";
		}else{
			$respuesta["resultado"] = false;
			$respuesta["mensaje"] = "Registro NO eliminado ";
		}
		
	}
	catch (Exception $e)
	{
		$respuesta['resultado']=false;
		$respuesta['mensaje']=$e;		
	}

	echo json_encode( $respuesta );	


}


?>