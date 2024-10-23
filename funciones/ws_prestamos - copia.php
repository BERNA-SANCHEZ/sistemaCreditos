<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors",0);
require_once("classSQL.php");

$accion =$_REQUEST['accion'];

switch ($accion) {
	case 'nuevo':
			nuevoPrestamo();
		break;
	case 'editar':
			editarPrestamo();
		break;
	case 'eliminar':
			eliminarPrestamo();
		break;
	case 'mostrar':
			mostrarPrestamo();
		break;
    case 'mostrarRP':
			mostrarRP();
		break;            
    case 'mostrarDetPrestamo':
			mostrarDetPrestamo();
		break;
	case 'nuevoPago':
			nuevoPago();
		break;
	case 'pagoCapital_plan5':
			pagoCapital_plan5();
		break;
	case 'pagoInteres_plan5':
			pagoInteres_plan5();
		break;
	case 'eliminarPago':
			eliminarPago();
		break;
	case 'eliminarInteres_plan5':
			eliminarInteres_plan5();
		break;		
	case 'mostrarMorasXprestamo':
			mostrarMorasXprestamo();
		break;	
	case 'exonerarMora':
			exonerarMora();
		break;		
	case 'pagarMora':
			pagarMora();
		break;
	case 'mostrarPrestamosCobrador':
			mostrarPrestamosCobrador();
		break;
	case 'eliminarCapital_plan5':
			eliminarCapital_plan5();
		break;
	case 'finalizarPrestamo':
			finalizarPrestamo();
		break;
	case 'actualizarPrestamosActivos':
			actualizarPrestamosActivos();
		break;		
	case 'verificarPago':
			verificarPago();
		break;
	case 'codigosiguiente':
			codigosiguiente();
		break;
	case 'prestamosPendientesClientes':
			prestamosPendientesClientes();
		break;
		
}


function nuevoPrestamo()
{
	try
	{	
		$conexion = new conexion();
        $conexion->transaccion();

        $error = 0;
		$contPrimeraCuota = 0;		
		
		$idapertura = 0;
		$verCaja = $conexion->sql("SELECT cajas.id, cajas.estado , cajas.descripcion as caja, cajasaperturas.id as idapertura FROM usuarios		
		INNER JOIN cajas ON cajas.idusuario = usuarios.id
		INNER JOIN cajasaperturas ON cajasaperturas.idcaja = cajas.id
		WHERE cajasaperturas.idusuarioinicio = {$_SESSION["idusuario"]} AND usuarios.idapertura = cajasaperturas.id ORDER BY cajasaperturas.id DESC LIMIT 1");

		if(count( $verCaja ) > 0){
			if($verCaja[0]["estado"] == 1 ){
				$idapertura = $verCaja[0]["idapertura"];
			}
		}


		$procederPrestamo = 0;
		$verAccesoCaja = $conexion->sql("SELECT accesocaja FROM usuarios WHERE id = {$_SESSION["idusuario"]} ");
		if ( $verAccesoCaja[0]["accesocaja"] == 1 && $idapertura == 0 ) {
			$respuesta["resultado"] = false;
			$conexion->respuestaTrans("ROLLBACK");
            $respuesta["mensaje"] = "No se puede generar el préstamo porque la caja está cerrada.";
			$procederPrestamo = 0;
		}else if($verAccesoCaja[0]["accesocaja"] == 1 && $idapertura != 0){

			$verSaldoActual = $conexion->sql("SELECT                               
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
				),2) as saldoactual FROM   cajas c
			INNER JOIN cajasaperturas ca ON ca.idcaja = c.id
			WHERE c.id = {$verCaja[0]["id"]}
			ORDER BY ca.id DESC
			LIMIT 1");

			if ( $verSaldoActual[0]["saldoactual"] >= $_REQUEST['capitalEntregado']) {
				$procederPrestamo = 1;				
			}else{
				$respuesta["resultado"] = false;
				$conexion->respuestaTrans("ROLLBACK");
				$respuesta["mensaje"] = "No hay saldo suficiente en la caja para hacer el préstamo";
				$procederPrestamo = 0;
			}

		}else if($verAccesoCaja[0]["accesocaja"] == 0){
			$procederPrestamo = 1;
		}


		if ( $procederPrestamo == 1 ) {

			


			$buscarPlan = $conexion->sql("SELECT cuotas, tipo, dias FROM planes WHERE id = '{$_REQUEST['idplan']}' ");            
			
			$sql = "INSERT INTO prestamos (idcliente, idusuario, codigo, fechaentregado, 
			horapago, mora, prestamo, resumenpagos, idcobrador, idsupervisor, idapertura, capitalEntregado) 
			VALUES ('".$_REQUEST['idcliente']."',
			'".$_SESSION["idusuario"]."',
			'".utf8_decode($_REQUEST['newCodigo'])."',
			NOW(),
			'".$_REQUEST['horapago']."',
			'0',
			'".$_REQUEST['newCapital']."',
			'".$_REQUEST['cuotaSeleccionada']."',       
			'".$_REQUEST['idcobrador']."',
			'".$_REQUEST['idsupervisor']."',
			'".$idapertura."',
			'".$_REQUEST['capitalEntregado']."')";

			$regPrestamo = $conexion->sqlOperacion($sql);
			if ($regPrestamo['resultado'] == false) { $error++; }

			$regplanesprestamo = $conexion->sqlOperacion("INSERT INTO planesprestamo ( nombre, cuotas, interes, tipo, dias, idprestamo, n, m, moraincrementable, totalMora) 
			SELECT nombre, cuotas, interes, tipo, dias, '".$regPrestamo['ultimoId']."', '".$_REQUEST["newn"]."',
			'".$_REQUEST["newm"]."', '".$_REQUEST["moraincrementable"]."' , '".$_REQUEST["montoTotalMora"]."' FROM planes WHERE id = '{$_REQUEST['idplan']}' ");

			if ( $regplanesprestamo["resultado"] == false ) { $error++; }


			//Se inserta el primer pago por papelería

			if ($_REQUEST["cobrarPapeleria"] == 1){
		
				$regcobroPapeleria = $conexion->sqlOperacion("INSERT INTO detprestamos(idprestamo, fecha, fechapago, monto, abono, pagado, plan, tipo) 
				VALUES ('".$regPrestamo['ultimoId']."', NOW(), NOW(), '".$_REQUEST['cobroPapeleria']."', '0','1','".$_REQUEST['idplan']."','1' )");
				if ($regcobroPapeleria['resultado'] == false) { $error++; }

				$regpagosrealizados = $conexion->sqlOperacion("INSERT INTO pagosrealizados (idprestamo, idcliente, idusuario, 
				idcierreganancias, iddeposito, plan, descripcion, monto, fechapago, estado, deposito, idtransaccion,verificado,idapertura) 
				VALUES ('".$regPrestamo['ultimoId']."','".$_REQUEST['idcliente']."',  '".$_SESSION["idusuario"]."', '0','0',
				'0' , '".utf8_decode("Pago por papelería")."', '".$_REQUEST['cobroPapeleria']."', NOW(), '3','0','0','1','".$idapertura."')");
				if ($regpagosrealizados['resultado'] == false) { $error++; }

			}

			//Se inserta el segundo pago por días festivos
			$_REQUEST["cobroDiasFestivos"] = isset($_REQUEST["cobroDiasFestivos"]) ? $_REQUEST["cobroDiasFestivos"] + 0 : 0;
			if ($_REQUEST["cobroDiasFestivos"] > 0) {

				$regpagosrealizados = $conexion->sqlOperacion("INSERT INTO pagosrealizados (idprestamo, idcliente, idusuario, 
				idcierreganancias, iddeposito, plan, descripcion, monto, fechapago, estado, deposito, idtransaccion,verificado,idapertura) 
				VALUES ('".$regPrestamo['ultimoId']."','".$_REQUEST['idcliente']."',  '".$_SESSION["idusuario"]."', '0','0',
				'0' , '".utf8_decode("Pago por días festivos")."', '".$_REQUEST['cobroDiasFestivos']."', NOW(), '3','0','0','1','".$idapertura."')");
				if ($regpagosrealizados['resultado'] == false) { $error++; }

			}

			//Pago de la primera cuota
			if ($_REQUEST["cobrarPrimeraCuota"] == 1) {

				$regpagosrealizados = $conexion->sqlOperacion("INSERT INTO pagosrealizados (idprestamo, idcliente, idusuario, 
				idcierreganancias, iddeposito, plan, descripcion, monto, fechapago, estado, deposito, idtransaccion,verificado,idapertura) 
				VALUES ('".$regPrestamo['ultimoId']."','".$_REQUEST['idcliente']."',  '".$_SESSION["idusuario"]."', '0','0',
				'0' , '".utf8_decode("Cobro primera cuota")."', '".$_REQUEST['cuotaSeleccionada']."', NOW(), '3','0','0','1','".$idapertura."')");
				if ($regpagosrealizados['resultado'] == false) { $error++; }

			}		

			$det_festivos = preg_replace("/([a-zA-Z0-9_]+?):/" , "\"$1\":", $_REQUEST["det_festivos"]); // fix variable names 
			$arrayDiasFestivos = json_decode($det_festivos, true);
			
			$fechainicioX = $_REQUEST['fechainicio']; #Por ahora esta en formato YYYY-MM-DD

			if ($buscarPlan[0]["tipo"] == 1) {

				$dias_pago = explode(";", $buscarPlan[0]["dias"] );

				for ($i=0; $i < $buscarPlan[0]["cuotas"]; $i++) { 
					

					if ($contPrimeraCuota > 0) {
						$fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 1 day"));
					}

					$dia = date("N", strtotime($fechainicioX));//aquí voy contando los días.

					if (in_array($dia, $dias_pago)) { // Si se encuentra en los días de pago, entonces lo inserta

						if ( !in_array( $fechainicioX, $arrayDiasFestivos ) ) {
							$regDetPrestamo = $conexion->sqlOperacion("INSERT INTO detprestamos(idprestamo, fecha, monto, abono, pagado, plan) 
							VALUES ('".$regPrestamo['ultimoId']."', '".$fechainicioX."', '".$_REQUEST['cuotaSeleccionada']."', '0','0','".$_REQUEST['idplan']."' )");
							if ($regDetPrestamo['resultado'] == false) { $error++; }
						}else{

							//Aqui se hacen los pagos de días festivos
							$regDetPrestamo = $conexion->sqlOperacion("INSERT INTO detprestamos(idprestamo, fecha,fechapago, monto, abono, pagado, plan, tipo) 
							VALUES ('".$regPrestamo['ultimoId']."', '".$fechainicioX."', NOW(),'".$_REQUEST['cuotaSeleccionada']."', '0','1','".$_REQUEST['idplan']."','2' )");
							if ($regDetPrestamo['resultado'] == false) { $error++; }

						}
								
						
					}else{
						$i--;
					}

					$contPrimeraCuota++;
				}

			}else if($buscarPlan[0]["tipo"] == 2){

				for ($i=0; $i < $buscarPlan[0]["cuotas"]; $i++) { 

					if ($contPrimeraCuota > 0){
						$fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 7 day"));
					}
					
					if ( !in_array( $fechainicioX, $arrayDiasFestivos ) ) {
						
						$regDetPrestamo = $conexion->sqlOperacion("INSERT INTO detprestamos(idprestamo, fecha, monto, abono, pagado, plan) 
						VALUES ('".$regPrestamo['ultimoId']."', '".$fechainicioX."', '".$_REQUEST['cuotaSeleccionada']."', '0','0','".$_REQUEST['idplan']."' )");
						if ($regDetPrestamo['resultado'] == false) { $error++; }

					}else{

						//Aqui se hacen los pagos de días festivos
						$regDetPrestamo = $conexion->sqlOperacion("INSERT INTO detprestamos(idprestamo, fecha,fechapago, monto, abono, pagado, plan, tipo) 
						VALUES ('".$regPrestamo['ultimoId']."', '".$fechainicioX."', NOW(),'".$_REQUEST['cuotaSeleccionada']."', '0','1','".$_REQUEST['idplan']."','2' )");
						if ($regDetPrestamo['resultado'] == false) { $error++; }

					}


					$contPrimeraCuota++;
				}

			}else if($buscarPlan[0]["tipo"] == 3){

				for ($i=0; $i < $buscarPlan[0]["cuotas"]; $i++) { 
					
					if ($contPrimeraCuota > 0){
						$fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 2 week")); //Consultar esto
					}
					
					if ( !in_array( $fechainicioX, $arrayDiasFestivos ) ) {
						
						$regDetPrestamo = $conexion->sqlOperacion("INSERT INTO detprestamos(idprestamo, fecha, monto, abono, pagado, plan) 
						VALUES ('".$regPrestamo['ultimoId']."', '".$fechainicioX."', '".$_REQUEST['cuotaSeleccionada']."', '0','0','".$_REQUEST['idplan']."' )");
						if ($regDetPrestamo['resultado'] == false) { $error++; }

					}else{

						//Aqui se hacen los pagos de días festivos
						$regDetPrestamo = $conexion->sqlOperacion("INSERT INTO detprestamos(idprestamo, fecha,fechapago, monto, abono, pagado, plan, tipo) 
						VALUES ('".$regPrestamo['ultimoId']."', '".$fechainicioX."', NOW(),'".$_REQUEST['cuotaSeleccionada']."', '0','1','".$_REQUEST['idplan']."','2' )");
						if ($regDetPrestamo['resultado'] == false) { $error++; }

					}

					$contPrimeraCuota++;
				}

			}else if($buscarPlan[0]["tipo"] == 4 || $buscarPlan[0]["tipo"] == 5){

				for ($i=0; $i < $buscarPlan[0]["cuotas"]; $i++) { 

					if ($contPrimeraCuota > 0){
						$fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 1 month"));
					}
									
					if ( !in_array( $fechainicioX, $arrayDiasFestivos ) ) {
						
						$regDetPrestamo = $conexion->sqlOperacion("INSERT INTO detprestamos(idprestamo, fecha, monto, abono, pagado, plan) 
						VALUES ('".$regPrestamo['ultimoId']."', '".$fechainicioX."', '".$_REQUEST['cuotaSeleccionada']."', '0','0','".$_REQUEST['idplan']."' )");
						if ($regDetPrestamo['resultado'] == false) { $error++; }

					}else{

						//Aqui se hacen los pagos de días festivos
						$regDetPrestamo = $conexion->sqlOperacion("INSERT INTO detprestamos(idprestamo, fecha,fechapago, monto, abono, pagado, plan, tipo) 
						VALUES ('".$regPrestamo['ultimoId']."', '".$fechainicioX."', NOW(),'".$_REQUEST['cuotaSeleccionada']."', '0','1','".$_REQUEST['idplan']."','2' )");
						if ($regDetPrestamo['resultado'] == false) { $error++; }

					}

					$contPrimeraCuota++;
					
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



	}
	catch (Exception $e)
	{
		$respuesta['resultado']=false;
		$respuesta['mensaje']=$e;
	}

	echo json_encode( $respuesta );
	$conexion->respuestaTrans("COMMIT");	
}

function editarPrestamo()
{

   
}

function eliminarPrestamo()
{
    
	try
	{	
		$conexion = new conexion();
        $conexion->transaccion();

        $resPrestamos = mysql_query("DELETE FROM detprestamos WHERE idprestamo={$_REQUEST['idprestamo']}");	

		//eliminamos solo el pago por papelería (Y días festivos si hubiera)
        $resPrestamos = mysql_query("DELETE FROM pagosrealizados WHERE idprestamo={$_REQUEST['idprestamo']} AND estado = 3");		

		if ($resPrestamos) {
			$resPrestamos = mysql_query("DELETE FROM planesprestamo WHERE idprestamo={$_REQUEST['idprestamo']}");
		}
		$resPrestamos = mysql_query("DELETE FROM prestamos WHERE id={$_REQUEST['idprestamo']}");

        if($resPrestamos){
			$respuesta["resultado"] = true;
			$conexion->respuestaTrans("COMMIT");
			$respuesta["mensaje"] = "Registro eliminado";
		}else{
			$respuesta["resultado"] = false;
			$conexion->respuestaTrans("ROLLBACK");
			$respuesta["mensaje"] = "Registro NO eliminado, verifique Pagos realizados";
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


function mostrarPrestamo()
{
    
	try
	{	
		$conexion = new conexion();
		
		if ($_REQUEST['id']) {
			$sql="SELECT * FROM prestamos WHERE id = {$_REQUEST['id']}  ";
		}else{
			$sql="SELECT *, 
            IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idusuario)  ,'')  as usuarioentrego,
            IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idcobrador)  ,'')  as usuariocobrador,
            IFNULL( (SELECT nombre FROM clientes WHERE id = prestamos.idcliente)  ,'')  as nombreCliente,
            IFNULL( (SELECT cuotas FROM planesprestamo WHERE idprestamo = prestamos.id)  ,'')  as cuotas
            FROM prestamos ";			
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



function mostrarRP()
{
    
	try
	{	
		$conexion = new conexion();
		
        $sql="SELECT *, 
		IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idusuario)  ,'')  as usuarioentrego,
		IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idcobrador)  ,'')  as usuariocobrador,
		IFNULL( (SELECT nombre FROM clientes WHERE id = prestamos.idcliente)  ,'')  as nombreCliente,
		IFNULL( (SELECT cuotas FROM planesprestamo WHERE idprestamo = prestamos.id)  ,'')  as cuotas,
		IFNULL( (SELECT tipo FROM planesprestamo WHERE idprestamo = prestamos.id)  ,'')  as tipoPlan,
		IFNULL( (SELECT (SUM(monto) - SUM(abono)) AS pendiente FROM detprestamos WHERE idprestamo = prestamos.id AND pagado = 0 )  ,0)  AS pendiente

		,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 1)  ,0)  AS morasPagadas
        ,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 0)  ,0)  AS morasPendientes
        ,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 2)  ,0)  AS morasExoneradas
		,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0)  ,0)  AS cuotasPendientes
		
		FROM prestamos WHERE idcliente = {$_REQUEST['idcliente']} ";	


		$morasPagadas = $conexion->sql("SELECT COUNT(*) AS morasPagadas FROM prestamos p
		INNER JOIN detprestamos dp ON p.id = dp.idprestamo
		WHERE p.idcliente = {$_REQUEST['idcliente']} AND dp.mora != 0 AND dp.morapagada = 1");

		$morasPendientes = $conexion->sql("SELECT COUNT(*) AS morasPendientes FROM prestamos p
		INNER JOIN detprestamos dp ON p.id = dp.idprestamo
		WHERE p.idcliente = {$_REQUEST['idcliente']} AND dp.mora != 0 AND dp.morapagada = 0");

		$morasExoneradas = $conexion->sql("SELECT COUNT(*) AS morasExoneradas FROM prestamos p
		INNER JOIN detprestamos dp ON p.id = dp.idprestamo
		WHERE p.idcliente = {$_REQUEST['idcliente']} AND dp.mora != 0 AND dp.morapagada = 2");

		$prestamosRealizados = $conexion->sql("SELECT COUNT(*) AS prestamosRealizados FROM prestamos 
		WHERE idcliente =  {$_REQUEST['idcliente']}");


		$respuesta["morasPagadas"] = $morasPagadas[0]["morasPagadas"];
		$respuesta["morasPendientes"] = $morasPendientes[0]["morasPendientes"];
		$respuesta["morasExoneradas"] = $morasExoneradas[0]["morasExoneradas"];
		$respuesta["prestamosRealizados"] = $prestamosRealizados[0]["prestamosRealizados"];


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


function mostrarPrestamosCobrador()
{

	try
	{	
		$conexion = new conexion();

		$buscarTipoUsuario = $conexion->sql("SELECT u.idtipousuario, tp.descripcion as tipousuario			
		FROM usuarios u
		INNER JOIN tiposusuarios tp ON tp.id = u.idtipousuario
		WHERE u.id = '{$_REQUEST['id_usuario']}' ");


		$queryFiltroPrestamos = "";
		$queryFiltroCobrador = "";
		$queryFiltroSupervisor = "";
		$fecha_hoy = date("Y-m-d");

		//Si se va agregar un supervisor solo hay que cambiar idcobrador por idsupervisor en todas las consultas


		if ($_REQUEST['checkNoVerificado'] == 1) {

			$queryFiltroPrestamos = " WHERE estado = 1 AND (SELECT COUNT(*) FROM pagosrealizados WHERE idprestamo = prestamos.id AND verificado = 0) > 0 ";
			$queryFiltroCobrador = 	" WHERE idcobrador = ".$_REQUEST['id_usuario']." AND estado = 1 AND (SELECT COUNT(*) FROM pagosrealizados WHERE idprestamo = prestamos.id AND verificado = 0) > 0 ";
			$queryFiltroSupervisor = " WHERE idsupervisor = ".$_REQUEST['id_usuario']." AND estado = 1 AND (SELECT COUNT(*) FROM pagosrealizados WHERE idprestamo = prestamos.id AND verificado = 0) > 0 ";

		}else if ($_REQUEST['checkTODOS'] == 1){

			$queryFiltroPrestamos = " WHERE estado = 1 ";
			$queryFiltroCobrador = 	" WHERE idcobrador = ".$_REQUEST['id_usuario']." AND estado = 1 ";
			$queryFiltroSupervisor = " WHERE idsupervisor = ".$_REQUEST['id_usuario']." AND estado = 1 ";

		}else{

			if ($_REQUEST['checkDIA'] == 1 && $_REQUEST['checkPENDIENTES'] == 0){

				$queryFiltroPrestamos = " 
            	INNER JOIN detprestamos ON prestamos.id = detprestamos.idprestamo
				WHERE prestamos.estado = 1
				AND detprestamos.fecha = '".$fecha_hoy."' AND detprestamos.pagado = 0 AND detprestamos.tipo = 0 GROUP BY prestamos.id ";

				$queryFiltroCobrador = " 
            	INNER JOIN detprestamos ON prestamos.id = detprestamos.idprestamo
				WHERE prestamos.estado = 1 AND prestamos.idcobrador = ".$_REQUEST['id_usuario']."
				AND detprestamos.fecha = '".$fecha_hoy."' AND detprestamos.pagado = 0 AND detprestamos.tipo = 0 GROUP BY prestamos.id ";

				$queryFiltroSupervisor = " 
            	INNER JOIN detprestamos ON prestamos.id = detprestamos.idprestamo
				WHERE prestamos.estado = 1 AND prestamos.idsupervisor = ".$_REQUEST['id_usuario']."
				AND detprestamos.fecha = '".$fecha_hoy."' AND detprestamos.pagado = 0 AND detprestamos.tipo = 0 GROUP BY prestamos.id ";
				


			}else if ($_REQUEST['checkDIA'] == 0 && $_REQUEST['checkPENDIENTES'] == 1){

				$queryFiltroPrestamos = " WHERE estado = 1 AND (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0) > 0 ";
				
				$queryFiltroCobrador = " WHERE estado = 1 AND idcobrador = ".$_REQUEST['id_usuario']." AND (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0) > 0 ";

				$queryFiltroSupervisor = " WHERE estado = 1 AND idsupervisor = ".$_REQUEST['id_usuario']." AND (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0) > 0 ";


			}else if ($_REQUEST['checkDIA'] == 1 && $_REQUEST['checkPENDIENTES'] == 1){

				$queryFiltroPrestamos = " INNER JOIN detprestamos ON prestamos.id = detprestamos.idprestamo
				WHERE prestamos.estado = 1
				AND detprestamos.fecha = '".$fecha_hoy."' AND detprestamos.pagado = 0 AND detprestamos.tipo = 0 
				OR (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0) > 0
				GROUP BY prestamos.id ";

				$queryFiltroCobrador = " INNER JOIN detprestamos ON prestamos.id = detprestamos.idprestamo
				WHERE prestamos.estado = 1 AND prestamos.idcobrador = ".$_REQUEST['id_usuario']."
				AND detprestamos.fecha = '".$fecha_hoy."' AND detprestamos.pagado = 0 AND detprestamos.tipo = 0 
				OR (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0
				AND idprestamo IN ( SELECT id FROM prestamos WHERE idcobrador = ".$_REQUEST['id_usuario']." AND estado = 1 ) ) > 0
				GROUP BY prestamos.id ";

				$queryFiltroSupervisor = " INNER JOIN detprestamos ON prestamos.id = detprestamos.idprestamo
				WHERE prestamos.estado = 1 AND prestamos.idsupervisor = ".$_REQUEST['id_usuario']."
				AND detprestamos.fecha = '".$fecha_hoy."' AND detprestamos.pagado = 0 AND detprestamos.tipo = 0 
				OR (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0
				AND idprestamo IN ( SELECT id FROM prestamos WHERE idsupervisor = ".$_REQUEST['id_usuario']." AND estado = 1 ) ) > 0
				GROUP BY prestamos.id ";


			}else{

				$queryFiltroPrestamos = " WHERE estado > 2 ";
				$queryFiltroCobrador = " WHERE estado > 2 ";
				$queryFiltroSupervisor = " WHERE estado > 2 ";

			}

		}
		

		if ($buscarTipoUsuario[0]["idtipousuario"] == 5) {	

			$sql="SELECT prestamos.*, 
			IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idusuario)  ,'')  as usuarioentrego,
			IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idcobrador)  ,'')  as usuariocobrador,
			IFNULL( (SELECT nombre FROM clientes WHERE id = prestamos.idcliente)  ,'')  as nombreCliente,
			IFNULL( (SELECT direccionvive FROM clientes WHERE id = prestamos.idcliente)  ,'')  as direccionvive,
			IFNULL( (SELECT cuotas FROM planesprestamo WHERE idprestamo = prestamos.id)  ,'')  as cuotas,
			IFNULL( (SELECT tipo FROM planesprestamo WHERE idprestamo = prestamos.id)  ,'')  as tipoPlan,
			IFNULL( (SELECT (SUM(monto) - SUM(abono)) AS pendiente FROM detprestamos WHERE idprestamo = prestamos.id AND pagado = 0 )  ,0)  AS pendiente

			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 1)  ,0)  AS morasPagadas
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 0)  ,0)  AS morasPendientes
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 2)  ,0)  AS morasExoneradas
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0)  ,0)  AS cuotasPendientes
			,IFNULL( (SELECT COUNT(*) FROM pagosrealizados WHERE idprestamo = prestamos.id AND verificado = 0)  ,0)  AS pagosNoVerificados
			,IFNULL( (SELECT ruta FROM rutas_clientes WHERE idClientes = prestamos.idcliente)  ,'upload/user.png')  as foto

			FROM prestamos $queryFiltroSupervisor ";	


			$morasPagadas = $conexion->sql("SELECT COUNT(*) AS morasPagadas FROM prestamos p
			INNER JOIN detprestamos dp ON p.id = dp.idprestamo
			WHERE p.idsupervisor = {$_REQUEST['id_usuario']} AND p.estado = 1 AND dp.mora != 0 AND dp.morapagada = 1");

			$morasPendientes = $conexion->sql("SELECT COUNT(*) AS morasPendientes FROM prestamos p
			INNER JOIN detprestamos dp ON p.id = dp.idprestamo
			WHERE p.idsupervisor = {$_REQUEST['id_usuario']} AND p.estado = 1 AND dp.mora != 0 AND dp.morapagada = 0");

			$morasExoneradas = $conexion->sql("SELECT COUNT(*) AS morasExoneradas FROM prestamos p
			INNER JOIN detprestamos dp ON p.id = dp.idprestamo
			WHERE p.idsupervisor = {$_REQUEST['id_usuario']} AND p.estado = 1 AND dp.mora != 0 AND dp.morapagada = 2");

			$prestamosRealizados = $conexion->sql("SELECT COUNT(*) AS prestamosRealizados FROM prestamos 
			WHERE idsupervisor = {$_REQUEST['id_usuario']} AND estado = 1");			

		}else if ($buscarTipoUsuario[0]["idtipousuario"] == 4){
			

			$sql="SELECT prestamos.*, 
			IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idusuario)  ,'')  as usuarioentrego,
			IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idcobrador)  ,'')  as usuariocobrador,
			IFNULL( (SELECT nombre FROM clientes WHERE id = prestamos.idcliente)  ,'')  as nombreCliente,
			IFNULL( (SELECT direccionvive FROM clientes WHERE id = prestamos.idcliente)  ,'')  as direccionvive,
			IFNULL( (SELECT cuotas FROM planesprestamo WHERE idprestamo = prestamos.id)  ,'')  as cuotas,
			IFNULL( (SELECT tipo FROM planesprestamo WHERE idprestamo = prestamos.id)  ,'')  as tipoPlan,
			IFNULL( (SELECT (SUM(monto) - SUM(abono)) AS pendiente FROM detprestamos WHERE idprestamo = prestamos.id AND pagado = 0 )  ,0)  AS pendiente

			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 1)  ,0)  AS morasPagadas
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 0)  ,0)  AS morasPendientes
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 2)  ,0)  AS morasExoneradas
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0)  ,0)  AS cuotasPendientes
			,IFNULL( (SELECT COUNT(*) FROM pagosrealizados WHERE idprestamo = prestamos.id AND verificado = 0)  ,0)  AS pagosNoVerificados
			,IFNULL( (SELECT ruta FROM rutas_clientes WHERE idClientes = prestamos.idcliente)  ,'upload/user.png')  as foto

			FROM prestamos $queryFiltroCobrador ";	


			$morasPagadas = $conexion->sql("SELECT COUNT(*) AS morasPagadas FROM prestamos p
			INNER JOIN detprestamos dp ON p.id = dp.idprestamo
			WHERE p.idcobrador = {$_REQUEST['id_usuario']} AND p.estado = 1 AND dp.mora != 0 AND dp.morapagada = 1");

			$morasPendientes = $conexion->sql("SELECT COUNT(*) AS morasPendientes FROM prestamos p
			INNER JOIN detprestamos dp ON p.id = dp.idprestamo
			WHERE p.idcobrador = {$_REQUEST['id_usuario']} AND p.estado = 1 AND dp.mora != 0 AND dp.morapagada = 0");

			$morasExoneradas = $conexion->sql("SELECT COUNT(*) AS morasExoneradas FROM prestamos p
			INNER JOIN detprestamos dp ON p.id = dp.idprestamo
			WHERE p.idcobrador = {$_REQUEST['id_usuario']} AND p.estado = 1 AND dp.mora != 0 AND dp.morapagada = 2");

			$prestamosRealizados = $conexion->sql("SELECT COUNT(*) AS prestamosRealizados FROM prestamos 
			WHERE idcobrador = {$_REQUEST['id_usuario']} AND estado = 1");				
			
		}else{

			$sql="SELECT prestamos.*, 
			IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idusuario)  ,'')  as usuarioentrego,
			IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idcobrador)  ,'')  as usuariocobrador,
			IFNULL( (SELECT nombre FROM clientes WHERE id = prestamos.idcliente)  ,'')  as nombreCliente,
			IFNULL( (SELECT direccionvive FROM clientes WHERE id = prestamos.idcliente)  ,'')  as direccionvive,
			IFNULL( (SELECT cuotas FROM planesprestamo WHERE idprestamo = prestamos.id)  ,'')  as cuotas,
			IFNULL( (SELECT tipo FROM planesprestamo WHERE idprestamo = prestamos.id)  ,'')  as tipoPlan,
			IFNULL( (SELECT (SUM(monto) - SUM(abono)) AS pendiente FROM detprestamos WHERE idprestamo = prestamos.id AND pagado = 0 )  ,0)  AS pendiente

			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 1)  ,0)  AS morasPagadas
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 0)  ,0)  AS morasPendientes
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 2)  ,0)  AS morasExoneradas
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0)  ,0)  AS cuotasPendientes
			,IFNULL( (SELECT COUNT(*) FROM pagosrealizados WHERE idprestamo = prestamos.id AND verificado = 0)  ,0)  AS pagosNoVerificados
			,IFNULL( (SELECT ruta FROM rutas_clientes WHERE idClientes = prestamos.idcliente)  ,'upload/user.png')  as foto

			FROM prestamos $queryFiltroPrestamos ";	


			$morasPagadas = $conexion->sql("SELECT COUNT(*) AS morasPagadas FROM prestamos p
			INNER JOIN detprestamos dp ON p.id = dp.idprestamo
			WHERE p.estado = 1 AND dp.mora != 0 AND dp.morapagada = 1");

			$morasPendientes = $conexion->sql("SELECT COUNT(*) AS morasPendientes FROM prestamos p
			INNER JOIN detprestamos dp ON p.id = dp.idprestamo
			WHERE p.estado = 1 AND dp.mora != 0 AND dp.morapagada = 0");

			$morasExoneradas = $conexion->sql("SELECT COUNT(*) AS morasExoneradas FROM prestamos p
			INNER JOIN detprestamos dp ON p.id = dp.idprestamo
			WHERE p.estado = 1 AND dp.mora != 0 AND dp.morapagada = 2");

			$prestamosRealizados = $conexion->sql("SELECT COUNT(*) AS prestamosRealizados FROM prestamos 
			WHERE estado = 1");	

		}
	




		$consultapagos="SELECT *,
		IFNULL( (SELECT nombre FROM clientes WHERE id = pagosrealizados.idcliente)  ,'')  as nombreCliente,
		IFNULL( (SELECT nombre FROM usuarios WHERE id = pagosrealizados.idusuario)  ,'')  as usuarioRecibio,
		IFNULL( (SELECT ruta FROM rutas_clientes WHERE idClientes = pagosrealizados.idcliente)  ,'upload/user.png')  as foto
		FROM pagosrealizados WHERE fechapago BETWEEN '".date("Y-m-d H:i:s",strtotime($fecha_hoy." 00:00:00"))."' AND '".date("Y-m-d H:i:s",strtotime($fecha_hoy." 23:59:59"))."' 
		AND idusuario = '{$_REQUEST['id_usuario']}' AND estado != 3";	
		$registroPagosRealizados = $conexion->sql($consultapagos);
	


		$respuesta["morasPagadas"] = $morasPagadas[0]["morasPagadas"];
		$respuesta["morasPendientes"] = $morasPendientes[0]["morasPendientes"];
		$respuesta["morasExoneradas"] = $morasExoneradas[0]["morasExoneradas"];
		$respuesta["prestamosRealizados"] = $prestamosRealizados[0]["prestamosRealizados"];
		$respuesta["txttipousuario"] = $buscarTipoUsuario[0]["tipousuario"];
		$respuesta["registroPagosRealizados"] = $registroPagosRealizados;



		$result = $conexion->sql($sql);



		$respuesta["reportemeta"] = array();


		foreach ($result as $key => $value1) {			
						
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


			$rowMeta["nombreCliente"] = $value1["nombreCliente"];
			$rowMeta["usuariocobrador"] = $value1["usuariocobrador"];			
			$rowMeta["foto"] = $value1["foto"];
			$rowMeta["cuotas_pendientes"] = $smo - $crda;
			$rowMeta["moras_pendientes"] = $sma - $mrda;

			array_push($respuesta["reportemeta"] , $rowMeta);


		}



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





function prestamosPendientesClientes()
{

	try
	{	
		$conexion = new conexion();

		$sql="SELECT id, IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idcobrador)  ,'')  as usuariocobrador,
		IFNULL( (SELECT nombre FROM clientes WHERE id = prestamos.idcliente)  ,'')  as nombreCliente,
		IFNULL( (SELECT ruta FROM rutas_clientes WHERE idClientes = prestamos.idcliente)  ,'upload/user.png')  as foto,
		IFNULL( (SELECT tipo FROM planesprestamo WHERE idprestamo = prestamos.id)  ,'')  as tipoPlan
		FROM prestamos WHERE estado = 1 AND idcliente = ".$_REQUEST['idcliente'];
		$result = $conexion->sql($sql);
		$respuesta["reportemeta"] = array();

		foreach ($result as $key => $value1) {			
						
			$rowMeta = array();
					
			$consultasql="SELECT *, date_format(fechapago, '%d-%m-%Y') as fechapago_formateada
			FROM detprestamos WHERE idprestamo = {$value1['id']} 
			AND tipo != 1";	

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
			$rowMeta["tipoPlan"] = $value1["tipoPlan"];
			$rowMeta["cuotas_pendientes"] = $smo - $crda;
			$rowMeta["moras_pendientes"] = $sma - $mrda;
			$rowMeta["total_pendiente"] = ($smo - $crda) + ($sma - $mrda);

			array_push($respuesta["reportemeta"] , $rowMeta);
		}



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




function mostrarDetPrestamo(){

    try
	{	
		$conexion = new conexion();
		$error = 0;		

		$buscarEstadoPrestamo = $conexion->sql("SELECT estado FROM prestamos WHERE id = '{$_REQUEST['idprestamo']}' ");

		//Si el préstamo esta activo, aquí se estarán modificando las moras//
		if ($buscarEstadoPrestamo[0]["estado"] == 1) {


			#Buscamos las cuotas que no tiene pago y tampoco la mora ha sido pagada
			$cuotasNoPagadas = $conexion->sql("SELECT id, fecha FROM detprestamos 
			WHERE idprestamo = '{$_REQUEST['idprestamo']}' AND pagado = 0 AND fecha <= ADDDATE(CURDATE(),-1) AND (morapagada = 0 OR morapagada = 3)");

			$buscarplanesprestamo = $conexion->sql("SELECT tipo, dias 
			,IFNULL( (SELECT prestamo FROM prestamos WHERE id = planesprestamo.idprestamo )  ,0)  AS prestamo
			,n,m,moraincrementable, totalMora
			FROM planesprestamo WHERE idprestamo = '{$_REQUEST['idprestamo']}' ");
			
			$fecha_actual = date("Y-m-d");
			$porMora = $buscarplanesprestamo[0]["n"]; //Cantidad que se cobra por cada 1000 de préstamo
			$capital = $buscarplanesprestamo[0]["prestamo"];		
				
			$fechahoy = date("Y-m-d");
			$cantidadRegistros = count($cuotasNoPagadas);

			//El quince y el mil se cambian por las variables dadas

			/////////////PARA BUSCAR EL MONTO Y FECHA DE LA ÚLTIMA CUOTA/////////////
			$buscarUltimaCuota = $conexion->sql("SELECT id, monto, fecha FROM detprestamos 
			WHERE idprestamo = '{$_REQUEST['idprestamo']}' 
			AND tipo != 1 ORDER BY fecha DESC LIMIT 1");

			$ultimaFecha = $buscarUltimaCuota[0]['fecha'];

			if ($fecha_actual > $ultimaFecha) {
				//Si anulamos esta validación, entonces las moras se incrementarán hasta el día actual y no hasta la ultima cuota
				$fecha_actual = date("Y-m-d", strtotime($ultimaFecha." + 1 day"));
			}

			if ($buscarplanesprestamo[0]["tipo"] == 1) {

				$dias_pago = explode(";", $buscarplanesprestamo[0]["dias"] );

				foreach ($cuotasNoPagadas as $key => $value) {
					
					$cuotasAtrasadas = 0;													
					$fechainicioX = $value['fecha'];
				
					while ( $fecha_actual > $fechainicioX ) {

						$fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 1 day"));
						$dia = date("N", strtotime($fechainicioX));//aquí voy contando los días.

						if (in_array($dia, $dias_pago)) { // Si se encuentra en los días de pago, entonces entra
							$cuotasAtrasadas++;						
						}
					}


					if ($key == ($cantidadRegistros - 1)) {
						$fechahoy = date("Y-m-d");
					}else{
						$fechahoy = $cuotasNoPagadas[($key+1)]['fecha'];
					}

					$diasAtrasados = 0;
					$fechainicioY = $value['fecha'];
					while ( $fechahoy > $fechainicioY ) {
						$fechainicioY = date("Y-m-d", strtotime($fechainicioY." + 1 day"));
						$diasAtrasados++;
					}					

					$totalMora = $buscarplanesprestamo[0]["totalMora"];

					if ($buscarplanesprestamo[0]["moraincrementable"] == 1) {
						$cuotasAtrasadas = $cuotasAtrasadas * $totalMora;
					}else if ($buscarplanesprestamo[0]["moraincrementable"] == 0){
						$cuotasAtrasadas = $totalMora;
					}else if ($buscarplanesprestamo[0]["moraincrementable"] == 2){
						$cuotasAtrasadas = $diasAtrasados * $totalMora;						
					}

					$sql = "UPDATE detprestamos SET mora= '".$cuotasAtrasadas."' WHERE id = {$value['id']} ";
					if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
				
				}        

			}else if( $buscarplanesprestamo[0]["tipo"] == 2 ){

				foreach ($cuotasNoPagadas as $key => $value) {
					
					$cuotasAtrasadas = 0;				
					$fechainicioX = $value['fecha'];

					while ( $fecha_actual > $fechainicioX ) {
						$fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 7 day"));                	
						$cuotasAtrasadas++;											
					}

					if ($key == ($cantidadRegistros - 1)) {
						$fechahoy = date("Y-m-d");
					}else{
						$fechahoy = $cuotasNoPagadas[($key+1)]['fecha'];
					}

					$diasAtrasados = 0;
					$fechainicioY = $value['fecha'];
					while ( $fechahoy > $fechainicioY ) {
						$fechainicioY = date("Y-m-d", strtotime($fechainicioY." + 1 day"));
						$diasAtrasados++;
					}

										
					$totalMora = $buscarplanesprestamo[0]["totalMora"];

					if ($buscarplanesprestamo[0]["moraincrementable"] == 1) {
						$cuotasAtrasadas = $cuotasAtrasadas * $totalMora;
					}else if ($buscarplanesprestamo[0]["moraincrementable"] == 0){
						$cuotasAtrasadas = $totalMora;
					}else if ($buscarplanesprestamo[0]["moraincrementable"] == 2){
						$cuotasAtrasadas = $diasAtrasados * $totalMora;						
					}

					
					$sql = "UPDATE detprestamos SET mora= '".$cuotasAtrasadas."' WHERE id = {$value['id']} ";
					if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 

					



				}  

			}else if( $buscarplanesprestamo[0]["tipo"] == 3 ){

				foreach ($cuotasNoPagadas as $key => $value) {
					
					$cuotasAtrasadas = 0;				
					$fechainicioX = $value['fecha'];

					while ( $fecha_actual > $fechainicioX ) {
						$fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 2 week"));                	
						$cuotasAtrasadas++;											
					}					

					if ($key == ($cantidadRegistros - 1)) {
						$fechahoy = date("Y-m-d");
					}else{
						$fechahoy = $cuotasNoPagadas[($key+1)]['fecha'];
					}
					
					$diasAtrasados = 0;
					$fechainicioY = $value['fecha'];
					while ( $fechahoy > $fechainicioY ) {
						$fechainicioY = date("Y-m-d", strtotime($fechainicioY." + 1 day"));
						$diasAtrasados++;
					}

										
					$totalMora = $buscarplanesprestamo[0]["totalMora"];

					if ($buscarplanesprestamo[0]["moraincrementable"] == 1) {
						$cuotasAtrasadas = $cuotasAtrasadas * $totalMora;
					}else if ($buscarplanesprestamo[0]["moraincrementable"] == 0){
						$cuotasAtrasadas = $totalMora;
					}else if ($buscarplanesprestamo[0]["moraincrementable"] == 2){
						$cuotasAtrasadas = $diasAtrasados * $totalMora;						
					}

					
					$sql = "UPDATE detprestamos SET mora= '".$cuotasAtrasadas."' WHERE id = {$value['id']} ";
					if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 

					


				}  

			}else if( $buscarplanesprestamo[0]["tipo"] == 4 ){

				foreach ($cuotasNoPagadas as $key => $value) {
					
					$cuotasAtrasadas = 0;				
					$fechainicioX = $value['fecha'];

					while ( $fecha_actual > $fechainicioX ) {
						$fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 1 month"));                	
						$cuotasAtrasadas++;											
					}

					if ($key == ($cantidadRegistros - 1)) {
						$fechahoy = date("Y-m-d");
					}else{
						$fechahoy = $cuotasNoPagadas[($key+1)]['fecha'];
					}
					
					
					$diasAtrasados = 0;
					$fechainicioY = $value['fecha'];
					while ( $fechahoy > $fechainicioY ) {
						$fechainicioY = date("Y-m-d", strtotime($fechainicioY." + 1 day"));
						$diasAtrasados++;
					}

										
					$totalMora = $buscarplanesprestamo[0]["totalMora"];

					if ($buscarplanesprestamo[0]["moraincrementable"] == 1) {
						$cuotasAtrasadas = $cuotasAtrasadas * $totalMora;
					}else if ($buscarplanesprestamo[0]["moraincrementable"] == 0){
						$cuotasAtrasadas = $totalMora;
					}else if ($buscarplanesprestamo[0]["moraincrementable"] == 2){
						$cuotasAtrasadas = $diasAtrasados * $totalMora;						
					}

					
					$sql = "UPDATE detprestamos SET mora= '".$cuotasAtrasadas."' WHERE id = {$value['id']} ";
					if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 

					

				}  

			}else if( $buscarplanesprestamo[0]["tipo"] == 5 ){


				foreach ($cuotasNoPagadas as $key => $value) {
					
					$cuotasAtrasadas = 0;				
					$fechainicioX = $value['fecha'];

					while ( $fecha_actual > $fechainicioX ) {
						$fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 1 month"));                	
						$cuotasAtrasadas++;											
					}
					
					if ($key == ($cantidadRegistros - 1)) {
						$fechahoy = date("Y-m-d");
					}else{
						$fechahoy = $cuotasNoPagadas[($key+1)]['fecha'];
					}
					
					$diasAtrasados = 0;
					$fechainicioY = $value['fecha'];
					while ( $fechahoy > $fechainicioY ) {
						$fechainicioY = date("Y-m-d", strtotime($fechainicioY." + 1 day"));
						$diasAtrasados++;
					}

										
					$totalMora = $buscarplanesprestamo[0]["totalMora"];

					if ($buscarplanesprestamo[0]["moraincrementable"] == 1) {
						$cuotasAtrasadas = $cuotasAtrasadas * $totalMora;
					}else if ($buscarplanesprestamo[0]["moraincrementable"] == 0){
						$cuotasAtrasadas = $totalMora;
					}else if ($buscarplanesprestamo[0]["moraincrementable"] == 2){
						$cuotasAtrasadas = $diasAtrasados * $totalMora;						
					}

					
					$sql = "UPDATE detprestamos SET mora= '".$cuotasAtrasadas."' WHERE id = {$value['id']} ";
					if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 

					

				}


				$fecha_actual = date("Y-m-d");

				$buscar_Abono = $conexion->sql("SELECT IFNULL(SUM(monto),0) AS abono 
				FROM pagoscapital WHERE idprestamo = '{$_REQUEST['idprestamo']}' ");

				$total_Pendiente = $capital - $buscar_Abono[0]['abono'];

				if ( $total_Pendiente > 0 && $fecha_actual > $buscarUltimaCuota[0]['fecha'] ) {

					$fechainicioX = $buscarUltimaCuota[0]['fecha'];

					while ( $fecha_actual > $fechainicioX ) {
						$fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 1 month"));                	
																		
						$regDetPrestamo = $conexion->sqlOperacion("INSERT INTO detprestamos(idprestamo, fecha, monto, abono, pagado, plan) 
						VALUES ('".$_REQUEST['idprestamo']."', '".$fechainicioX."', '".$buscarUltimaCuota[0]['monto']."', '0','0','0' )");
						if ($regDetPrestamo['resultado'] == false) { $error++; }
					
					}

				}		

			}
		
		}


		
        $sql="SELECT *, date_format(fechapago, '%d-%m-%Y') as fechapago_formateada
		FROM detprestamos WHERE idprestamo = {$_REQUEST['idprestamo']} 
		AND tipo != 1";
		$sql2="SELECT *, IFNULL( (SELECT nombre FROM usuarios WHERE id = pagosrealizados.idusuario)  ,'')  as usuariocobro 
		FROM pagosrealizados WHERE idprestamo =  {$_REQUEST['idprestamo']}";
		$sql3="SELECT * FROM pagoscapital WHERE idprestamo = {$_REQUEST['idprestamo']}";
		$sql4="SELECT p.resumenpagos as cuota, pp.cuotas, p.estado FROM prestamos p
		INNER JOIN planesprestamo pp ON p.id = pp.idprestamo WHERE p.id = {$_REQUEST['idprestamo']}";
		
		$sql6="SELECT *, date_format(fechapago, '%d-%m-%Y') as fechapago_formateada
		FROM detprestamos WHERE idprestamo = {$_REQUEST['idprestamo']} 
		AND tipo != 1
		AND fecha <= ADDDATE(CURDATE(),0)";	


		$result = $conexion->sql($sql);
		$result2 = $conexion->sql($sql2);
		$result3 = $conexion->sql($sql3);
		$result4 = $conexion->sql($sql4);
		$result6 = $conexion->sql($sql6);


		if (!$error) {
			$respuesta["registros"] = $result;
			$respuesta["pagosrealizados"] = $result2;
			$respuesta["pagoscapital"] = $result3;
			$respuesta["informePrestamo"] = $result4;
			$respuesta["pendienteactual"] = $result6;

			$respuesta["mensaje"] = "Datos consultados Exitosamente";
			$respuesta["resultado"] = true;
			$conexion->respuestaTrans("COMMIT");
		}else{
			$respuesta["resultado"] = false;
			$conexion->respuestaTrans("ROLLBACK");
			$respuesta["mensaje"] = "Datos No ingresados ";
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


function actualizarPrestamosActivos(){

    try
	{	
		$conexion = new conexion();
		$error = 0;
		
		$sql=" SELECT id FROM prestamos WHERE estado = 1 ";
		$detalle=$conexion->SQL($sql);

		foreach ($detalle as $key => $valuex) {		

			$buscarEstadoPrestamo = $conexion->sql("SELECT estado FROM prestamos WHERE id = '{$valuex['id']}' ");

			//Si el préstamo esta activo, aquí se estarán modificando las moras//
			if ($buscarEstadoPrestamo[0]["estado"] == 1) {


				#Buscamos las cuotas que no tiene pago y tampoco la mora ha sido pagada
				$cuotasNoPagadas = $conexion->sql("SELECT id, fecha FROM detprestamos 
				WHERE idprestamo = '{$valuex['id']}' AND pagado = 0 AND fecha <= ADDDATE(CURDATE(),-1) AND (morapagada = 0 OR morapagada = 3)");

				$buscarplanesprestamo = $conexion->sql("SELECT tipo, dias 
				,IFNULL( (SELECT prestamo FROM prestamos WHERE id = planesprestamo.idprestamo )  ,0)  AS prestamo
				,n,m,moraincrementable, totalMora
				FROM planesprestamo WHERE idprestamo = '{$valuex['id']}' ");
				
				$fecha_actual = date("Y-m-d");
				$porMora = $buscarplanesprestamo[0]["n"]; //Cantidad que se cobra por cada 1000 de préstamo
				$capital = $buscarplanesprestamo[0]["prestamo"];		
				
				$fechahoy = date("Y-m-d");
				$cantidadRegistros = count($cuotasNoPagadas);



				/////////////PARA BUSCAR EL MONTO Y FECHA DE LA ÚLTIMA CUOTA/////////////
				$buscarUltimaCuota = $conexion->sql("SELECT id, monto, fecha FROM detprestamos 
				WHERE idprestamo = '{$valuex['id']}' 
				AND tipo != 1 ORDER BY fecha DESC LIMIT 1");

				$ultimaFecha = $buscarUltimaCuota[0]['fecha'];

				if ($fecha_actual > $ultimaFecha) {
					//Si anulamos esta validación, entonces las moras se incrementarán hasta el día actual y no hasta la ultima cuota
					$fecha_actual = date("Y-m-d", strtotime($ultimaFecha." + 1 day"));
				}

				if ($buscarplanesprestamo[0]["tipo"] == 1) {

					$dias_pago = explode(";", $buscarplanesprestamo[0]["dias"] );

					foreach ($cuotasNoPagadas as $key => $value) {
						
						$cuotasAtrasadas = 0;				
						$fechainicioX = $value['fecha'];

						while ( $fecha_actual > $fechainicioX ) {

							$fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 1 day"));
							$dia = date("N", strtotime($fechainicioX));//aquí voy contando los días.

							if (in_array($dia, $dias_pago)) { // Si se encuentra en los días de pago, entonces entra
								$cuotasAtrasadas++;						
							}
						}
						

						if ($key == ($cantidadRegistros - 1)) {
							$fechahoy = date("Y-m-d");
						}else{
							$fechahoy = $cuotasNoPagadas[($key+1)]['fecha'];
						}


						$diasAtrasados = 0;
						$fechainicioY = $value['fecha'];
						while ( $fechahoy > $fechainicioY ) {
							$fechainicioY = date("Y-m-d", strtotime($fechainicioY." + 1 day"));
							$diasAtrasados++;
						}
						

						$totalMora = $buscarplanesprestamo[0]["totalMora"];

						if ($buscarplanesprestamo[0]["moraincrementable"] == 1) {
							$cuotasAtrasadas = $cuotasAtrasadas * $totalMora;
						}else if ($buscarplanesprestamo[0]["moraincrementable"] == 0){
							$cuotasAtrasadas = $totalMora;
						}else if ($buscarplanesprestamo[0]["moraincrementable"] == 2){
							$cuotasAtrasadas = $diasAtrasados * $totalMora;						
						}


						$sql = "UPDATE detprestamos SET mora= '".$cuotasAtrasadas."' WHERE id = {$value['id']} ";
						if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
						


					}        

				}else if( $buscarplanesprestamo[0]["tipo"] == 2 ){

					foreach ($cuotasNoPagadas as $key => $value) {
						
						$cuotasAtrasadas = 0;				
						$fechainicioX = $value['fecha'];

						while ( $fecha_actual > $fechainicioX ) {
							$fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 7 day"));                	
							$cuotasAtrasadas++;											
						}

						if ($key == ($cantidadRegistros - 1)) {
							$fechahoy = date("Y-m-d");
						}else{
							$fechahoy = $cuotasNoPagadas[($key+1)]['fecha'];
						}
						
						$diasAtrasados = 0;
						$fechainicioY = $value['fecha'];
						while ( $fechahoy > $fechainicioY ) {
							$fechainicioY = date("Y-m-d", strtotime($fechainicioY." + 1 day"));
							$diasAtrasados++;
						}
						

						$totalMora = $buscarplanesprestamo[0]["totalMora"];

						if ($buscarplanesprestamo[0]["moraincrementable"] == 1) {
							$cuotasAtrasadas = $cuotasAtrasadas * $totalMora;
						}else if ($buscarplanesprestamo[0]["moraincrementable"] == 0){
							$cuotasAtrasadas = $totalMora;
						}else if ($buscarplanesprestamo[0]["moraincrementable"] == 2){
							$cuotasAtrasadas = $diasAtrasados * $totalMora;						
						}


						$sql = "UPDATE detprestamos SET mora= '".$cuotasAtrasadas."' WHERE id = {$value['id']} ";
						if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
						

					}  

				}else if( $buscarplanesprestamo[0]["tipo"] == 3 ){

					foreach ($cuotasNoPagadas as $key => $value) {
						
						$cuotasAtrasadas = 0;				
						$fechainicioX = $value['fecha'];

						while ( $fecha_actual > $fechainicioX ) {
							$fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 2 week"));                	
							$cuotasAtrasadas++;											
						}

						if ($key == ($cantidadRegistros - 1)) {
							$fechahoy = date("Y-m-d");
						}else{
							$fechahoy = $cuotasNoPagadas[($key+1)]['fecha'];
						}
						
						$diasAtrasados = 0;
						$fechainicioY = $value['fecha'];
						while ( $fechahoy > $fechainicioY ) {
							$fechainicioY = date("Y-m-d", strtotime($fechainicioY." + 1 day"));
							$diasAtrasados++;
						}
						

						$totalMora = $buscarplanesprestamo[0]["totalMora"];

						if ($buscarplanesprestamo[0]["moraincrementable"] == 1) {
							$cuotasAtrasadas = $cuotasAtrasadas * $totalMora;
						}else if ($buscarplanesprestamo[0]["moraincrementable"] == 0){
							$cuotasAtrasadas = $totalMora;
						}else if ($buscarplanesprestamo[0]["moraincrementable"] == 2){
							$cuotasAtrasadas = $diasAtrasados * $totalMora;						
						}


						$sql = "UPDATE detprestamos SET mora= '".$cuotasAtrasadas."' WHERE id = {$value['id']} ";
						if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 						

					}  

				}else if( $buscarplanesprestamo[0]["tipo"] == 4 ){

					foreach ($cuotasNoPagadas as $key => $value) {
						
						$cuotasAtrasadas = 0;				
						$fechainicioX = $value['fecha'];

						while ( $fecha_actual > $fechainicioX ) {
							$fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 1 month"));                	
							$cuotasAtrasadas++;											
						}

						if ($key == ($cantidadRegistros - 1)) {
							$fechahoy = date("Y-m-d");
						}else{
							$fechahoy = $cuotasNoPagadas[($key+1)]['fecha'];
						}
						
						$diasAtrasados = 0;
						$fechainicioY = $value['fecha'];
						while ( $fechahoy > $fechainicioY ) {
							$fechainicioY = date("Y-m-d", strtotime($fechainicioY." + 1 day"));
							$diasAtrasados++;
						}						

						$totalMora = $buscarplanesprestamo[0]["totalMora"];

						if ($buscarplanesprestamo[0]["moraincrementable"] == 1) {
							$cuotasAtrasadas = $cuotasAtrasadas * $totalMora;
						}else if ($buscarplanesprestamo[0]["moraincrementable"] == 0){
							$cuotasAtrasadas = $totalMora;
						}else if ($buscarplanesprestamo[0]["moraincrementable"] == 2){
							$cuotasAtrasadas = $diasAtrasados * $totalMora;						
						}


						$sql = "UPDATE detprestamos SET mora= '".$cuotasAtrasadas."' WHERE id = {$value['id']} ";
						if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 						

					}  

				}else if( $buscarplanesprestamo[0]["tipo"] == 5 ){


					foreach ($cuotasNoPagadas as $key => $value) {
						
						$cuotasAtrasadas = 0;				
						$fechainicioX = $value['fecha'];

						while ( $fecha_actual > $fechainicioX ) {
							$fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 1 month"));                	
							$cuotasAtrasadas++;											
						}

						if ($key == ($cantidadRegistros - 1)) {
							$fechahoy = date("Y-m-d");
						}else{
							$fechahoy = $cuotasNoPagadas[($key+1)]['fecha'];
						}
						
						$diasAtrasados = 0;
						$fechainicioY = $value['fecha'];
						while ( $fechahoy > $fechainicioY ) {
							$fechainicioY = date("Y-m-d", strtotime($fechainicioY." + 1 day"));
							$diasAtrasados++;
						}
						

						$totalMora = $buscarplanesprestamo[0]["totalMora"];

						if ($buscarplanesprestamo[0]["moraincrementable"] == 1) {
							$cuotasAtrasadas = $cuotasAtrasadas * $totalMora;
						}else if ($buscarplanesprestamo[0]["moraincrementable"] == 0){
							$cuotasAtrasadas = $totalMora;
						}else if ($buscarplanesprestamo[0]["moraincrementable"] == 2){
							$cuotasAtrasadas = $diasAtrasados * $totalMora;						
						}


						$sql = "UPDATE detprestamos SET mora= '".$cuotasAtrasadas."' WHERE id = {$value['id']} ";
						if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
				
					}


					$fecha_actual = date("Y-m-d");

					$buscar_Abono = $conexion->sql("SELECT IFNULL(SUM(monto),0) AS abono 
					FROM pagoscapital WHERE idprestamo = '{$valuex['id']}' ");

					$total_Pendiente = $capital - $buscar_Abono[0]['abono'];

					if ( $total_Pendiente > 0 && $fecha_actual > $buscarUltimaCuota[0]['fecha'] ) {

						$fechainicioX = $buscarUltimaCuota[0]['fecha'];

						while ( $fecha_actual > $fechainicioX ) {
							$fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 1 month"));                	
																			
							$regDetPrestamo = $conexion->sqlOperacion("INSERT INTO detprestamos(idprestamo, fecha, monto, abono, pagado, plan) 
							VALUES ('".$valuex['id']."', '".$fechainicioX."', '".$buscarUltimaCuota[0]['monto']."', '0','0','0' )");
							if ($regDetPrestamo['resultado'] == false) { $error++; }
						
						}

					}		

				}
			
			}


		}



		if (!$error) {
			$respuesta["mensaje"] = "Datos Actualizados Exitosamente";
			$respuesta["resultado"] = true;
			$conexion->respuestaTrans("COMMIT");
		}else{
			$respuesta["resultado"] = false;
			$conexion->respuestaTrans("ROLLBACK");
			$respuesta["mensaje"] = "Datos No Actualizados ";
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




function nuevoPago(){

	try
	{	
		$conexion = new conexion();
        $conexion->transaccion();
        $error = 0;



		$idapertura = 0;
		$verCaja = $conexion->sql("SELECT cajas.id, cajas.estado , cajas.descripcion as caja, cajasaperturas.id as idapertura FROM usuarios		
		INNER JOIN cajas ON cajas.idusuario = usuarios.id
		INNER JOIN cajasaperturas ON cajasaperturas.idcaja = cajas.id
		WHERE cajasaperturas.idusuarioinicio = {$_SESSION["idusuario"]} AND usuarios.idapertura = cajasaperturas.id ORDER BY cajasaperturas.id DESC LIMIT 1");

		if(count( $verCaja ) > 0){
			if($verCaja[0]["estado"] == 1 ){
				$idapertura = $verCaja[0]["idapertura"];
			}
		}


		$procederPrestamo = 0;
		$verAccesoCaja = $conexion->sql("SELECT accesocaja FROM usuarios WHERE id = {$_SESSION["idusuario"]} ");
		if ( $verAccesoCaja[0]["accesocaja"] == 1 && $idapertura == 0 ) {
			$respuesta["resultado"] = false;
			$conexion->respuestaTrans("ROLLBACK");
			$respuesta["mensaje"] = "No se puede generar el pago porque la caja está cerrada.";
			$procederPrestamo = 0;
		}else if($verAccesoCaja[0]["accesocaja"] == 1 && $idapertura != 0){
			$procederPrestamo = 1;
		}else if($verAccesoCaja[0]["accesocaja"] == 0){
			$procederPrestamo = 1;
		}


		if ( $procederPrestamo == 1 ) {

			$buscarSumaMoras = $conexion->sql("SELECT IFNULL(SUM(mora) - SUM(abonomora),0) as sumamoras FROM detprestamos 
			WHERE idprestamo = '{$_REQUEST['idprestamo']}' AND mora > 0
			AND fecha <= ADDDATE(CURDATE(),-1) AND morapagada = 0 OR morapagada = 3 ORDER BY id ASC");

			if ( $_REQUEST['btnPrimeroMorasPendientes'] == 1 && $_REQUEST['monto'] != '' && $buscarSumaMoras[0]["sumamoras"] > 0 ) {

				//Entra en este bloque
				//1.	Cuando hay moras pendientes y se seleccionó pagar moras primero

				$montoRecibido = $_REQUEST['monto'];
				$estadopagorealizado = 5; //5=pago de moras y cuotas 

				$descripcion_de_pago = "Pago por moras y cuotas";
				if ( $buscarSumaMoras[0]["sumamoras"] >= $montoRecibido ) {
					$descripcion_de_pago = "Pago por moras";
					$estadopagorealizado = 4; //4=pago de moras dinámicas //Puede cubrir varias moras, o pagos parciales en moras
				}


				$buscarDatoCliente = $conexion->sql("SELECT idcliente FROM prestamos WHERE id = '{$_REQUEST['idprestamo']}' ");			

				$regpagosrealizados = $conexion->sqlOperacion("INSERT INTO pagosrealizados (idprestamo, idcliente, idusuario, 
				idcierreganancias, iddeposito, plan, descripcion, monto, fechapago, estado, deposito, idtransaccion, idapertura) 
				VALUES ('".$_REQUEST['idprestamo']."','".$buscarDatoCliente[0]["idcliente"]."', '".$_SESSION["idusuario"]."', '0','0',
				'0' , '".utf8_decode($descripcion_de_pago)."', '".$montoRecibido."', '".$_REQUEST['fechainicio']."' , '".$estadopagorealizado."','0','0','".$idapertura."' )");
				if ($regpagosrealizados['resultado'] == false) { $error++; }


				$buscarMorasPendientes = $conexion->sql("SELECT * FROM detprestamos WHERE idprestamo = '{$_REQUEST['idprestamo']}' AND mora > 0
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
						$_REQUEST['monto'] = $montoRecibido;

						//Aquí se copio el bloque de código que esta del otro lado de la condición, 
						//Con la salvedad que no ingresa otro pago


						$buscarMontoPendiente = $conexion->sql("SELECT (SUM(monto) - SUM(abono)) AS pendiente, SUM(abono) as abono 
						FROM detprestamos WHERE idprestamo = '{$_REQUEST['idprestamo']}' AND pagado = 0");
						
						if ($_REQUEST['monto'] <= $buscarMontoPendiente[0]["pendiente"] && $_REQUEST['monto'] != '') {

							$buscarPrestamo = $conexion->sql("SELECT resumenpagos AS montoUnitario, idcliente FROM prestamos WHERE id = '{$_REQUEST['idprestamo']}' ");
							
							$montoCobrado = $_REQUEST['monto'] + $buscarMontoPendiente[0]["abono"];
							$montoUnitario = $buscarPrestamo[0]["montoUnitario"];
							$cuotas = floor( ( $montoCobrado / $montoUnitario ) );
							//$abono = $montoCobrado % $montoUnitario;						
							$abono = fmod($montoCobrado,$montoUnitario);						

							$buscarCuotasPendiente = $conexion->sql("SELECT id FROM detprestamos 
							WHERE idprestamo = '{$_REQUEST['idprestamo']}' AND pagado = 0 ORDER BY id ASC LIMIT {$cuotas} ");

							foreach ($buscarCuotasPendiente as $key => $value) {
								$sql = "UPDATE detprestamos SET fechapago= '".$_REQUEST['fechainicio']."' , abono = '0', pagado = '1' WHERE id = {$value['id']} ";
								if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
							}


							if ($abono > 0) {
								$buscarCuotasPendiente = $conexion->sql("SELECT id FROM detprestamos 
								WHERE idprestamo = '{$_REQUEST['idprestamo']}' AND pagado = 0 ORDER BY id ASC LIMIT 1 ");
								$sql = "UPDATE detprestamos SET abono='{$abono}' WHERE id = {$buscarCuotasPendiente[0]['id']} ";
								if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
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
							
						}else{
							$respuesta["resultado"] = false;
							$conexion->respuestaTrans("ROLLBACK");
							$respuesta["mensaje"] = "El monto ingresado es mayor a lo pendiente ";
						}


					}else{

						$_REQUEST['monto'] = 0;

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

								
				}



			}else{

				//Entrará aquí solo cuando, 
				//1.	No hay moras pendientes  
				//2.	Solo se están cancelando las cuotas (Sin moras)


				$buscarMontoPendiente = $conexion->sql("SELECT (SUM(monto) - SUM(abono)) AS pendiente, SUM(abono) as abono 
				FROM detprestamos WHERE idprestamo = '{$_REQUEST['idprestamo']}' AND pagado = 0");
				
				if ($_REQUEST['monto'] <= $buscarMontoPendiente[0]["pendiente"] && $_REQUEST['monto'] != '') {

					$buscarPrestamo = $conexion->sql("SELECT resumenpagos AS montoUnitario, idcliente FROM prestamos WHERE id = '{$_REQUEST['idprestamo']}' ");
					
					$montoCobrado = $_REQUEST['monto'] + $buscarMontoPendiente[0]["abono"];
					$montoUnitario = $buscarPrestamo[0]["montoUnitario"];
					$cuotas = floor( ( $montoCobrado / $montoUnitario ) );
					//$abono = $montoCobrado % $montoUnitario;
					$abono = fmod($montoCobrado,$montoUnitario);

					$regpagosrealizados = $conexion->sqlOperacion("INSERT INTO pagosrealizados (idprestamo, idcliente, idusuario, 
					idcierreganancias, iddeposito, plan, descripcion, monto, fechapago, estado, deposito, idtransaccion, idapertura) 
					VALUES ('".$_REQUEST['idprestamo']."','".$buscarPrestamo[0]["idcliente"]."', '".$_SESSION["idusuario"]."', '0','0',
					'0' , '".utf8_decode("Pago de Cuotas")."', '".$_REQUEST['monto']."', '".$_REQUEST['fechainicio']."' , '1','0','0', '".$idapertura."' )");
					if ($regpagosrealizados['resultado'] == false) { $error++; }

					$buscarCuotasPendiente = $conexion->sql("SELECT id FROM detprestamos 
					WHERE idprestamo = '{$_REQUEST['idprestamo']}' AND pagado = 0 ORDER BY id ASC LIMIT {$cuotas} ");

					foreach ($buscarCuotasPendiente as $key => $value) {
						$sql = "UPDATE detprestamos SET fechapago= '".$_REQUEST['fechainicio']."' , abono = '0', pagado = '1' WHERE id = {$value['id']} ";
						if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
					}


					if ($abono > 0) {
						$buscarCuotasPendiente = $conexion->sql("SELECT id FROM detprestamos 
						WHERE idprestamo = '{$_REQUEST['idprestamo']}' AND pagado = 0 ORDER BY id ASC LIMIT 1 ");
						$sql = "UPDATE detprestamos SET abono='{$abono}' WHERE id = {$buscarCuotasPendiente[0]['id']} ";
						if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
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
					
				}else{
					$respuesta["resultado"] = false;
					$respuesta["mensaje"] = "El monto ingresado es mayor a lo pendiente ";
				}


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



function eliminarCapital_plan5(){

	try
	{	
		$conexion = new conexion();
        $conexion->transaccion();
        $error = 0;

		$buscarIdPrestamo = $conexion->sql("SELECT idprestamo FROM pagoscapital WHERE id = '{$_REQUEST['idpagoscapital']}' ");
		$idprestamo = $buscarIdPrestamo[0]["idprestamo"];
		$resPagosCapital = mysql_query("DELETE FROM pagoscapital WHERE id = {$_REQUEST['idpagoscapital']}");

		$buscarAbono = $conexion->sql("SELECT IFNULL(SUM(monto),0) AS abono 
		FROM pagoscapital WHERE idprestamo = '{$idprestamo}' ");
		
		$buscarPrestamo = $conexion->sql("SELECT resumenpagos AS montoUnitario, idcliente, prestamo 
		,IFNULL( (SELECT interes FROM planesprestamo WHERE idprestamo = prestamos.id)  ,0)  as interes
		FROM prestamos WHERE id = '{$idprestamo}' ");		

		$interes = $buscarPrestamo[0]['interes'];
				
		$nuevoTotalPendiente = $buscarPrestamo[0]['prestamo'] - $buscarAbono[0]['abono'];
		$nuevoMonto = ($nuevoTotalPendiente / 100) * $interes;
		$nuevoMonto = ceil($nuevoMonto/5) * 5;

		//Solo se les cambia el monto a las cuotas que aun no tienen mora, y aun no llega la fecha de cobro.
		$buscardetprestamos = $conexion->sql("SELECT * FROM detprestamos 
		WHERE idprestamo = '{$idprestamo}' AND pagado = 0 AND abono = 0 AND mora = 0 OR monto = 0");

		foreach ($buscardetprestamos as $key => $value) {
			$sql = "UPDATE detprestamos SET monto= '".$nuevoMonto."', fechapago = NULL , abono = '0', pagado = '0' WHERE id = {$value['id']} ";
			if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 						
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


function pagoCapital_plan5(){

	try
	{	
		$conexion = new conexion();
        $conexion->transaccion();
        $error = 0;


		$idapertura = 0;
		$verCaja = $conexion->sql("SELECT cajas.id, cajas.estado , cajas.descripcion as caja, cajasaperturas.id as idapertura FROM usuarios		
		INNER JOIN cajas ON cajas.idusuario = usuarios.id
		INNER JOIN cajasaperturas ON cajasaperturas.idcaja = cajas.id
		WHERE cajasaperturas.idusuarioinicio = {$_SESSION["idusuario"]} AND usuarios.idapertura = cajasaperturas.id ORDER BY cajasaperturas.id DESC LIMIT 1");

		if(count( $verCaja ) > 0){
			if($verCaja[0]["estado"] == 1 ){
				$idapertura = $verCaja[0]["idapertura"];
			}
		}


				
		$procederPrestamo = 0;
		$verAccesoCaja = $conexion->sql("SELECT accesocaja FROM usuarios WHERE id = {$_SESSION["idusuario"]} ");
		if ( $verAccesoCaja[0]["accesocaja"] == 1 && $idapertura == 0 ) {
			$respuesta["resultado"] = false;
			$conexion->respuestaTrans("ROLLBACK");
			$respuesta["mensaje"] = "No se puede generar el pago porque la caja está cerrada.";
			$procederPrestamo = 0;
		}else if($verAccesoCaja[0]["accesocaja"] == 1 && $idapertura != 0){
			$procederPrestamo = 1;
		}else if($verAccesoCaja[0]["accesocaja"] == 0){
			$procederPrestamo = 1;
		}


		if ( $procederPrestamo == 1 ) {



			$buscarAbono = $conexion->sql("SELECT IFNULL(SUM(monto),0) AS abono 
			FROM pagoscapital WHERE idprestamo = '{$_REQUEST['idprestamo']}' ");
			
			$buscarPrestamo = $conexion->sql("SELECT resumenpagos AS montoUnitario, idcliente, prestamo 
			,IFNULL( (SELECT interes FROM planesprestamo WHERE idprestamo = prestamos.id)  ,0)  as interes
			FROM prestamos WHERE id = '{$_REQUEST['idprestamo']}' ");		

			$totalPendiente = $buscarPrestamo[0]['prestamo'] - $buscarAbono[0]['abono'];
			$interes = $buscarPrestamo[0]['interes'];
			
			if ($_REQUEST['monto'] <= $totalPendiente ) {

				$regpagoscapital = $conexion->sqlOperacion("INSERT INTO pagoscapital (idprestamo, idcliente, 
				idusuario, descripcion, monto, fechapago,idapertura) 
				VALUES ('".$_REQUEST['idprestamo']."','".$buscarPrestamo[0]["idcliente"]."', '".$_SESSION["idusuario"]."',
				'".utf8_decode("Pago de Capital")."', '".$_REQUEST['monto']."', '".$_REQUEST['fechainicio']."','".$idapertura."')");
				if ($regpagoscapital['resultado'] == false) { $error++; }



				$nuevoTotalPendiente = $totalPendiente - $_REQUEST['monto'];
				$nuevoMonto = ($nuevoTotalPendiente / 100) * $interes;
				$nuevoMonto = ceil($nuevoMonto/5) * 5;

				//Solo se les cambia el monto a las cuotas que aun no tienen mora, y aun no llega la fecha de cobro.
				$buscardetprestamos = $conexion->sql("SELECT * FROM detprestamos 
				WHERE idprestamo = '{$_REQUEST['idprestamo']}' AND pagado = 0 AND abono = 0 AND mora = 0");

				foreach ($buscardetprestamos as $key => $value) {

					if ($nuevoMonto == 0) {//Cuando se paga todo el capital
						$sql = "UPDATE detprestamos SET monto= '".$nuevoMonto."', fechapago = NOW() , abono = '0', pagado = '1' WHERE id = {$value['id']} ";
						if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
					}else{
						$sql = "UPDATE detprestamos SET monto= '".$nuevoMonto."' WHERE id = {$value['id']} ";
						if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
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
				
			}else{
				$respuesta["resultado"] = false;
				$respuesta["mensaje"] = "El monto ingresado es mayor a lo pendiente ";
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



function pagoInteres_plan5(){

	try
	{	
		$conexion = new conexion();
        $conexion->transaccion();
        $error = 0;


		$idapertura = 0;
		$verCaja = $conexion->sql("SELECT cajas.id, cajas.estado , cajas.descripcion as caja, cajasaperturas.id as idapertura FROM usuarios		
		INNER JOIN cajas ON cajas.idusuario = usuarios.id
		INNER JOIN cajasaperturas ON cajasaperturas.idcaja = cajas.id
		WHERE cajasaperturas.idusuarioinicio = {$_SESSION["idusuario"]} AND usuarios.idapertura = cajasaperturas.id ORDER BY cajasaperturas.id DESC LIMIT 1");

		if(count( $verCaja ) > 0){
			if($verCaja[0]["estado"] == 1 ){
				$idapertura = $verCaja[0]["idapertura"];
			}
		}


		$procederPrestamo = 0;
		$verAccesoCaja = $conexion->sql("SELECT accesocaja FROM usuarios WHERE id = {$_SESSION["idusuario"]} ");
		if ( $verAccesoCaja[0]["accesocaja"] == 1 && $idapertura == 0 ) {
			$respuesta["resultado"] = false;
			$conexion->respuestaTrans("ROLLBACK");
			$respuesta["mensaje"] = "No se puede generar el pago porque la caja está cerrada.";
			$procederPrestamo = 0;
		}else if($verAccesoCaja[0]["accesocaja"] == 1 && $idapertura != 0){
			$procederPrestamo = 1;
		}else if($verAccesoCaja[0]["accesocaja"] == 0){
			$procederPrestamo = 1;
		}


		if ( $procederPrestamo == 1 ) {


			$buscarSumaMoras = $conexion->sql("SELECT IFNULL(SUM(mora) - SUM(abonomora),0) as sumamoras FROM detprestamos 
			WHERE idprestamo = '{$_REQUEST['idprestamo']}' AND mora > 0
			AND fecha <= ADDDATE(CURDATE(),-1) AND morapagada = 0 OR morapagada = 3 ORDER BY id ASC");

			if ( $_REQUEST['btnPrimeroMorasPendientes'] == 1 && $_REQUEST['monto'] != '' && $buscarSumaMoras[0]["sumamoras"] > 0 ) {

				//Entra en este bloque
				//1.	Cuando hay moras pendientes y se seleccionó pagar moras primero

				$montoRecibido = $_REQUEST['monto'];
				$estadopagorealizado = 5; //5=pago de moras y cuotas 

				$descripcion_de_pago = "Pago por moras y cuotas";
				if ( $buscarSumaMoras[0]["sumamoras"] >= $montoRecibido ) {
					$descripcion_de_pago = "Pago por moras";
					$estadopagorealizado = 4; //4=pago de moras dinámicas //Puede cubrir varias moras, o pagos parciales en moras
				}


				$buscarDatoCliente = $conexion->sql("SELECT idcliente FROM prestamos WHERE id = '{$_REQUEST['idprestamo']}' ");			

				$regpagosrealizados = $conexion->sqlOperacion("INSERT INTO pagosrealizados (idprestamo, idcliente, idusuario, 
				idcierreganancias, iddeposito, plan, descripcion, monto, fechapago, estado, deposito, idtransaccion,idapertura) 
				VALUES ('".$_REQUEST['idprestamo']."','".$buscarDatoCliente[0]["idcliente"]."', '".$_SESSION["idusuario"]."', '0','0',
				'0' , '".utf8_decode($descripcion_de_pago)."', '".$montoRecibido."', '".$_REQUEST['fechainicio']."' , '".$estadopagorealizado."','0','0','".$idapertura."' )");
				if ($regpagosrealizados['resultado'] == false) { $error++; }


				$buscarMorasPendientes = $conexion->sql("SELECT * FROM detprestamos WHERE idprestamo = '{$_REQUEST['idprestamo']}' AND mora > 0
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
						$_REQUEST['monto'] = $montoRecibido;

						//Aquí se copio el bloque de código que esta del otro lado de la condición, 
						//Con la salvedad que no ingresa otro pago


						$buscarMontoPendiente = $conexion->sql("SELECT (SUM(monto) - SUM(abono)) AS pendiente, SUM(abono) as abono 
						FROM detprestamos WHERE idprestamo = '{$_REQUEST['idprestamo']}' AND pagado = 0");
						
						if ($_REQUEST['monto'] <= $buscarMontoPendiente[0]["pendiente"] && $_REQUEST['monto'] != '') {

							
							$montoRecibido = $_REQUEST['monto'] + $buscarMontoPendiente[0]["abono"];			

							

							$buscarCuotasPendiente = $conexion->sql("SELECT id, monto FROM detprestamos 
							WHERE idprestamo = '{$_REQUEST['idprestamo']}' AND pagado = 0 ORDER BY id ASC");

							foreach ($buscarCuotasPendiente as $key => $value) {

								if ($montoRecibido != 0) {

									if ($montoRecibido < $value['monto']) {
										$sql = "UPDATE detprestamos SET abono='{$montoRecibido}' WHERE id = {$value['id']} ";
										if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
										$montoRecibido = 0;
									}else{
										$sql = "UPDATE detprestamos SET fechapago= '".$_REQUEST['fechainicio']."' , abono = '0', pagado = '1' WHERE id = {$value['id']} ";
										if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
										$montoRecibido -= $value['monto'];
									}

								}


								if ($value['monto'] == 0) {
									$sql = "UPDATE detprestamos SET fechapago= '".$_REQUEST['fechainicio']."' , abono = '0', pagado = '1' WHERE id = {$value['id']} ";
									if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
									$montoRecibido -= $value['monto'];
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
							
						}else{
							$respuesta["resultado"] = false;
							$respuesta["mensaje"] = "El monto ingresado es mayor a lo pendiente ";
						}


					}else{

						$_REQUEST['monto'] = 0;

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

								
				}



			}else{

				//Entrará aquí solo cuando, 
				//1.	No hay moras pendientes  
				//2.	Solo se están cancelando las cuotas (Sin moras)
			
				$buscarMontoPendiente = $conexion->sql("SELECT (SUM(monto) - SUM(abono)) AS pendiente, SUM(abono) as abono 
				FROM detprestamos WHERE idprestamo = '{$_REQUEST['idprestamo']}' AND pagado = 0");
				
				if ($_REQUEST['monto'] <= $buscarMontoPendiente[0]["pendiente"] && $_REQUEST['monto'] != '') {

					$buscarPrestamo = $conexion->sql("SELECT resumenpagos AS montoUnitario, idcliente FROM prestamos WHERE id = '{$_REQUEST['idprestamo']}' ");
					
					$montoRecibido = $_REQUEST['monto'] + $buscarMontoPendiente[0]["abono"];			

					$regpagosrealizados = $conexion->sqlOperacion("INSERT INTO pagosrealizados (idprestamo, idcliente, idusuario, 
					idcierreganancias, iddeposito, plan, descripcion, monto, fechapago, estado, deposito, idtransaccion,idapertura) 
					VALUES ('".$_REQUEST['idprestamo']."','".$buscarPrestamo[0]["idcliente"]."', '".$_SESSION["idusuario"]."', '0','0',
					'0' , '".utf8_decode("Pago de Cuotas")."', '".$_REQUEST['monto']."', '".$_REQUEST['fechainicio']."' , '1','0','0','".$idapertura."' )");
					if ($regpagosrealizados['resultado'] == false) { $error++; }

					$buscarCuotasPendiente = $conexion->sql("SELECT id, monto FROM detprestamos 
					WHERE idprestamo = '{$_REQUEST['idprestamo']}' AND pagado = 0 ORDER BY id ASC");

					foreach ($buscarCuotasPendiente as $key => $value) {

						if ($montoRecibido != 0) {

							if ($montoRecibido < $value['monto']) {
								$sql = "UPDATE detprestamos SET abono='{$montoRecibido}' WHERE id = {$value['id']} ";
								if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
								$montoRecibido = 0;
							}else{
								$sql = "UPDATE detprestamos SET fechapago= '".$_REQUEST['fechainicio']."' , abono = '0', pagado = '1' WHERE id = {$value['id']} ";
								if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
								$montoRecibido -= $value['monto'];
							}

						}


						if ($value['monto'] == 0) {
							$sql = "UPDATE detprestamos SET fechapago= '".$_REQUEST['fechainicio']."' , abono = '0', pagado = '1' WHERE id = {$value['id']} ";
							if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
							$montoRecibido -= $value['monto'];
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
					
				}else{
					$respuesta["resultado"] = false;
					$respuesta["mensaje"] = "El monto ingresado es mayor a lo pendiente ";
				}


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


function eliminarPago(){
	
	try
	{	
		$conexion = new conexion();
        $conexion->transaccion();
        $error = 0;

		if ( $_REQUEST['pagocuota'] == 1 ) {
	

			$buscarMontoPagado = $conexion->sql("SELECT monto, idprestamo,
			IFNULL( (SELECT SUM(abono) FROM detprestamos WHERE idprestamo = pagosrealizados.idprestamo )  ,0)  AS abono,
			IFNULL( (SELECT resumenpagos FROM prestamos WHERE id = pagosrealizados.idprestamo )  ,'')  as montoUnitario
			FROM pagosrealizados WHERE id = '{$_REQUEST['idpago']}' ");

			$montoPagado = $buscarMontoPagado[0]["monto"];
			$abonoPagado = $buscarMontoPagado[0]["abono"];
			$idprestamo = $buscarMontoPagado[0]["idprestamo"];
			$montoUnitario = $buscarMontoPagado[0]["montoUnitario"];

			#Es porque existe algún abono en la última cuota
			if ($abonoPagado > 0) {

				$buscarAbono = $conexion->sql("SELECT id FROM detprestamos WHERE abono > 0 && idprestamo = '{$idprestamo}' ");
				
				#El abono es más grande al monto a cancelar
				if ($abonoPagado >= $montoPagado) {

					/*
						Aquí solo hacemos la resta del abono menos el monto
						En el registro que tenga el abono
					*/

					$abonoPagado -= $montoPagado;
					$montoPagado = 0;

					$sql = "UPDATE detprestamos SET abono='{$abonoPagado}' WHERE id = {$buscarAbono[0]['id']} ";
					if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; };				

				}else{

					/*
						Aquí en el registro que tiene el abono lo ponemos en 0				
					*/

					$montoPagado -= $abonoPagado;

					$sql = "UPDATE detprestamos SET abono='0' WHERE id = {$buscarAbono[0]['id']} ";
					if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; };
					
				}

			}

			if ($montoPagado > 0) {

				$totalMontoPagado = $montoPagado;
				$cuotas = floor( ( $totalMontoPagado / $montoUnitario ) );
				//$abono = $totalMontoPagado % $montoUnitario;
				$abono = fmod($totalMontoPagado,$montoUnitario);

				$abonoRestante = 0;

				if ($abono > 0) {
					$abonoRestante = $buscarMontoPagado[0]["montoUnitario"] - $abono;
				}

				$buscarCuotasAnular = $conexion->sql("SELECT id FROM detprestamos 
				WHERE idprestamo = '{$idprestamo}' AND pagado = 1 AND tipo = 0 ORDER BY id DESC LIMIT {$cuotas}");

				foreach ($buscarCuotasAnular as $key => $value) {
					$sql = "UPDATE detprestamos SET fechapago= NULL , abono = '0', pagado = '0' WHERE id = {$value['id']} ";
					if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
				}

				if ($abonoRestante > 0) {

					$buscarCuotasAnular = $conexion->sql("SELECT id FROM detprestamos 
					WHERE idprestamo = '{$idprestamo}' AND pagado = 1 AND tipo = 0 ORDER BY id DESC LIMIT 1");
					$sql = "UPDATE detprestamos SET fechapago= NULL, abono='{$abonoRestante}', pagado = '0' WHERE id = {$buscarCuotasAnular[0]['id']} ";
					if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; };

				}

			}


		}else if ( $_REQUEST['pagocuota'] == 4 || $_REQUEST['pagocuota'] == 5 ) {


			$buscarIDPrestamo = $conexion->sql("SELECT idprestamo
			FROM pagosrealizados WHERE id = '{$_REQUEST['idpago']}' ");

			$idprestamo = $buscarIDPrestamo[0]["idprestamo"];

			$buscarCuotasMorasAnular = $conexion->sql("SELECT id FROM detprestamos WHERE idprestamo = '{$idprestamo}' AND mora > 0
			AND fecha <= ADDDATE(CURDATE(),-1) AND morapagada != 2");

			foreach ($buscarCuotasMorasAnular as $key => $value) {
				$sql = "UPDATE detprestamos SET abonomora = '0', morapagada = '0' WHERE id = {$value['id']} ";
				if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
			}


			$buscarSumaMorasPagadas = $conexion->sql("SELECT IFNULL(SUM(monto) - SUM(abonocuota),0) AS pagoPorMora 
			FROM pagosrealizados WHERE idprestamo = '{$idprestamo}' AND (estado = 4 OR estado = 5) AND id != '{$_REQUEST['idpago']}' ");

			$montoRecibido = $buscarSumaMorasPagadas[0]["pagoPorMora"];

			$buscarMorasPendientes = $conexion->sql("SELECT * FROM detprestamos WHERE idprestamo = '{$idprestamo}' AND mora > 0
			AND fecha <= ADDDATE(CURDATE(),-1) AND morapagada = 0 ORDER BY id ASC");


			foreach ($buscarMorasPendientes as $key => $value) {

				if ($montoRecibido != 0) {

					if ($montoRecibido < $value['mora']) {
				
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

		}



		/*CUANDO ES DE TIPO = 5, ES PORQUE TIENE SALDO QUE SE CARGO A LAS CUOTAS. 
		POR ENDE, HAY QUE DESCARGAR ESE SALDO */


		if ( $_REQUEST['pagocuota'] == 5 && $_REQUEST['tipoPlan'] != 5 ) {
	
			$buscarMontoPagado = $conexion->sql("SELECT abonocuota as monto, idprestamo,
			IFNULL( (SELECT SUM(abono) FROM detprestamos WHERE idprestamo = pagosrealizados.idprestamo )  ,0)  AS abono,
			IFNULL( (SELECT resumenpagos FROM prestamos WHERE id = pagosrealizados.idprestamo )  ,'')  as montoUnitario
			FROM pagosrealizados WHERE id = '{$_REQUEST['idpago']}' ");

			$montoPagado = $buscarMontoPagado[0]["monto"];
			$abonoPagado = $buscarMontoPagado[0]["abono"];
			$idprestamo = $buscarMontoPagado[0]["idprestamo"];
			$montoUnitario = $buscarMontoPagado[0]["montoUnitario"];

			#Es porque existe algún abono en la última cuota
			if ($abonoPagado > 0) {

				$buscarAbono = $conexion->sql("SELECT id FROM detprestamos WHERE abono > 0 && idprestamo = '{$idprestamo}' ");
				
				#El abono es más grande al monto a cancelar
				if ($abonoPagado >= $montoPagado) {

					
					//	Aquí solo hacemos la resta del abono menos el monto
					//	En el registro que tenga el abono
					

					$abonoPagado -= $montoPagado;
					$montoPagado = 0;

					$sql = "UPDATE detprestamos SET abono='{$abonoPagado}' WHERE id = {$buscarAbono[0]['id']} ";
					if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; };				

				}else{

					
					//	Aquí en el registro que tiene el abono lo ponemos en 0				
					

					$montoPagado -= $abonoPagado;

					$sql = "UPDATE detprestamos SET abono='0' WHERE id = {$buscarAbono[0]['id']} ";
					if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; };
					
				}

			}

			if ($montoPagado > 0) {

				$totalMontoPagado = $montoPagado;
				$cuotas = floor( ( $totalMontoPagado / $montoUnitario ) );
				//$abono = $totalMontoPagado % $montoUnitario;
				$abono = fmod($totalMontoPagado,$montoUnitario);

				$abonoRestante = 0;

				if ($abono > 0) {
					$abonoRestante = $buscarMontoPagado[0]["montoUnitario"] - $abono;
				}

				$buscarCuotasAnular = $conexion->sql("SELECT id FROM detprestamos 
				WHERE idprestamo = '{$idprestamo}' AND pagado = 1 AND tipo = 0 ORDER BY id DESC LIMIT {$cuotas}");

				foreach ($buscarCuotasAnular as $key => $value) {
					$sql = "UPDATE detprestamos SET fechapago= NULL , abono = '0', pagado = '0' WHERE id = {$value['id']} ";
					if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
				}

				if ($abonoRestante > 0) {

					$buscarCuotasAnular = $conexion->sql("SELECT id FROM detprestamos 
					WHERE idprestamo = '{$idprestamo}' AND pagado = 1 AND tipo = 0 ORDER BY id DESC LIMIT 1");
					$sql = "UPDATE detprestamos SET fechapago= NULL, abono='{$abonoRestante}', pagado = '0' WHERE id = {$buscarCuotasAnular[0]['id']} ";
					if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; };

				}

			}


		}else if( $_REQUEST['pagocuota'] == 5 && $_REQUEST['tipoPlan'] == 5){
			
			$buscarMontoEliminado = $conexion->sql("SELECT idprestamo, abonocuota as monto FROM pagosrealizados WHERE id = '{$_REQUEST['idpago']}' ");

			$idprestamo = $buscarMontoEliminado[0]["idprestamo"];

			$buscarMontoAbono = $conexion->sql("SELECT IFNULL(SUM(monto),0)+IFNULL((SELECT SUM(abonocuota) 
			FROM pagosrealizados pg WHERE pg.idprestamo = '{$idprestamo}' ),0) as totalAbono FROM 
			pagosrealizados WHERE idprestamo = '{$idprestamo}' AND estado = 1 ");
			
			//Se resetean todos los pagos en la tabla 'detprestamos'
			$sql="SELECT * FROM detprestamos WHERE idprestamo = {$idprestamo} AND tipo = 0";		
			
			$result = $conexion->sql($sql);
			
			foreach ($result as $key => $value) {
				$sql = "UPDATE detprestamos SET fechapago= NULL , abono = '0', pagado = '0' WHERE id = {$value['id']} ";
				if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
			}

			$montoRecibido = $buscarMontoAbono[0]["totalAbono"] - $buscarMontoEliminado[0]["monto"];

			$buscarCuotasPendiente = $conexion->sql("SELECT id, monto FROM detprestamos 
			WHERE idprestamo = '{$idprestamo}' AND pagado = 0 ORDER BY id ASC");

			foreach ($buscarCuotasPendiente as $key => $value) {

				if ($montoRecibido != 0) {

					if ($montoRecibido < $value['monto']) {
						$sql = "UPDATE detprestamos SET abono='{$montoRecibido}' WHERE id = {$value['id']} ";
						if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
						$montoRecibido = 0;
					}else{
						$sql = "UPDATE detprestamos SET fechapago= NOW() , abono = '0', pagado = '1' WHERE id = {$value['id']} ";
						if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
						$montoRecibido -= $value['monto'];
					}

				}
							
			}

		}



		//Antes de eliminar el pago capturamos el registro del pago en la tabla de pagosanulados


		$idapertura = 0;
		$verCaja = $conexion->sql("SELECT cajas.id, cajas.estado , cajas.descripcion as caja, cajasaperturas.id as idapertura FROM usuarios		
		INNER JOIN cajas ON cajas.idusuario = usuarios.id
		INNER JOIN cajasaperturas ON cajasaperturas.idcaja = cajas.id
		WHERE cajasaperturas.idusuarioinicio = {$_SESSION["idusuario"]} AND usuarios.idapertura = cajasaperturas.id ORDER BY cajasaperturas.id DESC LIMIT 1");

		if(count( $verCaja ) > 0){
			if($verCaja[0]["estado"] == 1 ){
				$idapertura = $verCaja[0]["idapertura"];
			}
		}


		$regppagosanulados = $conexion->sqlOperacion("INSERT INTO pagosanulados (idprestamo, idcliente, idusuario, 
		idcierreganancias, iddeposito, plan, descripcion, monto, fechapago, estado, deposito, idtransaccion,idapertura,justificacion) 
		SELECT idprestamo, idcliente, '".$_SESSION['idusuario']."', idcierreganancias, iddeposito, 
		plan, descripcion, monto, fechapago, estado, deposito, idtransaccion, '".$idapertura."' , '".utf8_decode($_REQUEST["justificacion"])."' 
		FROM pagosrealizados WHERE id = {$_REQUEST['idpago']}");
		if ($regppagosanulados['resultado'] == false) { $error++; }


		$resPagosrealizados = mysql_query("DELETE FROM pagosrealizados WHERE id = {$_REQUEST['idpago']}");

		if (!$error) {
			$respuesta["resultado"] = true;
			$conexion->respuestaTrans("COMMIT");
			$respuesta["mensaje"] = "Datos eliminados correctamente ";
		}else{
			$respuesta["resultado"] = false;
			$conexion->respuestaTrans("ROLLBACK");
			$respuesta["mensaje"] = "Datos No eliminados ";
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



function eliminarInteres_plan5(){
	
	try
	{	
		$conexion = new conexion();
        $conexion->transaccion();
        $error = 0;

		$buscarMontoEliminado = $conexion->sql("SELECT idprestamo, monto FROM pagosrealizados WHERE id = '{$_REQUEST['idpago']}' ");

		$idprestamo = $buscarMontoEliminado[0]["idprestamo"];

		$buscarMontoAbono = $conexion->sql("SELECT IFNULL(SUM(monto),0)+IFNULL((SELECT SUM(abonocuota) 
		FROM pagosrealizados pg WHERE pg.idprestamo = '{$idprestamo}' ),0) as totalAbono FROM 
		pagosrealizados WHERE idprestamo = '{$idprestamo}' AND estado = 1 ");
		
		//Se resetean todos los pagos en la tabla 'detprestamos'
		$sql="SELECT * FROM detprestamos WHERE idprestamo = {$idprestamo} AND tipo = 0";		

		$result = $conexion->sql($sql);
		
		foreach ($result as $key => $value) {
			$sql = "UPDATE detprestamos SET fechapago= NULL , abono = '0', pagado = '0' WHERE id = {$value['id']} ";
			if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
		}

		$montoRecibido = $buscarMontoAbono[0]["totalAbono"] - $buscarMontoEliminado[0]["monto"];

		$buscarCuotasPendiente = $conexion->sql("SELECT id, monto FROM detprestamos 
		WHERE idprestamo = '{$idprestamo}' AND pagado = 0 ORDER BY id ASC");

		foreach ($buscarCuotasPendiente as $key => $value) {

			if ($montoRecibido != 0) {

				if ($montoRecibido < $value['monto']) {
					$sql = "UPDATE detprestamos SET abono='{$montoRecibido}' WHERE id = {$value['id']} ";
					if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
					$montoRecibido = 0;
				}else{
					$sql = "UPDATE detprestamos SET fechapago= NOW() , abono = '0', pagado = '1' WHERE id = {$value['id']} ";
					if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
					$montoRecibido -= $value['monto'];
				}

			}
						
		}
		

		//Antes de eliminar el pago capturamos el registro del pago en la tabla de pagosanulados

		$idapertura = 0;
		$verCaja = $conexion->sql("SELECT cajas.id, cajas.estado , cajas.descripcion as caja, cajasaperturas.id as idapertura FROM usuarios		
		INNER JOIN cajas ON cajas.idusuario = usuarios.id
		INNER JOIN cajasaperturas ON cajasaperturas.idcaja = cajas.id
		WHERE cajasaperturas.idusuarioinicio = {$_SESSION["idusuario"]} AND usuarios.idapertura = cajasaperturas.id ORDER BY cajasaperturas.id DESC LIMIT 1");

		if(count( $verCaja ) > 0){
			if($verCaja[0]["estado"] == 1 ){
				$idapertura = $verCaja[0]["idapertura"];
			}
		}


		$regppagosanulados = $conexion->sqlOperacion("INSERT INTO pagosanulados (idprestamo, idcliente, idusuario, 
		idcierreganancias, iddeposito, plan, descripcion, monto, fechapago, estado, deposito, idtransaccion,idapertura,justificacion) 
		SELECT idprestamo, idcliente, '".$_SESSION['idusuario']."', idcierreganancias, iddeposito, 
		plan, descripcion, monto, fechapago, estado, deposito, idtransaccion, '".$idapertura."' , '".utf8_decode($_REQUEST["justificacion"])."' 
		FROM pagosrealizados WHERE id = {$_REQUEST['idpago']}");
		if ($regppagosanulados['resultado'] == false) { $error++; }


		$resPagosrealizados = mysql_query("DELETE FROM pagosrealizados WHERE id = {$_REQUEST['idpago']}");

		if (!$error) {
			$respuesta["resultado"] = true;
			$conexion->respuestaTrans("COMMIT");
			$respuesta["mensaje"] = "Datos eliminados correctamente ";
		}else{
			$respuesta["resultado"] = false;
			$conexion->respuestaTrans("ROLLBACK");
			$respuesta["mensaje"] = "Datos No eliminados ".$error;
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



function mostrarMorasXprestamo(){
	try
	{	
		$conexion = new conexion();
				
		$sql="SELECT *, IFNULL( (SELECT n FROM planesprestamo WHERE idprestamo = detprestamos.idprestamo)  ,0)  as cantidadn 
		FROM detprestamos WHERE idprestamo = '{$_REQUEST['idprestamo']}' AND mora > 0
		AND fecha <= ADDDATE(CURDATE(),-1) AND morapagada = 0 ORDER BY id ASC";			
		
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



function exonerarMora(){

	
	try
	{	
		$conexion = new conexion();
        $conexion->transaccion();
        $error = 0;

		$detalle = preg_replace("/([a-zA-Z0-9_]+?):/" , "\"$1\":", $_REQUEST["morasAnuladas"]); // fix variable names 
		$arrayDetalle = json_decode($detalle, true); 	

		foreach ($arrayDetalle as $key => $value) {
			$sql = "UPDATE detprestamos SET morapagada= '2' WHERE id = {$value} ";
			if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
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

function pagarMora(){
		
	try
	{	
		$conexion = new conexion();
        $conexion->transaccion();
        $error = 0;

		$idapertura = 0;
		$verCaja = $conexion->sql("SELECT cajas.id, cajas.estado , cajas.descripcion as caja, cajasaperturas.id as idapertura FROM usuarios		
		INNER JOIN cajas ON cajas.idusuario = usuarios.id
		INNER JOIN cajasaperturas ON cajasaperturas.idcaja = cajas.id
		WHERE cajasaperturas.idusuarioinicio = {$_SESSION["idusuario"]} AND usuarios.idapertura = cajasaperturas.id ORDER BY cajasaperturas.id DESC LIMIT 1");

		if(count( $verCaja ) > 0){
			if($verCaja[0]["estado"] == 1 ){
				$idapertura = $verCaja[0]["idapertura"];
			}
		}


		$buscarPrestamo = $conexion->sql("SELECT idcliente FROM prestamos WHERE id = '{$_REQUEST['id_prestamo']}' ");			

		$regpagosrealizados = $conexion->sqlOperacion("INSERT INTO pagosrealizados (idprestamo, idcliente, idusuario, 
		idcierreganancias, iddeposito, plan, descripcion, monto, fechapago, estado, deposito, idtransaccion,idapertura) 
		VALUES ('".$_REQUEST['id_prestamo']."','".$buscarPrestamo[0]["idcliente"]."', '".$_SESSION["idusuario"]."', '0','0',
		'0' , '".utf8_decode("Pago por mora")."', '".$_REQUEST['montomora']."', '".$_REQUEST['fechaPagarMora']."' , '0','0','0','".$idapertura."' )");
		if ($regpagosrealizados['resultado'] == false) { $error++; }
	
		$sql = "UPDATE detprestamos SET mora = '".$_REQUEST['montomora']."', morapagada= '1' WHERE id = {$_REQUEST['iddetprestamos']} ";
		if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 

		
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


function finalizarPrestamo(){
			
	try
	{	
		$conexion = new conexion();
        $conexion->transaccion();
        $error = 0;
		
		$sql = "UPDATE prestamos SET estado = '0' WHERE id = {$_REQUEST['idprestamo']} ";
		if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
		
		if (!$error) {
			$respuesta["resultado"] = true;
			$conexion->respuestaTrans("COMMIT");
			$respuesta["mensaje"] = "Registro finalizado correctamente ";
		}else{
			$respuesta["resultado"] = false;
			$conexion->respuestaTrans("ROLLBACK");
			$respuesta["mensaje"] = "Datos No finalizados ";
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


function verificarPago(){

	try
	{	
		$conexion = new conexion();
        $conexion->transaccion();
        $error = 0;
		
		$sql = "UPDATE pagosrealizados SET verificado = '{$_REQUEST['dato']}' WHERE id = {$_REQUEST['id']} ";
		if($conexion->sqlOperacion($sql)["resultado"] == false){ $error++; }; 
		
		if (!$error) {
			$respuesta["resultado"] = true;
			$conexion->respuestaTrans("COMMIT");
			$respuesta["mensaje"] = "Registro verificado correctamente ";
		}else{
			$respuesta["resultado"] = false;
			$conexion->respuestaTrans("ROLLBACK");
			$respuesta["mensaje"] = "Datos No verificado ";
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



function codigosiguiente()
{
	try
	{	
		$conexion = new conexion();		
		$sql="SELECT MAX(codigo)+1 as codigosiguiente FROM prestamos";	
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


?>