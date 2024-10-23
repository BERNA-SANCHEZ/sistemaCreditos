<?php
session_start();
require_once ("../funciones/classSQL.php");
$conexion = new conexion();
if($conexion->permisos($_SESSION['idtipousuario'],"5","acceso"))
{
?>


<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h2 class='titulo'>Planes</h2>
            </div>
           
        </div>
    </div><!-- /.container-fluid -->
</section>


<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-header">
                
            <?php if($conexion->permisos($_SESSION['idtipousuario'],"5","crear")) { ?>
                <button type="button" id="btnNuevoPlan" data-toggle="modal" class="btn bg-navy btn-lg">Nuevo Plan</button>
            <?php } ?>
                
        </div>
        <!-- /.card-header -->
        <div class="card-body" style="overflow-x: scroll;">
           
            <div  class="table-responsive " >
                <table id="tablaPlanes" class="table table-striped" >
                    <thead>
                    <tr>  
                        <th>No.</th>
                        <th>NOMBRE DEL PLAN</th>
                        <th>CUOTAS</th>
                        <th>INTERÉS</th>
                        <th>TIPO</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    <!-- /.card-body -->
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->


<div id="divNuevoPlan" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form id='formNuevoPlan' class="form form-validate"  role="form"   method="post" >
            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Nuevo Plan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body row">

                    <div class="form-group col-md-12">
                        <label for="newNombre">Nombre:</label>
                        <input type="text" class="form-control" id="newNombre" name="newNombre" placeholder="Ingrese Nombre del Plan" required >
                    </div>

                    <div class="form-group col-md-12">
                        <label for="newTipoPlan">Tipo de Plan: </label>
                        <select class="form-control select2-list" id="newTipoPlan" name="newTipoPlan" data-placeholder="Seleccione una opción" required> 
                        <option value=""> </option>
                        <option value="1"> PLAN DIARIO</option>
                        <option value="2"> PLAN SEMANAL</option>
                        <option value="3"> PLAN QUINCENAL</option>
                        <option value="4"> PLAN MENSUAL (Interés + capital)</option>
                        <option value="5"> PLAN MENSUAL (Por interés)</option>
                        </select><div class="form-control-line"></div>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="newCantidadCuotas">Cantidad de cuotas:</label>
                        <input type="number" class="form-control" id="newCantidadCuotas" name="newCantidadCuotas" placeholder="Ingrese Cantidad de cuotas" required >
                    </div>

                    <div class="form-group col-md-12">
                        <label for="newTasaInteres">Tasa de interés:</label>
                        <input type="number" class="form-control" id="newTasaInteres" name="newTasaInteres" placeholder="Ingrese Tasa de interés" required >
                    </div>

                    <div class="col-md-12">

                        <div class="form-group" id="diasSemana" style="display:none;">
                            <label for="newDias">Seleccione los días para cobrar: </label>
                            <select class="form-control select2-list" multiple="multiple" id="newDias" name="newDias" data-placeholder="Seleccione los días de cobro" required> 
                            <option value="1">LUNES</option>
                            <option value="2">MARTES</option>
                            <option value="3">MIÉRCOLES</option>
                            <option value="4">JUEVES</option>
                            <option value="5">VIERNES</option>
                            <option value="6">SÁBADO</option>
                            <option value="7">DOMINGO</option>
                            </select><div class="form-control-line"></div>
                        </div>

                    </div>


                    <div class="col-md-12">
                        <label for="newn">Mora:</label>
                    </div>


                    <div class="form-group col-md-5">

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Q</span>
                            </div>

                            <input type="number" class="form-control " id="newn" name="newn" value="15" >

                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>

                        </div>


                    </div>

                    <div class="col-md-2">
                        <label for="newm" style="margin-top: 5px;">por cada</label>
                    </div>

                    <div class="form-group col-md-5">

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Q</span>
                            </div>

                            <input type="number" class="form-control " id="newm" name="newm" value="1000" >

                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="response"></div>
                    <button type="button" id="btnGuardarNuevoPlan" class="btn bg-navy">Guardar</button>
                    <button type="button" id="btnCancelarNuevoPlan" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>


<div id="divEditarPlan" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form id='formEditarPlan' class="form form-validate"  role="form"   method="post" >
            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Editar Plan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body row">

                    <div class="form-group col-md-12">
                        <label for="editNombre">Nombre:</label>
                        <input type="hidden" class="form-control" name="idplan" id="idplan" />
                        <input type="text" class="form-control" id="editNombre" name="editNombre" placeholder="Ingrese Nombre del Plan" required >
                    </div>

                    <div class="form-group col-md-12">
                        <label for="editTipoPlan">Tipo de Plan: </label>
                        <select class="form-control select2-list" id="editTipoPlan" name="editTipoPlan" data-placeholder="Seleccione una opción" required> 
                        <option value=""> </option>
                        <option value="1"> PLAN DIARIO</option>
                        <option value="2"> PLAN SEMANAL</option>
                        <option value="3"> PLAN QUINCENAL</option>
                        <option value="4"> PLAN MENSUAL (Interés + capital)</option>
                        <option value="5"> PLAN MENSUAL (Por interés)</option>
                        </select><div class="form-control-line"></div>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="editCantidadCuotas">Cantidad de cuotas:</label>
                        <input type="number" class="form-control" id="editCantidadCuotas" name="editCantidadCuotas" placeholder="Ingrese Cantidad de cuotas" required >
                    </div>

                    <div class="form-group col-md-12">
                        <label for="editTasaInteres">Tasa de interés:</label>
                        <input type="number" class="form-control" id="editTasaInteres" name="editTasaInteres" placeholder="Ingrese Tasa de interés" required >
                    </div>

                    <div class="col-md-12">

                        <div class="form-group" id="dias_Semana" style="display:none;">
                            <label for="editDias">Seleccione los días para cobrar: </label>
                            <select class="form-control select2-list" multiple="multiple" id="editDias" name="editDias" data-placeholder="Seleccione los días de cobro" required> 
                            <option value="1">LUNES</option>
                            <option value="2">MARTES</option>
                            <option value="3">MIÉRCOLES</option>
                            <option value="4">JUEVES</option>
                            <option value="5">VIERNES</option>
                            <option value="6">SÁBADO</option>
                            <option value="7">DOMINGO</option>
                            </select><div class="form-control-line"></div>
                        </div>

                    </div>

                    <div class="col-md-12">
                        <label for="editn">Mora:</label>
                    </div>


                    <div class="form-group col-md-5">

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Q</span>
                            </div>

                            <input type="number" class="form-control " id="editn" name="editn">

                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>

                        </div>


                    </div>

                    <div class="col-md-2">
                        <label for="editm" style="margin-top: 5px;">por cada</label>
                    </div>

                    <div class="form-group col-md-5">

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Q</span>
                            </div>

                            <input type="number" class="form-control " id="editm" name="editm">

                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="response"></div>
                    <button type="button" id="btnGuardarEditarPlan" class="btn bg-navy">Guardar</button>
                    <button type="button" id="btnCancelarEditarPlan" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>



<div id="divEliminarPlan" class="modal fade show" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar Plan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="ideliminarPlan" id="ideliminarPlan" class="form-control" />
                <p><h4>¿Desea eliminar el registro?</h4></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnEliminarPlan">Si estoy seguro</button>
                <button type="button" class="btn btn-default" id="btnCancelarEliminarPlan" data-dismiss="modal">Cancelar</button>
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


    
    function verficarPermisos () {
        $.post("funciones/ws_usuarios.php", {accion:"consultarPermisos" , idmodulo:"5"} ,function(data)
        {
            if(data.resultado){
                Acceso = data.registros[0]["acceso"];
                Crear = data.registros[0]["crear"];
                Modificar = data.registros[0]["modificar"];
                Eliminar = data.registros[0]["eliminar"];
                Consultar = data.registros[0]["consultar"];
                mostrarPlanes();
            }
            else
              toastr.warning(data.mensaje,"Info");
        }, "json")
        .fail(function()
        {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });
    }
    

    function mostrarPlanes () {
      $("#tablaPlanes  tbody tr").remove();
      $.post("funciones/ws_planes.php", { accion: "mostrar" }, function(data) {
        if(data.resultado)
          {

            var btnEditar = "";
            var btnEliminar = "";
            var btnConsultar = "";

            $.each(data.registros,function(key,value) {


              var tipo_plan ="";


              if (value["tipo"] == 1) {
                var tipo_plan ="PLAN DIARIO";
              }else if (value["tipo"] == 2) {
                var tipo_plan ="PLAN SEMANAL";
              }else if (value["tipo"] == 3) {
                var tipo_plan ="PLAN QUINCENAL";
              }else if (value["tipo"] == 4) {
                var tipo_plan ="PLAN MENSUAL (Interés + capital)";
              }else if (value["tipo"] == 5) {
                var tipo_plan ="PLAN MENSUAL (Por interés)";
              } 

              if (Modificar == 1) {
                btnEditar = " <button class='btn btn-default bg-lightblue tooltip2' style='cursor:pointer; ' href='#' ><span class='tooltiptext'>Editar Plan</span><i class='fa fa-edit fa-lg '></i></button>";
              };

              if (Eliminar == 1) {
                btnEliminar = " <button class='btn btn-default tooltip2' style='cursor:pointer' href='#' ><span class='tooltiptext'>Eliminar Plan</span> <i class='fa fa-trash fa-lg '></i></button>";
              };



        

              $("<tr></tr>")
                .append( "<td>" + (key + 1) + "</td>" )
                .append( "<td>" + value["nombre"] + "</td>" )
                .append( "<td>" + value["cuotas"] + "</td>" )
                .append( "<td>" + value["interes"] + "%</td>" )
                .append( "<td>" + tipo_plan + "</td>" )
               .append( $("<td></td>").append( 
                $("<div class='btn-group'></div>") 
                        
                    .append( $(btnEditar)
                        .on("click",{ idplan:value["id"] } , editarPlan) ) 
                    .append( $(btnEliminar)
                        .on("click",{ idplan:value["id"] } , eliminarPlan) )  
                    
                          
                    )
                  )
                .appendTo("#tablaPlanes > tbody");
            });

            $("#tablaPlanes a").tooltip(); 
            $("#tablaPlanes").DataTable({ 

                /*responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.childRowImmediate,
                        type: 'none',
                        target: ''
                    }
                },*/

                responsive: true,

                columnDefs: [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 2, targets: -1 }
                ],

                dom: 'Blfrtip',
                buttons: [

                        {
                               extend: 'copy', 
                          orientation: 'Portrait',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            ,exportOptions: {
                                columns: [ 0, 1, 2, 3, 4 ]
                            }

                        },

                        {
                               extend: 'csv', 
                          orientation: 'Portrait',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            ,exportOptions: {
                                columns: [ 0, 1, 2, 3, 4 ]
                            }

                        },
                       
                        {
                               extend: 'excel', 
                          orientation: 'Portrait',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            ,exportOptions: {
                                columns: [ 0, 1, 2, 3, 4 ]
                            }

                        },
                        
                        {
                               extend: 'pdf', 
                          orientation: 'Portrait',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            ,exportOptions: {
                                columns: [ 0, 1, 2, 3, 4 ]
                            }

                        }  ,
                        'print'
                    ],
                "sPaginationType": "full_numbers",

                
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


        
    /****************** MOSTRAR MODAL NUEVO REGISTRO *******************/
    $("#btnNuevoPlan").on("click",mostrarModalNuevoPlan);
    
    function mostrarModalNuevoPlan(e){
        e.preventDefault();
        $("#formNuevoPlan")[0].reset();
        $("#formNuevoPlan input").removeClass("dirty");

        $("#formNuevoPlan #newTipoPlan option[value='']").attr("selected","selected");
        $(".select2-list").select2({ allowClear: true });
        $("#diasSemana").fadeOut("fast");

        $("#divNuevoPlan").modal("show", {backdrop: "static"});
    }



    $("#formNuevoPlan #newTipoPlan").on("change",function(e){
        e.preventDefault();
        mostrarDias();
    });

    function mostrarDias() {

        if ($("#formNuevoPlan #newTipoPlan option:selected").val() == 1) {
            $("#diasSemana").fadeIn("slow");
            $('#newDias').val(null).trigger('change');   
        }else{
            $("#diasSemana").fadeOut("slow");
            $('#newDias').val(null).trigger('change');
        }
        
    }

    $("#formEditarPlan #editTipoPlan").on("change",function(e){
        e.preventDefault();
        mostrar_Dias();
    });

    function mostrar_Dias() {

        if ($("#formEditarPlan #editTipoPlan option:selected").val() == 1) {
            $("#dias_Semana").fadeIn("slow"); 
            $('#editDias').val(null).trigger('change');          
        }else{
            $("#dias_Semana").fadeOut("slow");
            $('#editDias').val(null).trigger('change');
        }
        
    }



    /****************** GUARDAR DATOS DEL REGISTRO *******************/
    $("#btnGuardarNuevoPlan").on("click",guardarNuevoPlan);
    function guardarNuevoPlan(e){
      e.preventDefault();

      var Dias = "";
      var cadenaDias = "";

      if($("#formNuevoPlan #newDias").val() != null){

        Dias = $("#formNuevoPlan #newDias").val();
        cadenaDias = Dias.join(';');       

      }

      if($("#formNuevoPlan").valid()) {
          $.post("funciones/ws_planes.php", "accion=nuevo&cadenaDias="+cadenaDias+"&"+$("#formNuevoPlan").serialize() ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");
                $("#divNuevoPlan").modal("hide");
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

    }



    /******************  MUESTRA EL FORMULARIO PARA EDITAR LOS REGISTROS *******************/
    function editarPlan (e) {

        e.preventDefault();
        $.getJSON("funciones/ws_planes.php", { accion:"mostrar" , id:e.data.idplan }, function(data) {
          if(data.resultado)
            {
              $("#formEditarPlan")[0].reset();
              $("#divEditarPlan").modal("show", {backdrop: "static"});
              $("#formEditarPlan input").addClass("dirty");

              $("#formEditarPlan #idplan").val(data.registros[0]["id"]);
              $("#formEditarPlan #editNombre").val(data.registros[0]["nombre"]);
              $("#formEditarPlan #editCantidadCuotas").val(data.registros[0]["cuotas"]);
              $("#formEditarPlan #editTasaInteres").val(data.registros[0]["interes"]);

              $("#formEditarPlan #editn").val(data.registros[0]["n"]);
              $("#formEditarPlan #editm").val(data.registros[0]["m"]);

              $('#formEditarPlan #editTipoPlan').val(data.registros[0]["tipo"]).trigger('change.select2');

              if (data.registros[0]["tipo"] == 1) {
                $("#dias_Semana").fadeIn("fast");
              }else{
                $("#dias_Semana").fadeOut("fast");
              }
              

              var arrayDias = data.registros[0]["dias"].split(';');

              $('#formEditarPlan #editDias').val(arrayDias); // Select the option with a value of '1'
              $('#formEditarPlan #editDias').trigger('change'); // Notify any JS components that the value changed

  
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




    /****************** MODIFICAR DATOS DEL REGISTRO *******************/
    $("#btnGuardarEditarPlan").on("click",guardarEditarPlan);
    function guardarEditarPlan(e){

      var Dias = "";
      var cadenaDias = "";

      if($("#formEditarPlan #editDias").val() != null){

        Dias = $("#formEditarPlan #editDias").val();
        cadenaDias = Dias.join(';');       

      }

      e.preventDefault();
      if($("#formEditarPlan").valid()) {
          $.post("funciones/ws_planes.php", "accion=editar&cadenaDias="+cadenaDias+"&"+$("#formEditarPlan").serialize() ,function(data) {
            if(data.resultado){              
                toastr.success(data.mensaje, "Exito");;
                $("#divEditarPlan").modal("hide");
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

    }






    /******************  MUESTRA EL FORMULARIO PARA ELIMINAR LOS REGISTROS *******************/
    function eliminarPlan (e) {
      e.preventDefault();
      $("#divEliminarPlan").modal("show", {backdrop: "static"});
      $("#ideliminarPlan").val(e.data.idplan);
    }


    $("#btnEliminarPlan").on("click",guardarEliminarPlan);
    
    function guardarEliminarPlan(e){
        e.preventDefault();
        $.post("funciones/ws_planes.php", { idplan:$("#ideliminarPlan").val() , accion:"eliminar" } ,function(data) {
          if(data.resultado){
              toastr.success(data.mensaje, "Exito");
              $("#divEliminarPlan").modal("hide");
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