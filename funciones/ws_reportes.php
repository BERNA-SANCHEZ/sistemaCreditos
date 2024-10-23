<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors",0);
require_once("classSQL.php");

$accion =$_REQUEST['accion'];

switch ($accion) {
	    
	case 'reporteCaja':
            reporteCaja();
		break;
	case 'reportePagosAnulados':
			reportePagosAnulados();
		break;
	case 'eliminarpagosanulados':
			eliminarpagosanulados();
		break;
	case 'reporteCartera':
			reporteCartera();
		break;		
	case 'reporteProximosFinalizar':
			reporteProximosFinalizar();
		break;
		
}


function reporteCaja()
{
	try
	{	
		$conexion = new conexion();

        if ($_REQUEST['vertodo'] == 1) {
            $sql="SELECT *,
            IFNULL( (SELECT nombre FROM clientes WHERE id = pagosrealizados.idcliente)  ,'')  as nombreCliente,
            IFNULL( (SELECT nombre FROM usuarios WHERE id = pagosrealizados.idusuario)  ,'')  as usuarioRecibio
            FROM pagosrealizados";		
		}else{
            $sql="SELECT *,
            IFNULL( (SELECT nombre FROM clientes WHERE id = pagosrealizados.idcliente)  ,'')  as nombreCliente,
            IFNULL( (SELECT nombre FROM usuarios WHERE id = pagosrealizados.idusuario)  ,'')  as usuarioRecibio
            FROM pagosrealizados WHERE fechapago BETWEEN '".date("Y-m-d H:i:s",strtotime($_REQUEST['fechainicio']." 00:00:00"))."' AND '".date("Y-m-d H:i:s",strtotime($_REQUEST['fechafin']." 23:59:59"))."' ";	
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


function reportePagosAnulados()
{
	try
	{	
		$conexion = new conexion();

        if ($_REQUEST['vertodo'] == 1) {
            $sql="SELECT *,
            IFNULL( (SELECT nombre FROM clientes WHERE id = pagosanulados.idcliente)  ,'')  as nombreCliente,
            IFNULL( (SELECT nombre FROM usuarios WHERE id = pagosanulados.idusuario)  ,'')  as usuarioRecibio
            FROM pagosanulados";		
		}else{
            $sql="SELECT *,
            IFNULL( (SELECT nombre FROM clientes WHERE id = pagosanulados.idcliente)  ,'')  as nombreCliente,
            IFNULL( (SELECT nombre FROM usuarios WHERE id = pagosanulados.idusuario)  ,'')  as usuarioRecibio
            FROM pagosanulados WHERE fechapago BETWEEN '".date("Y-m-d H:i:s",strtotime($_REQUEST['fechainicio']." 00:00:00"))."' AND '".date("Y-m-d H:i:s",strtotime($_REQUEST['fechafin']." 23:59:59"))."' ";	
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



function eliminarpagosanulados(){
	
	try
	{	
		$conexion = new conexion();


		$resPagosrealizados = mysql_query("DELETE FROM pagosanulados WHERE id = {$_REQUEST['idpago']}");

		if (!$error) {
			$respuesta["resultado"] = true;
			$respuesta["mensaje"] = "Datos eliminados correctamente ";
		}else{
			$respuesta["resultado"] = false;
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



function reporteCartera()
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


		if ($_REQUEST['checkFinalizado'] == 1) {

			$queryFiltroPrestamos = " WHERE estado = 0 ";
			$queryFiltroCobrador = 	" WHERE idcobrador = ".$_REQUEST['id_usuario']." AND estado = 0 ";
			$queryFiltroSupervisor = " WHERE idsupervisor = ".$_REQUEST['id_usuario']." AND estado = 0 ";

		}else if ($_REQUEST['checkTODOS'] == 1){

			$queryFiltroPrestamos = " WHERE estado = 1 ";
			$queryFiltroCobrador = 	" WHERE idcobrador = ".$_REQUEST['id_usuario']." AND estado = 1 ";
			$queryFiltroSupervisor = " WHERE idsupervisor = ".$_REQUEST['id_usuario']." AND estado = 1 ";

		}else{

			if ($_REQUEST['checkDIA'] == 1 && $_REQUEST['checkPENDIENTES'] == 0){

				$queryFiltroPrestamos = " 
            	INNER JOIN detprestamos ON prestamos.id = detprestamos.idprestamo
				WHERE prestamos.estado = 1
				AND detprestamos.fecha = '".$fecha_hoy."' AND detprestamos.pagado = 0 AND (detprestamos.tipo = 0 OR detprestamos.tipo = 3) GROUP BY prestamos.id ";

				$queryFiltroCobrador = " 
            	INNER JOIN detprestamos ON prestamos.id = detprestamos.idprestamo
				WHERE prestamos.estado = 1 AND prestamos.idcobrador = ".$_REQUEST['id_usuario']."
				AND detprestamos.fecha = '".$fecha_hoy."' AND detprestamos.pagado = 0 AND (detprestamos.tipo = 0 OR detprestamos.tipo = 3) GROUP BY prestamos.id ";

				$queryFiltroSupervisor = " 
            	INNER JOIN detprestamos ON prestamos.id = detprestamos.idprestamo
				WHERE prestamos.estado = 1 AND prestamos.idsupervisor = ".$_REQUEST['id_usuario']."
				AND detprestamos.fecha = '".$fecha_hoy."' AND detprestamos.pagado = 0 AND (detprestamos.tipo = 0 OR detprestamos.tipo = 3) GROUP BY prestamos.id ";
				


			}else if ($_REQUEST['checkDIA'] == 0 && $_REQUEST['checkPENDIENTES'] == 1){

				$queryFiltroPrestamos = " WHERE estado = 1 AND (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0 AND (tipo = 0 OR tipo = 3) ) > 0 ";
				
				$queryFiltroCobrador = " WHERE estado = 1 AND idcobrador = ".$_REQUEST['id_usuario']." AND (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0 AND (tipo = 0 OR tipo = 3) ) > 0 ";

				$queryFiltroSupervisor = " WHERE estado = 1 AND idsupervisor = ".$_REQUEST['id_usuario']." AND (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0 AND (tipo = 0 OR tipo = 3) ) > 0 ";


			}else if ($_REQUEST['checkDIA'] == 1 && $_REQUEST['checkPENDIENTES'] == 1){

				$queryFiltroPrestamos = " INNER JOIN detprestamos ON prestamos.id = detprestamos.idprestamo
				WHERE prestamos.estado = 1
				AND detprestamos.fecha = '".$fecha_hoy."' AND detprestamos.pagado = 0 AND (detprestamos.tipo = 0 OR detprestamos.tipo = 3) 
				OR (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0 AND (tipo = 0 OR tipo = 3)) > 0
				GROUP BY prestamos.id ";

				$queryFiltroCobrador = " INNER JOIN detprestamos ON prestamos.id = detprestamos.idprestamo
				WHERE prestamos.estado = 1 AND prestamos.idcobrador = ".$_REQUEST['id_usuario']."
				AND detprestamos.fecha = '".$fecha_hoy."' AND detprestamos.pagado = 0 AND (detprestamos.tipo = 0 OR detprestamos.tipo = 3) 
				OR (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0 AND (tipo = 0 OR tipo = 3)
				AND idprestamo IN ( SELECT id FROM prestamos WHERE idcobrador = ".$_REQUEST['id_usuario']." AND estado = 1 ) ) > 0
				GROUP BY prestamos.id ";

				$queryFiltroSupervisor = " INNER JOIN detprestamos ON prestamos.id = detprestamos.idprestamo
				WHERE prestamos.estado = 1 AND prestamos.idsupervisor = ".$_REQUEST['id_usuario']."
				AND detprestamos.fecha = '".$fecha_hoy."' AND detprestamos.pagado = 0 AND (detprestamos.tipo = 0 OR detprestamos.tipo = 3) 
				OR (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0 AND (tipo = 0 OR tipo = 3)
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
			(prestamos.prestamo + ((prestamos.prestamo*planesprestamo.interes)/100)) as 'prestamoMasInteres' ,
			IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idusuario)  ,'')  as usuarioentrego,
			IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idcobrador)  ,'')  as usuariocobrador,
			IFNULL( (SELECT nombre FROM clientes WHERE id = prestamos.idcliente)  ,'')  as nombreCliente
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 1)  ,0)  AS morasPagadas
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND (morapagada = 0 OR morapagada = 4))  ,0)  AS morasPendientes
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 2)  ,0)  AS morasExoneradas
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0 AND (tipo = 0 OR tipo = 3))  ,0)  AS cuotasPendientes
			,IFNULL( (SELECT COUNT(*) FROM pagosrealizados WHERE idprestamo = prestamos.id AND verificado = 0)  ,0)  AS pagosNoVerificados
			,IFNULL( (SELECT ruta FROM rutas_clientes WHERE idClientes = prestamos.idcliente)  ,'upload/user.png')  as foto
			FROM prestamos 
			INNER JOIN planesprestamo on prestamos.id = planesprestamo.idprestamo			
			$queryFiltroSupervisor ";	
		

		}else if ($buscarTipoUsuario[0]["idtipousuario"] == 4){
			

			$sql="SELECT prestamos.*, 
			(prestamos.prestamo + ((prestamos.prestamo*planesprestamo.interes)/100)) as 'prestamoMasInteres' ,
			IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idusuario)  ,'')  as usuarioentrego,
			IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idcobrador)  ,'')  as usuariocobrador,
			IFNULL( (SELECT nombre FROM clientes WHERE id = prestamos.idcliente)  ,'')  as nombreCliente
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 1)  ,0)  AS morasPagadas
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND (morapagada = 0 OR morapagada = 4))  ,0)  AS morasPendientes
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 2)  ,0)  AS morasExoneradas
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0 AND (tipo = 0 OR tipo = 3))  ,0)  AS cuotasPendientes
			,IFNULL( (SELECT COUNT(*) FROM pagosrealizados WHERE idprestamo = prestamos.id AND verificado = 0)  ,0)  AS pagosNoVerificados
			,IFNULL( (SELECT ruta FROM rutas_clientes WHERE idClientes = prestamos.idcliente)  ,'upload/user.png')  as foto
			FROM prestamos 
			INNER JOIN planesprestamo on prestamos.id = planesprestamo.idprestamo			
			$queryFiltroCobrador ";	
			
			
		}else{

			$sql="SELECT prestamos.*, 
			(prestamos.prestamo + ((prestamos.prestamo*planesprestamo.interes)/100)) as 'prestamoMasInteres' ,
			IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idusuario)  ,'')  as usuarioentrego,
			IFNULL( (SELECT nombre FROM usuarios WHERE id = prestamos.idcobrador)  ,'')  as usuariocobrador,
			IFNULL( (SELECT nombre FROM clientes WHERE id = prestamos.idcliente)  ,'')  as nombreCliente
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 1)  ,0)  AS morasPagadas
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND (morapagada = 0 OR morapagada = 4))  ,0)  AS morasPendientes
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND morapagada = 2)  ,0)  AS morasExoneradas
			,IFNULL( (SELECT COUNT(*) FROM detprestamos WHERE idprestamo = prestamos.id AND mora != 0 AND pagado = 0 AND (tipo = 0 OR tipo = 3))  ,0)  AS cuotasPendientes
			,IFNULL( (SELECT COUNT(*) FROM pagosrealizados WHERE idprestamo = prestamos.id AND verificado = 0)  ,0)  AS pagosNoVerificados
			,IFNULL( (SELECT ruta FROM rutas_clientes WHERE idClientes = prestamos.idcliente)  ,'upload/user.png')  as foto
			FROM prestamos			
			INNER JOIN planesprestamo on prestamos.id = planesprestamo.idprestamo			
			$queryFiltroPrestamos ";	


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




function reporteProximosFinalizar()
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


		
		$queryFiltroPrestamos = " WHERE estado = 1 ";
		$queryFiltroCobrador = 	" WHERE idcobrador = ".$_REQUEST['id_usuario']." AND estado = 1 ";
		$queryFiltroSupervisor = " WHERE idsupervisor = ".$_REQUEST['id_usuario']." AND estado = 1 ";



		if ($buscarTipoUsuario[0]["idtipousuario"] == 5) {	

			$sql="SELECT prestamos.*, '#FFF' as textColor,
			IFNULL( (SELECT nombre FROM clientes WHERE id = prestamos.idcliente)  ,'')  AS 'title',
			(SELECT MAX(fecha) FROM detprestamos WHERE 
              detprestamos.idprestamo = prestamos.id AND detprestamos.tipo != 1)  as start
			FROM prestamos 
			INNER JOIN planesprestamo on prestamos.id = planesprestamo.idprestamo			
			$queryFiltroSupervisor ";	
		

		}else if ($buscarTipoUsuario[0]["idtipousuario"] == 4){
			

			$sql="SELECT prestamos.*, '#FFF' as textColor,			
			IFNULL( (SELECT nombre FROM clientes WHERE id = prestamos.idcliente)  ,'')  AS 'title',
			(SELECT MAX(fecha) FROM detprestamos WHERE 
              detprestamos.idprestamo = prestamos.id AND detprestamos.tipo != 1)  as start
			FROM prestamos 
			INNER JOIN planesprestamo on prestamos.id = planesprestamo.idprestamo			
			$queryFiltroCobrador ";	
						
		}else{

			$sql="SELECT prestamos.*,'#FFF' as textColor,
			IFNULL( (SELECT nombre FROM clientes WHERE id = prestamos.idcliente)  ,'')  AS 'title',
			(SELECT MAX(fecha) FROM detprestamos WHERE 
              detprestamos.idprestamo = prestamos.id AND detprestamos.tipo != 1)  as start
			FROM prestamos			
			INNER JOIN planesprestamo on prestamos.id = planesprestamo.idprestamo			
			$queryFiltroPrestamos ";	


		}
	

		$result = $conexion->sql($sql);

		$respuesta["registros"] = $result;

		$respuesta["sql"] = $sql;



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