<?php
session_start();
require_once ("../funciones/classSQL.php");
$conexion = new conexion();
if($conexion->permisos($_SESSION['idtipousuario'],"10","acceso"))
{
?>

<!--NUEVO CODIGO-->
<style>

  .image_area {
    position: relative;
  }

  img {
      /*display: block;*/
      max-width: 100%;
  }

  .preview {
      overflow: hidden;
      width: 160px; 
      height: 160px;
      margin: 10px;
      border: 1px solid red;
  }

  .modal-lg{
      max-width: 1000px !important;
  }

  .newoverlay {
    position: absolute;
    bottom: 10px;
    left: 0;
    right: 0;
    background-color: rgba(255, 255, 255, 0.5);
    overflow: hidden;
    height: 0;
    transition: .5s ease;
    width: 100%;
  }

  .image_area:hover .newoverlay {
    height: 50%;
    cursor: pointer;
  }

  .text {
    color: #333;
    font-size: 20px;
    position: absolute;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    text-align: center;
  }


</style>
<!--NUEVO CODIGO-->


<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h2 class='titulo'>Reporte próximos a finalizar</h2>
            </div>           
        </div>
    </div><!-- /.container-fluid -->
</section>


<!-- Main content -->
<section id="divTablaDetalle" class="content" style="display:none;">
    <div class="card">


    <div class="card-header row">
                

                    


         
    
                <div class="form-group col-sm-6">

                <label>Usuarios:</label>

                   

                    <?php
                        if ($_SESSION['idtipousuario'] == 4 || $_SESSION['idtipousuario'] == 5) {
                            echo "<select disabled class='form-control select2-list select2-success' data-dropdown-css-class='select2-success' name='usuarioseleccionado' id='usuarioseleccionado' data-placeholder='Seleccione Usuario' required>";
                        }else{
                            echo "<select class='form-control select2-list select2-success' data-dropdown-css-class='select2-success' name='usuarioseleccionado' id='usuarioseleccionado' data-placeholder='Seleccione Usuario' required>";
                        }

                            echo "<option value='1000' >TODOS</option>";   
                            $usuarios = $conexion->sql("SELECT id, nombre FROM usuarios WHERE idtipousuario = 4 OR idtipousuario = 5");
                            foreach ($usuarios as $key => $value) {
                                if ($value['id'] == $_SESSION['idusuario']) {
                                    echo "<option value='".$value['id']."' selected>".$value["nombre"]."</option>";
                                }else{
                                    echo "<option value='".$value['id']."' >".$value["nombre"]."</option>";
                                }
                            }
                        echo "</select>";
                    ?>

                    
                
                </div>
                      

    
    
                    
            </div>


            
 
            

        <div class="card-body">



            <div id="contenedorCalendario"></div>
            


        </div>
    <!-- /.card-body -->
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->





<style type="text/css">

    .sender{
       margin-bottom: 0px !important;
       font-weight: 600;
    }

    .tachado{
        text-decoration: line-through;
        color: #697582;
        font-weight: 500;
    }

    @media only screen and (max-width: 700px) {
        video {
            max-width: 100%;
        }
    }

    .chartWrapper {
        position: relative;
        
    }

    .chartWrapper > canvas {
        position: absolute;
        left: 0;
        top: 0;
        pointer-events:none;
    }
    
    .chartAreaWrapper {
        overflow-x: scroll;
        position: relative;
        width: 100%;
    }

    .chartAreaWrapper2 {
        
        position: relative;
        height: 300px;
    }


</style>


<?php 
}
?>



<script type="text/javascript">


  $(document).ready(function() {

    var Acceso = 0;
    var Crear = 0;
    var Modificar = 0;
    var Eliminar = 0;
    var Consultar = 0;
    

    verficarPermisos();
    $(".select2-list").select2({ allowClear: true });

 
    
    
    function verficarPermisos () {
        $.post("funciones/ws_usuarios.php", {accion:"consultarPermisos" , idmodulo:"10"} ,function(data)
        {
            if(data.resultado){
                Acceso = data.registros[0]["acceso"];
                Crear = data.registros[0]["crear"];
                Modificar = data.registros[0]["modificar"];
                Eliminar = data.registros[0]["eliminar"];
                Consultar = data.registros[0]["consultar"];

                mostrarRegistroPrestamos();
            }
            else
              toastr.warning(data.mensaje,"Info");
        }, "json")
        .fail(function()
        {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });
    }
    

    
    

    function mostrarRegistroPrestamos () {

      $("#divTablaDetalle").fadeIn("slow");
      
    
      $.post("funciones/ws_reportes.php", { 
        accion: "reporteProximosFinalizar", 
        id_usuario:$("#usuarioseleccionado").val()
        }, function(data) {
        if(data.resultado)
          { 
            

            $("#contenedorCalendario").html('');
            $("#contenedorCalendario").html('<div id="CalendarioWeb"></div>');
            $("#CalendarioWeb").fullCalendar({

                header:{
                    left:'today,prev,next',
                    center:'title',
                    right:'month,basicWeek, basicDay, agendaWeek, agendaDay'
                },


                events: data.registros,
                textColor:"yellow",
                
                defaultView: 'month',
                showNonCurrentDates:false,
                fixedWeekCount:false,
                contentHeight:"auto",
                handleWindowResize:true
          
            });
           
            
            


          }
          else{
            toastr.warning(data.mensaje,"Info");
          }
      }, "json")
      .fail(function()
      {
          toastr.error("no se pudo conectar al servidor", "Error Conexión");
      });
    

    }


    $("#usuarioseleccionado").on("change",function(e){
        e.preventDefault();
        mostrarRegistroPrestamos();
    });


    




  });
</script>