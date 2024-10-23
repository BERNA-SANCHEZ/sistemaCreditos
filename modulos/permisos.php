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
                <h2 class='titulo'>Permisos</h2>
            </div>
           
        </div>
    </div><!-- /.container-fluid -->
</section>


<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-header">
            
            <?php if($conexion->permisos($_SESSION['idtipousuario'],"1","crear")) { ?>
               
                <a href="#/usuarios" class="btn bg-navy btn-lg">Usuarios</a>

            <?php } ?>
                
        </div>
        <!-- /.card-header -->
        <div class="card-body" style="overflow-x: scroll;">
        
        
           
            <div  class="table-responsive " >
                
                <table id="tablaPermisos" class="table order-column hover">
                    <thead>
                    <tr>  
                        <th>No.</th>
                        <th>TIPO USUARIO</th>
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

<br>
<br>

<!-- Main content -->
<section id="divTablaDetalle" class="content" style="display:none;">
    <div class="card">
        
        <div class="card-body" >

            <div  id="divTablaModulos" class="table-responsive" ></div>

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


    mostrarTiposUsuarios();

    function mostrarTiposUsuarios() {
      $("#tablaPermisos  tbody tr").remove();
      $.post("funciones/ws_tipousuarios.php", { accion: "mostrarTU" }, function(data) {
        if(data.resultado)
          {
            $.each(data.registros,function(key,value) {

              $("<tr  rel='"+value["id"]+"'></tr>")
                .append( "<td>" + (key + 1) + "</td>" )
                .append( "<td>" + value["descripcion"] + "</td>" )
                .append( $("<td></td>").append("<div class='btn-group'></div>")
                    .append( $("<button class='btn btn-default bg-lightblue tooltip2' rel='" + value["id"] + "' href='#' > <span class='tooltiptext'>Ver modulos</span> <i class='fa fa-eye fa-lg'></button>")
                        .on("click", mostrarModulos) )                    
                  )
                .appendTo("#tablaPermisos > tbody");
            });

                $("#tablaPermisos a").tooltip(); 
                $("#tablaPermisos").DataTable();

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
    


    function mostrarModulos (e) {
      e.preventDefault();
      
      $("#divTablaDetalle").fadeIn("slow");
      $("#tablaModulos  tbody tr").remove();
      var idTipoUsuario = $(e.target).closest("button").attr("rel");


      $.post("funciones/ws_tipousuarios.php", { accion: "mostrarPU" , id : idTipoUsuario}, function(data) {
        if(data.resultado)
          { 
            var tabla =
            "<h3 id='tituloModulos' class='tituloH3'></h3><br>"+
            "<table id='tablaModulos' class='table order-column hover' >"+
              "<thead>"+
                "<tr>  "+
                   "<th>No.</th>"+
                   "<th>Modulo</th>"+
                   "<th>Acceso</th>"+
                   "<th>Crear</th>"+
                   "<th>Modificar</th>"+
                   "<th>Eliminar</th>"+
                   "<th>Consultar</th>"+
                "</tr>"+
              "</thead>"+
              "<tbody></tbody>"+
            "</table>";
            $("#divTablaModulos").html(tabla);

            var num = 1;

            $.each(data.registros,function(key,value) {
                num++;
              $("#tituloModulos").html("Tipo Usuario " + value["tipousuario"]);

              $("<tr></tr>")
                .append( "<td>" + (key + 1) + "</td>" )
                .append( "<td>" + value["modulo"] + "</td>" )
                .append( "<td> <div class='icheck-success d-inline'><input  type='checkbox' modulo='"+value["modulo"]+"' rel='"+value["Id"]+"' value='acceso'    "+value["acceso"]+"  id='acceso"+(num)+"'><label for='acceso"+(num)+"'></label></div> </td>" )
                .append( "<td> <div class='icheck-success d-inline'><input  type='checkbox' modulo='"+value["modulo"]+"' rel='"+value["Id"]+"' value='crear'    "+value["crear"]+"  id='crear"+(num)+"'><label for='crear"+(num)+"'></label></div> </td>" )
                .append( "<td> <div class='icheck-success d-inline'><input  type='checkbox' modulo='"+value["modulo"]+"' rel='"+value["Id"]+"' value='modificar'    "+value["modificar"]+"  id='modificar"+(num)+"'><label for='modificar"+(num)+"'></label></div> </td>" )
                .append( "<td> <div class='icheck-success d-inline'><input  type='checkbox' modulo='"+value["modulo"]+"' rel='"+value["Id"]+"' value='eliminar'    "+value["eliminar"]+"  id='eliminar"+(num)+"'><label for='eliminar"+(num)+"'></label></div> </td>" )
                .append( "<td> <div class='icheck-success d-inline'><input  type='checkbox' modulo='"+value["modulo"]+"' rel='"+value["Id"]+"' value='consultar'    "+value["consultar"]+"  id='consultar"+(num)+"'><label for='consultar"+(num)+"'></label></div> </td>" )                               


                .appendTo("#tablaModulos > tbody");
            });

               
                $("input[type='checkbox']").on('change', function() {  actualizarPermisos($(this)); });
                $('#tablaModulos').ScrollTo();

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

    function actualizarPermisos (input) {
      var id = input.attr("rel");
      var modulo = input.attr("modulo");
      var campo = input.val();
      var valor = "0";
      var mensaje = "El el Tipo Usuario NO puede "+campo+" en el modulo de "+modulo;
      if (input.prop('checked')){
        var valor = "1";
        var mensaje = "El el Tipo Usuario ya puede "+campo+" en el modulo de "+modulo;
      }

      $.post("funciones/ws_tipousuarios.php", { accion: "actializarPermiso" , id : id , campo : campo, valor : valor}, function(data) {
        if(data.resultado)
          {
            toastr.success(data.mensaje, "Exito");
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



  });
</script>