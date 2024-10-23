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
                <h2 class='titulo'>Reporte de pagos anulados</h2>
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
                        <th>USUARIO ANULÓ</th>
                        <th>CLIENTE</th>
                        <th>FECHA PAGO</th>
                        <th>DESCRIPCIÓN</th>
                        <th>JUSTIFICACIÓN</th>
                        <th>CUOTAS</th>
                        <th></th>
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



<div id="divEliminarPago" class="modal fade show" aria-modal="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar Registro</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
        
                <input type="hidden" name="ideliminarPago" id="ideliminarPago" class="form-control" />
                <p><h4>¿Desea eliminar el registro?</h4></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnEliminarPago">Si estoy seguro</button>
                <button type="button" class="btn btn-default" id="btnCancelarEliminarPago" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>




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
                mostrarReporteCaja();
            }
            else
              toastr.warning(data.mensaje,"Info");
        }, "json")
        .fail(function()
        {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });
    }
    

    $("#verdatos").on("click",function(e){    
        mostrarReporteCaja();
    });


    function mostrarReporteCaja () {
      $("#tablaReporteCaja tbody tr").remove();

      vertodo=0;
      if ( $("#vertodo").prop('checked') ) { // VENTA CON FACTURA
        vertodo = 1;  
      }

      $.post("funciones/ws_reportes.php", { accion: "reportePagosAnulados" , vertodo:vertodo, fechainicio : $("#fechainicio").val() , fechafin : $("#fechafin").val() }, function(data) {
        if(data.resultado)
          {

            if ( $.fn.dataTable.isDataTable( '#tablaReporteCaja' ) ) {
                $("#tablaReporteCaja").DataTable().destroy();
                $("#tablaReporteCaja  tbody tr").remove();
            }


            var total = 0;

            $.each(data.registros,function(key,value) {


                if (Eliminar == 1 ) {                    
                    btnEliminar = " <button class='btn btn-default tooltip2' pCuota='1' style='cursor:pointer;' href='#' ><span class='tooltiptext'>Eliminar</span> <i class='fa fa-trash fa-lg '></i></button>";                        
                }



                var dateTime = moment( value["fechapago"] );
                var full = dateTime.format('LL');
                total += parseFloat(value["monto"]);

              $("<tr></tr>")
                .append( "<td>" + (key + 1) + "</td>" )
                .append( "<td>" + value["usuarioRecibio"] + "</td>" )
                .append( "<td>" + value["nombreCliente"] + "</td>" )
                .append( "<td>" + full + "</td>" )
                .append( "<td>" + value["descripcion"] + "</td>" )
                .append( "<td>" + value["justificacion"] + "</td>" )
                .append( "<td>Q." + parseFloat(value["monto"]).toFixed(2) + "</td>" )

                .append( $("<td></td>").append( 
                    $("<div class='btn-group'></div>")                         
                    .append( $(btnEliminar)
                        .on("click",{ idpagosanulados:value["id"] } , eliminarPago) )                           
                    )
                  )


                .appendTo("#tablaReporteCaja > tbody");
            });

            $("#tablaReporteCaja").DataTable({
                initComplete: function() {
                    $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
                }
            });
            $("#h3Total").html("Total <b> Q. "+total.toFixed(2)+"</b>");

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


    /******************  MUESTRA EL FORMULARIO PARA ELIMINAR LOS REGISTROS *******************/
    function eliminarPago (e) {
      e.preventDefault();
      $("#divEliminarPago").modal("show", {backdrop: "static"});
      $("#ideliminarPago").val(e.data.idpagosanulados);    
    }


    $("#btnEliminarPago").on("click",guardarEliminarPago);
    
    function guardarEliminarPago(e){
        e.preventDefault();

         $.post("funciones/ws_reportes.php", { 
            idpago:$("#ideliminarPago").val() , 
            accion:"eliminarpagosanulados"
        } ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");
                $("#divEliminarPago").modal("hide");
                setTimeout(function(){ratPack.refresh();},300);

            }
            else{
                toastr.warning(data.mensaje,"Info");
            }
            }, "json")
            .fail(function() {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });

    }





        
    

  });
</script>