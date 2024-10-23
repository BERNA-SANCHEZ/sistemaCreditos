<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors",0);
require_once("classSQL.php");

$accion =$_REQUEST['accion'];

switch ($accion) {	
	case 'mostrarCobradorSupervisor':
            mostrarCobradorSupervisor();
		break;
    case 'mostrarPagosPlanilla':
            mostrarPagosPlanilla();
        break;
    case 'mostrarPrestamosPendientes':
            mostrarPrestamosPendientes();
        break;	        
	case 'nuevo':
			nuevodatosempleados();
		break;
	case 'mostrar':
			mostrardatosempleados();
		break;  
	case 'nuevoPagoEmpleado':
			nuevoPagoEmpleado();
		break;
	case 'mostrarDetallleDescuentos':
			mostrarDetallleDescuentos();
		break;
	case 'eliminarPagoEmpleado':
			eliminarPagoEmpleado();
		break;
		
}


function mostrarCobradorSupervisor()
{
	try
	{	
		$conexion = new conexion();
		

        $sql="SELECT u.id, u.idtipousuario, u.idsucursal , t.descripcion as tipousuario, u.usuario, u.activado, u.nombre, s.nombre as sucursal
        FROM usuarios u
        INNER JOIN tiposusuarios t ON t.id = u.idtipousuario
        INNER JOIN sucursales s ON s.id = u.idsucursal 
        WHERE u.Id > 1 AND ( u.idtipousuario = 4 OR u.idtipousuario = 5 )
        ORDER BY u.idtipousuario ";

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


function mostrarPagosPlanilla()
{
	try
	{	
		$conexion = new conexion();
		

        $sql=" SELECT * FROM planilla WHERE idusuario = {$_REQUEST['idusuario']} ORDER BY id DESC";

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


function mostrarPrestamosPendientes()
{

	try
	{	
		$conexion = new conexion();
		//Como actualmente estamos trabajando solo con usuarios cobrador y supervisor 
		//entonces solo mandamos valores de 4 y 5 para idtiposusuarios


        if ($_REQUEST['idtipousuario'] == 4) {
		
            $sql="SELECT prestamos.*, 
            IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idusuario)  ,'')  as usuarioentrego,
            IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idcobrador)  ,'')  as usuariocobrador,
            IFNULL( (SELECT nombre FROM clientes WHERE id = prestamos.idcliente)  ,'')  as nombreCliente,
            IFNULL( (SELECT direccionvive FROM clientes WHERE id = prestamos.idcliente)  ,'')  as direccionvive,
            IFNULL( (SELECT cuotas FROM planesprestamo WHERE idprestamo = prestamos.id)  ,'')  as cuotas,
            IFNULL( (SELECT tipo FROM planesprestamo WHERE idprestamo = prestamos.id)  ,'')  as tipoPlan,
            IFNULL( (SELECT SUM(abono) FROM detprestamos WHERE idprestamo = prestamos.id AND pagado = 0 )  ,0)  AS abono

            ,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 0)  ,0)  AS morasPendientes   
            ,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0)  ,0)  AS cuotasPendientes                        
            ,IFNULL( (SELECT SUM(mora) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 0)  ,0)  AS totalMorasPendientes   
            ,IFNULL( (SELECT SUM(monto) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0)  ,0)  AS totalCuotasPendientes

            ,IFNULL( (SELECT ruta FROM rutas_clientes WHERE idClientes = prestamos.idcliente)  ,'upload/user.png')  as foto

            FROM prestamos WHERE estado = 1 AND idcobrador = {$_REQUEST['idusuario']} AND (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0) > 0 
			AND (SELECT MAX(fecha) FROM detprestamos WHERE detprestamos.idprestamo = prestamos.id AND detprestamos.tipo != 1) <= ADDDATE('".$_REQUEST['fechainicio']."',-1)";	


			////////////////////////////////////////////////////////////
			//PARA CLIENTES NUEVOS QUE SOLO TIENEN 1 PRESTAMO HECHO
			////////////////////////////////////////////////////////////
			$sql2="SELECT prestamos.*,		
			IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idcobrador)  ,'')  as usuariocobrador,
			IFNULL( (SELECT nombre FROM clientes WHERE id = prestamos.idcliente)  ,'')  as nombreCliente,			
			IFNULL( (SELECT ruta FROM rutas_clientes WHERE idClientes = prestamos.idcliente)  ,'upload/user.png')  as foto
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 0)  ,0)  AS morasPendientes   
            ,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0)  ,0)  AS cuotasPendientes
			FROM prestamos WHERE idcobrador = {$_REQUEST['idusuario']} AND incentivocobrador = 0 AND id = (SELECT MIN(pr.id) FROM prestamos pr WHERE pr.idcliente = prestamos.idcliente)
			AND fechaentregado <= ADDDATE('".$_REQUEST['fechainicio']."',-1) ";	

			////////////////////////////////////////////////////////////
			//PARA CLIENTES QUE HAN RENOVADO SU PRÉSTAMO
			////////////////////////////////////////////////////////////
			$sql3="SELECT prestamos.*,		
			IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idcobrador)  ,'')  as usuariocobrador,
			IFNULL( (SELECT nombre FROM clientes WHERE id = prestamos.idcliente)  ,'')  as nombreCliente,			
			IFNULL( (SELECT ruta FROM rutas_clientes WHERE idClientes = prestamos.idcliente)  ,'upload/user.png')  as foto
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 0)  ,0)  AS morasPendientes   
            ,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0)  ,0)  AS cuotasPendientes
			FROM prestamos WHERE idcobrador = {$_REQUEST['idusuario']} AND incentivocobrador = 0 AND id != (SELECT MIN(pr.id) FROM prestamos pr WHERE pr.idcliente = prestamos.idcliente) 
			AND fechaentregado <= ADDDATE('".$_REQUEST['fechainicio']."',-1)";	

        }else if ($_REQUEST['idtipousuario'] == 5){

            $sql="SELECT prestamos.*, 
            IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idusuario)  ,'')  as usuarioentrego,
            IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idcobrador)  ,'')  as usuariocobrador,
            IFNULL( (SELECT nombre FROM clientes WHERE id = prestamos.idcliente)  ,'')  as nombreCliente,
            IFNULL( (SELECT direccionvive FROM clientes WHERE id = prestamos.idcliente)  ,'')  as direccionvive,
            IFNULL( (SELECT cuotas FROM planesprestamo WHERE idprestamo = prestamos.id)  ,'')  as cuotas,
            IFNULL( (SELECT tipo FROM planesprestamo WHERE idprestamo = prestamos.id)  ,'')  as tipoPlan,
            IFNULL( (SELECT SUM(abono) FROM detprestamos WHERE idprestamo = prestamos.id AND pagado = 0 )  ,0)  AS abono


            ,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 0)  ,0)  AS morasPendientes   
            ,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0)  ,0)  AS cuotasPendientes                        
            ,IFNULL( (SELECT SUM(mora) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 0)  ,0)  AS totalMorasPendientes   
            ,IFNULL( (SELECT SUM(monto) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0)  ,0)  AS totalCuotasPendientes

            ,IFNULL( (SELECT ruta FROM rutas_clientes WHERE idClientes = prestamos.idcliente)  ,'upload/user.png')  as foto

            FROM prestamos WHERE estado = 1 AND idsupervisor = {$_REQUEST['idusuario']} AND (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0) > 0 
			AND (SELECT MAX(fecha) FROM detprestamos WHERE detprestamos.idprestamo = prestamos.id AND detprestamos.tipo != 1) <= ADDDATE('".$_REQUEST['fechainicio']."',-1)";	


			////////////////////////////////////////////////////////////
			//PARA CLIENTES NUEVOS QUE SOLO TIENEN 1 PRESTAMO HECHO
			////////////////////////////////////////////////////////////
			$sql2="SELECT prestamos.*,		
			IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idcobrador)  ,'')  as usuariocobrador,
			IFNULL( (SELECT nombre FROM clientes WHERE id = prestamos.idcliente)  ,'')  as nombreCliente,			
			IFNULL( (SELECT ruta FROM rutas_clientes WHERE idClientes = prestamos.idcliente)  ,'upload/user.png')  as foto
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 0)  ,0)  AS morasPendientes   
            ,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0)  ,0)  AS cuotasPendientes
			FROM prestamos WHERE idsupervisor = {$_REQUEST['idusuario']} AND incentivosupervisor = 0 AND id = (SELECT MIN(pr.id) FROM prestamos pr WHERE pr.idcliente = prestamos.idcliente) 
			AND fechaentregado <= ADDDATE('".$_REQUEST['fechainicio']."',-1)";	

			////////////////////////////////////////////////////////////
			//PARA CLIENTES QUE HAN RENOVADO SU PRÉSTAMO
			////////////////////////////////////////////////////////////
			$sql3="SELECT prestamos.*,		
			IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idcobrador)  ,'')  as usuariocobrador,
			IFNULL( (SELECT nombre FROM clientes WHERE id = prestamos.idcliente)  ,'')  as nombreCliente,			
			IFNULL( (SELECT ruta FROM rutas_clientes WHERE idClientes = prestamos.idcliente)  ,'upload/user.png')  as foto
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 0)  ,0)  AS morasPendientes   
            ,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0)  ,0)  AS cuotasPendientes
			FROM prestamos WHERE idsupervisor = {$_REQUEST['idusuario']} AND incentivosupervisor = 0 AND id != (SELECT MIN(pr.id) FROM prestamos pr WHERE pr.idcliente = prestamos.idcliente) 
			AND fechaentregado <= ADDDATE('".$_REQUEST['fechainicio']."',-1)";	

        }


		$result = $conexion->sql($sql);


		$respuesta["reportemeta"] = array();


		foreach ($result as $key => $value1) {			

			$verTipoPrestamo=$conexion->sql("SELECT tipo FROM planesprestamo WHERE idprestamo = {$value1['id']} ");

			if ( $verTipoPrestamo[0]["tipo"] != 5 ) {
			
				
				$rowMeta = array();
						
				$consultasql="SELECT *, date_format(fechapago, '%d-%m-%Y') as fechapago_formateada
				FROM detprestamos WHERE idprestamo = {$value1['id']} 
				AND tipo != 1
				AND fecha <= ADDDATE(CURDATE(),0)";	

				$smo = 0;
				$sma = 0;
				$crda = 0;
				$mrda = 0;

				$detallepagos=$conexion->sql($consultasql);
									
				foreach ($detallepagos as $key => $value2) {
					
					$smo += $value2["monto"];

					if ($value2["morapagada"] != 2) {
						$sma += $value2["mora"];                    
					}
					
					if ($value2["pagado"] == 1) {
						$crda+=$value2["monto"];
					}

					if ($value2["morapagada"] == 1) {
						$mrda += $value2["mora"];                    
					}

					$mrda += $value2["abonomora"];
					$crda+=$value2["abono"];

				}

				$rowMeta["id"] = $value1["id"];
				$rowMeta["nombreCliente"] = $value1["nombreCliente"];
				$rowMeta["usuariocobrador"] = $value1["usuariocobrador"];			
				$rowMeta["foto"] = $value1["foto"];
				$rowMeta["cuotas_pendientes"] = $smo - $crda;
				$rowMeta["moras_pendientes"] = $sma - $mrda;
				$rowMeta["total_pendiente"] = ($smo - $crda) + ($sma - $mrda);

				array_push($respuesta["reportemeta"] , $rowMeta);

			}


		}


		
		$prestamosNuevos = $conexion->sql($sql2);
		$prestamosRenovados = $conexion->sql($sql3);

		$buscardatosempleados = $conexion->sql("SELECT * FROM datosempleados WHERE idusuario = '{$_REQUEST['idusuario']}' ");

		$respuesta["registros"] = $result;
		$respuesta["prestamosNuevos"] = $prestamosNuevos;
		$respuesta["prestamosRenovados"] = $prestamosRenovados;
		$respuesta["datosempleados"] = $buscardatosempleados;
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



function nuevodatosempleados()
{
	try
	{	
		$conexion = new conexion();


		$buscardatosempleados = $conexion->sql("SELECT * FROM datosempleados WHERE idusuario = '{$_REQUEST['idusuario']}' ");

		if (count($buscardatosempleados) > 0){
			$error = 0;

			$sql = "UPDATE datosempleados SET sueldobase= '".$_REQUEST['sueldobase']."',
			metaclientesnuevos= '".$_REQUEST['metaclientesnuevos']."', metarenovaciones='".$_REQUEST['metarenovaciones']."',
			descuentodeudas='".$_REQUEST['descuentodeudas']."', n = '".$_REQUEST['n']."', m = '".$_REQUEST['m']."' WHERE idusuario = {$_REQUEST['idusuario']} ";
			if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 

			if (!$error) {
				$respuesta["mensaje"] = "Datos modificados Exitosamente";
				$respuesta["resultado"] = true;
			}else{
				$respuesta["resultado"] = false;
				$respuesta["mensaje"] = "Datos No ingresados ";
			}

		}else{

			$sql = "INSERT INTO datosempleados (idusuario, sueldobase, metaclientesnuevos, metarenovaciones, descuentodeudas, n, m)
			VALUES ('".$_REQUEST['idusuario']."', '".$_REQUEST['sueldobase']."', '".$_REQUEST['metaclientesnuevos']."',
			'".$_REQUEST['metarenovaciones']."','".$_REQUEST['descuentodeudas']."','".$_REQUEST['n']."','".$_REQUEST['m']."' )";


			$regdatosempleados = $conexion->sqlOperacion($sql);

			if ($regdatosempleados["resultado"] == true ) {
				$respuesta["resultado"] = true;
				$respuesta["mensaje"] = "Datos ingresados correctamente";
			}else{
				$respuesta["resultado"] = false;
				$respuesta["mensaje"] = "Datos No ingresados ";
			}

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


function mostrardatosempleados()
{
	try
	{	
		$conexion = new conexion();
		
		$sql="SELECT * FROM datosempleados WHERE idusuario = '{$_REQUEST['idusuario']}' ";		

		$result = $conexion->sql($sql);
		
		if (count($result) > 0){
			$respuesta["registros"] = $result;
			$respuesta["mensaje"] = "Datos consultados Exitosamente";
			$respuesta["resultado"] = true;
		}else{
			$respuesta["resultado"] = false;
		}
		
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



function nuevoPagoEmpleado(){


	//Queda pendiente el funcionamiento, a la espera de hacer los pagos en los detalles de prestamos

	//Cambiar el estado de los incentivos en los prestamos en esta función
	try
	{	
		$conexion = new conexion();
		$conexion->transaccion();
        $error = 0;

		$_REQUEST["pagoclientesnuevos"]     	= isset($_REQUEST["pagoclientesnuevos"])      	?  $_REQUEST["pagoclientesnuevos"] + 0       	:  0 ;
		$_REQUEST["pagorenovaciones"]     		= isset($_REQUEST["pagorenovaciones"])      	?  $_REQUEST["pagorenovaciones"] + 0       		:  0 ;
		$_REQUEST["pagosueldobase"]     		= isset($_REQUEST["pagosueldobase"])      		?  $_REQUEST["pagosueldobase"] + 0       		:  0 ;
		$_REQUEST["subtotalpagos"]     			= isset($_REQUEST["subtotalpagos"])      		?  $_REQUEST["subtotalpagos"] + 0       		:  0 ;
		$_REQUEST["descuentoPorCuotasMoras"]    = isset($_REQUEST["descuentoPorCuotasMoras"])   ?  $_REQUEST["descuentoPorCuotasMoras"] + 0     :  0 ;
		$_REQUEST["subtotaldescuentos"]     	= isset($_REQUEST["subtotaldescuentos"])      	?  $_REQUEST["subtotaldescuentos"] + 0      	:  0 ;
		$_REQUEST["totalliquido"]     			= isset($_REQUEST["totalliquido"])      		?  $_REQUEST["totalliquido"] + 0       			:  0 ;
		$_REQUEST["depreciacion"]     			= isset($_REQUEST["depreciacion"])      		?  $_REQUEST["depreciacion"] + 0       			:  0 ;
		$_REQUEST["otrosDescuentos"]     		= isset($_REQUEST["otrosDescuentos"])      		?  $_REQUEST["otrosDescuentos"] + 0       		:  0 ;
	
	
		if ($_REQUEST["btnPagoCN"] == 0 ) {
			$_REQUEST["pagoclientesnuevos"] = 0;
		}

		if ($_REQUEST["btnPagoCR"] == 0 ) {
			$_REQUEST["pagorenovaciones"] = 0;
		}

		if ($_REQUEST["btnDescuentoCM"] == 0 ) {
			$_REQUEST["descuentoPorCuotasMoras"] = 0;
		}

		if ($_REQUEST["btnDepreciacion"] == 0 ) {
			$_REQUEST["depreciacion"] = 0;
		}

		if ($_REQUEST["btnotrosDescuentos"] == 0 ) {
			$_REQUEST["otrosDescuentos"] = 0;
		}        


        $sql = "INSERT INTO planilla (idusuario, sueldobase, incentivoclientesnuevos, 
		incentivorenovaciones, subtotalpagos, descuentoPorCuotasMoras,otrosDescuentos, subtotaldescuentos, 
		totalliquido, observaciones, idusuariopago, fecha_pago,depreciacion) 
		VALUES ( '".$_REQUEST['idempleado']."', '".$_REQUEST['pagosueldobase']."', 
		'".$_REQUEST['pagoclientesnuevos']."', '".$_REQUEST["pagorenovaciones"]."', 
		'".$_REQUEST["subtotalpagos"]."', '".$_REQUEST["descuentoPorCuotasMoras"]."', '".$_REQUEST["otrosDescuentos"]."', 
		'".$_REQUEST["subtotaldescuentos"]."', '".$_REQUEST["totalliquido"]."', 
		'".utf8_decode($_REQUEST["newDescripcion"])."', '".$_SESSION["idusuario"]."', NOW(), '".$_REQUEST["depreciacion"]."')";

        $regPlanilla = $conexion->sqlOperacion($sql);

		if ($regPlanilla['resultado'] == false) { $error++; }       



		if ($_REQUEST["btnPagoCN"] == 0 ) {
			$_REQUEST["pagoclientesnuevos"] = 0;
		}else{

			$detalle = preg_replace("/([a-zA-Z0-9_]+?):/" , "\"$1\":", $_REQUEST["arrayPrestamosNuevos"]); // fix variable names 
			$arrayDetalle = json_decode($detalle, true); 	
						
			if ($_REQUEST["idtipousuario"] == 4) {
				//incentivocobrador = 1 de todos los prestamos de la tabla si es un cobrador

				foreach ($arrayDetalle as $key => $value) {
					$sql = "UPDATE prestamos SET incentivocobrador= '1', idplanillacobrador = '{$regPlanilla['ultimoId']}' WHERE id = {$value} ";
					if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
				}

			}else if($_REQUEST["idtipousuario"] == 5){
				//incentivosupervisor = 1 de todos los prestamos de la tabla si es un supervisor

				foreach ($arrayDetalle as $key => $value) {
					$sql = "UPDATE prestamos SET incentivosupervisor= '1', idplanillasupervisor = '{$regPlanilla['ultimoId']}' WHERE id = {$value} ";
					if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
				}
			}

		}

		if ($_REQUEST["btnPagoCR"] == 0 ) {
			$_REQUEST["pagorenovaciones"] = 0;
		}else{

			$detalle = preg_replace("/([a-zA-Z0-9_]+?):/" , "\"$1\":", $_REQUEST["arrayPrestamosRenovados"]); // fix variable names 
			$arrayDetalle = json_decode($detalle, true); 	
						
			if ($_REQUEST["idtipousuario"] == 4) {
				//incentivocobrador = 1 de todos los prestamos de la tabla si es un cobrador

				foreach ($arrayDetalle as $key => $value) {
					$sql = "UPDATE prestamos SET incentivocobrador= '1', idplanillacobrador = '{$regPlanilla['ultimoId']}' WHERE id = {$value} ";
					if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
				}

			}else if($_REQUEST["idtipousuario"] == 5){
				//incentivosupervisor = 1 de todos los prestamos de la tabla si es un supervisor

				foreach ($arrayDetalle as $key => $value) {
					$sql = "UPDATE prestamos SET incentivosupervisor= '1', idplanillasupervisor = '{$regPlanilla['ultimoId']}' WHERE id = {$value} ";
					if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
				}
			}
		}


		if ($_REQUEST["btnDescuentoCM"] == 0 ) {
			$_REQUEST["descuentoPorCuotasMoras"] = 0;
		}else{
			//validar que no sea 0 PARA HACER LOS DESCUENTOS


			$detalle = preg_replace("/([a-zA-Z0-9_]+?):/" , "\"$1\":", $_REQUEST["prestamos_pendientes"]); // fix variable names 
			$arrayDetalle = json_decode($detalle, true); 

			foreach ($arrayDetalle as $key => $valuexx) {

				
				$idapertura = 0;
				$valorMontoPagado = 0;		
				$buscarSumaMoras = $conexion->sql("SELECT IFNULL(SUM(mora) - SUM(abonomora),0) as sumamoras FROM detprestamos 
				WHERE idprestamo = '{$valuexx["id"]}' AND mora > 0
				AND fecha <= ADDDATE(CURDATE(),-1) AND morapagada = 0 OR morapagada = 3 ORDER BY id ASC");
				
				if ( $_REQUEST['porcentajeDescuento'] == 100) {
					$valorMontoPagado = $valuexx["total_pendiente"];
				}else{
					$valorMontoPagado = ceil(($valuexx["total_pendiente"] * $_REQUEST['porcentajeDescuento']) / 100);
				}						
		
				if ( $valorMontoPagado != '' && $buscarSumaMoras[0]["sumamoras"] > 0 ) {
		
					//Entra en este bloque
					//1.	Cuando hay moras pendientes y se seleccionó pagar moras primero
		
					$montoRecibido = $valorMontoPagado;
					$estadopagorealizado = 5; //5=pago de moras y cuotas 
		
					$descripcion_de_pago = $_REQUEST['descripcionPago'];
					if ( $buscarSumaMoras[0]["sumamoras"] >= $montoRecibido ) {
						$descripcion_de_pago = $_REQUEST['descripcionPago'];
						$estadopagorealizado = 4; //4=pago de moras dinámicas //Puede cubrir varias moras, o pagos parciales en moras
					}
		
		
					$buscarDatoCliente = $conexion->sql("SELECT idcliente FROM prestamos WHERE id = '{$valuexx["id"]}' ");			
		
					$regpagosrealizados = $conexion->sqlOperacion("INSERT INTO pagosrealizados (idprestamo, idcliente, idusuario, 
					idcierreganancias, iddeposito, plan, descripcion, monto, fechapago, estado, deposito, idtransaccion, idapertura) 
					VALUES ('".$valuexx["id"]."','".$buscarDatoCliente[0]["idcliente"]."', '".$_SESSION["idusuario"]."', '0','0',
					'0' , '".utf8_decode($descripcion_de_pago)."', '".$montoRecibido."', '".$_REQUEST['fechainicio']."' , '".$estadopagorealizado."','0','0','".$idapertura."' )");
					if ($regpagosrealizados['resultado'] == false) { $error++; }


					$regdescuentoprestamoscobrador = $conexion->sqlOperacion("INSERT INTO descuentoprestamoscobrador 
					(idplanilla, idprestamo, idpagosrealizados, idusuario) 
					VALUES ('{$regPlanilla['ultimoId']}', '{$valuexx["id"]}', '{$regpagosrealizados['ultimoId']}', '".$_REQUEST['idempleado']."')");
					if ($regdescuentoprestamoscobrador['resultado'] == false) { $error++; }

				
					$buscarMorasPendientes = $conexion->sql("SELECT * FROM detprestamos WHERE idprestamo = '{$valuexx["id"]}' AND mora > 0
					AND fecha <= ADDDATE(CURDATE(),-1) AND morapagada = 0 OR morapagada = 3 ORDER BY id ASC");
		
					if (count($buscarMorasPendientes) > 0){
		
						$sumaAbonoMora = 0;
						foreach ($buscarMorasPendientes as $key => $value) {
							$sumaAbonoMora += $value['abonomora'];
						}
		
						$montoRecibido += $sumaAbonoMora;
		
						foreach ($buscarMorasPendientes as $key => $value) {
		
							if ($montoRecibido != 0) {
			
								if ($montoRecibido < $value['mora']) {
		
									//Cuando entra aquí, es porque tiene algún valor sobrante, menor a la mora evaluada y no es 0
							
									$sql = "UPDATE detprestamos SET mora = '".$value['mora']."' , abonomora=".$montoRecibido." ,morapagada= '3' WHERE id = {$value['id']} ";
									if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
									$montoRecibido = 0;
									break;
		
								}else{							
										
									$sql = "UPDATE detprestamos SET mora = '".$value['mora']."', morapagada= '1', abonomora='0' WHERE id = {$value['id']} ";
									if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
									$montoRecibido -= $value['mora'];
		
								}
			
							}else{
							
								break;
		
							}
														
						}
		
						if ($montoRecibido > 0) {
		
							$sql = "UPDATE pagosrealizados SET abonocuota= '".$montoRecibido."' WHERE id = {$regpagosrealizados['ultimoId']} ";
							if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
		
							//Aqui el valor para la variable es: $estadopagorealizado = 5
		
							//Cuando termina el ciclo y aun hay saldo en la variable $montoRecibido
							//Este saldo mandarlo a las cuotas, pero no perderá su origen de este pago
							$valorMontoPagado = $montoRecibido;
		
							//Aquí se copio el bloque de código que esta del otro lado de la condición, 
							//Con la salvedad que no ingresa otro pago
		
		
							$buscarMontoPendiente = $conexion->sql("SELECT (SUM(monto) - SUM(abono)) AS pendiente, SUM(abono) as abono 
							FROM detprestamos WHERE idprestamo = '{$valuexx["id"]}' AND pagado = 0 AND tipo = 0");
							
							if ($valorMontoPagado <= $buscarMontoPendiente[0]["pendiente"] && $valorMontoPagado != '') {
		
								$buscarPrestamo = $conexion->sql("SELECT resumenpagos AS montoUnitario, idcliente FROM prestamos WHERE id = '{$valuexx["id"]}' ");
								
								$montoCobrado = $valorMontoPagado + $buscarMontoPendiente[0]["abono"];
								$montoUnitario = $buscarPrestamo[0]["montoUnitario"];
								$cuotas = floor( ( $montoCobrado / $montoUnitario ) );
								//$abono = $montoCobrado % $montoUnitario;						
								$abono = fmod($montoCobrado,$montoUnitario);						
		
								$buscarCuotasPendiente = $conexion->sql("SELECT id FROM detprestamos 
								WHERE idprestamo = '{$valuexx["id"]}' AND pagado = 0 AND tipo = 0 ORDER BY id ASC LIMIT {$cuotas} ");
		
								foreach ($buscarCuotasPendiente as $key => $value) {
									$sql = "UPDATE detprestamos SET fechapago= '".$_REQUEST['fechainicio']."' , abono = '0', pagado = '1' WHERE id = {$value['id']} ";
									if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
								}
				
								if ($abono > 0) {
									$buscarCuotasPendiente = $conexion->sql("SELECT id FROM detprestamos 
									WHERE idprestamo = '{$valuexx["id"]}' AND pagado = 0 AND tipo = 0 ORDER BY id ASC LIMIT 1 ");
									$sql = "UPDATE detprestamos SET abono='{$abono}' WHERE id = {$buscarCuotasPendiente[0]['id']} ";
									if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
								}					
		
																
							}else{
								
								$error++;
							}
				
						}else{
		
							$valorMontoPagado = 0;
		
							if (!$error) {
								$error = $error;
							}else{
								$error++;
							}
		
						}
											
					}else{
						$error++;
					}
		
		
		
				}else{
		
					//Entrará aquí solo cuando, 
					//1.	No hay moras pendientes  
					//2.	Solo se están cancelando las cuotas (Sin moras)
				
					$buscarMontoPendiente = $conexion->sql("SELECT (SUM(monto) - SUM(abono)) AS pendiente, SUM(abono) as abono 
					FROM detprestamos WHERE idprestamo = '{$valuexx["id"]}' AND pagado = 0 AND tipo = 0");
					
					if ($valorMontoPagado <= $buscarMontoPendiente[0]["pendiente"] && $valorMontoPagado != '') {
		
						$buscarPrestamo = $conexion->sql("SELECT resumenpagos AS montoUnitario, idcliente FROM prestamos WHERE id = '{$valuexx["id"]}' ");
						
						$montoCobrado = $valorMontoPagado + $buscarMontoPendiente[0]["abono"];
						$montoUnitario = $buscarPrestamo[0]["montoUnitario"];
						$cuotas = floor( ( $montoCobrado / $montoUnitario ) );
						//$abono = $montoCobrado % $montoUnitario;
						$abono = fmod($montoCobrado,$montoUnitario);
		
						$regpagosrealizados = $conexion->sqlOperacion("INSERT INTO pagosrealizados (idprestamo, idcliente, idusuario, 
						idcierreganancias, iddeposito, plan, descripcion, monto, fechapago, estado, deposito, idtransaccion, idapertura) 
						VALUES ('".$valuexx["id"]."','".$buscarPrestamo[0]["idcliente"]."', '".$_SESSION["idusuario"]."', '0','0',
						'0' , '".utf8_decode($_REQUEST['descripcionPago'])."', '".$valorMontoPagado."', '".$_REQUEST['fechainicio']."' , '1','0','0', '".$idapertura."' )");
						if ($regpagosrealizados['resultado'] == false) { $error++; }

						$regdescuentoprestamoscobrador = $conexion->sqlOperacion("INSERT INTO descuentoprestamoscobrador 
						(idplanilla, idprestamo, idpagosrealizados, idusuario) 
						VALUES ('{$regPlanilla['ultimoId']}', '{$valuexx["id"]}', '{$regpagosrealizados['ultimoId']}', '".$_REQUEST['idempleado']."')");
						if ($regdescuentoprestamoscobrador['resultado'] == false) { $error++; }


						$buscarCuotasPendiente = $conexion->sql("SELECT id FROM detprestamos 
						WHERE idprestamo = '{$valuexx["id"]}' AND pagado = 0 AND tipo = 0 ORDER BY id ASC LIMIT {$cuotas} ");
		
						foreach ($buscarCuotasPendiente as $key => $value) {
							$sql = "UPDATE detprestamos SET fechapago= '".$_REQUEST['fechainicio']."' , abono = '0', pagado = '1' WHERE id = {$value['id']} ";
							if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
						}
		
		
						if ($abono > 0) {
							$buscarCuotasPendiente = $conexion->sql("SELECT id FROM detprestamos 
							WHERE idprestamo = '{$valuexx["id"]}' AND pagado = 0 AND tipo = 0 ORDER BY id ASC LIMIT 1 ");
							$sql = "UPDATE detprestamos SET abono='{$abono}' WHERE id = {$buscarCuotasPendiente[0]['id']} ";
							if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
						}
						
				
						if (!$error) {
							$error = $error;
						}else{
							$error++;
						}
						
					}else{
						$error++;
					}
				
				}
				
			}
			
		}











		if (!$error) {
			$respuesta["resultado"] = true;     
			$conexion->respuestaTrans("COMMIT");
			$respuesta["mensaje"] = "Datos ingresados correctamente ";
		}else{
			$respuesta["resultado"] = false;
			$conexion->respuestaTrans("ROLLBACK");
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


function mostrarDetallleDescuentos(){
	try
	{	
		$conexion = new conexion();
		

        $sql=" SELECT *,
		IFNULL( (SELECT nombre FROM clientes 
				 INNER JOIN prestamos ON clientes.id = prestamos.idcliente
				 WHERE prestamos.id = descuentoprestamoscobrador.idprestamo)  ,'')  as nombreCliente
		FROM descuentoprestamoscobrador
		INNER JOIN pagosrealizados ON descuentoprestamoscobrador.idpagosrealizados = pagosrealizados.id
		WHERE idplanilla = {$_REQUEST['idplanilla']} ";

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


function eliminarPagoEmpleado(){

	try
	{	
		$conexion = new conexion();
		$conexion->transaccion();
		$error = 0;		


        $sql=" SELECT *
		FROM descuentoprestamoscobrador
		INNER JOIN pagosrealizados ON descuentoprestamoscobrador.idpagosrealizados = pagosrealizados.id
		WHERE idplanilla = {$_REQUEST['idplanilla']} ";
		$verPagosHechos = $conexion->sql($sql);


		if(count($verPagosHechos) > 0){		
						
			$respuesta["registros"] = $result;
			$conexion->respuestaTrans("ROLLBACK");
			$respuesta["mensaje"] = "Para eliminar este pago, primero debe eliminar los pagos en los préstamos pendientes";
			$respuesta["resultado"] = false;
						
		}else{


			$resPrestamos = mysql_query("DELETE FROM descuentoprestamoscobrador WHERE idplanilla= {$_REQUEST['idplanilla']} ");	

			if ($_REQUEST["idtipousuario"] == 4) {
				
				$sql = "UPDATE prestamos SET incentivocobrador= '0', idplanillacobrador = '0' WHERE idplanillacobrador = {$_REQUEST['idplanilla']} ";
				if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 

			}else if($_REQUEST["idtipousuario"] == 5){

				$sql = "UPDATE prestamos SET incentivosupervisor= '0', idplanillasupervisor = '0' WHERE idplanillasupervisor = {$_REQUEST['idplanilla']} ";
				if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
			}


			$resPrestamos = mysql_query("DELETE FROM planilla WHERE id= {$_REQUEST['idplanilla']} ");	

			if (!$error) {
				$conexion->respuestaTrans("COMMIT");
				$respuesta["mensaje"] = "Datos eliminados Exitosamente";
				$respuesta["resultado"] = true;
			}else{
				$respuesta["resultado"] = false;
				$conexion->respuestaTrans("ROLLBACK");
				$respuesta["mensaje"] = "Datos no eliminados ";
			}

		
		}
		
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