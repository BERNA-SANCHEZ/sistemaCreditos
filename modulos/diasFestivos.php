<?php
session_start();
require_once ("../funciones/classSQL.php");
$conexion = new conexion();
if($conexion->permisos($_SESSION['idtipousuario'],"7","acceso"))
{
?>


<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h2 class='titulo'>Días festivos</h2>
            </div>
           
        </div>
    </div><!-- /.container-fluid -->
</section>


<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-header">
                
            <?php if($conexion->permisos($_SESSION['idtipousuario'],"7","crear")) { ?>
                <button type="button" id="btnNuevoDiaFestivo" data-toggle="modal" class="btn bg-navy btn-lg">Nuevo Día festivo</button>
            <?php } ?>
                
        </div>
        <!-- /.card-header -->
        <div class="card-body" style="overflow-x: scroll;">
           
            <div  class="table-responsive " >
                <table id="tabladiasFestivos" class="table table-striped" >
                    <thead>
                    <tr>  
                        <th>No.</th>
                        <th>DESCRIPCIÓN</th>
                        <th>FECHA</th>
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


<div id="divNuevoDiaFestivo" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form id='formNuevoDiaFestivo' class="form form-validate"  role="form"   method="post" >
            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Nuevo Día festivo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body row">

                    <div class="form-group col-md-12">
                        <label for="newDescripcion">Descripción:</label>
                        <input type="text" class="form-control" id="newDescripcion" name="newDescripcion" placeholder="Ingrese Descripción" required >
                    </div>
                
                    <div class="form-group col-md-6">
                        <label for="newDia">Seleccione el día:</label>
                        <select id="newDia" name="newDia" class="form-control select2-list select2-success" data-dropdown-css-class="select2-success" data-placeholder="Seleccione el día" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>

                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>

                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>

                            <option value="30">30</option>
                            <option value="31">31</option>
                           
                        </select>                        
                    </div>


                    <div class="form-group col-md-6">
                        <label for="newMeses">Seleccione Mes:</label>
                        <select id="newMeses" name="newMeses" class="form-control select2-list select2-success" data-dropdown-css-class="select2-success" data-placeholder="Seleccione Mes" required>
                            <option value="1">ENERO</option>
                            <option value="2">FEBRERO</option>
                            <option value="3">MARZO</option>
                            <option value="4">ABRIL</option>
                            <option value="5">MAYO</option>
                            <option value="6">JUNIO</option>
                            <option value="7">JULIO</option>
                            <option value="8">AGOSTO</option>
                            <option value="9">SEPTIEMBRE</option>
                            <option value="10">OCTUBRE</option>
                            <option value="11">NOVIEMBRE</option>
                            <option value="12">DICIEMBRE</option>
                        </select>                        
                    </div>                    

                </div>

                <div class="modal-footer">
                    <div class="response"></div>
                    <button type="button" id="btnGuardarNuevoDiaFestivo" class="btn bg-navy">Guardar</button>
                    <button type="button" id="btnCancelarNuevoDiaFestivo" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>


<div id="divEditarDiaFestivo" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form id='formEditarDiaFestivo' class="form form-validate"  role="form"   method="post" >
            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Editar Día festivo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body row">

                <div class="form-group col-md-12">
                        <label for="editDescripcion">Descripción:</label>
                        <input type="hidden" class="form-control" name="iddiaFestivo" id="iddiaFestivo" />
                        <input type="text" class="form-control" id="editDescripcion" name="editDescripcion" placeholder="Ingrese Descripción" required >
                    </div>
                
                    <div class="form-group col-md-6">
                        <label for="editDia">Seleccione el día:</label>
                        <select id="editDia" name="editDia" class="form-control select2-list select2-success" data-dropdown-css-class="select2-success" data-placeholder="Seleccione el día" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>

                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>

                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>

                            <option value="30">30</option>
                            <option value="31">31</option>
                           
                        </select>                        
                    </div>


                    <div class="form-group col-md-6">
                        <label for="editMeses">Seleccione Mes:</label>
                        <select id="editMeses" name="editMeses" class="form-control select2-list select2-success" data-dropdown-css-class="select2-success" data-placeholder="Seleccione Mes" required>
                            <option value="1">ENERO</option>
                            <option value="2">FEBRERO</option>
                            <option value="3">MARZO</option>
                            <option value="4">ABRIL</option>
                            <option value="5">MAYO</option>
                            <option value="6">JUNIO</option>
                            <option value="7">JULIO</option>
                            <option value="8">AGOSTO</option>
                            <option value="9">SEPTIEMBRE</option>
                            <option value="10">OCTUBRE</option>
                            <option value="11">NOVIEMBRE</option>
                            <option value="12">DICIEMBRE</option>
                        </select>                        
                    </div>   

                </div>

                <div class="modal-footer">
                    <div class="response"></div>
                    <button type="button" id="btnGuardarEditarDiaFestivo" class="btn bg-navy">Guardar</button>
                    <button type="button" id="btnCancelarEditarDiaFestivo" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>



<div id="divEliminarDiaFestivo" class="modal fade show" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar Día festivo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="ideliminarDiaFestivo" id="ideliminarDiaFestivo" class="form-control" />
                <p><h4>¿Desea eliminar el registro?</h4></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnEliminarDiaFestivo">Si estoy seguro</button>
                <button type="button" class="btn btn-default" id="btnCancelarEliminarDiaFestivo" data-dismiss="modal">Cancelar</button>
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
        $.post("funciones/ws_usuarios.php", {accion:"consultarPermisos" , idmodulo:"7"} ,function(data)
        {
            if(data.resultado){
                Acceso = data.registros[0]["acceso"];
                Crear = data.registros[0]["crear"];
                Modificar = data.registros[0]["modificar"];
                Eliminar = data.registros[0]["eliminar"];
                Consultar = data.registros[0]["consultar"];
                mostrardiasFestivos();
            }
            else
              toastr.warning(data.mensaje,"Info");
        }, "json")
        .fail(function()
        {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });
    }
    

    function mostrardiasFestivos () {
      $("#tabladiasFestivos  tbody tr").remove();
      $.post("funciones/ws_diasFestivos.php", { accion: "mostrar" }, function(data) {
        if(data.resultado)
          {

            var btnEditar = "";
            var btnEliminar = "";
            var btnConsultar = "";

            $.each(data.registros,function(key,value) {


              

              if (Modificar == 1) {
                btnEditar = " <button class='btn btn-default bg-lightblue tooltip2' style='cursor:pointer; ' href='#' ><span class='tooltiptext'>Editar Día festivo</span><i class='fa fa-edit fa-lg '></i></button>";
              };

              if (Eliminar == 1) {
                btnEliminar = " <button class='btn btn-default tooltip2' style='cursor:pointer' href='#' ><span class='tooltiptext'>Eliminar Día festivo</span> <i class='fa fa-trash fa-lg '></i></button>";
              };

              var dateTime = moment( value["fecha"] );
              var full = dateTime.format('LL');
              full = full.substring(0, full.length - 8);        

              $("<tr></tr>")
                .append( "<td>" + (key + 1) + "</td>" )
                .append( "<td>" + value["descripcion"] + "</td>" )
                .append( "<td>" + full + "</td>" )
               .append( $("<td></td>").append( 
                $("<div class='btn-group'></div>") 
                        
                    .append( $(btnEditar)
                        .on("click",{ iddiaFestivo:value["id"] } , editarDiaFestivo) ) 
                    .append( $(btnEliminar)
                        .on("click",{ iddiaFestivo:value["id"] } , eliminarDiaFestivo) )  
                    
                          
                    )
                  )
                .appendTo("#tabladiasFestivos > tbody");
            });

            $("#tabladiasFestivos").DataTable();

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
    $("#btnNuevoDiaFestivo").on("click",mostrarModalNuevoDiaFestivo);
    
    function mostrarModalNuevoDiaFestivo(e){
        e.preventDefault();
        $("#formNuevoDiaFestivo")[0].reset();
        $("#divNuevoDiaFestivo").modal("show", {backdrop: "static"});
    }

    



    /****************** GUARDAR DATOS DEL REGISTRO *******************/
    $("#btnGuardarNuevoDiaFestivo").on("click",guardarNuevoDiaFestivo);
    function guardarNuevoDiaFestivo(e){
      e.preventDefault();


      if($("#formNuevoDiaFestivo").valid()) {
          $.post("funciones/ws_diasFestivos.php", "accion=nuevo&"+$("#formNuevoDiaFestivo").serialize() ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");
                $("#divNuevoDiaFestivo").modal("hide");
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
    function editarDiaFestivo (e) {

        e.preventDefault();
        $.getJSON("funciones/ws_diasFestivos.php", { accion:"mostrar" , id:e.data.iddiaFestivo }, function(data) {
          if(data.resultado)
            {
              $("#formEditarDiaFestivo")[0].reset();
              $("#divEditarDiaFestivo").modal("show", {backdrop: "static"});
              
              $("#formEditarDiaFestivo #iddiaFestivo").val(data.registros[0]["id"]);
              $("#formEditarDiaFestivo #editDescripcion").val(data.registros[0]["descripcion"]);
              
              var no_mes = data.registros[0]["fecha"];
              no_mes = no_mes.substr(5, 2);

              var no_dia = data.registros[0]["fecha"];
              no_dia = no_dia.substr(8, 9);

              $('#formEditarDiaFestivo #editDia').val(parseInt(no_dia)).trigger('change.select2');
              $('#formEditarDiaFestivo #editMeses').val(parseInt(no_mes)).trigger('change.select2');

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
    $("#btnGuardarEditarDiaFestivo").on("click",guardarEditarDiaFestivo);
    function guardarEditarDiaFestivo(e){

      e.preventDefault();
      if($("#formEditarDiaFestivo").valid()) {
          $.post("funciones/ws_diasFestivos.php", "accion=editar&"+$("#formEditarDiaFestivo").serialize() ,function(data) {
            if(data.resultado){              
                toastr.success(data.mensaje, "Exito");;
                $("#divEditarDiaFestivo").modal("hide");
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
    function eliminarDiaFestivo (e) {
      e.preventDefault();
      $("#divEliminarDiaFestivo").modal("show", {backdrop: "static"});
      $("#ideliminarDiaFestivo").val(e.data.iddiaFestivo);
    }


    $("#btnEliminarDiaFestivo").on("click",guardarEliminarDiaFestivo);
    
    function guardarEliminarDiaFestivo(e){
        e.preventDefault();
        $.post("funciones/ws_diasFestivos.php", { iddiaFestivo:$("#ideliminarDiaFestivo").val() , accion:"eliminar" } ,function(data) {
          if(data.resultado){
              toastr.success(data.mensaje, "Exito");
              $("#divEliminarDiaFestivo").modal("hide");
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


    function restringir_Horario(){

        $.post("funciones/ws_usuarios.php", { accion:"restringir_Horario" } ,function(data) {
            if(data.resultado){                
                if (data.accesar == 0) {
                    window.open('index.php?a=logout', '_self');
                }              
            }
            else{
                toastr.warning(data.mensaje,"Info");
            }
        }, "json")
        .fail(function() {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });

    }


    setInterval(restringir_Horario, 10000);


  });
</script>