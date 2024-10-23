<?php
error_reporting(0);
ini_set("memory_limit","-1");
session_start();
error_reporting(E_ALL);
ini_set("display_errors",0);
header("Access-Control-Allow-Origin: *");
require_once("classSQL.php");
include("mpdf/mpdf.php");
include("NumeroALetras.php");


$mpdf=new mPDF('c','legal','','',25,15,35,25,16,13);


function getfechaInicio(){
    $arrayMeses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"); 

	$d=date("d",strtotime($_GET['fechaReconocimiento']));
	$m = $arrayMeses[date("n",strtotime($_GET['fechaReconocimiento']))];
	$y = date("Y",strtotime($_GET['fechaReconocimiento']));

	if ( $d == 1) {
		return (" al primer día del mes de ".$m." del año ".strtolower(convertir($y)));		
	}else{
		return (" a los ".$d." días del mes de ".$m." del año ".strtolower(convertir($y)));
	}

}



$fechaInicio .= getfechaInicio();

function getDatosCliente(){
	$conexion = new conexion();
	$sql="SELECT c.* FROM prestamos p INNER JOIN clientes c ON p.idcliente = c.id WHERE p.id = ".$_GET['idRecPrestamo'];
	$detalle=$conexion->SQL($sql);
	return $detalle;
}

$nombreCliente = strtoupper(getDatosCliente()[0]['nombre']);
$direccionvive = getDatosCliente()[0]['direccionvive'];
$dpiCliente = leerDPI( substr(getDatosCliente()[0]['dpi'],0,4) ).', '.leerDPI( substr(getDatosCliente()[0]['dpi'],4,5) ).', '.leerDPI( substr(getDatosCliente()[0]['dpi'],9,4) );


function obtener_edad_segun_fecha($fecha_nacimiento)
{
    $nacimiento = new DateTime($fecha_nacimiento);
    $ahora = new DateTime(date("Y-m-d"));
    $diferencia = $ahora->diff($nacimiento);
    return $diferencia->format("%y");
}


function leerDPI($dpi){

	if (substr( $dpi , 0, 1) != '0') {
		return strtolower(convertir($dpi));
	}else{

		$nuevoDPI = '';
		$indice = 0;

		for($i=0;$i<strlen($dpi);$i++){ 
			if ( substr($dpi,$i,1) == '0' ) {
				$indice = $i;
			}
		} 

		return "cero ".strtolower(convertir( substr($dpi,$indice) ));

	}
}


function getDatosRepresentanteLegal(){
	$conexion = new conexion();
	$sql="SELECT * FROM representantelegal WHERE id = ".$_GET['idselectrepresentantelegal'];
	$detalle=$conexion->SQL($sql);
	return $detalle;
}


$nombreRepresentanteLegal = strtoupper(getDatosRepresentanteLegal()[0]['nombre']);
$edad = strtolower(convertir( obtener_edad_segun_fecha(getDatosRepresentanteLegal()[0]['nacimiento']) )) ;
$estadoCivil = getDatosRepresentanteLegal()[0]['estadocivil'] == 1? 'soltero' : 'casado' ;
$nacionalidad = getDatosRepresentanteLegal()[0]['nacionalidad'];
$oficio = getDatosRepresentanteLegal()[0]['oficio'];
$dpiRL = leerDPI( substr(getDatosRepresentanteLegal()[0]['dpi'],0,4) ).', '.leerDPI( substr(getDatosRepresentanteLegal()[0]['dpi'],4,5) ).', '.leerDPI( substr(getDatosRepresentanteLegal()[0]['dpi'],9,4) );



function getDatosPrestamo(){
	
	$conexion = new conexion();
	$sql="SELECT p.prestamo, p.horapago , p.resumenpagos , pp.* 
	,(SELECT max(dp.fecha) FROM detprestamos dp WHERE dp.idprestamo = p.id) AS fechafin
	FROM prestamos p 
	INNER JOIN planesprestamo pp ON p.id = pp.idprestamo
	WHERE p.id = ".$_GET['idRecPrestamo'];
	$detalle=$conexion->SQL($sql);


	return $detalle;
}



$totalDeuda = number_format((getDatosPrestamo()[0]['resumenpagos'] * getDatosPrestamo()[0]['cuotas']) , 2, '.', '' );
$txttotalDeuda = convertir(getDatosPrestamo()[0]['resumenpagos'] * getDatosPrestamo()[0]['cuotas']);


$date1 = new DateTime( $_GET['fechaReconocimiento'] );
$date2 = new DateTime( getDatosPrestamo()[0]['fechafin'] );


$diff = $date1->diff($date2);

function getfechaFin(){
    $arrayMeses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"); 
	$d=date("d",strtotime(getDatosPrestamo()[0]['fechafin']));
	$m = $arrayMeses[date("n",strtotime(getDatosPrestamo()[0]['fechafin']))];
	return ("finaliza el ". strtolower(convertir( $d )) ." de ".$m);
}



function getamortizaciones(){

	$tipo_plan = "";
	if (getDatosPrestamo()[0]['tipo'] == 1) {
		$tipo_plan ="diarias";
	}else if (getDatosPrestamo()[0]['tipo'] == 2) {
		$tipo_plan ="semanales";
	}else if (getDatosPrestamo()[0]['tipo'] == 3) {
		$tipo_plan ="quincenales";
	}else if (getDatosPrestamo()[0]['tipo'] == 4) {
		$tipo_plan ="mensuales";
	} 

	return ("amortizaciones ".$tipo_plan." por la cantidad de ".strtolower(convertir( getDatosPrestamo()[0]['cuotas'] ))." pagos de Q".number_format(getDatosPrestamo()[0]['resumenpagos'], 2, '.', ''));
	
}


function getTipoPlan(){

	$tipo_plan = "";
	if (getDatosPrestamo()[0]['tipo'] == 1) {
		$tipo_plan ="diarias";
	}else if (getDatosPrestamo()[0]['tipo'] == 2) {
		$tipo_plan ="semanales";
	}else if (getDatosPrestamo()[0]['tipo'] == 3) {
		$tipo_plan ="quincenales";
	}else if (getDatosPrestamo()[0]['tipo'] == 4) {
		$tipo_plan ="mensuales";
	} 
	return $tipo_plan;
	
}


function getGarantia(){

	$conexion = new conexion();
	$sql="SELECT * FROM garantias WHERE idprestamo = ".$_GET['idRecPrestamo'];
	$detalle=$conexion->SQL($sql);


	$cadenaGarantia = "           Serie:           Modelo:           Marca:           ";

	if (count($detalle) > 0) {

		$cadenaGarantia = "";
		
		foreach ($detalle as $key => $value) {

			$cadenaGarantia.= $value["nombre"]." Serie: ".$value["serie"]." Modelo: ".$value["modelo"]." Marca: ".$value["modelo"].", ";
			
		}


	}


	return $cadenaGarantia;

}


function getAbogado(){


	
	$conexion = new conexion();
	$sql="SELECT * FROM abogado WHERE id = ".$_GET['idselectabogado'];
	$detalle=$conexion->SQL($sql);

	$cadenaAbogado = "Yo, <b>".strtoupper($detalle[0]["nombre"])."</b>, Notario, colegiado activo número ".strtolower(convertir($detalle[0]["colegiado"]));
	return $cadenaAbogado;

}



$html = '

<style>

p {
  font-family: sans-serif;
  line-height: 2; 
  font-size: 11pt;
  text-align: justify;
}

</style>


<p style="padding-top: 70px;">
En el municipio y departamento de Retalhuleu,'.$fechaInicio.', Nosotros: 
<b>'.$nombreRepresentanteLegal.'</b>, de '.$edad.' años, '.$estadoCivil.', '.$nacionalidad.', 
'.$oficio.', de este domicilio, me identifico con el Documento Personal de 
Identificación con código único de identificación número: 
'.$dpiRL.', 
extendido por el Registro Nacional de las Personas, 
comparezco en mi calidad de Representante Legal de la Entidad <b>“Prestamos”</b>; y, por la otra parte comparece 
<b>'.$nombreCliente.'</b>, de '.strtolower(convertir($_GET['newEdadCliente'])).' años, soltero, guatemalteco, comerciante, de este domicilio y con 
residencia en '.$direccionvive.', me identifico con el 
Documento Personal de Identificación número '.$dpiCliente.', 
extendido por el Registro Nacional de las Personas. Ambos comparecientes aseguramos hallarnos en el libre ejercicio de nuestros 
derechos civiles, de palabra y en español que hablamos y entendemos por este acto otorgamos <b>RECONOCIMIENTO DE DEUDA EN DOCUMENTO PRIVADO </b>
como sigue: <b>PRIMERO:</b> Yo, <b>'.$nombreCliente.'</b> reconozco liso y llano deudor de <b>“Prestamos”</b>, por la cantidad de 
<b>'.$txttotalDeuda.' QUETZALES EXACTOS (Q. '.$totalDeuda.')</b>, que recibo en estos momentos y en efectivo en calidad de Mutuo, dinero que 
estará sujeto a las siguientes estipulaciones: <b>A) DEL PLAZO:</b> Será de '.strtolower(convertir($diff->days)) .' días a partir de la presente fecha y 
'.getfechaFin().', el cual será prorrogable a voluntad de las partes, debiendo para el efecto realizar un nuevo 
documento para constancia de la ampliación.  <b>B) DEL PAGO:</b> El pago de la cantidad dineraria se realizara mediante  
'.getamortizaciones().', y se cancelaran en '.$direccionvive.'
y el atraso en el pago de dos cuotas '.getTipoPlan().' dará derecho a la entidad a dar por terminado el presente contrato y a 
recoger el bien mueble que el día de hoy deja en garantía el deudor el cual consiste en: '.getGarantia().'
</p>
';




$html2 = '
<p style="padding-top: 70px;">
<b>C)  GARANTIA:</b> Yo, <b>'.$nombreCliente.'</b>, además  del bien mueble 
identificado en el inciso <b>B)</b>, garantizo el cumplimiento de la presente obligación, con mis bienes, presentes y futuros 
los que me obligo a no vender, ceder traspasar, gravar y/o enajenar mientras subsista la presente obligación, 
especialmente con los frutos de mi trabajo. <b>D)</b> Yo el deudor en caso de no cancelar las cuotas estipuladas en la fecha 
pactada podré ser enjuiciada en la vía legal correspondiente y me comprometo a pagar entonces los intereses legales 
correspondientes y los gastos del juicio. <b>E)</b> Yo el deudor en caso de insolvencia desde ya acepto como buenas y exactas 
las cuentas que se me  formulen y acepto como título ejecutivo el  presente documento; <b>F)</b> Yo el deudor renuncio al fuero 
de mi  domicilio y señalo como lugar para recibir mis notificaciones mi domicilio antes mencionado las que se tendrán por 
validas y bien hechas las que ahí se me efectúen. <b>SEGUNDO:</b> Yo <b>'.$nombreCliente.'</b>, manifiesto que acepto el 
contenido del presente documento privado de reconocimiento de deuda, reconozco además la calidad de título ejecutivo al 
presente documento o su reproducción. <b>TERCERA:</b> Yo, <b>'.$nombreRepresentanteLegal.'</b>, en la calidad con que actúo, acepto el 
reconocimiento de deuda que hoy hace <b>'.$nombreCliente.'</b> a mi favor, así mismo la garantía que se deja para cumplir 
la obligación. Ambos comparecientes aceptamos el contenido íntegro del presente documento el cual previa lectura lo, aceptamos, 
ratificamos y firmamos, comprometiéndonos a legalizar nuestras firmas ante Notario.

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>


En la ciudad de Retalhuleu, municipio y departamento del mismo nombre, '.$fechaInicio.', 
'.getAbogado().', DOY FE: 

</p>

';

$html3 = '
<p style="padding-top: 70px;">
A) Que las firmas que anteceden son AUTENTICAS por haber sido puestas a mi presencia el día de hoy en Documento Privado de 
Reconocimiento de Deuda por <b>'.$nombreRepresentanteLegal.'</b> y <b>'.$nombreCliente.'</b> quienes se identifican con los 
Documentos Personales de Identificación números: 
'.$dpiRL.'; 
y,  '.$dpiCliente.' extendidos por el 
Registro Nacional de las Personas. B) Leído lo anterior lo aceptan, ratifican  y  firman nuevamente  en la presente acta de legalización.
</p>

<p style="padding-top: 110px; text-align: right;">
<b>ANTE MÍ:</b>
</p>

';



$mpdf->WriteHTML($html);
$mpdf->AddPage();
$mpdf->WriteHTML($html2);
$mpdf->AddPage();
$mpdf->WriteHTML($html3);

$mpdf->Output();

?>