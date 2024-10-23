<?php 
session_start(); 
if(!$_SESSION["usuario"]) 
{ 
  $_SESSION['redirect'] = 'http://'.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']; 
  header ("Location: index.php"); 
} 
?>


<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <link rel="shortcut icon" href="images/favicon.png" type="image/png">
  <title>Préstamos</title>

	<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

	<!-- jQuery -->
	<script src="plugins/jquery/jquery.min.js"></script>
	<!-- SweetAlert2 -->
	<link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
	<!-- SweetAlert2 -->
	<link rel="stylesheet" href="plugins/sweetalert2/sweetalert2.min.css">
	<!-- Toastr -->
	<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
	<!-- Ion Slider -->
	<link rel="stylesheet" href="plugins/ion-rangeslider/css/ion.rangeSlider.min.css">
	<!-- bootstrap slider -->
	<link rel="stylesheet" href="plugins/bootstrap-slider/css/bootstrap-slider.min.css">	
	<!-- daterange picker -->
	<link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
	<!-- iCheck for checkboxes and radio inputs -->
	<link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- Bootstrap Color Picker -->
	<link rel="stylesheet" href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
	<!-- Tempusdominus Bbootstrap 4 -->
	<link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<!-- Select2 -->
	<link rel="stylesheet" href="plugins/select2/css/select2.min.css">
	<link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
	<!-- Bootstrap4 Duallistbox -->
	<link rel="stylesheet" href="plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
	<!-- DataTables -->
	<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
	<!-- jsGrid -->
	<link rel="stylesheet" href="plugins/jsgrid/jsgrid.min.css">
	<link rel="stylesheet" href="plugins/jsgrid/jsgrid-theme.min.css">


	<link rel="stylesheet" href="dist/css/adminlte.min.css">
	<link rel="stylesheet" href="dist/css/new/bootstrap.min3.css">


	<!--Librerías del sistema anterior-->
	<script type="text/javascript" src="js/libs/jquery/jquery.blockUI.js"></script>
	<script type="text/javascript" src="js/libs/jquery/jquery-scrollto.js"></script>
	<!--Librerías del sistema anterior-->



	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">


	<!-- Dropzone -->
	<link rel="stylesheet" href="dropzone/dropzone.css">
	<link href="dropzone/cropper.css" rel="stylesheet"/>
	<script src="dropzone/dropzone.js"></script>
	<script src="dropzone/cropper.js"></script>

	<!-- Ekko Lightbox -->
	<link rel="stylesheet" href="plugins/ekko-lightbox/ekko-lightbox.css">

	<link rel="stylesheet" type="text/css" href="fullcalendar/fullcalendar.min.css">


	<style type="text/css">
		.divider-full-bleed{
			margin-top: -15px;
		}

		.titulo{
			font-size: 38px;
			font-style: italic;
			color: #ababab;
			text-shadow: -1px -1px 0px #101010, 1px 1px 0px #ffffff;
			text-align: center;
		}

		.panel-close{    
			float: right;
			margin-right: 17px;
			font-size: 28px;
			cursor: pointer;
		}


		.tooltip2 {
			position: relative;
			display: inline-block;
		}

		.tooltip2 .tooltiptext {
			visibility: hidden;
			width: 120px;
			background-color: #555;
			color: #fff;
			text-align: center;
			border-radius: 6px;
			padding: 5px 0;
			position: absolute;
			z-index: 1;
			bottom: 125%;
			left: 50%;
			margin-left: -60px;
			opacity: 0;
			transition: opacity 0.3s;
		}

		.tooltip2 .tooltiptext::after {
			content: "";
			position: absolute;
			top: 100%;
			left: 50%;
			margin-left: -5px;
			border-width: 5px;
			border-style: solid;
			border-color: #555 transparent transparent transparent;
		}

		.tooltip2:hover .tooltiptext {
			visibility: visible;
			opacity: 1;
		}




		.tooltipRight {
			position: relative;
			display: inline-block;
		}

		.tooltipRight .tooltiptextRight {
			visibility: hidden;
			width: 120px;
			background-color: black;
			color: #fff;
			text-align: center;
			border-radius: 6px;
			padding: 5px 0;
			
			/* Position the tooltipRight */
			position: absolute;
			z-index: 1;
			top: -5px;
			left: 105%;
		}

		.tooltipRight:hover .tooltiptextRight {
			visibility: visible;
		}
		

	</style>



</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark navbar-navy">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      
    </ul>
    

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      

      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
          <img src="dist/img/user2-160x160.jpg" class="user-image img-circle elevation-2" alt="User Image">
          <span class="d-none d-md-inline"><?php echo $_SESSION["nombre"] ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <!-- User image -->
          <li class="user-header bg-navy">
            <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">

            <p>
			<?php echo $_SESSION["nombre"] ?>
              <small><?php echo $_SESSION["tipousuario"] ?></small>
            </p>
          </li>
          
          <!-- Menu Footer-->
          <li class="user-footer">
            <a href="index.php?a=logout" class="btn btn-default btn-flat float-right">
              <i class="fa fa-fw fa-power-off"></i>
              Cerrar sesión
            </a>
          </li>
        </ul>
      </li>

      
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar elevation-4 sidebar-dark-navy">
    <!-- Brand Logo -->
    <a href="" class="brand-link navbar-navy">


		<img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">		
		<span class="brand-text font-weight-light">Préstamos</span>
		





    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      




      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" id="main-menu" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->


			   <?php 
			        require_once("funciones/classSQL.php");
			        $conexion = new conexion();			        

		
											
					if($conexion->permisos($_SESSION['idtipousuario'],"1","acceso")){
						echo"<li class='nav-item'>
								<a id='menu-usuarios' href='#/usuarios' class='nav-link'>
								<i class='nav-icon fas fa-users'></i>
								<p>
									Usuarios
								</p>
								</a>
							</li>";
					}

					if($conexion->permisos($_SESSION['idtipousuario'],"2","acceso")){
						echo"<li class='nav-item'>
								<a id='menu-clientes' href='#/clientes' class='nav-link'>
								<i class='nav-icon far fa-user'></i>
								<p>
									Clientes
								</p>
								</a>
							</li>";
					}

					if($conexion->permisos($_SESSION['idtipousuario'],"5","acceso")){
						echo"<li class='nav-item'>
								<a id='menu-planes' href='#/planes' class='nav-link'>
								<i class='nav-icon fas fa-cog'></i>
								<p>
									Planes
								</p>
								</a>
							</li>";
					}

					if($conexion->permisos($_SESSION['idtipousuario'],"7","acceso")){
						echo"<li class='nav-item'>
								<a id='menu-diasFestivos' href='#/diasFestivos' class='nav-link'>
								<i class='nav-icon fas fa-calendar-check'></i>
								<p>
									Días festivos 
								</p>
								</a>
							</li>";
					}

					if($conexion->permisos($_SESSION['idtipousuario'],"9","acceso")){
						echo"<li class='nav-item'>
								<a id='menu-cajas' href='#/cajas' class='nav-link'>
								<i class='nav-icon fas fa-unlock-alt'></i>
								<p>
									Caja
								</p>
								</a>
							</li>";
					}



					if($conexion->permisos($_SESSION['idtipousuario'],"3","acceso")){
						echo"<li class='nav-item'>
								<a id='menu-prestamos' href='#/prestamos' class='nav-link'>
								<i class='nav-icon fas fa-money-bill'></i>
								<p>
									Préstamos
								</p>
								</a>
							</li>";
					}

					if($conexion->permisos($_SESSION['idtipousuario'],"10","acceso")){
						echo"<li class='nav-item'>
								<a id='menu-rProximosFinalizar' href='#/rProximosFinalizar' class='nav-link'>
								<i class='nav-icon fas fa-calendar-times'></i>
								<p>
									Próximos Finalizar
								</p>
								</a>
							</li>";
					}





					if($conexion->permisos($_SESSION['idtipousuario'],"6","acceso")){
						echo"<li class='nav-item'>
								<a id='menu-cobradores' href='#/cobradores' class='nav-link'>
								<i class='nav-icon fas fa-hand-holding-usd'></i>
								<p>
									Cobradores y rutas
								</p>
								</a>
							</li>";
					}

					if($conexion->permisos($_SESSION['idtipousuario'],"8","acceso")){
						echo"<li class='nav-item'>
								<a id='menu-planilla' href='#/planilla' class='nav-link'>
								<i class='nav-icon fas fa-outdent'></i>
								<p>
									Planilla
								</p>
								</a>
							</li>";
					}

					/*if($conexion->permisos($_SESSION['idtipousuario'],"3","acceso")){
						echo"<li class='nav-item'>
								<a id='menu-ganancias' href='#/ganancias' class='nav-link'>
								<i class='nav-icon fas fa-comment-dollar'></i>
								<p>
									Comisiones
								</p>
								</a>
							</li>";
					}*/

				

					if($conexion->permisos($_SESSION['idtipousuario'],"4","acceso")){
						echo"
						<li class='nav-item has-treeview'>
							<a id='menu-reporte' href='' class='nav-link'>
							<i class='nav-icon fas fa-chart-pie'></i>
							<p>
								Reportes
								<i class='right fas fa-angle-left'></i>
							</p>
							</a>
							<ul class='nav nav-treeview'>
								<li class='nav-item'>
									<a id='menu-rCaja' href='#/rCaja' class='nav-link'>
									<i class='fas fa-unlock-alt nav-icon'></i>
									<p>Reporte de Caja</p>
									</a>
								</li>
								<!--<li class='nav-item'>
									<a id='menu-rXfecha' href='#/rXfecha' class='nav-link'>
									<i class='far fa-calendar nav-icon'></i>
									<p>Movimientos por Fecha</p>
									</a>
								</li>
								<li class='nav-item'>
									<a id='menu-rResumenIngresos' href='#/rResumenIngresos' class='nav-link'>
									<i class='fas fa-plus-circle nav-icon'></i>
									<p>Resumen de ingresos</p>
									</a>
								</li>
								<li class='nav-item'>
									<a id='menu-rCapitalRecuperado' href='#/rCapitalRecuperado' class='nav-link'>
									<i class='far fa-chart-bar nav-icon'></i>
									<p>Recuperación de capital</p>
									</a>
								</li>
								<li class='nav-item'>
									<a id='menu-rGanancias' href='#/rGanancias' class='nav-link'>
									<i class='fas fa-balance-scale nav-icon'></i>
									<p>Dividir ganancias</p>
									</a>
								</li>
								<li class='nav-item'>
									<a id='menu-rPlanificacion' href='#/rPlanificacion' class='nav-link'>
									<i class='fas fa-calendar nav-icon'></i>
									<p>Planificación por fecha</p>
									</a>
								</li>-->
								<li class='nav-item'>
									<a id='menu-rPagosAnulados' href='#/rPagosAnulados' class='nav-link'>
									<i class='fas fa-times-circle nav-icon'></i>
									<p>Pagos anulados</p>
									</a>
								</li>
								<li class='nav-item'>
									<a id='menu-rCartera' href='#/rCartera' class='nav-link'>
									<i class='fas fa-address-book nav-icon'></i>
									<p>Cartera</p>
									</a>
								</li>

								


							</ul>
						</li>
						";
					}



				?>


        </ul>
      </nav>


      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- CONTIENE CONTENIDO DE PÁGINA -->
  <div class="content-wrapper">
    <!-- CONTIENE CONTENIDO DE PÁGINA -->
    <div class="content">
      <div class="container-fluid">
       
        <!-- BEGIN CONTENT-->
        <div id="content">






          



              
        </div><!--end #content-->
        <!-- END CONTENT -->

      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->



	<!-- Main Footer -->
	<footer class="main-footer">

	<small class="no-linebreak hidden-folded">
		<span class="opacity-75">Copyright © 2022</span> <strong>Sinfo Sistemas</strong>
	</small>

	</footer>

	<script type="text/javascript">	

		function bloquearPantalla(mensaje) { 

			$.blockUI({ 
				css: { 
				border: 'none', 
				padding: '15px',          
				backgroundColor:'transparent', 
				'-webkit-border-radius': '10px', 
				'-moz-border-radius': '10px', 
				color: '#fff' 
				},
				message: "<h1>"+mensaje+"</h1>",
			});   

		}

		function desbloquearPantalla()  {
			setTimeout($.unblockUI, 500); 
		}


		$(document).on('click', '[data-toggle="lightbox"]', function(event) {
			event.preventDefault();
			$(this).ekkoLightbox({
			alwaysShowClose: true
			});
		});




		//CODIGO PARA CERRAR SESSION

		let timer, currSeconds = 0;

		function resetTimer() {

			/* Clear the previous interval */
			clearInterval(timer);

			/* Reset the seconds of the timer */
			currSeconds = 0;

			/* Set a new interval */
			timer = 
				setInterval(startIdleTimer, 1000);
		}

		// Define the events that
		// would reset the timer
		window.onload = resetTimer;
		window.onmousemove = resetTimer;
		window.onmousedown = resetTimer;
		window.ontouchstart = resetTimer;
		window.onclick = resetTimer;
		window.onkeypress = resetTimer;

		function startIdleTimer() {
			
			/* Increment the
				timer seconds */
			currSeconds++;

			//console.log(currSeconds);

			if(currSeconds > 1800){
				window.open('index.php?a=logout', '_self');
			}

		}





	</script>		