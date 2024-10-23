<?php 
session_start(); error_reporting(0);
if($_GET[a]=="logout")
{
  session_destroy();
  header ("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="images/favicon.png" type="image/png">

  <title>[ Login Prestamos ]</title>


  <script type="text/javascript"  src="js/libs/jquery/jquery-1.11.2.js"></script>
  <script type="text/javascript" src="js/libs/jquery/jquery.blockUI.js"></script>
  <script type="text/javascript" src="js/funciones.js"></script>
 	<!-- Toastr -->
   <link rel="stylesheet" href="plugins/toastr/toastr.min.css">

  <link rel="stylesheet" type="text/css" href="login/style.css">
	<link href="login/icon.css" rel="stylesheet">
	<script src="login/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
 
</head>

<body>
    


  <!-- PRELOADER -->
  <div id='mensaje' style='display:none;'> <!--<img src="img/loader1.gif" width="48" height="48" />-->
        <div class="blockMsg">Cargando espere un momento<br><br><br></div>
        <div class="circle"></div>
        <div class="circle1"></div>
    </div> <!-- end PRELOADER -->



	<div class="container">
		<div class="img">
			<img src="login/fondo.jpg">
		</div>
		<div class="login-content">
			<form  id="form-login">
				<h2 class="title">Inicio de Sesión</h2>
           		<div class="input-div one">
           		   <div class="i">
           		   		<i class="fas fa-user"></i>
           		   </div>
           		   <div class="div">
           		   		<h5>Usuario</h5>
           		   		<input id="usuario" name="usuario" type="text" class="input">
           		   </div>
           		</div>
           		<div class="input-div pass">
           		   <div class="i"> 
           		    	<i class="fas fa-lock"></i>
           		   </div>
           		   <div class="div">
           		    	<h5>Clave</h5>
           		    	<input id="clave" name="clave" type="password" class="input">
            	   </div>
            	</div>
            	<input id="btn-login"  type="submit" class="btn" value="Iniciar sesión">
            </form>
        </div>
    </div>


<script  src="login/script.js"></script>
<script>
  $(function(){

      $("#btn-login").on('click',function(e){
        e.preventDefault();
        bloquearPantalla("Espere por favor");
        $.getJSON("funciones/ws_login.php" , $("#form-login").serialize() ,function(data) {
            if (!data.resultado) 
            {
                toastr.warning(data.mensaje);
                desbloquearPantalla();
            } else {
                setTimeout("window.location.href = 'sinfo.php'", 700);
                desbloquearPantalla();
            }
        })
        .fail(function() {
            alert(data.mensaje);
            desbloquearPantalla();
        });
      });

  });


</script>
<?php include('footer.php'); ?>
</body>
</html>