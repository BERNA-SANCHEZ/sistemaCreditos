<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors",0);
require_once("classSQL.php");
require_once("Swift-5.0.3/lib/swift_required.php");

$accion =$_REQUEST['accion'];

switch ($accion) {	
	case 'cajas':
			mostrarCajas();
		break;
	case 'aperturas':
			mostrarAperturas();
		break;
	case 'nueva':
			nuevaApertura();
		break;	
	case 'nuevoretiro':
			nuevoRetiro();
		break;
	case 'nuevoingreso':
			nuevoIngreso();
		break;
	case 'eliminaretiro':
			eliminarRetiro();
		break;
		case 'eliminarIngreso':
			eliminarIngreso();
		break;		
}



function mostrarCajas()
{
	try
	{		
		$conexion = new conexion();

		if ( $_SESSION['idtipousuario'] != 1) {
			$sql="SELECT c.*, u.nombre FROM cajas c
			INNER JOIN usuarios u ON c.idusuario = u.id WHERE c.idusuario = ".$_SESSION['idusuario']." AND u.accesocaja = 1";		
		}else{
			$sql="SELECT c.*, u.nombre FROM cajas c
			INNER JOIN usuarios u ON c.idusuario = u.id";
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
	
	echo json_encode($respuesta);
	$conexion->respuestaTrans("COMMIT");
}


function mostrarAperturas()
{
	try
	{		
		$conexion = new conexion();
		$retiros = array();
		$ingresos = array();
		$respuesta = array();
		$ventas = array();
		$abonos = array();

		if ($_REQUEST['estado'] == 1) { // APERTURO CAJA			

			$sql="SELECT c.id as idcaja, c.descripcion, IF(c.estado=0,'APERTURADA','OCUPADA') as estadoImpresora,
			ca.id as idapertura, 
			ca.idusuarioinicio, (SELECT nombre FROM usuarios WHERE id = ca.idusuarioinicio) as usuarioinicio,
			ca.fechainicio, 				
			ca.idusuariocerro, 
			ca.fechacierre,
			ROUND(ca.efectivoinicial,2) as efectivoinicial, 
			ROUND(
				IFNULL((SELECT ROUND(SUM(monto),2) FROM pagosrealizados WHERE idapertura = ca.id ),'0.00')
			,2)as ventaefectivo,                
			ROUND(
				IFNULL((SELECT ROUND(SUM(monto),2) FROM pagoscapital WHERE idapertura = ca.id ),'0.00')
			,2)as pagoscapital,                                                
			ROUND(
				IFNULL((SELECT ROUND(SUM(capitalEntregado),2) FROM prestamos WHERE idapertura = ca.id ),'0.00')
			,2)as prestamos,                                
			ROUND((
					(
						ROUND(ca.efectivoinicial,2)  +					    
						IFNULL((SELECT ROUND(SUM(monto),2) FROM pagosrealizados WHERE idapertura = ca.id ),'0.00') + 
						IFNULL((SELECT ROUND(SUM(monto),2) FROM pagoscapital WHERE idapertura = ca.id ),'0.00') +  
						IFNULL((SELECT ROUND(SUM(valor),2) FROM cajasingresos WHERE idapertura = ca.id ),'0.00')                            
					)-(
						IFNULL((SELECT ROUND(SUM(capitalEntregado),2) FROM prestamos WHERE idapertura = ca.id ),'0.00')+ 
						IFNULL((SELECT ROUND(SUM(valor),2) FROM cajasretiros WHERE idapertura = ca.id ),'0.00')
					)
				),2) as saldoactual,                                                                                                                        
			IFNULL((SELECT ROUND(SUM(valor),2) FROM cajasretiros WHERE idapertura = ca.id ),'0.00') as retiros,                	  
			IFNULL((SELECT ROUND(SUM(valor),2) FROM cajasingresos WHERE idapertura = ca.id ),'0.00') as ingresos,                
			ca.idusuariocerro, 
			ca.fechacierre,
			ROUND(
				IFNULL((SELECT ROUND(SUM(monto),2) FROM pagosanulados WHERE idapertura = ca.id ),'0.00')
			,2) as anuladas
			FROM   cajas c
			INNER JOIN cajasaperturas ca ON ca.idcaja = c.id
			WHERE c.id = {$_REQUEST['id']}
			ORDER BY ca.id DESC
			LIMIT 1";

			
			$result = $conexion->sql($sql);

			$retiros = $conexion->sql("SELECT ca.*, u.nombre 
								,IFNULL( (SELECT ruta FROM rutas_cajasretiros WHERE idcajasretiros = ca.id)  ,'upload/opcion.png')  as foto
								FROM cajasretiros ca
								INNER JOIN usuarios u ON u.id = ca.idusuario
								WHERE ca.idapertura = {$result[0]['idapertura']}");


			$ingresos = $conexion->sql("SELECT ca.*, u.nombre  FROM cajasingresos ca
								INNER JOIN usuarios u ON u.id = ca.idusuario
								WHERE ca.idapertura = {$result[0]['idapertura']}");
		

			$consultapagos="SELECT *,
			IFNULL( (SELECT nombre FROM clientes WHERE id = pagosrealizados.idcliente)  ,'')  as nombreCliente,
			IFNULL( (SELECT nombre FROM usuarios WHERE id = pagosrealizados.idusuario)  ,'')  as usuarioRecibio,
			IFNULL( (SELECT ruta FROM rutas_clientes WHERE idClientes = pagosrealizados.idcliente)  ,'upload/user.png')  as foto
			FROM pagosrealizados WHERE idapertura = {$result[0]['idapertura']} ";	
			$registroPagosRealizados = $conexion->sql($consultapagos);

			$consultapagoscapital="SELECT *,
			IFNULL( (SELECT nombre FROM clientes WHERE id = pagoscapital.idcliente)  ,'')  as nombreCliente,
			IFNULL( (SELECT nombre FROM usuarios WHERE id = pagoscapital.idusuario)  ,'')  as usuarioRecibio,
			IFNULL( (SELECT ruta FROM rutas_clientes WHERE idClientes = pagoscapital.idcliente)  ,'upload/user.png')  as foto
			FROM pagoscapital WHERE idapertura = {$result[0]['idapertura']} ";	
			$registropagoscapital = $conexion->sql($consultapagoscapital);

			$sqlmostrarPrestamos="SELECT *, 
			IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idusuario)  ,'')  as usuarioentrego,
			IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idcobrador)  ,'')  as usuariocobrador,
			IFNULL( (SELECT nombre FROM clientes WHERE id = prestamos.idcliente)  ,'')  as nombreCliente,
			IFNULL( (SELECT cuotas FROM planesprestamo WHERE idprestamo = prestamos.id)  ,'')  as cuotas
			FROM prestamos WHERE idapertura = {$result[0]['idapertura']} ";	
			$mostrarPrestamos = $conexion->sql($sqlmostrarPrestamos);



		}elseif ($_REQUEST['estado'] == 0) { //DISPONIBE
			$sql="SELECT c.id as idcaja, c.descripcion, IF(c.estado=0,'DISPONIBLE','OCUPADA') as estadoImpresora,
				ca.ingresocaja,
				'0' as idapertura, 
				'' as idusuarioinicio, '' as usuarioinicio,
				'' as fechainicio, '0.00' as efectivoinicial, 
				'0.00' as ventaefectivo, '0.00' as retiros, '0.00' as ingresos, 
				'0.00' as pagoscapital, '0.00' as prestamos,				
				'0.00' as saldoactual, '0.00' as anuladas, 
				'' as idusuariocerro, 
				'' as fechacierre
				FROM   cajas c
				INNER JOIN cajasaperturas ca ON ca.idcaja = c.id
				WHERE c.id = {$_REQUEST['id']}
				ORDER BY ca.id DESC
				LIMIT 1";
			$result = $conexion->sql($sql);
		}


		$respuesta["registros"] = $result;
		$respuesta["retiros"] = $retiros;	
		$respuesta["ingresos"] = $ingresos;	
		$respuesta["registroPagosRealizados"] = $registroPagosRealizados;
		$respuesta["registropagoscapital"] = $registropagoscapital;
		$respuesta["mostrarPrestamos"] = $mostrarPrestamos;

		$respuesta["resultado"] = true;
		$respuesta["mensaje"] = "Datos consultados Exitosamente";
	
	}
	catch (Exception $e)
	{
		$respuesta['registros']=array();
		$respuesta['resultado']=false;
		$respuesta['mensaje']=$e;
	}
	
	echo json_encode($respuesta);
	$conexion->respuestaTrans("COMMIT");
}





function nuevaApertura()
{
	try
	{		
		$conexion = new conexion();
		$conexion->transaccion();
		$error = 0;
		

		if ($_REQUEST['idestado'] == 1) { // ABRIO CAJA
			$sql = "INSERT INTO cajasaperturas(idcaja, idusuarioinicio, fechainicio, efectivoinicial, ventaefectivo, retiros, anulaciones, totalcierre, ingresocaja, diferencia, idusuariocerro, fechacierre) 
				VALUES( '".$_REQUEST["cajas"]."','".$_SESSION["idusuario"]."',now(),'".$_REQUEST['apertura']."','0','0','0','0','0','0',NULL,NULL)";
			
			$regApertura = $conexion->sqlOperacion($sql); 
			if ($regApertura["resultado"] == false) { $error++; }
			

			$sql = ("UPDATE usuarios SET idapertura={$regApertura['ultimoId']} WHERE id=".$_SESSION['idusuario']);
			if($conexion->sqlOperacion($sql)["resultado"]== false){ $error++; } 

			if (!$error) { $_SESSION["idapertura"] = $regApertura['ultimoId']; }
			
			$motivo ="ABRIO CAJA";
			$msjBody = "Inicio Caja, con un efectivo inicial de Q.".number_format($_REQUEST['apertura'],2);

		}else{ // CERRO CAJA		

			$sql="SELECT c.id as idcaja, c.descripcion, IF(c.estado=0,'APERTURADA','OCUPADA') as estadoImpresora,
			ca.id as idapertura, 
			ca.idusuarioinicio, (SELECT nombre FROM usuarios WHERE id = ca.idusuarioinicio) as usuarioinicio,
			ca.fechainicio, 				
			ca.idusuariocerro, 
			ca.fechacierre,
			ROUND(ca.efectivoinicial,2) as efectivoinicial, 
			ROUND(
				IFNULL((SELECT ROUND(SUM(monto),2) FROM pagosrealizados WHERE idapertura = ca.id ),'0.00')
			,2)as ventaefectivo,                
			ROUND(
				IFNULL((SELECT ROUND(SUM(monto),2) FROM pagoscapital WHERE idapertura = ca.id ),'0.00')
			,2)as pagoscapital,                                                
			ROUND(
				IFNULL((SELECT ROUND(SUM(capitalEntregado),2) FROM prestamos WHERE idapertura = ca.id ),'0.00')
			,2)as prestamos,                                
			ROUND((
					(
						ROUND(ca.efectivoinicial,2)  +					    
						IFNULL((SELECT ROUND(SUM(monto),2) FROM pagosrealizados WHERE idapertura = ca.id ),'0.00') + 
						IFNULL((SELECT ROUND(SUM(monto),2) FROM pagoscapital WHERE idapertura = ca.id ),'0.00') +  
						IFNULL((SELECT ROUND(SUM(valor),2) FROM cajasingresos WHERE idapertura = ca.id ),'0.00')                            
					)-(
						IFNULL((SELECT ROUND(SUM(capitalEntregado),2) FROM prestamos WHERE idapertura = ca.id ),'0.00')+ 
						IFNULL((SELECT ROUND(SUM(valor),2) FROM cajasretiros WHERE idapertura = ca.id ),'0.00')
					)
				),2) as saldoactual,                                                                                                                        
			IFNULL((SELECT ROUND(SUM(valor),2) FROM cajasretiros WHERE idapertura = ca.id ),'0.00') as retiros,                	  
			IFNULL((SELECT ROUND(SUM(valor),2) FROM cajasingresos WHERE idapertura = ca.id ),'0.00') as ingresos,                
			ca.idusuariocerro, 
			ca.fechacierre,
			'0.00' as anuladas
			FROM   cajas c
			INNER JOIN cajasaperturas ca ON ca.idcaja = c.id
			WHERE c.id = {$_REQUEST['cajas']}
			ORDER BY ca.id DESC
			LIMIT 1";

			$result = $conexion->sql($sql);

			$sql = "UPDATE cajasaperturas SET ventaefectivo='".$result[0]["ventaefectivo"]."', 			
			retiros='".$result[0]["retiros"]."', totalcierre='".$result[0]["saldoactual"]."', 
			ingresocaja = '".$_REQUEST['ingresocaja']."', diferencia = '".$_REQUEST['diferencia']."',  
			idusuariocerro='".$_SESSION['idusuario']."', 
			fechacierre=NOW(), ingresos='".$result[0]["ingresos"]."', prestamos='".$result[0]["prestamos"]."',
			pagoscapital='".$result[0]["pagoscapital"]."' 			
			WHERE id = '".$result[0]["idapertura"]."'";
			if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++;} 

			$sql = "UPDATE usuarios SET idapertura=0 WHERE idapertura=".$result[0]["idapertura"];
			if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++;} 

			if (!$error) { $_SESSION["idapertura"] = 0; }
			
		}

		if($conexion->sqlOperacion("UPDATE cajas SET estado = {$_REQUEST['idestado']} WHERE id = {$_REQUEST['cajas']}")["resultado"] == false){ $error++; }

		
		if (!$error ) {
			$conexion->respuestaTrans("COMMIT");
			$respuesta["resultado"] = true;
			$respuesta["mensaje"] = "Operación realizada correctamente";			
		}else{
			$conexion->respuestaTrans("ROLLBACK");
			$respuesta["resultado"] = false;
			$respuesta["mensaje"] = "Datos No ingresados";
		}

	}
	catch (Exception $e)
	{
		$respuesta['registros']=array();
		$respuesta['resultado']=false;
		$respuesta['mensaje']=$e;
	}
	
	echo json_encode($respuesta);
	$conexion->respuestaTrans("COMMIT");
}


function nuevoIngreso(){

	try
	{		
		$conexion = new conexion();
		
		$sql = "INSERT INTO cajasingresos(idapertura, idusuario, descripcion, valor,fecha) VALUES ('".$_REQUEST['id_apertura']."','".$_SESSION['idusuario']."','".utf8_decode($_REQUEST['ing_descripcion'])."','".$_REQUEST['in_valor']."', NOW())";
		$regIngresos = $conexion->sqlOperacion($sql);
		if ($regIngresos["resultado"] == true ) {
			$ingresos = $conexion->sql("SELECT ca.*, u.nombre  FROM cajasingresos ca
									INNER JOIN usuarios u ON u.id = ca.idusuario
									WHERE ca.idapertura = {$_REQUEST['idapertura']}");
			
			$respuesta["ingresos"] = $ingresos;
			$respuesta["resultado"] = true;
			$respuesta["mensaje"] = "Operación realizada correctamente";
		}else{
			$respuesta["resultado"] = false;
			$respuesta["mensaje"] = "Datos No ingresados";
		}
	
	}
	catch (Exception $e)
	{
		$respuesta['resultado']=false;
		$respuesta['mensaje']=$e;
	}
	
	echo json_encode($respuesta);
	$conexion->respuestaTrans("COMMIT");	

}

function nuevoRetiro()
{
	try
	{		
		$conexion = new conexion();
		
		$sql = "INSERT INTO cajasretiros(idapertura, idusuario, descripcion, valor,fecha,tipogasto) 
		VALUES ('".$_REQUEST['idapertura']."','".$_SESSION['idusuario']."','".utf8_decode($_REQUEST['descripcion'])."','".$_REQUEST['valor']."', NOW(),'".$_REQUEST['tipoGasto']."')";
		$regRetiros = $conexion->sqlOperacion($sql);
		if ($regRetiros["resultado"] == true ) {
			$retiros = $conexion->sql("SELECT ca.*, u.nombre  FROM cajasretiros ca
									INNER JOIN usuarios u ON u.id = ca.idusuario
									WHERE ca.idapertura = {$_REQUEST['idapertura']}");


			if ($_REQUEST['ruta'] != '') {			
				$sql2 = "INSERT INTO rutas_cajasretiros (idcajasretiros,ruta) VALUES ('".$regRetiros["ultimoId"]."','".$_REQUEST['ruta']."')";
				$regRutasProductos = $conexion->sqlOperacion($sql2);
			}

			
			$respuesta["retiros"] = $retiros;
			$respuesta["resultado"] = true;
			$respuesta["mensaje"] = "Operación realizada correctamente";
		}else{
			$respuesta["resultado"] = false;
			$respuesta["mensaje"] = "Datos No ingresados";
		}
	
	}
	catch (Exception $e)
	{
		$respuesta['resultado']=false;
		$respuesta['mensaje']=$e;
	}
	
	echo json_encode($respuesta);
	$conexion->respuestaTrans("COMMIT");	
}



function eliminarRetiro()
{
	try
	{	
		$conexion = new conexion();

		$consultaImagen = $conexion->sql("SELECT * FROM rutas_cajasretiros WHERE idcajasretiros = {$_REQUEST['id']}");
		if(count($consultaImagen) > 0){				
			unlink('../'.$consultaImagen[0]["ruta"]);
			$resrutas_cajasretiros = mysql_query("DELETE FROM rutas_cajasretiros WHERE idcajasretiros ={$_REQUEST['id']}");
		}

		$resRetiros = mysql_query("DELETE FROM cajasretiros WHERE id={$_REQUEST['id']}");

		if($resRetiros){
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
	
	echo json_encode($respuesta);
	$conexion->respuestaTrans("COMMIT");
}



function eliminarIngreso()
{
	try
	{	
		$conexion = new conexion();
		$resIngresos = mysql_query("DELETE FROM cajasingresos WHERE id={$_REQUEST['id']}");

		if($resIngresos){
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
	
	echo json_encode($respuesta);
	$conexion->respuestaTrans("COMMIT");
}




?>