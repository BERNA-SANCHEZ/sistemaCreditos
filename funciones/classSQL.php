<?php


$server="localhost";
$dbuser="root";
$dbpass="";
$dbname="sistemaprestamos";


$chandle = @mysql_connect($server, $dbuser, $dbpass) or die("Error conectando a la BBDD");

mysql_select_db($dbname, $chandle) or die ($dbname . " Base de datos no encontrada." . $dbuser);

date_default_timezone_set("America/Guatemala");
ini_set("session.cookie_lifetime","45000");
ini_set("session.gc_maxlifetime","45000");
error_reporting(0);


class conexion 
{

	public function sql($consulta){
		$query =mysql_query ($consulta);
		
		$datos =array();
		
		if($query){			

			while ($registros =mysql_fetch_assoc ($query)){
				array_push($datos, array_map(utf8_encode, $registros));
			}
			
		}

		return $datos;
	}

	public function sqlOperacion($sql)
	{
		$respuesta = array();
		mysql_query($sql);
		$resultado = mysql_error()=='' ? true : false;
		$pos = strpos(strtoupper($sql), "INSERT");
		$ultimoId= -1;

		if($resultado){
			if ($pos !== false) {  $ultimoId = mysql_insert_id(); }

	    	$respuesta['mensaje']="Operación realizada correctamente";
	    	$respuesta['ultimoId']=$ultimoId;
	    	$respuesta['resultado']= true;

		}else{
	    	$respuesta['mensaje']="Operación no realizada";
	    	$respuesta['error']=mysql_error();
	    	$respuesta['resultado']= false;
		}

		return $respuesta;
	}

	public function transaccion(){
 		mysql_query("SET AUTOCOMMIT=0");// habilitar las transacciones ...quita la relación momentaneamente
		mysql_query("START TRANSACTION");//inicia la transacción
	}

	public function respuestaTrans($respuesta){// Commit para gantizar que se guardaron los datos
 		mysql_query($respuesta); // rollBack para cancelar la operación si no se completo alguna de las dos
		mysql_close($chandle);
	}

	public function permisos($idtipousuario, $idmodulo, $campo)
	{
		$query =mysql_query("SELECT * FROM permisos WHERE IdTipoUsuario = $idtipousuario AND IdModulo = $idmodulo AND $campo = 1");
		$registros =mysql_fetch_assoc($query);
		if (count($registros) > 1 ) 
			return true;
  		else
    		return false;
	}

	
}



?>