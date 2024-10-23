<?php
session_start();
require_once ("../funciones/classSQL.php");
$conexion = new conexion();
if($conexion->permisos($_SESSION['idtipousuario'],"9","acceso"))
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
                <h2 class='titulo'>Cajas</h2>
            </div>
           
        </div>
    </div><!-- /.container-fluid -->
</section>



<section class="content">
  <div class="section-body">
   

    <div class="contentpanel">
      <div class="row">
          <div class="col-sm-8">
            <div class="card card-success card-outline">
              <div class="card-body">                  
                  <form class="form-horizontal" id="formAperturaCaja" role="form"   method="post" >
                      
                      <div class="form-group row">
                         <label class="col-md-3 control-label"  for="cajas">Cajas: </label>
                         <div class="col-md-9">
                            <select class="form-control select2-list select2-success" data-dropdown-css-class="select2-success" id="cajas" name="cajas" ></select>              
                         </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-md-3 control-label" for="apertura">Efectivo Inicial:</label>
                          <div class="col-md-9">
                            <input type="text" class="form-control" name="apertura" id="apertura" /><div class="form-control-line"></div>
                          </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-md-3 control-label" for="fechaapertura">Fecha Apertura:</label>
                          <div class="col-md-9">
                            <input type="text" class="form-control input-md " name="fechaapertura" id="fechaapertura" readonly />
                          </div>
                      </div> 

                      <div class="form-group row">
                        <label class="col-md-3 control-label" for="saldoactual">Saldo Actual:</label>
                          <div class="col-md-9">
                            <input type="text" class="form-control input-md " name="saldoactual" id="saldoactual" readonly />
                          </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-md-3 control-label" for="ingresocaja">Ingreso en caja:</label>
                          <div class="col-md-9">
                            <input type="text" class="form-control required " name="ingresocaja" id="ingresocaja" /><div class="form-control-line"></div>
                          </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-md-3 control-label" for="diferencia">Diferencia:</label>
                          <div class="col-md-6">
                            <input type="text" class="form-control input-md " name="diferencia" id="diferencia" readonly />
                          </div>
                          <div class="col-md-3">
                            <table>
                              <tbody>
                                <tr>
                                  <td class="legendColorBox">
                                    <div style="border:1px solid #ccc;padding:1px">
                                      <div style="border:5px solid #1CAF9A;"></div>
                                    </div>
                                  </td>
                                  <td class="legendLabel"> Cuadrado </td>
                                </tr>
                                <tr>
                                  <td class="legendColorBox">
                                    <div style="border:1px solid #ccc;padding:1px">
                                      <div style="border:5px solid #DC6969;"></div>
                                    </div>
                                  </td>
                                  <td class="legendLabel"> Faltante</td>
                                 
                                </tr>


                                <td class="legendColorBox">
                                    <div style="border:1px solid #ccc;padding:1px">
                                      <div style="border:5px solid #eea236;"></div>
                                    </div>
                                  </td>
                                  <td class="legendLabel"> Sobrante</td>

                                  
                                <tr>
                                    
                                </tr>
                              </tbody>
                            </table>
                          </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-md-3 control-label" for="estado">Estado:</label>
                          <div class="col-md-9">
                            <input type="text" class="form-control input-md " name="estado" id="estado" readonly />
                          </div>
                      </div>


                  </form>
                  <br>
                <button id="btnMovimientoCaja"  class="btn bg-navy btn-block" disabled > A P E R T U R A R </button>
              </div> 
            </div>
          </div>

          <div class="col-sm-4">
            <div id="resumenIngresos" class="card card-success card-outline">


                <div class="card-header">
                    <h3 class="card-title">INGRESOS DEL DÍA</h3>             
                </div>



              <div class="card-body">
                    
                    <div class="height-8">
                       Apertura (Q. 0.00)
                      <span class="pull-right text-success text-sm">0%</span>
                      <div class="progress progress-hairline">
                        <div class="progress-bar progress-bar-primary" style="width:0%"></div>
                      </div>

                      Recaudo (Q. 0.00)
                      <span class="pull-right text-success text-sm">0%</span>
                      <div class="progress progress-hairline">
                        <div class="progress-bar progress-bar-primary-success" style="width:0%"></div>
                      </div>

                      Ingresos (Q. 0.00)
                      <span class="pull-right text-success text-sm">0%</span>
                      <div class="progress progress-hairline">
                        <div class="progress-bar progress-bar-primary-dark" style="width:0%"></div>
                      </div>

                      Desembolsos préstamos (Q. 0.00)
                      <span class="pull-right text-success text-sm">0%</span>
                      <div class="progress progress-hairline">
                        <div class="progress-bar progress-bar-primary-dark" style="width:0%"></div>
                      </div>


                      Retiros (Q. 0.00)
                      <span class="pull-right text-success text-sm">0%</span>
                      <div class="progress progress-hairline">
                        <div class="progress-bar progress-bar-primary-dark" style="width:0%"></div>
                      </div>                                          

                       Anuladas (Q. 0.00)
                      <span class="pull-right text-success text-sm">0%</span>
                      <div class="progress progress-hairline">
                        <div class="progress-bar progress-bar-primary-dark" style="width:0%"></div>
                      </div>

                    </div><!--end .card-body -->
              </div>
              <br>
            </div>
          </div>
      </div>
    </div>

    <div class="contentpanel">
      <h2 style="margin-left:30px;" > Ingresos de caja </h2> 
      <div class="card card-success card-outline">
        <div class="card-header">
            <form id="formIngresos" class="form-inline">

                <div class="row" style="width: 100%;">

                    <div class="form-group col-md-5">
                        <input type="hidden"  class="form-control" id="id_apertura" name="id_apertura" >
                        <input type="text" style="width: 100%;" class="form-control" id="ing_descripcion" name="ing_descripcion" placeholder="Descripción" readonly>
                    </div>
                    <div class="form-group col-md-5">
                        <input type="number" style="width: 100%;" class="form-control" id="in_valor" name="in_valor" placeholder="Valor" readonly>
                    </div>

                    <div class="col-md-2">
                        <button type="button" id="btnNuevoIngreso" class="btn ink-reaction btn-floating-action bg-lightblue" disabled ><i class="fa fa-check"></i></button>
                    </div>
                                    
                </div>
            
            </form>
        </div>
        <div class="card-body">
          <div  class="table-responsive ">
            <table class="table order-column hover" id="tablaIngresos">
              <thead>
                <tr> 
                   <th>No.</th>
                   <th>USUARIO</th>
                   <th>DESCRIPCION</th>
                   <th>VALOR</th>
                   <th></th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    

    <div class="contentpanel">
      <h2 style="margin-left:30px;" > Retiros de caja </h2> 
      <div class="card card-success card-outline">
        <div class="card-header">
            <form id="formRetiros" class="form-inline">

                <div class="row" style="width: 100%;">


                  <div class="col-md-4 row">                

                    <div class="col-md-2 col-sm-4 col-xs-4 col-3">&nbsp;</div>

                    <div class="col-md-8 col-sm-4 col-xs-4 col-6">
                        <div class="image_area">
                        <form method="post">
                            <label for="upload_image">
                            <img src="upload/opcion.png" id="uploaded_image" class="img-responsive img-circle" />
                            <div class="newoverlay">
                                <div class="text">Ver Galería</div>
                            </div>
                                <input type="file" name="image" class="image" id="upload_image" style="display:none" disabled>
                            </label>
                        </form>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-4 col-xs-4 col-3">&nbsp;</div>


                  </div>

                  <div class="col-md-8 row">


                    <div class="form-group col-md-12">
                        <select class="form-control select2-list select2-success" disabled data-dropdown-css-class="select2-success" id="tipoGasto" name="tipoGasto" data-placeholder="Seleccione una opción" style="width: 100%;"  required> 
                        <option value=""> </option>
                        <option value="1">Alimentación </option>
                        <option value="2">Gasolina </option>
                        <option value="3">Servicios de reparación de motocicletas </option>
                        <option value="4">Papelería y útiles </option>
                        <option value="5">Gastos telefónicos </option>
                        <option value="6">Hospedaje </option>
                        <option value="7">Otros gastos </option>
                        <option value="8">Sueldo por faltante de Ruta Cobrador </option>
                        <option value="9">Sueldo por faltante de Ruta Supervisor </option>
                        <option value="10">Sueldo de trabajador </option>
                        </select><div class="form-control-line"></div>
                    </div>




                    <div class="form-group col-md-12">
                        <input type="hidden"  class="form-control" id="idapertura" name="idapertura" placeholder="Descripción">
                        <input type="text" style="width: 100%;" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción" readonly>
                    </div>
                    <div class="form-group col-md-12">
                        <input type="number" style="width: 100%;" class="form-control" id="valor" name="valor" placeholder="Valor" readonly>
                    </div>

                    <div class="col-md-12">

                      <div class="btn-group">
                          <button style='cursor:pointer' class="btn btn-default bg-lightblue tooltip2" id="btnNuevoRetiro" disabled>
                              <span class='tooltiptext'>Guardar</span> 
                              <i class='fa fa-check fa-lg'></i>
                          </button> 
                      </div>   

                      <div id="contEliminarImagen" class="btn-group">
                          <button style='cursor:pointer' class="btn btn-default tooltip2" id="btnEliminarImagen" disabled>
                              <span class='tooltiptext'>Limpiar formulario</span> 
                              <i class='fa fa-trash fa-lg'></i>
                          </button> 
                      </div>      

                    </div>


                  </div>
                                    
                </div>
            
            </form>
        </div>
        <div class="card-body">
          <div  class="table-responsive ">
            <table class="table order-column hover" id="tablaRetiros">
              <thead>
                <tr> 
                   <th>No.</th>
                   <th>USUARIO</th>
                   <th>DESCRIPCION</th>
                   <th>FOTO</th>
                   <th>TIPO</th>
                   <th>VALOR</th>
                   <th></th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="contentpanel">
      <h2 style="margin-left:30px;" > Registro de Cobros  </h2> 
      <div class="card card-success card-outline">
        <div class="card-body">
          <div  class="table-responsive " id="divTablaCobros">
            
          </div>
        </div>
      </div>
    </div>


    <div class="contentpanel">
      <h2 style="margin-left:30px;" > Registro de Préstamos  </h2> 
      <div class="card card-success card-outline">
        <div class="card-body">
          


          <div  class="table-responsive " id="divTablaPrestamos">               
          </div>



        </div>
      </div>
    </div>



    <div class="contentpanel" id="divDetalleVenta" style="display:none;">
        <h2 style="margin-left:30px;" > Detalle de venta  </h2> 
        <div class="card card-success card-outline">
            <div class="card-body">
            <div  id="divTabla" class="table-responsive ">
                <table class="table order-column hover" id="tablaDetVenta">
                    <thead>
                    <tr> 
                        <th>#</th>
                        <th>CANTIDAD</th>
                        <th>CODIGO</th>
                        <th>PRODUCTO</th>
                        <th>PRECIO VENTA</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            </div>
        </div>
    </div>





    <div id="divEliminarRetiro" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
                <div class="modal-header">                  
                <h4 class="modal-title">Eliminar Retiro</h4>

                    <a class="panel-close" data-dismiss="modal" aria-hidden="true">×</a>
                </div>
                <div class="modal-body">
                    <div class="form-group floating-label">
                        <input type="hidden" name="idEliminarRetiro" id="idEliminarRetiro" class="form-control" />
                        <h4>¿Desea eliminar el registro?</h4>
                    </div>
                </div> <!-- /.card-body-->
              <div class="modal-footer">
                  <div class="response"></div>
                  <button type="button" id="btnEliminarRetiro" class="btn btn-danger">Si estoy seguro</button>
                  <button type="button" id="btnCancelarEliminarRetiro" class="btn btn-default" data-dismiss="modal">Cancelar</button>
              </div>
          </div>
      </div>
    </div>



    <div id="divEliminarIngreso" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
                <div class="modal-header">                  
                <h4 class="modal-title">Eliminar Ingreso</h4>

                    <a class="panel-close" data-dismiss="modal" aria-hidden="true">×</a>
                </div>
                <div class="modal-body">
                    <div class="form-group floating-label">
                        <input type="hidden" name="idEliminarIngreso" id="idEliminarIngreso" class="form-control" />
                        <h4>¿Desea eliminar el registro?</h4>
                    </div>
                </div> <!-- /.card-body-->
              <div class="modal-footer">
                  <div class="response"></div>
                  <button type="button" id="btnEliminarIngreso" class="btn btn-danger">Si estoy seguro</button>
                  <button type="button" id="btnCancelarEliminarIngreso" class="btn btn-default" data-dismiss="modal">Cancelar</button>
              </div>
          </div>
      </div>
    </div>



<!--NUEVO CODIGO-->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Imagen de perfil</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            </div>
            <div class="modal-body">
            <div class="img-container">
                <div class="row">
                    <div class="col-md-8">
                        <img src="" id="sample_image" />
                    </div>
                    <div class="col-md-4">
                        <div class="preview"></div>
                    </div>
                </div>
            </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="crop">Aceptar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

            </div>
        </div>
    </div>
</div>
<!--NUEVO CODIGO-->


    
    

    
  </div><!--end .section-body -->
</section>




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
    var rutaActual = ""; 
    var modalActivo = 1;

    
    verficarPermisos();
    function verficarPermisos () {
        $.post("funciones/ws_usuarios.php", {accion:"consultarPermisos" , idmodulo:"9"} ,function(data)
        {
            if(data.resultado){
                Acceso = data.registros[0]["acceso"];
                Crear = data.registros[0]["crear"];
                Modificar = data.registros[0]["modificar"];
                Eliminar = data.registros[0]["eliminar"];
                Consultar = data.registros[0]["consultar"];
                mostrarCajas();
            }
            else
              toastr.warning(data.mensaje,"Info");
        }, "json")
        .fail(function()
        {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });
    }


    $("#ingresocaja").on("change",function(){
      var ingreso = parseFloat($("#ingresocaja").val());
      var saldo = parseFloat($("#saldoactual").val());
      var diferencia = 0;
      if ( ingreso  < 1 ||  $("#ingresocaja").val() == "") { 
        ingreso = 0; 
      }

      diferencia = saldo - ingreso; 
      

      if (diferencia > 0 ) {
        $("#diferencia").css({"color":"#DC6969"});
      }else if(diferencia < 0){
        $("#diferencia").css({"color":"#eea236"});
      }else{
        $("#diferencia").css({"color":"#1CAF9A"});        
      }

      $("#diferencia").val(diferencia.toFixed(2));

    });


    
    function mostrarCajas() {
        $.post("funciones/ws_apertura.php", {accion:"cajas"} ,function(data)
        {
            $("#cajas").html("<option value='0'>Seleccione Caja</option>");
            if(data.resultado){
               
                $.each(data.registros,function(key,value) {
                    $("#formAperturaCaja #cajas").append("<option rel='"+value["estado"]+"' value="+value["id"]+" >Caja: "+value["nombre"]+"</option>");                
                });
            }
            else
              toastr.warning(data.mensaje,"Info");

            /****** MOSTRAR SELECT ********/
            $(".select2-list").select2({ allowClear: false });
            
        }, "json")
        .fail(function()
        {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });
    }
    


    $("#cajas").on("change",function(e){
        e.preventDefault();
        mostrarApertura();
    });



    function mostrarApertura () {
        var idestado = $("#cajas option:selected").attr("rel");
        var idcaja = $("#cajas option:selected").val();
        $("#formRetiros #descripcion").val('');
        $("#formRetiros #valor").val('');
        
        $("#formIngresos #ing_descripcion").val('');
        $("#formIngresos #in_valor").val('');

        if (idcaja == 0) {
          ratPack.refresh();
        }else{
          $.post("funciones/ws_apertura.php", { accion:"aperturas", id:idcaja, estado:idestado } ,function(data)
          {
              if(data.resultado){
                $("#btnMovimientoCaja").removeAttr("disabled",true);
                $("#upload_image").removeAttr("disabled",true);
                $("#tipoGasto").removeAttr("disabled",true);
                $("#btnMovimientoCaja").removeClass("bg-lightblue");
                $("#btnMovimientoCaja").removeClass("bg-navy");
                $("#resumenIngresos").html("");

                if (idestado == 0) {

                  $("#btnMovimientoCaja").attr("rel", "1");
                  $("#btnMovimientoCaja").removeAttr("disabled",false);
                  $("#upload_image").removeAttr("disabled",false);
                  $("#tipoGasto").removeAttr("disabled",true);

                  $("#apertura").removeAttr("readonly");
                  $("#apertura").val(data.registros[0]["ingresocaja"]);
                  //$("#apertura").val("0");
                  $("#fechaapertura").val(data.registros[0]["fechainicio"]);
                  $("#saldoactual").val(data.registros[0]["saldoactual"]);
                  $("#estado").val(data.registros[0]["estadoImpresora"]);
                  $("#btnMovimientoCaja").html(" A P E R T U R A R ");
                  $("#btnMovimientoCaja").addClass("bg-navy");
                  $("#ingresocaja").val('0');

                }else if(idestado == 1){
                
                  $("#btnMovimientoCaja").attr("rel", "0");
                  $("#btnMovimientoCaja").removeAttr("disabled",false);
                  $("#upload_image").removeAttr("disabled",false);
                  $("#tipoGasto").removeAttr("disabled",false);

                  $("#apertura").attr("readonly","readonly");
                  $("#ingresocaja").removeAttr("readonly");
                  $("#apertura").val(data.registros[0]["efectivoinicial"]);
                  $("#fechaapertura").val(data.registros[0]["fechainicio"]);
                  $("#saldoactual").val(data.registros[0]["saldoactual"]);
                  $("#estado").val(data.registros[0]["estadoImpresora"]);

                  $("#btnMovimientoCaja").html(" C E R R A R   -   C A J A ");
                  $("#btnMovimientoCaja").addClass("bg-lightblue");


                  $("#formRetiros #descripcion").removeAttr("readonly");
                  $("#formRetiros #valor").removeAttr("readonly");
                  $("#formRetiros #btnNuevoRetiro").removeAttr("disabled");
                  $("#formRetiros #btnEliminarImagen").removeAttr("disabled");


                  $("#formIngresos #ing_descripcion").removeAttr("readonly");
                  $("#formIngresos #in_valor").removeAttr("readonly");
                  $("#formIngresos #btnNuevoIngreso").removeAttr("disabled");


                }

                saldoactual = data.registros[0]["saldoactual"];
                $("#formRetiros #idapertura").val(data.registros[0]["idapertura"]);
                $("#formIngresos #id_apertura").val(data.registros[0]["idapertura"]);

                var porEfectivoInicial=0;
                var porVentaEfectivo = 0;
                var porAnuladas = 0;
                var porRetiros = 0;
                var porIngresos = 0;
                var porPrestamos = 0;

                
                var total = parseFloat(data.registros[0]["ventaefectivo"]) + parseFloat(data.registros[0]["efectivoinicial"]) + parseFloat(data.registros[0]["ingresos"]) + parseFloat(data.registros[0]["pagoscapital"]);

                if ( parseFloat(data.registros[0]["efectivoinicial"]) > 0 ) {
                  porEfectivoInicial = ( parseFloat(data.registros[0]["efectivoinicial"]) / parseFloat(total)) * 100;
                }

                if ( parseFloat(data.registros[0]["ventaefectivo"]) > 0 ) {
                  porVentaEfectivo   = ( parseFloat(data.registros[0]["ventaefectivo"])   / parseFloat(total)) * 100;
                }

                if ( parseFloat(data.registros[0]["anuladas"]) > 0 ) {
                  porAnuladas        = ( parseFloat(data.registros[0]["anuladas"])         / parseFloat(total)) * 100;
                }

                if ( parseFloat(data.registros[0]["retiros"]) > 0 ) {
                  porRetiros         = ( parseFloat(data.registros[0]["retiros"])         / parseFloat(total)) * 100;
                }

                if ( parseFloat(data.registros[0]["ingresos"]) > 0 ) {
                  porIngresos         = ( parseFloat(data.registros[0]["ingresos"])         / parseFloat(total)) * 100;
                }

                if ( parseFloat(data.registros[0]["prestamos"]) > 0 ) {
                  porPrestamos         = ( parseFloat(data.registros[0]["prestamos"])         / parseFloat(total)) * 100;
                }
                
                
                var resumenIngresos =
                "<div class='card-header'>"+
                  "<h3 class='card-title'>INGRESOS DEL DÍA</h3>"+
                "</div>"+
                "<div class='card-body height-8'>"+
                  " Apertura (Q. "+data.registros[0]["efectivoinicial"]+")"+
                  "<span class='pull-right text-success text-sm'>"+porEfectivoInicial.toFixed(2)+"%'</span>"+
                  "<div class='progress progress-hairline'>"+
                    "<div class='progress-bar progress-bar-primary' style='width:"+parseInt(porEfectivoInicial)+"%'></div>"+
                  "</div>"+

                  " Recaudo (Q. "+  ( parseFloat(data.registros[0]["ventaefectivo"]) + parseFloat(data.registros[0]["pagoscapital"]) )+")"+
                  "<span class='pull-right text-success text-sm'>"+porVentaEfectivo.toFixed(2)+"%</span>"+
                  "<div class='progress progress-hairline'>"+
                    "<div class='progress-bar progress-bar-primary' style='width:"+porVentaEfectivo+"%'></div>"+
                  "</div>"+             
                  
                  " Ingresos (Q. "+data.registros[0]["ingresos"]+")"+
                  "<span class='pull-right text-success text-sm'>"+porIngresos.toFixed(2)+"%</span>"+
                  "<div class='progress progress-hairline'>"+
                    "<div class='progress-bar progress-bar-primary' style='width:"+porIngresos+"%'></div>"+
                  "</div>"+

                  " Desembolsos préstamos (Q. "+data.registros[0]["prestamos"]+")"+
                  "<span class='pull-right text-success text-sm'>"+porPrestamos.toFixed(2)+"%</span>"+
                  "<div class='progress progress-hairline'>"+
                    "<div class='progress-bar progress-bar-primary' style='width:"+porPrestamos+"%'></div>"+
                  "</div>"+

                  " Retiros (Q. "+data.registros[0]["retiros"]+")"+
                  "<span class='pull-right text-success text-sm'>"+porRetiros.toFixed(2)+"%</span>"+
                  "<div class='progress progress-hairline'>"+
                    "<div class='progress-bar progress-bar-primary' style='width:"+porRetiros+"%'></div>"+
                  "</div>"+


                  " Anuladas (Q. "+data.registros[0]["anuladas"]+")"+
                  "<span class='pull-right text-success text-sm'>"+porAnuladas.toFixed(2)+"%</span>"+
                  "<div class='progress progress-hairline'>"+
                    "<div class='progress-bar progress-bar-primary' style='width:"+porAnuladas+"%'></div>"+
                  "</div>"+
                  
                "</div>";
                


                $("<div class='panel-body'></div><br>")
                  .append(resumenIngresos)
                  .appendTo("#resumenIngresos");


                  $("#tablaRetiros  tbody tr").remove();
                  $("#tablaIngresos  tbody tr").remove();



                  
                  $.each(data.retiros,function(key,value) {
                    btnEliminar = ""
            
                    if (Eliminar == 1) {
                      btnEliminar = " <button class='btn btn-default tooltip2' style='cursor:pointer;' href='#' ><span class='tooltiptext'>Anular retiro</span> <i class='fa fa-trash fa-lg '></i></button>";
                    };



                    var tipoGasto = "";
                    if (value["tipogasto"] == 1) {
                      tipoGasto = "Alimentación";
                    }else if (value["tipogasto"] == 2) {
                      tipoGasto = "Gasolina";
                    }else if (value["tipogasto"] == 3) {
                      tipoGasto = "Servicios de reparación de motocicletas ";
                    }else if (value["tipogasto"] == 4) {
                      tipoGasto = "Papelería y útiles";
                    }else if (value["tipogasto"] == 5) {
                      tipoGasto = "Gastos telefónicos";
                    }else if (value["tipogasto"] == 6) {
                      tipoGasto = "Hospedaje";
                    }else if (value["tipogasto"] == 7) {
                      tipoGasto = "Otros gastos";
                    }else if (value["tipogasto"] == 8) {
                      tipoGasto = "Sueldo por faltante de Ruta Cobrador";
                    }else if (value["tipogasto"] == 9) {
                      tipoGasto = "Sueldo por faltante de Ruta Supervisor";
                    }else if (value["tipogasto"] == 10) {
                      tipoGasto = "Sueldo de trabajador";
                    }



                    $("<tr></tr>")
                      .append( "<td>" + (key + 1) + "</td>" )
                      .append( "<td>" + value["nombre"] + "</td>" )
                      .append( "<td>" + value["descripcion"] + "</td>" )
                      .append( "<td> <div class='filtr-item' data-category='1' data-sort='white sample'><a href='"+ value["foto"] +"' data-toggle='lightbox' data-title='Imagen de perfil'><img src='"+ value["foto"] +"' class='img-circle img-size-32 mr-2' alt='white sample'/></a></div> </td>" )

                      .append( "<td>" + tipoGasto + "</td>" )                     
                      .append( "<td>" + value["valor"] + "</td>" )                     
                      .append( $("<td></td>").append( 
                            $("<div class='btn-group'></div>")                                 
                              .append( $( btnEliminar )
                                .on("click", { idretiro:value["id"] } , eliminarRetiro) )                                      
                            )
                        )

                      .appendTo("#tablaRetiros > tbody");
                  });


                  $.each(data.ingresos,function(key,value) {
                    btnEliminar = "";                  

                    if (Eliminar == 1) {
                      btnEliminar = " <button class='btn btn-default tooltip2' style='cursor:pointer;' href='#' ><span class='tooltiptext'>Anular Ingreso</span> <i class='fa fa-trash fa-lg '></i></button>";
                    };

                    $("<tr></tr>")
                      .append( "<td>" + (key + 1) + "</td>" )
                      .append( "<td>" + value["nombre"] + "</td>" )
                      .append( "<td>" + value["descripcion"] + "</td>" )
                      .append( "<td>" + value["valor"] + "</td>" )
                      .append( $("<td></td>").append( 
                            $("<div class='btn-group'></div>")                                 
                              .append( $( btnEliminar )
                                .on("click", { idingreso:value["id"] } , eliminarIngreso) )                                      
                            )
                        )
                      .appendTo("#tablaIngresos > tbody");
                  });


                  $("#tablaCobros  tbody tr").remove();
                  $("#tablaPrestamos  tbody tr").remove();


                  var tabla =  
                  "<table class='table table-sm table-striped' id='tablaCobros'>"+
                    "<thead>"+
                      "<tr> "+
                         "<th>No.</th>"+
                         "<th>USUARIO REGISTRÓ</th>"+
                         "<th>CLIENTE</th>"+
                         "<th>FOTO</th>"+
                         "<th>FECHA PAGO</th>"+
                         "<th>DESCRIPCIÓN</th>"+
                         "<th>CUOTA</th>"+
                         "<th></th>"+
                      "</tr>"+
                    "</thead>"+
                    "<tbody></tbody>"+
                  "</table>";
                  $("#divTablaCobros").html(tabla);

                  var indicePago = 0;

                   $.each(data.registroPagosRealizados,function(key,value) {
                      var dateTime = moment( value["fechapago"] );
                      var full = dateTime.format('LL');        
                      indicePago++;

                    $("<tr></tr>")
                      .append( "<td>" + (indicePago) + "</td>" )
                      .append( "<td>" + value["usuarioRecibio"] + "</td>" )
                      .append( "<td>" + value["nombreCliente"] + "</td>" )
                      .append( "<td> <div class='filtr-item' data-category='1' data-sort='white sample'><a href='"+ value["foto"] +"' data-toggle='lightbox' data-title='Imagen de perfil'><img src='"+ value["foto"] +"' class='img-circle img-size-32 mr-2' alt='white sample'/></a></div> </td>" )                
                      .append( "<td>" + full + "</td>" )
                      .append( "<td>" + value["descripcion"] + "</td>" )
                      .append( "<td> <b> Q." + parseFloat(value["monto"]).toFixed(2) + " </b></td>" )
                      .append( "<td> </td>" )
                      .appendTo("#tablaCobros > tbody");
                  });

                  $.each(data.registropagoscapital,function(key,value) {
                      var dateTime = moment( value["fechapago"] );
                      var full = dateTime.format('LL');                             
                      indicePago++;
                                            
                    $("<tr></tr>")
                      .append( "<td>" + (indicePago) + "</td>" )
                      .append( "<td>" + value["usuarioRecibio"] + "</td>" )
                      .append( "<td>" + value["nombreCliente"] + "</td>" )
                      .append( "<td> <div class='filtr-item' data-category='1' data-sort='white sample'><a href='"+ value["foto"] +"' data-toggle='lightbox' data-title='Imagen de perfil'><img src='"+ value["foto"] +"' class='img-circle img-size-32 mr-2' alt='white sample'/></a></div> </td>" )                
                      .append( "<td>" + full + "</td>" )
                      .append( "<td>" + value["descripcion"] + "</td>" )
                      .append( "<td> <b> Q." + parseFloat(value["monto"]).toFixed(2) + " </b></td>" )
                      .append( "<td> </td>" )
                      .appendTo("#tablaCobros > tbody");
                  });
                                  
                  $("#tablaCobros a").tooltip(); 
                  $("#tablaCobros").DataTable( {
                      "dom": 'T<"clear">lfrtip',
                      "paging": false
                  } );


                  var tablaP =  
                  "<table class='table table-sm table-striped' id='tablaPrestamos'>"+
                    "<thead>"+
                      "<tr> "+
                        "<th>No.</th>"+
                        "<th>Usuario que registró</th>"+
                        "<th>Nombre Cliente</th>"+
                        "<th>Código</th>"+
                        "<th>Fecha Entrega</th>"+
                        "<th>Capital Préstamo</th>"+
                        "<th>Plan</th>"+
                        "<th>Cuotas</th>"+
                        "<th>Capital entregado</th>"+
                      "</tr>"+
                    "</thead>"+
                    "<tbody></tbody>"+
                  "</table>";
                  $("#divTablaPrestamos").html(tablaP);



                  $.each(data.mostrarPrestamos,function(key,value) {
                                
                    $("<tr></tr>")
                      .append( "<td>" + (key + 1) + "</td>" )
                      .append( "<td>" + value["usuarioentrego"] + "</td>" )
                      .append( "<td>" + value["nombreCliente"] + "</td>" )
                      .append( "<td>" + value["codigo"] + "</td>" )
                      .append( "<td>" + value["fechaentregado"] + "</td>" )
                      .append( "<td>Q." + value["prestamo"] + "</td>" )
                      .append( "<td>" + value["cuotas"] + "</td>" )
                      .append( "<td>Q." + value["resumenpagos"] + "</td>" )
                      .append( "<td> <b> Q." + parseFloat(value["capitalEntregado"]).toFixed(2) + " </b></td>" )
                      .appendTo("#tablaPrestamos > tbody");
                  });

                $("#tablaPrestamos a").tooltip(); 
                $("#tablaPrestamos").DataTable( {
                      "dom": 'T<"clear">lfrtip',
                      "paging": false
                  } );


              }
              else{
                toastr.warning(data.mensaje,"Info");
              }

              /****** MOSTRAR SELECT ********/
              $(".select2-list").select2({ allowClear: false });
              
          }, "json")
          .fail(function()
          {
              toastr.error("no se pudo conectar al servidor", "Error Conexión");
          });
        }
    }



    $("#btnMovimientoCaja").on("click",function(e){
      e.preventDefault();
      bloquearPantalla("Espere por favor");
      var btnEstado = $(e.target).closest("button").attr("rel");

      if($("#formAperturaCaja").valid()) {
         
          $.post("funciones/ws_apertura.php", "accion=nueva&idestado="+btnEstado+"&"+$("#formAperturaCaja").serialize() ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");
                desbloquearPantalla();
                 //if (btnEstado == 0) {
                   // window.open("funciones/pdfcierre.php?idaperturaca="+data.idaperturacaja);
                  //}
                setTimeout(function(){ratPack.refresh();},300);
            }
            else{
                toastr.warning(data.mensaje,"Info");
                desbloquearPantalla();
            }
          }, "json")
          .fail(function() {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
            desbloquearPantalla();
          });
      }else{
        desbloquearPantalla();
      }

    });




    
    $("#btnNuevoRetiro").on("click",function(e){
      e.preventDefault();



      var valor = $("#formRetiros #valor").val();
      if(  parseFloat(valor) <= parseFloat(saldoactual)  )
      {
        $.post("funciones/ws_apertura.php", "accion=nuevoretiro&ruta="+rutaActual+"&"+$("#formRetiros").serialize()  ,function(data)
        {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");            
                
                rutaActual = "";
                $('#uploaded_image').attr('src', 'upload/opcion.png');


                mostrarApertura();
            }
            else{
              toastr.warning(data.mensaje,"Info");
            }        
            
        }, "json")
        .fail(function()
        {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });
      }else{          
          toastr.warning("No tiene suficiente efectivo para realizar el retiro","Info");
      }
    });



    
    
    $("#btnNuevoIngreso").on("click",function(){

      if ( $("#formIngresos #in_valor").val() != '' && $("#formIngresos #ing_descripcion").val() != '' ) {            
        
        $.post("funciones/ws_apertura.php?accion=nuevoingreso&"+$("#formIngresos").serialize() ,function(data)
        {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");                  
                mostrarApertura();
            }
            else{
              toastr.warning(data.mensaje,"Info");
            }        
            
        }, "json")
        .fail(function()
        {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });


      }else{
        toastr.warning("Los campos son requeridos","Info");

      }
        
    });







    function eliminarRetiro(e){
       e.preventDefault();
      $("#divEliminarRetiro").modal({backdrop: 'static', keyboard: false});
      $("#idEliminarRetiro").val(e.data.idretiro);
    }

    /****************** MODIFICAR DATOS DEL REGISTRO *******************/
    $("#btnEliminarRetiro").on("click",guardarEliminarRetiro);
    
    function guardarEliminarRetiro(e){
        e.preventDefault();
        $.post("funciones/ws_apertura.php", { id:$("#idEliminarRetiro").val() , accion:"eliminaretiro" } ,function(data) {
          if(data.resultado){
              toastr.success(data.mensaje, "Exito");
              $("#divEliminarRetiro").modal("hide");
              mostrarApertura();
          }
          else{
              toastr.warning(data.mensaje,"Info");
          }
        }, "json")
        .fail(function() {
          toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });
    }

    
  function eliminarIngreso(e){
    e.preventDefault();
    $("#divEliminarIngreso").modal({backdrop: 'static', keyboard: false});
    $("#idEliminarIngreso").val(e.data.idingreso);
  }

  /****************** MODIFICAR DATOS DEL REGISTRO *******************/
  $("#btnEliminarIngreso").on("click",guardarEliminarIngreso);
  
  function guardarEliminarIngreso(e){
      e.preventDefault();
      $.post("funciones/ws_apertura.php", { id:$("#idEliminarIngreso").val() , accion:"eliminarIngreso" } ,function(data) {
        if(data.resultado){
            toastr.success(data.mensaje, "Exito");
            $("#divEliminarIngreso").modal("hide");
            mostrarApertura();
        }
        else{
            toastr.warning(data.mensaje,"Info");
        }
      }, "json")
      .fail(function() {
        toastr.error("no se pudo conectar al servidor", "Error Conexión");
      });
  }



  

///////////////////////////////////////////////////////////////
////////////////////CÓDIGO PARA SUBIR IMAGEN///////////////////
///////////////////////////////////////////////////////////////

var $modal = $('#modal');
    var image = document.getElementById('sample_image');
    var cropper;

    $('#upload_image').change(function(event){
        var files = event.target.files;
        var done = function (url) {
            image.src = url;
            $modal.modal('show');
        };

        if (files && files.length > 0)
        {
        reader = new FileReader();
        reader.onload = function (event) {
            done(reader.result);
        };
        reader.readAsDataURL(files[0]);

        }
    });


    $modal.on('shown.bs.modal', function() {
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 3,
            preview: '.preview'
        });
    }).on('hidden.bs.modal', function() {
        cropper.destroy();
        cropper = null;
    });

    $("#crop").click(function(){

    var rutaTemporal = rutaActual;

    canvas = cropper.getCroppedCanvas({
        width: 400,
        height: 400,
    });

    canvas.toBlob(function(blob) {

        var reader = new FileReader();
        reader.readAsDataURL(blob); 
        reader.onloadend = function() {
            var base64data = reader.result;  
            
            $.ajax({
                url: "modulos/upload.php",
                method: "POST",                	
                data: {image: base64data},
                success: function(data){

                    var nuevaruta = data.substr(3, data.length);

                    rutaActual = nuevaruta;


                    $modal.modal('hide');

                    if(modalActivo == 1){
                        $('#uploaded_image').attr('src', nuevaruta);
                    }else if(modalActivo == 2){
                        $('#uploaded_image_2').attr('src', nuevaruta);
                    }
                    
                }
                });
        }
    });


    if(rutaTemporal != ''){
        $.post("funciones/ws_clientes.php", { accion:"eliminarImagen", ruta:rutaTemporal } ,function(data) {
        if(data.resultado){
            //console.log(data.mensaje);
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





    $("#btnEliminarImagen").click(function(e){  
      e.preventDefault();


        $("#formRetiros #descripcion").val('');
        $("#formRetiros #valor").val('');

        if(rutaActual != ''){
        $.post("funciones/ws_clientes.php", { accion:"eliminarImagen", ruta:rutaActual } ,function(data) {
        if(data.resultado){
            //console.log(data.mensaje);

            rutaActual = "";
            $('#uploaded_image').attr('src', 'upload/opcion.png');

          
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






///////////////////////////////////////////////////////////////
////////////////////CÓDIGO PARA SUBIR IMAGEN///////////////////
///////////////////////////////////////////////////////////////






  });
</script>