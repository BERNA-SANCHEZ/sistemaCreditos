<?php
error_reporting(0);
ini_set("memory_limit","-1");
session_start();
error_reporting(E_ALL);
ini_set("display_errors",0);
header("Access-Control-Allow-Origin: *");
require_once("classSQL.php");
include("mpdf/mpdf.php");

$DateAndTime = date('d/m/Y', time());


$mpdf=new mPDF('c','letter','','',25,15,35,25,16,13);

$contenidoTabla = "";
$contenidoDatosCliente = "";
$contenidoDatosPrestamo = "";
$fechaIniciofechaFin = "";
$idcliente = 0;
$nombreCliente = "";



$number = $_GET['id'];
$stringNoFactura = substr(str_repeat(0, 6).$number, - $length);




$contenidoTabla.= mostrarProductos($_GET['id']);

function mostrarProductos($idPrestamo)
{

	
	$conexion = new conexion();
	$sql="SELECT *, date_format(fechapago, '%d-%m-%Y') as fechapago_formateada FROM detprestamos WHERE idprestamo = ".$idPrestamo." AND tipo != 1";
	$detalle=$conexion->SQL($sql);

    $arrayMeses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"); 


	$filas = ceil(count($detalle)/7);
	$contador = 1;


	foreach ($detalle as $key => $value) {

		$colorFila = $value["pagado"] == 0 ? "" : " style='background: #F8F19F;' ";
		$abono = $value["abono"] == 0 ? $value["fechapago_formateada"] : "Q.".number_format($value['abono'], 2, '.', '');
		$interes = $value["mora"] == 0 ? "" : "Q.".number_format($value['mora'], 2, '.', '');
		$colorFilaInteres = $value["morapagada"] == 1 ? " background: #F8F19F; " : "";
		if ($value["morapagada"] == 2) {
			$colorFilaInteres = " color: #a3a8af;font-weight: 500; ";
			$interes = $value["mora"] == 0 ? "" : "<strike>Q.".number_format($value['mora'], 2, '.', '')."</strike>";			
		}


		$abonomora = $value["abonomora"] > 0 ? "Q.".number_format($value['abonomora'], 2, '.', '') : "";


		$d=date("d",strtotime($value['fecha']));
		$m = $arrayMeses[date("n",strtotime($value['fecha']))];
		$y = date("Y",strtotime($value['fecha']));


		$contenidoTabla.="<tr>
		<td ".$colorFila.">".($key + 1)."</td>
		<td ".$colorFila.">Q ".number_format($value['monto'], 2, '.', '')."</td>
		<td ".$colorFila.">".$d." / ".$m." / ".$y."</td>
		<td ".$colorFila.">".$abono."</td>
		<td style='".$colorFilaInteres."'>".$interes."</td>
		<td>".$abonomora."</td>
		<td></td>
		<td></td>
		</tr>
		";

		
	}
		



	
	return $contenidoTabla;
}


function getIdCliente($idPrestamo)
{
	$conexion = new conexion();
	$sql="SELECT idcliente FROM prestamos WHERE id = ".$idPrestamo;
	$detalle=$conexion->SQL($sql);

   return $detalle[0]['idcliente'];
}

$idcliente = getIdCliente($_GET['id']);



function getDatosCliente($idcliente){
   $conexion = new conexion();
	$sql="SELECT * FROM clientes WHERE id =  ".$idcliente;
	$detalle=$conexion->SQL($sql);

   return ('CLIENTE: <b>'.$detalle[0]['nombre'].'</b><br />NUMERO DE DPI:  <b>'.$detalle[0]['dpi'].' </b><br />DIRECCIÓN:  <b>'.$detalle[0]['direccionvive'].' </b><br />TELÉFONO:  <b>'.$detalle[0]['telefono'].'</b>');
}


$contenidoDatosCliente .= getDatosCliente($idcliente);


function getNombreCliente($idcliente){
	$conexion = new conexion();
	$sql="SELECT nombre FROM clientes WHERE id = ".$idcliente;
	$detalle=$conexion->SQL($sql);
	return $detalle[0]['nombre'];
}

$nombreCliente .= getNombreCliente($idcliente);


function getDatosPrestamo($idPrestamo){
	
	$conexion = new conexion();
	$sql="SELECT p.prestamo, p.horapago , p.resumenpagos , pp.* FROM prestamos p 
	INNER JOIN planesprestamo pp ON p.id = pp.idprestamo
	WHERE p.id = ".$idPrestamo;
	$detalle=$conexion->SQL($sql);

	$tipo_plan = "";


	if ($detalle[0]['tipo'] == 1) {
		$tipo_plan ="PLAN DIARIO";
	}else if ($detalle[0]['tipo'] == 2) {
		$tipo_plan ="PLAN SEMANAL";
	}else if ($detalle[0]['tipo'] == 3) {
		$tipo_plan ="PLAN QUINCENAL";
	}else if ($detalle[0]['tipo'] == 4) {
		$tipo_plan ="PLAN MENSUAL";
	} 
	 
	//return ('VALOR DEL PRÉSTAMO: <b>Q.'.number_format($detalle[0]['prestamo'], 2, '.', '').'</b><br />CUOTAS A PAGAR:  <b>'.$detalle[0]['cuotas'].' </b><br />PLAN:  <b>'.$detalle[0]['nombre'].' </b><br />TIPO PRÉSTAMO:  <b>'.$tipo_plan.' </b><br />HORA  PAGO:  <b>'.$detalle[0]['horapago'].'</b>');
	return ('MONTO: <b>Q.'.number_format(($detalle[0]['resumenpagos']*$detalle[0]['cuotas']), 2, '.', '').'</b><br /> MORA DIARIA: <b>Q.'.number_format($detalle[0]['n'], 2, '.', '').'</b> POR CADA <b>Q.'.number_format($detalle[0]['m'], 2, '.', '').'</b><br /> CUOTAS A PAGAR:  <b>'.$detalle[0]['cuotas'].' </b><br /> VALOR CUOTA:  <b>Q. '.number_format($detalle[0]['resumenpagos'], 2, '.', '').' </b><br /> PLAN:  <b>'.$detalle[0]['nombre'].' </b><br />TIPO PRÉSTAMO:  <b>'.$tipo_plan.' </b><br />HORA  PAGO:  <b>'.$detalle[0]['horapago'].'</b>');
}
 
 
$contenidoDatosPrestamo .= getDatosPrestamo($_GET['id']);


function getfechaIniciofechaFin($idPrestamo){
    $arrayMeses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"); 

	$conexion = new conexion();
	$sql="SELECT min(fecha) as fechainicio,
	max(fecha) as fechafin FROM detprestamos WHERE idprestamo = ".$idPrestamo;
	$detalle=$conexion->SQL($sql);

	$d=date("d",strtotime($detalle[0]['fechainicio']));
	$m = $arrayMeses[date("n",strtotime($detalle[0]['fechainicio']))];
	$y = date("Y",strtotime($detalle[0]['fechainicio']));

	$df=date("d",strtotime($detalle[0]['fechafin']));
	$mf = $arrayMeses[date("n",strtotime($detalle[0]['fechafin']))];
	$yf = date("Y",strtotime($detalle[0]['fechafin']));

   return ("<br />FECHA DE INICIO: <b>".$d." / ".$m." / ".$y."</b><br />FECHA FIN: <b>".$df." / ".$mf." / ".$yf."</b> ");
}

$fechaIniciofechaFin .= getfechaIniciofechaFin($_GET['id']);


$cabecera = '
<html>
<head>
<style>
body {font-family: sans-serif;
	font-size: 9pt;
}
p {	margin: 0pt; }
table.items {
	border: 0.01mm solid #000000;
}
td { vertical-align: top; }
.items td {
	border-left: 0.1mm solid #000000;
	border-right: 0.1mm solid #000000;
	border-bottom: 0.1mm solid #000000;
}
table thead td { background-color: #EEEEEE;
	text-align: center;
	border: 0.1mm solid #000000;
	font-variant: small-caps;
	font-size: 6pt;
}
.items td.blanktotal {
	background-color: #EEEEEE;
	border: 0.1mm solid #000000;
	background-color: #FFFFFF;
	border: 0mm none #000000;
	border-top: 0.1mm solid #000000;
	border-right: 0.1mm solid #000000;
}
.items td.totals {
	text-align: right;
	border: 0.1mm solid #000000;
}
.items td.cost {
	text-align: "." center;
}

ol, ul { text-align: justify;
}
.lista { list-style-type: upper-roman; }
.listb{ list-style-type: decimal; font-family: sans-serif; color: blue; font-weight: bold; font-style: italic; font-size: 19pt; }
.listc{ list-style-type: upper-alpha; padding-left: 25mm; }
.listd{ list-style-type: lower-alpha; color: teal; line-height: 2; }
.liste{ list-style-type: disc; }
.listarabic { direction: rtl; list-style-type: arabic-indic; font-family: dejavusanscondensed; padding-right: 40px;}


</style>
</head>
<body>
<!--mpdf


<htmlpageheader name="myheader">

<table width="100%">
<tr>


	<td width="50%" style="color:#0000BB; ">

	<img style="vertical-align: top"  src="../images/AdminLTELogo.png"  width="80" >
	</td>

	<td width="50%" style="text-align: right;">Documento No.<br /><span style="font-weight: bold; font-size: 12pt;">'.$stringNoFactura.'</span></td>
</tr>
</table>


</htmlpageheader>
<htmlpagefooter name="myfooter">
<div style="border-top: 1px solid #000000; font-size: 7pt; text-align: center; padding-top: 3mm; ">
Page {PAGENO} of {nb}
</div>
</htmlpagefooter>
<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->
<div style="text-align: right">'.$DateAndTime.'</div>


';



	$cabeceraTabla .= '

	<table width="100%" style="font-family: serif;" cellspacing="6" cellpadding="6"><tr>
	<td width="56%" style="border: 0.1mm solid #888888; font-size: 9pt;"><span style="font-size: 7pt; color: #555555; font-family: sans;">DATOS DEL CLIENTE:</span><br /><br /> '.$contenidoDatosCliente.' '.$fechaIniciofechaFin.' </td>
	<td width="2%">&nbsp;</td>
	<td width="42%" style="border: 0.1mm solid #888888; font-size: 9pt;"><span style="font-size: 7pt; color: #555555; font-family: sans;">DATOS DEL PRÉSTAMO:</span><br /><br /> '.$contenidoDatosPrestamo.' </td>
	</tr></table>

	<br>

	<table class="items" width="100%" style="font-size: 7pt; border-collapse: collapse;" cellspacing="6" cellpadding="6">
	<thead>
	<tr>
	<td >#</td>
	<td >VALOR</td>
	<td >FECHA DE PAGO</td>
	<td>ABONO</td>
	<td>MORA</td>
	<td>ABONO MORA</td>
	<td>FIRMA CLIENTE</td>
	<td>FIRMA ASESOR</td>
	</tr>
	</thead>
	<tbody> ';












$finTabla = '


</tbody>
</table>


<div style="text-align: center; font-style: italic;">  </div>
</div>





';






$mpdf->SetProtection(array('print'));
$mpdf->SetTitle("Generación de reporte");
$mpdf->SetAuthor("Acme Trading Co.");
//$mpdf->SetWatermarkText("Prestamos");
$mpdf->showWatermarkText = true;
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->watermarkTextAlpha = 0.1;
$mpdf->SetDisplayMode('fullpage');

$mpdf->WriteHTML($cabecera);
$mpdf->WriteHTML($cabeceraTabla);
$mpdf->WriteHTML($contenidoTabla);
$mpdf->WriteHTML($finTabla);

$mpdf->WriteHTML("</body></html>");


$mpdf->Output();


?>