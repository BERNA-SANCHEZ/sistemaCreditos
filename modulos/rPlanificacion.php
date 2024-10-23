<?php
session_start();
require_once ("../funciones/classSQL.php");
$conexion = new conexion();
if($conexion->permisos($_SESSION['idtipousuario'],"4","acceso"))
{
?>


<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h2 class='titulo'>Planificación por fecha</h2>
            </div>           
        </div>
    </div><!-- /.container-fluid -->
</section>


<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-header row">
                
         
            <div class="col-sm-1"></div>        

            <div class="form-group col-sm-3">
                <label>Fecha inicio:</label>

                <div class="input-group date" id="timepicker2" data-target-input="nearest">
                    <input type="text" class="form-control  datetimepicker-input" name="fechainicio" id="fechainicio" data-target="#timepicker2" value=<?php echo date("Y-m-d") ?> required>
                    <div class="input-group-append" data-target="#timepicker2" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="far fa-calendar"></i></div>
                    </div>
                </div>
                <!-- /.input group -->
            </div>

            <div class="form-group col-sm-3">
                <label>Fecha fin:</label>

                <div class="input-group date" id="timepicker1" data-target-input="nearest">
                    <input type="text" class="form-control  datetimepicker-input" name="fechafin" id="fechafin" data-target="#timepicker1" value=<?php echo date("Y-m-d") ?> required>
                    <div class="input-group-append" data-target="#timepicker1" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="far fa-calendar"></i></div>
                    </div>
                </div>
                <!-- /.input group -->
            </div>

            <div class="col-sm-2" style="margin-top: 35px;">

                <div class="icheck-success">
                    <input type="checkbox" id="vertodo" checked>
                    <label for="vertodo">Todos</label>
                </div>

            </div>

            <div class="col-sm-2" style="margin-top: 30px;">
                <button class="btn btn-default bg-olive tooltip2" id="verdatos"> 
                    <span class="tooltiptext">Ver Datos</span> <i class="fa fa-eye fa-lg"></i>
                </button>
            </div>

            <div class="col-sm-1"></div>        

                
        </div>
        <!-- /.card-header -->
        <div class="card-body" style="overflow-x: scroll;">
           
            <div  class="table-responsive " >
                <table id="tablaReporteCaja" class="table table-striped" >
                    <thead>
                    <tr>  
                        <th>No.</th>
                        <th>USUARIO REGISTRÓ</th>
                        <th>CLIENTE</th>
                        <th>FECHA PAGO</th>
                        <th>DESCRIPCIÓN</th>
                        <th>CUOTAS</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <br>

            <h3 id="h3Total" >Total</h3> 


        </div>
    <!-- /.card-body -->
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->




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

    $('#timepicker2').datetimepicker({pickTime: false, format: 'YYYY-MM-DD'});
    $('#timepicker1').datetimepicker({pickTime: false, format: 'YYYY-MM-DD'});


    
    function verficarPermisos () {
        $.post("funciones/ws_usuarios.php", {accion:"consultarPermisos" , idmodulo:"4"} ,function(data)
        {
            if(data.resultado){
                Acceso = data.registros[0]["acceso"];
                Crear = data.registros[0]["crear"];
                Modificar = data.registros[0]["modificar"];
                Eliminar = data.registros[0]["eliminar"];
                Consultar = data.registros[0]["consultar"];
                
            }
            else
              toastr.warning(data.mensaje,"Info");
        }, "json")
        .fail(function()
        {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });
    }
    


        
    

  });
</script>