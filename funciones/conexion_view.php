<?php


$serverdb='localhost';
$userdb='root';
$passdb='';
$dataBase='sistemaprestamos';


mysql_pconnect ($serverdb, $userdb,$passdb);
mysql_select_db ($dataBase);
mysql_query("SET NAMES 'utf8'");
mysql_query("SET lc_time_names = 'es_ES'");
date_default_timezone_get("America/Guatemala");
error_reporting(0);



/**
* 
*/
class conexion_view
{


	public function mostrarMensaje($titulo, $msg, $tipo)
	{
		echo "
		<div class='alert alert-".$tipo."'  role='alert' >
			<a class='close' data-dismiss='alert'> × </a>
			<strong>".$titulo."</strong> ".$msg."&nbsp;
		</div>";
	}
	
	public function sql ($consulta){
		$query =mysql_query ($consulta);
		
		$datos =array();
		$respuesta = array();
		
		if($query){
			while ($registros =mysql_fetch_assoc($query)){
				array_push ($datos, $registros);
			}

			$respuesta['registros']= $datos;
	    	$respuesta['mensaje']="Datos encontrados";
	    	$respuesta['resultado']= true;

		}else{
			$respuesta['registros']= $datos;
	    	$respuesta['mensaje']="Datos no encontrados";
	    	$respuesta['resultado']= false;
		}
	    
	    return $respuesta;
	}


}



?>