<?php 
include('header.php'); 
?>

	<script src="js/libs/sammy/sammy.js"></script>
	<script type="text/javascript">
	  var ratPack = $.sammy(function(e) {	  	
	    this.element_selector = '#content';

		this.get('#/', function(context) {
	      	context.app.swap('');
	      	$("#main-menu").find('a').removeClass('active');
	      	context.partial("modulos/usuarios.php",function(){});
	      	$("#menu-usuarios").addClass('nav-link active');
	    });

	
	    this.get('#/usuarios', function(context) {
	      	context.app.swap('');
	      	$("#main-menu").find('a').removeClass('active');
	      	context.partial("modulos/usuarios.php",function(){});
	      	$("#menu-usuarios").addClass('nav-link active');
	    });	 
		
		this.get('#/permisos', function(context) {
	    	context.app.swap('');	    	
			$("#main-menu").find('a').removeClass('active');
	    	context.partial("modulos/permisos.php",function(){});	
	      	$("#menu-usuarios").addClass('nav-link active');
	    });
		
		this.get('#/clientes', function(context) {
	      	context.app.swap('');
	      	$("#main-menu").find('a').removeClass('active');
	      	context.partial("modulos/clientes.php",function(){});
	      	$("#menu-clientes").addClass('nav-link active');
	    });	 

		this.get('#/prestamos', function(context) {
	      	context.app.swap('');
	      	$("#main-menu").find('a').removeClass('active');
	      	context.partial("modulos/prestamos.php",function(){});
	      	$("#menu-prestamos").addClass('nav-link active');
	    });	 


		this.get('#/rProximosFinalizar', function(context) {
	      	context.app.swap('');
	      	$("#main-menu").find('a').removeClass('active');
	      	context.partial("modulos/rProximosFinalizar.php",function(){});
	      	$("#menu-rProximosFinalizar").addClass('nav-link active');
	    });	 


		this.get('#/cajas', function(context) {
	      	context.app.swap('');
	      	$("#main-menu").find('a').removeClass('active');
	      	context.partial("modulos/cajas.php",function(){});
	      	$("#menu-cajas").addClass('nav-link active');
	    });	 

		this.get('#/diasFestivos', function(context) {
	      	context.app.swap('');
	      	$("#main-menu").find('a').removeClass('active');
	      	context.partial("modulos/diasFestivos.php",function(){});
	      	$("#menu-diasFestivos").addClass('nav-link active');
	    });	 

		this.get('#/planes', function(context) {
	      	context.app.swap('');
	      	$("#main-menu").find('a').removeClass('active');
	      	context.partial("modulos/planes.php",function(){});
	      	$("#menu-planes").addClass('nav-link active');
	    });	 

		this.get('#/cobradores', function(context) {
	      	context.app.swap('');
	      	$("#main-menu").find('a').removeClass('active');
	      	context.partial("modulos/cobradores.php",function(){});
	      	$("#menu-cobradores").addClass('nav-link active');
	    });	 


		this.get('#/planilla', function(context) {
	      	context.app.swap('');
	      	$("#main-menu").find('a').removeClass('active');
	      	context.partial("modulos/planilla.php",function(){});
	      	$("#menu-planilla").addClass('nav-link active');
	    });	 

		/*this.get('#/ganancias', function(context) {
	      	context.app.swap('');
	      	$("#main-menu").find('a').removeClass('active');
	      	context.partial("modulos/ganancias.php",function(){});
	      	$("#menu-ganancias").addClass('nav-link active');
	    });	 */


		////////// Reportes //////////

		this.get('#/rCaja', function(context) {
	      	context.app.swap('');
	      	$("#main-menu").find('a').removeClass('active');
	      	context.partial("modulos/rCaja.php",function(){});
	      	$("#menu-rCaja").addClass('nav-link active');
	      	$("#menu-reporte").addClass('nav-link active');			
	    });

		this.get('#/rXfecha', function(context) {
	      	context.app.swap('');
	      	$("#main-menu").find('a').removeClass('active');
	      	context.partial("modulos/rXfecha.php",function(){});
	      	$("#menu-rXfecha").addClass('nav-link active');
	      	$("#menu-reporte").addClass('nav-link active');			
	    });

		this.get('#/rResumenIngresos', function(context) {
	      	context.app.swap('');
	      	$("#main-menu").find('a').removeClass('active');
	      	context.partial("modulos/rResumenIngresos.php",function(){});
	      	$("#menu-rResumenIngresos").addClass('nav-link active');
	      	$("#menu-reporte").addClass('nav-link active');			
	    });

		this.get('#/rCapitalRecuperado', function(context) {
	      	context.app.swap('');
	      	$("#main-menu").find('a').removeClass('active');
	      	context.partial("modulos/rCapitalRecuperado.php",function(){});
	      	$("#menu-rCapitalRecuperado").addClass('nav-link active');
	      	$("#menu-reporte").addClass('nav-link active');			
	    });

		this.get('#/rGanancias', function(context) {
	      	context.app.swap('');
	      	$("#main-menu").find('a').removeClass('active');
	      	context.partial("modulos/rGanancias.php",function(){});
	      	$("#menu-rGanancias").addClass('nav-link active');
	      	$("#menu-reporte").addClass('nav-link active');			
	    });

		this.get('#/rPlanificacion', function(context) {
	      	context.app.swap('');
	      	$("#main-menu").find('a').removeClass('active');
	      	context.partial("modulos/rPlanificacion.php",function(){});
	      	$("#menu-rPlanificacion").addClass('nav-link active');
	      	$("#menu-reporte").addClass('nav-link active');			
	    });

		this.get('#/rPagosAnulados', function(context) {
	      	context.app.swap('');
	      	$("#main-menu").find('a').removeClass('active');
	      	context.partial("modulos/rPagosAnulados.php",function(){});
	      	$("#menu-rPagosAnulados").addClass('nav-link active');
	      	$("#menu-reporte").addClass('nav-link active');			
	    });


		this.get('#/rCartera', function(context) {
	      	context.app.swap('');
	      	$("#main-menu").find('a').removeClass('active');
	      	context.partial("modulos/rCartera.php",function(){});
	      	$("#menu-rCartera").addClass('nav-link active');
	      	$("#menu-reporte").addClass('nav-link active');			
	    });
		
		

		

	    this.notFound = function(context,url){
           	console.log("Url no encontrada");
        }




    });
	
	$(function() {
		ratPack.run('#/');
	});

  </script>

<?php include('footer.php'); ?>