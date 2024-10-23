<?php
session_start();
require_once ("../funciones/classSQL.php");
$conexion = new conexion();
if($conexion->permisos($_SESSION['idtipousuario'],"1","acceso"))
{
?>


<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h2 class='titulo'>Usuarios</h2>
            </div>
           
        </div>
    </div><!-- /.container-fluid -->
</section>


<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-header">
                
            <?php if($conexion->permisos($_SESSION['idtipousuario'],"1","crear")) { ?>
                <button type="button" id="btnNuevoUsuario" data-toggle="modal" class="btn bg-navy btn-lg">Nuevo Usuario</button>
                <a href="#/permisos" class="btn btn-default bg-lightblue btn-lg">Permisos</a>
            <?php } ?>
                
        </div>
        <!-- /.card-header -->
        <div class="card-body" style="overflow-x: scroll;">
           
            <div  class="table-responsive " >
                <table id="tablaUsuarios" class="table table-striped" >
                    <thead>
                    <tr>  
                        <th>No.</th>
                        <th>TIPO USUARIO</th>
                        <th>AREA</th>
                        <th>USUARIO</th>
                        <th>NOMBRE</th>
                        <th>ACTIVO</th>
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



<div id="divNuevoUsuario" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form id='formNuevoUsuario' class="form form-validate"  role="form"   method="post" >
            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Nuevo usuario</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body">

                    <div class="form-group floating-label">
                        <label for="idtipousuario">Tipo usuario: </label>
                        <select class="form-control select2-list" id="idtipousuario" name="idtipousuario" data-placeholder="Elija Tipo Usuario" required>                          
                        </select><div class="form-control-line"></div>
                    </div>

                    <div class="form-group floating-label">
                        <label for="idsucursal">Área de Trabajo: </label>
                        <select class="form-control select2-list" id="idsucursal" name="idsucursal" data-placeholder="Elija Área de Trabajo" required> 
                        <option value=""> </option>
                        <option value="1"> ADMINISTRACIÓN</option>
                        <option value="2"> OFICINA</option>
                        <option value="3"> EXTERIOR</option>
                        </select><div class="form-control-line"></div>
                    </div>

                    <div class="form-group floating-label">
                        <label for="usuario">Usuario:</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingrese Usuario" required >
                    </div>

                    <div class="form-group floating-label">
                        <label for="clave">Clave:</label>
                        <input type="password" class="form-control" id="clave" name="clave" placeholder="Ingrese Clave" required >
                    </div>

                    <div class="form-group floating-label">
                        <label for="nombre">Nombre Usuario:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese Nombre" required >
                    </div>

                    <div class="form-group floating-label">

            

                        <div class="col-sm-12" style="margin-top: 20px;">

                            <div class="icheck-success d-inline">
                                <input type="radio" name="activado" id="activado0" value="1" checked >
                                <label for="activado0">Activo
                                </label>
                            </div>

                            <div class="icheck-success d-inline" style="margin-left: 20px;">
                                <input type="radio" name="activado" id="activado1" value="0">
                                <label for="activado1">De baja
                                </label>
                            </div>


                        </div><!--end .col -->
                    </div>


                    <br>


                    <div class="info-box bg-light">
                        <div class="info-box-content">      
                            
                        
                            <div class="form-group">
                            
                                <div class="icheck-success">
                                    <input type="checkbox" id="newcheckaccesocaja">
                                    <label for="newcheckaccesocaja">HABILITAR CAJA</label>  
                                    <input type="hidden" class="form-control" name="newaccesocaja" id="newaccesocaja" />
                                </div>

                            </div>

                            <!--<div class="form-group" style="display:none;">
                            
                                <div class="icheck-success">
                                    <input type="checkbox" id="newcheckaccesocaja">
                                    <label for="newcheckaccesocaja">HABILITAR CAJA</label>  
                                    <input type="hidden" class="form-control" name="newaccesocaja" id="newaccesocaja" />
                                </div>

                            </div>-->



                            <div class="form-group">
                            
                                <div class="icheck-danger">
                                    <input type="checkbox" id="newcheckRestringirHorario">
                                    <label for="newcheckRestringirHorario">Restringir horario</label>  
                                    <input type="hidden" class="form-control" name="newRestringirHorario" id="newRestringirHorario" />

                                </div>

                            </div>

                            <div class="row" id="contenedorNuevoHorario" style="display:none">


                                <div class="form-group col-md-6">
                                    <label>Hora de inicio:</label>

                                    <div class="input-group date" id="timepicker" data-target-input="nearest">
                                    <input type="text" class="form-control  datetimepicker-input" name="newhorainicio" data-target="#timepicker" required>
                                    <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                    </div>
                                    
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Hora de fin:</label>

                                    <div class="input-group date" id="timepicker2" data-target-input="nearest">
                                    <input type="text" class="form-control  datetimepicker-input" name="newhorafin" data-target="#timepicker2" required>
                                    <div class="input-group-append" data-target="#timepicker2" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                    </div>
                                    
                                </div>

                            </div>

                        
                        </div>
                    </div>



                </div>

                <div class="modal-footer">
                    <div class="response"></div>
                    <button type="button" id="btnGuardarNuevoUsuario" class="btn bg-navy">Guardar</button>
                    <button type="button" id="btnCancelarNuevoUsuario" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>


<div id="divEditarUsuario" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
    <form id='formEditarUsuario' class="form form-validate"  role="form"   method="post" >
        <div class="modal-content  panel panel-warning">           

            <div class="modal-header">
              <h4 class="modal-title">Editar Usuario</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>

            <div class="card-body">

            

                <div class="form-group floating-label">
                    <label for="id_tipousuario">Tipo usuario: </label>
                    <select class="form-control select2-list" id="id_tipousuario" name="id_tipousuario" data-placeholder="Elija Tipo Usuario" required>                          
                    </select><div class="form-control-line"></div>
                </div>

                <div class="form-group floating-label">
                    <label for="id_sucursal">Área de Trabajo: </label>
                    <select class="form-control select2-list" id="id_sucursal" name="id_sucursal" data-placeholder="Elija Área de Trabajo" required> 
                    <option value=""> </option>
                    <option value="1"> ADMINISTRACIÓN</option>
                    <option value="2"> OFICINA</option>
                    <option value="3"> EXTERIOR</option>
                    </select><div class="form-control-line"></div>
                </div>

                <div class="form-group floating-label">
                    <label for="usuario">Usuario:</label>
                    <input type="hidden" class="form-control" name="idusuario" id="idusuario" />
                    <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingrese Usuario" required >
                </div>

                <div class="form-group floating-label">
                    <label for="clave">Clave:</label>
                    <input type="password" class="form-control" id="clave" name="clave" placeholder="Ingrese Clave" required >
                </div>

                <div class="form-group floating-label">
                    <label for="nombre">Nombre Usuario:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese Nombre" required >
                </div>

                <div class="form-group floating-label">

        

                    <div class="col-sm-12" style="margin-top: 20px;">

                        <div class="icheck-success d-inline">
                            <input type="radio" name="activado" id="activado3" value="1" checked >
                            <label for="activado3">Activo
                            </label>
                        </div>

                        <div class="icheck-success d-inline" style="margin-left: 20px;">
                            <input type="radio" name="activado" id="activado4" value="0">
                            <label for="activado4">De baja
                            </label>
                        </div>


                    </div><!--end .col -->
                </div>


                <br>



                <div class="info-box bg-light">
                        <div class="info-box-content">       
                                
                            <div class="form-group">
                                
                                <div class="icheck-success">
                                    <input type="checkbox" id="editcheckaccesocaja">
                                    <label for="editcheckaccesocaja">HABILITAR CAJA</label>  
                                    <input type="hidden" class="form-control" name="editaccesocaja" id="editaccesocaja" />
                                </div>

                            </div>

                            <!--<div class="form-group" style="display:none;">
                                
                                <div class="icheck-success">
                                    <input type="checkbox" id="editcheckaccesocaja">
                                    <label for="editcheckaccesocaja">HABILITAR CAJA</label>  
                                    <input type="hidden" class="form-control" name="editaccesocaja" id="editaccesocaja" />
                                </div>

                            </div>-->



                            <div class="form-group">
                            
                                <div class="icheck-danger">
                                    <input type="checkbox" id="editcheckRestringirHorario">
                                    <label for="editcheckRestringirHorario">Restringir horario</label>  
                                    <input type="hidden" class="form-control" name="editRestringirHorario" id="editRestringirHorario" />

                                </div>

                            </div>

                            <div class="row" id="editcontenedorNuevoHorario" style="display:none">


                                <div class="form-group col-md-6">
                                    <label>Hora de inicio:</label>

                                    <div class="input-group date" id="timepicker3" data-target-input="nearest">
                                    <input type="text" class="form-control  datetimepicker-input" name="edithorainicio" id="edithorainicio" data-target="#timepicker3" required>
                                    <div class="input-group-append" data-target="#timepicker3" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                    </div>
                                    
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Hora de fin:</label>

                                    <div class="input-group date" id="timepicker4" data-target-input="nearest">
                                    <input type="text" class="form-control  datetimepicker-input" name="edithorafin" id="edithorafin" data-target="#timepicker4" required>
                                    <div class="input-group-append" data-target="#timepicker4" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                    </div>
                                    
                                </div>

                            </div>

                        
                        </div>
                    </div>




            </div>
            

            <div class="modal-footer">
                <div class="response"></div>
                <button type="button" id="btnGuardarEditarUsuario" class="btn bg-navy">Guardar</button>
                <button type="button" id="btnCancelarEditarUsuario" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </form>  
    </div>
</div>


<div id="divEliminarUsuario" class="modal fade show" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar Usuario</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="ideliminarusuario" id="ideliminarusuario" class="form-control" />
                <p><h4>¿Desea eliminar el registro?</h4></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnEliminarUsuario">Si estoy seguro</button>
                <button type="button" class="btn btn-default" id="btnCancelarEliminarUsuario" data-dismiss="modal">Cancelar</button>
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

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT',  language: 'es'
    });

    $('#timepicker2').datetimepicker({
      format: 'LT',  language: 'es'
    });


    $('#timepicker3').datetimepicker({
      format: 'LT',  language: 'es'
    });

    $('#timepicker4').datetimepicker({
      format: 'LT',  language: 'es'
    });

    verficarPermisos();


    
    function verficarPermisos () {
        $.post("funciones/ws_usuarios.php", {accion:"consultarPermisos" , idmodulo:"1"} ,function(data)
        {
            if(data.resultado){
                Acceso = data.registros[0]["acceso"];
                Crear = data.registros[0]["crear"];
                Modificar = data.registros[0]["modificar"];
                Eliminar = data.registros[0]["eliminar"];
                Consultar = data.registros[0]["consultar"];
                tipousuarios();
                mostrarUsuarios();
            }
            else
              toastr.warning(data.mensaje,"Info");
        }, "json")
        .fail(function()
        {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });
    }
    



    
    function mostrarUsuarios () {
      $("#tablaUsuarios  tbody tr").remove();
      $.post("funciones/ws_usuarios.php", { accion: "mostrar" }, function(data) {
        if(data.resultado)
          {

            var btnEditar = "";
            var btnEliminar = "";
            var btnPermisos = "";

            $.each(data.registros,function(key,value) {
              var activado ="Activado";
              if (value["activado"] == 0) {
                var activado ="Desactivado";
              }

              if (Modificar == 1) {
                btnEditar = " <button class='btn btn-default bg-lightblue tooltip2' style='cursor:pointer; ' href='#' ><span class='tooltiptext'>Editar usuario</span><i class='fa fa-edit fa-lg '></i></button>";
              };

              if (Eliminar == 1) {
                btnEliminar = " <button class='btn btn-default tooltip2' style='cursor:pointer' href='#' ><span class='tooltiptext'>Eliminar usuario</span> <i class='fa fa-trash fa-lg '></i></button>";
              };

              $("<tr  rel='"+value["id"]+"'></tr>")
                .append( "<td>" + (key + 1) + "</td>" )
                .append( "<td>" + value["tipousuario"] + "</td>" )
                .append( "<td>" + value["sucursal"] + "</td>" )
                .append( "<td>" + value["usuario"] + "</td>" )
                .append( "<td>" + value["nombre"] + "</td>" )
                .append( "<td>" + activado + "</td>" )
               .append( $("<td></td>").append( 
                $("<div class='btn-group'></div>") 
                        
                    .append( $(btnEditar)
                        .on("click",{ idusuario:value["id"] } , editarUsuario) ) 
                    .append( $(btnEliminar)
                        .on("click",{ idusuario:value["id"] } , eliminarUsuario) )  
                        
                    )
                  )
                .appendTo("#tablaUsuarios > tbody");
            });

                $("#tablaUsuarios a").tooltip(); 
                $("#tablaUsuarios").DataTable({ 

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
                ]


                
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



    
    function tipousuarios() {
        $.post("funciones/ws_tipousuarios.php", {accion:"mostrarTU"} ,function(data)
        {
            $("#idtipousuario").html('');
            
            if(data.resultado){
                $("#formNuevoUsuario #idtipousuario").append("<option value='' ></option>");
                $("#formEditarUsuario #id_tipousuario").append("<option value='' ></option>");
                $.each(data.registros,function(key,value) {
                  $("#formNuevoUsuario #idtipousuario").append("<option value="+value["id"]+" >"+value["descripcion"]+"</option>");
                  $("#formEditarUsuario #id_tipousuario").append("<option value="+value["id"]+" >"+value["descripcion"]+"</option>");
                });

                /****** MOSTRAR SELECT ********/
                $(".select2-list").select2({ allowClear: true });
            }
            else
              toastr.warning(data.mensaje,"Info");

            
            
        }, "json")
        .fail(function()
        {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });
    }


    
    /****************** MOSTRAR MODAL NUEVO REGISTRO *******************/
    $("#btnNuevoUsuario").on("click",mostrarModalNuevoUsuario);
    
    function mostrarModalNuevoUsuario(e){
        e.preventDefault();
        $("#formNuevoUsuario")[0].reset();

        $("#formNuevoUsuario #idtipousuario option[value='']").attr("selected","selected");
        $(".select2-list").select2({ allowClear: true });
        $("#formNuevoUsuario #idsucursal option[value='']").attr("selected","selected");
        $(".select2-list").select2({ allowClear: true });

        $("#formNuevoUsuario input").removeClass("dirty");

        $("#contenedorNuevoHorario").fadeOut("fast");


        $("#divNuevoUsuario").modal("show", {backdrop: "static"});
    }

    /****************** GUARDAR DATOS DEL REGISTRO *******************/
    $("#btnGuardarNuevoUsuario").on("click",guardarNuevoUsuario);
    function guardarNuevoUsuario(e){
      e.preventDefault();
      var valRestringirHorario = $("#newcheckRestringirHorario").is(':checked') ? 0:1;
      $("#newRestringirHorario").val(valRestringirHorario);


      var valaccesocaja = $("#newcheckaccesocaja").is(':checked') ? 1:0;
      $("#newaccesocaja").val(valaccesocaja);




      if($("#formNuevoUsuario").valid()) {
          //console.log($("#formNuevoUsuario").serialize());
          $.post("funciones/ws_usuarios.php", "accion=nuevo&"+$("#formNuevoUsuario").serialize() ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");
                $("#divNuevoUsuario").modal("hide");
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
    function editarUsuario (e) {
        e.preventDefault();
        $.post("funciones/ws_usuarios.php", { accion:"mostrar" , id:e.data.idusuario }, function(data) {
          if(data.resultado)
            {

              $("#formEditarUsuario")[0].reset();
              $("#divEditarUsuario").modal("show", {backdrop: "static"});
              $("#formEditarUsuario input").addClass("dirty");
              
              $("#formEditarUsuario #idusuario").val(data.registros[0]["id"]);                    
              $("#formEditarUsuario #usuario").val(data.registros[0]["usuario"]);
              $("#formEditarUsuario #clave").val(data.registros[0]["clave"]);
              $("#formEditarUsuario #nombre").val(data.registros[0]["nombre"]);

              $('#id_tipousuario').val(data.registros[0]["idtipousuario"]).trigger('change.select2');
              $('#id_sucursal').val(data.registros[0]["idsucursal"]).trigger('change.select2');


              if (data.registros[0]["activado"] == 1) {
                $("#formEditarUsuario #activado3").prop("checked", true);
              }else{
                $("#formEditarUsuario #activado4").prop("checked", true);
              }

              if (data.registros[0]["restringirhorario"] == 0) {
                $("#editcontenedorNuevoHorario").fadeIn("slow");
                $("#editcheckRestringirHorario").prop("checked", true);  
                $("#formEditarUsuario #edithorainicio").val(data.registros[0]["horainicio"]);
                $("#formEditarUsuario #edithorafin").val(data.registros[0]["horafin"]);


              }else{
                $("#editcheckRestringirHorario").prop("checked", false); 
                $("#editcontenedorNuevoHorario").fadeOut("slow");
              }


              if (data.registros[0]["accesocaja"] == 1) {
                $("#editcheckaccesocaja").prop("checked", true);  
              }else{
                $("#editcheckaccesocaja").prop("checked", false); 
              }


             
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
    $("#btnGuardarEditarUsuario").on("click",guardarEditarUsuario);

    function guardarEditarUsuario(e){
      e.preventDefault();

      var valRestringirHorario = $("#editcheckRestringirHorario").is(':checked') ? 0:1;
      $("#editRestringirHorario").val(valRestringirHorario);

      var valaccesocaja = $("#editcheckaccesocaja").is(':checked') ? 1:0;
      $("#editaccesocaja").val(valaccesocaja);


      if($("#formEditarUsuario").valid()) {
          $.post("funciones/ws_usuarios.php", "accion=editar&"+$("#formEditarUsuario").serialize() ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");;
                $("#divEditarUsuario").modal("hide");
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
     function eliminarUsuario (e) {
      e.preventDefault();
      $("#divEliminarUsuario").modal("show", {backdrop: "static"});
      $("#ideliminarusuario").val(e.data.idusuario);
    }


    $("#btnEliminarUsuario").on("click",guardarEliminarUsuario);
    
    function guardarEliminarUsuario(e){
        e.preventDefault();
        $.post("funciones/ws_usuarios.php", { idusuario:$("#ideliminarusuario").val() , accion:"eliminar" } ,function(data) {
          if(data.resultado){
              toastr.success(data.mensaje, "Exito");
              $("#divEliminarUsuario").modal("hide");
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


    $("#newcheckRestringirHorario").on("click",function(e){    
        
        if ($("#newcheckRestringirHorario").is(':checked')) {
            $("#contenedorNuevoHorario").fadeIn("slow");            
        }else{
            $("#contenedorNuevoHorario").fadeOut("slow");            
        }

    });


    $("#editcheckRestringirHorario").on("click",function(e){    
        
        if ($("#editcheckRestringirHorario").is(':checked')) {
            $("#editcontenedorNuevoHorario").fadeIn("slow");            
        }else{
            $("#editcontenedorNuevoHorario").fadeOut("slow");            
        }

    });
    

    jQuery.fn.DataTable.ext.type.search.string = function ( data ) {
    return ! data ?
        '' :
        typeof data === 'string' ?
            data
                .replace( /\n/g, ' ' )
                .replace( /[áâàä]/g, 'a' )
                .replace( /[éêèë]/g, 'e' )
                .replace( /[íîìï]/g, 'i' )
                .replace( /[óôòö]/g, 'o' )
                .replace( /[úûùü]/g, 'u' )
                .replace( /ç/g, 'c' ) :
            data;
    };



  });
</script>