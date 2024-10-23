<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors",0);
require_once("classSQL.php");

$accion =$_REQUEST['accion'];

switch ($accion) {
	case 'nuevo':
			nuevoDiaFestivo();
		break;
	case 'editar':
			editarDiaFestivo();
		break;
	case 'eliminar':
			eliminarDiaFestivo();
		break;
	case 'mostrar':
			mostrarDiasFestivos();
		break;  		
	case 'llenarCalendario':
			llenarCalendario();
		break;
	case 'buscarFecha':
			buscarFecha();
		break;
		
}


function nuevoDiaFestivo()
{
	try
	{	
		$conexion = new conexion();
            
        $sql = "INSERT INTO diasfestivos (descripcion, fecha) 
        VALUES ('".utf8_decode($_REQUEST['newDescripcion'])."',
        '2023-".$_REQUEST['newMeses']."-".$_REQUEST['newDia']."')";

        $regDiaFestivo = $conexion->sqlOperacion($sql);

        if ($regDiaFestivo["resultado"] == true ) {
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

function editarDiaFestivo()
{
	try
	{	
		$conexion = new conexion();
		
        $sql = "UPDATE diasfestivos SET descripcion= '".utf8_decode($_REQUEST['editDescripcion'])."' ,
        fecha= '2023-".$_REQUEST['editMeses']."-".$_REQUEST['editDia']."'
        WHERE id = ".$_REQUEST['iddiaFestivo'];

        $resDiasFestivos = $conexion->sqlOperacion($sql);

        if($resDiasFestivos){
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

function eliminarDiaFestivo()
{
	try
	{	
		$conexion = new conexion();
		$sql = "DELETE FROM diasfestivos WHERE id=".$_REQUEST['iddiaFestivo'];

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


function mostrarDiasFestivos()
{
	try
	{	
		$conexion = new conexion();
		
		if ($_REQUEST['id']) {
			$sql="SELECT * FROM diasfestivos WHERE id = {$_REQUEST['id']}  ";
		}else{
			$sql="SELECT * FROM diasfestivos ORDER BY fecha ASC";			
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

function llenarCalendario()
{
	try
	{	
		$conexion = new conexion();
        $conexion->transaccion();
		$respuesta["diasMarcados"] = array();
        $error = 0;
		$contPrimeraCuota = 0;

        $buscarDiasFestivos = $conexion->sql("SELECT fecha FROM diasfestivos ORDER BY fecha ASC");  
        $buscarPlan = $conexion->sql("SELECT cuotas, tipo, dias FROM planes WHERE id = '{$_REQUEST['idplan']}' ");  

        $fechainicioX = $_REQUEST['fechainicio']; #Por ahora esta en formato YYYY-MM-DD

        if ($buscarPlan[0]["tipo"] == 1) {
            $dias_pago = explode(";", $buscarPlan[0]["dias"] );
            for ($i=0; $i < $buscarPlan[0]["cuotas"]; $i++) {            
				
				if ($contPrimeraCuota > 0) {
					$fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 1 day"));
				}
			
                $dia = date("N", strtotime($fechainicioX));//aquí voy contando los días.
                if (in_array($dia, $dias_pago)) { // Si se encuentra en los días de pago, entonces lo inserta
					$fechainicioX;                   
                    array_push($respuesta["diasMarcados"] , $fechainicioX); 
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

                array_push($respuesta["diasMarcados"] , $fechainicioX);  

				$contPrimeraCuota++;
            }
        }else if($buscarPlan[0]["tipo"] == 3){
            for ($i=0; $i < $buscarPlan[0]["cuotas"]; $i++) {               
				
				if ($contPrimeraCuota > 0){
					$fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 2 week")); //Consultar esto
				}

                array_push($respuesta["diasMarcados"] , $fechainicioX);      
				
				$contPrimeraCuota++;				
            }
        }else if($buscarPlan[0]["tipo"] == 4 || $buscarPlan[0]["tipo"] == 5){
            for ($i=0; $i < $buscarPlan[0]["cuotas"]; $i++) {             
				
				if ($contPrimeraCuota > 0){
					$fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 1 month"));
				}
                
                array_push($respuesta["diasMarcados"] , $fechainicioX); 

				$contPrimeraCuota++;
            }
        }

    
		$respuesta["resultado"] = true;
		$respuesta["buscarDiasFestivos"] = $buscarDiasFestivos;
	

	}
	catch (Exception $e)
	{
		$respuesta['resultado']=false;
		$respuesta['mensaje']=$e;
	}

	echo json_encode( $respuesta );
	$conexion->respuestaTrans("COMMIT");	
}

function buscarFecha(){

	try
	{	
		$conexion = new conexion();
        $conexion->transaccion();		
        $error = 0;

        $buscarPlan = $conexion->sql("SELECT tipo FROM planes WHERE id = '{$_REQUEST['idplan']}' ");  

        $fechainicioX = date("Y-m-d");

        if ($buscarPlan[0]["tipo"] == 1){   
			$fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 1 day"));							
        }else if($buscarPlan[0]["tipo"] == 2){
            $fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 7 day"));
        }else if($buscarPlan[0]["tipo"] == 3){
            $fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 2 week")); //Consultar esto
        }else if($buscarPlan[0]["tipo"] == 4 || $buscarPlan[0]["tipo"] == 5){
            $fechainicioX = date("Y-m-d", strtotime($fechainicioX." + 1 month"));
        }

    
		$respuesta["resultado"] = true;
		$respuesta["buscarFecha"] = $fechainicioX;
	

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