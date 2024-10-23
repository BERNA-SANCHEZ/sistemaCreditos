<?php
session_start();
require_once ("../funciones/classSQL.php");
$conexion = new conexion();
if($conexion->permisos($_SESSION['idtipousuario'],"8","acceso"))
{
?>


<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h2 class='titulo'>Planilla</h2>
            </div>
           
        </div>
    </div><!-- /.container-fluid -->
</section>


<!-- Main content -->
<section class="content">
    <div class="card">
        
        <!-- /.card-header -->
        <div class="card-body" style="overflow-x: scroll;">
           
            <div  class="table-responsive " >
                <table id="tablaUsuarios" class="table table-striped" >
                    <thead>
                    <tr>  
                        <th>No.</th>
                        <th>TIPO USUARIO</th>                        
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


<br>
<br>

<!-- Main content -->
<section id="divTablaDetalle" class="content" style="display:none;">
    <div class="card">         


        <div class="card-header">
            
            <?php if($conexion->permisos($_SESSION['idtipousuario'],"2","crear")) { ?>
                <!--Pago a los cobradores y supervisores-->
                <button type="button" id="btnNuevoPago" data-toggle="modal" class="btn bg-navy btn-lg">Nuevo Pago</button>
            <?php } ?>
                
        </div>


        <div class="card-body">
            <div  id="divtablaRegistroPagos" class="table-responsive " >

            <h3 id='tituloPagos' class='tituloH3'></h3><br>


            </div>
        </div>
    <!-- /.card-body -->
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->




<div id="divNuevoPago" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: auto;">
    <div class="modal-dialog modal-lg">
        <form id='formNuevoPago' class="form form-validate"  role="form"   method="post" >
            <input type="hidden" class="form-control" name="idempleado" id="idempleado" />
            <input type="hidden" class="form-control" name="idtipousuario" id="idtipousuario" />
            <input type="hidden" class="form-control" name="nameUser" id="nameUser" />


            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title" id="titleNuevoPago">Nuevo Pago</h4>


                &nbsp;&nbsp;

                <div class="form-group row" style="margin-left: 50px;">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Hasta</label>
                    <div class="col-sm-10">
                   
                    
                        <div class="input-group date" id="timepicker2" data-target-input="nearest">
                            <input type="text" class="form-control  datetimepicker-input" name="fechainicio" id='fechainicio' data-target="#timepicker2" required value=<?php echo date("Y-m-d") ?> />
                            <div class="input-group-append" data-target="#timepicker2" data-toggle="datetimepicker">
                                <div class="input-group-text" ><i class="far fa-calendar"></i></div>
                            </div>
                        </div>



                    </div>
                </div>
                &nbsp;&nbsp;


                <div class="btn-group">
                    <button type="button" style="cursor: pointer;" id="btnCalcular" class="btn btn-default tooltip2">
                        <span class='tooltiptext'>Calcular</span> 
                        <i class="fa fa-eye fa-lg"></i>
                    </button>
                </div>




                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body">


                    <div id="contenedorTablas"></div>


                    

                    <div class="card card-outline card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Tabla de cuotas y moras atrasadas</h3>

                            <div class="card-tools">
                                <button type="button" id="btnMaximizar3" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div id="divtablaRegistroPrestamos" class="card-body table-responsive" style="display: block;">
                        </div>
                    </div>


                    <div class="row">


                        <div class="col-12">
                            <div class="mailbox-controls with-border text-center">
                                <h5 class="text-success"><i class="fas fa-plus-circle"></i> PAGOS</h5>     
                                
                                <div class="btn-group">
                                    <button type="button" id="configurarvalores" class="btn btn-default tooltip2"><span class='tooltiptext'>Configurar valores</span><i class="fas fa-cog"></i></button>
                                </div>

                            </div>
                        </div>


                
                        <div class="form-group col-md-6">
                            <label for="pagoclientesnuevos" class="tooltip2" id="tittleICN">Incentivo por clientes nuevos:</label>

                            <div class="input-group">
                                <div class="input-group-prepend">

                                    <span class="input-group-text" style="padding: 0px 0px 0px 10px;">

                                        <div class="icheck-success">
                                            <input type="checkbox" id="btnPagoCN" checked>
                                            <label for="btnPagoCN"></label>
                                        </div>

                                    </span>


                                </div>

                                <input type="number" class="form-control " id="pagoclientesnuevos" name="pagoclientesnuevos" >

                                <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div>

                            </div>

                            <div class="progress-group" id="contenedorprogress1">
                            

                            </div>




                        </div>

                        <div class="form-group col-md-6">

                            <label for="pagorenovaciones" class="tooltip2" id="tittleIPR">Incentivo por prestamos renovados:</label>


                            <div class="input-group">
                                <div class="input-group-prepend">

                                     <span class="input-group-text" style="padding: 0px 0px 0px 10px;">

                                        <div class="icheck-success">
                                            <input type="checkbox" id="btnPagoCR">
                                            <label for="btnPagoCR"></label>
                                        </div>

                                    </span>

                                </div>

                                <input type="number" class="form-control" id="pagorenovaciones" name="pagorenovaciones" >

                                <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div>

                            </div>


                            <div class="progress-group" id="contenedorprogress2">

                            </div>




                        </div>


                        <div class="form-group col-md-6">
                            <label for="pagosueldobase">Pago sueldo base:</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Q</span>
                                </div>

                                <input type="number" class="form-control " id="pagosueldobase" name="pagosueldobase" >

                                <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div>

                            </div>


                        </div>


                        <div class="form-group col-md-6">

                            <label for="depreciacion" >Por depreciación:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                     <span class="input-group-text" style="padding: 0px 0px 0px 10px;">
                                        <div class="icheck-success">
                                            <input type="checkbox" id="btnDepreciacion">
                                            <label for="btnDepreciacion"></label>
                                        </div>
                                    </span>
                                </div>

                                <input type="number" class="form-control" id="depreciacion" name="depreciacion" value="250">

                                <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div>

                            </div>

                        </div>

                        <div class="form-group col-md-6"> 

                        </div>



                        <div class="form-group col-md-6">
                            <label for="subtotalpagos">SUBTOTAL PAGOS:</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Q</span>
                                </div>

                                <input type="number" class="form-control " id="subtotalpagos" name="subtotalpagos" readonly>

                                <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div>

                            </div>


                        </div>


                        <div class="col-12">
                            <div class="mailbox-controls with-border text-center">
                                <h5 class="text-danger"><i class="fas fa-minus-circle"></i> DESCUENTOS</h5>                                
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="descuentoPorCuotasMoras" class="tooltip2" id="tittleDCMP">Descuento por cuotas y moras pendientes:</label>

                            <div class="input-group">
                                <div class="input-group-prepend">

                                    <span class="input-group-text" style="padding: 0px 0px 0px 10px;">

                                        <div class="icheck-success">
                                            <input type="checkbox" id="btnDescuentoCM" checked>
                                            <label for="btnDescuentoCM"></label>
                                        </div>

                                    </span>


                                </div>

                                <input type="number" class="form-control " id="descuentoPorCuotasMoras" name="descuentoPorCuotasMoras" readonly>

                                <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div>

                            </div>

                        </div>

                        
                        <div class="form-group col-md-6">

                            <label for="otrosDescuentos" >Otros descuentos:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="padding: 0px 0px 0px 10px;">
                                        <div class="icheck-success">
                                            <input type="checkbox" id="btnotrosDescuentos">
                                            <label for="btnotrosDescuentos"></label>
                                        </div>
                                    </span>
                                </div>

                                <input type="number" class="form-control" id="otrosDescuentos" name="otrosDescuentos" value="0">

                                <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div>

                            </div>

                        </div>

                        <div class="form-group col-md-6"> 

                        </div>



                        <div class="form-group col-md-6">
                            <label for="subtotaldescuentos">SUBTOTAL DESCUENTOS:</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Q</span>
                                </div>

                                <input type="number" class="form-control " id="subtotaldescuentos" name="subtotaldescuentos" readonly>

                                <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div>

                            </div>


                        </div>

                        <div class="col-12">
                            <div class="mailbox-controls with-border text-center">
                                <h5 class="text-success"><i class="fas fa-check-circle"></i> TOTAL A PAGAR</h5>                                
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="newDescripcion">Descripción:</label>
                            <input type="text" class="form-control" id="newDescripcion" name="newDescripcion" placeholder="Ingrese Descripción" required >                        
                        </div>

                        <div class="form-group col-md-6">
                            <label for="totalliquido">TOTAL LIQUIDO:</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Q</span>
                                </div>

                                <input type="number" class="form-control" id="totalliquido" name="totalliquido" style="border-color: #28a745;" readonly>

                                <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div>

                            </div>


                        </div>
            
                    </div>
                                    
                    
                </div>

                <div class="modal-footer">
                    <div class="response"></div>
                    <button type="button" id="btnGuardarNuevoPago" class="btn bg-navy">Generar pago</button>
                    <button type="button" id="btnCancelarNuevoPago" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>




<div id="divConfigurarValores" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: auto;">
    <div class="modal-dialog">
        <form id='formConfigurarValores' class="form form-validate"  role="form"   method="post" >

        <input type="hidden" class="form-control" name="idImprimirPrestamo" id="idImprimirPrestamo" />



            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Configurar valores</h4>

                &nbsp;&nbsp;

                <div id="contenedorRestaurarvalores"></div>



                </div>

                <div class="card-body row">



                    <div class="col-md-12">
                        <label for="nclientenuevo">Incentivo de cliente nuevo colocado:</label>
                    </div>

                    <div class="form-group col-md-5">

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Q</span>
                            </div>

                            <input type="number" class="form-control " id="nclientenuevo" name="nclientenuevo" >

                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>

                        </div>


                    </div>

                    <div class="col-md-2">
                        <label for="m" style="margin-top: 5px;">por cada</label>
                    </div>

                    <div class="form-group col-md-5">

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Q</span>
                            </div>

                            <input type="number" class="form-control " id="mclientenuevo" name="mclientenuevo" >

                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>

                        </div>

                    </div>




                    
                    <div class="col-md-12">
                        <label for="nclienterenovado">Incentivo de cliente renovado:</label>
                    </div>



                    <div class="form-group col-md-5">

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Q</span>
                            </div>

                            <input type="number" class="form-control " id="nclienterenovado" name="nclienterenovado" >

                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>

                        </div>


                    </div>

                    <div class="col-md-2">
                        <label for="m" style="margin-top: 5px;">por cada</label>
                    </div>

                    <div class="form-group col-md-5">

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Q</span>
                            </div>

                            <input type="number" class="form-control " id="mclienterenovado" name="mclienterenovado" >

                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>

                        </div>

                    </div>





                    <div class="input-group input-group-sm col-md-12">

                        <label for="">Aproximación de total de incentivos</label>
                                                                                                            

                        <div class="form-group clearfix row" style="width: 100%;">
                            <div class="icheck-success d-inline col-md-6">
                                <input type="radio" name="r3" id="aproximacionBasica">
                                <label for="aproximacionBasica">
                                    Aproximación Básica
                                </label>
                            </div>
                            <div class="icheck-success d-inline col-md-6">
                                <input type="radio" name="r3" checked="" id="aproximacionExacta">
                                <label for="aproximacionExacta">
                                    Aproximación Exacta
                                </label>
                            </div>                                                       
                        </div>        
                    </div>


                    <div class="form-group col-md-12" style="margin-bottom: 0px;">
                        <label>Porcentaje de descuento por cuotas y moras pendientes:</label>
                        
                        <div id="contenedorRangeConfig"></div>

                       <br>

                        <div class="input-group mb-3">
                            <input type="number" id="descuentoconfigdeudas" name="descuentoconfigdeudas" class="form-control" readonly="">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-percent"></i></span>
                            </div>
                        </div>


                    
                    </div>

                

                </div>

                <div class="modal-footer">
                    <div class="response"></div>     

                    <div id="contenedorBotonConfigurarValores"></div>                    

                </div>
            </div>
        </form>  
    </div>
</div>



<div id="divNuevoDatosPago" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form id='formNuevoDatosPago' class="form form-validate"  role="form"   method="post" >
            <input type="hidden" class="form-control" name="idusuario" id="idusuario" />
            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Datos de pago</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body row">


                    <div class="form-group col-md-12">
                        <label for="sueldobase">Sueldo base:</label>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Q</span>
                            </div>

                            <input type="number" class="form-control " id="sueldobase" name="sueldobase" >

                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>

                        </div>


                    </div>

                    <div class="form-group col-md-12">
                        <label for="metaclientesnuevos">Meta por clientes nuevos:</label>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Q</span>
                            </div>

                            <input type="number" class="form-control " id="metaclientesnuevos" name="metaclientesnuevos" >

                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>

                        </div>


                    </div>

                    <div class="form-group col-md-12">
                        <label for="metarenovaciones">Meta por clientes que renuevan:</label>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Q</span>
                            </div>

                            <input type="number" class="form-control" id="metarenovaciones" name="metarenovaciones" >

                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>

                        </div>


                    </div>

                    <div class="form-group col-md-12" style="margin-bottom: 0px;">
                        <label for="descuentodeudas">Porcentaje de descuento por cuotas y moras pendientes:</label>

                        <div id="contenedorRange"></div>                                                    
                        <br>

                        <div class="input-group mb-3">
                            <input type="number" id="descuentodeudas" name="descuentodeudas" class="form-control" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-percent"></i></span>
                            </div>
                        </div>


                    
                    </div>



                    <div class="col-md-12">
                        <label for="n">Incentivo:</label>
                    </div>



                    <div class="form-group col-md-5">

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Q</span>
                            </div>

                            <input type="number" class="form-control " id="n" name="n" >

                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>

                        </div>


                    </div>

                    <div class="col-md-2">
                        <label for="m" style="margin-top: 5px;">por cada</label>
                    </div>

                    <div class="form-group col-md-5">

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Q</span>
                            </div>

                            <input type="number" class="form-control " id="m" name="m" >

                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>

                        </div>

                    </div>
                    
                </div>

                <div class="modal-footer">
                    <div class="response"></div>
                    <button type="button" id="btnGuardarNuevoDatosPago" class="btn bg-navy">Guardar</button>
                    <button type="button" id="btnCancelarNuevoDatosPago" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>



<div id="divDetallleDescuentos" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: auto;">
    <div class="modal-dialog modal-lg">
        <form id='formDetallleDescuentos' class="form form-validate"  role="form"   method="post" >
            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Detalle descuentos</h4>




                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body">

                                    
                    <div  class="table-responsive " >
                        <table id="tablaDetallleDescuentos" class="table table-striped" style="width: 100%;">
                            <thead>
                            <tr>  
                                <th>No.</th>
                                <th>NOMBRE CLIENTE</th>
                                <th>DESCRIPCIÓN</th>
                                <th>MONTO</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <br>

                    <h3 id="h3Total" >Total</h3> 

                </div>

                <div class="modal-footer">
                    <div class="response"></div>                    
                    <button type="button" id="btnCancelarDetallleDescuentos" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>

<div id="divEliminarPago" class="modal fade show" aria-modal="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar Pago</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label class="col-form-label" for="claveAnulacionPago"><i class="far fa-bell"></i> Contraseña de anulación </label>
                    <input type="password" class="form-control is-invalid form-control-lg" autocomplete="off" id="claveAnulacionPago" >
                </div>


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



var arrayPrestamosNuevos = Array();
var arrayPrestamosRenovados = Array();
var prestamos_pendientes = Array();
var descuentoPorDeuda = 0;

  $(document).ready(function() {

    var Acceso = 0;
    var Crear = 0;
    var Modificar = 0;
    var Eliminar = 0;
    var Consultar = 0;


    verficarPermisos();


    $('#timepicker2').datetimepicker({
        pickTime: false, format: 'YYYY-MM-DD'
    });



    
    function verficarPermisos () {
        $.post("funciones/ws_usuarios.php", {accion:"consultarPermisos" , idmodulo:"8"} ,function(data)
        {
            if(data.resultado){
                Acceso = data.registros[0]["acceso"];
                Crear = data.registros[0]["crear"];
                Modificar = data.registros[0]["modificar"];
                Eliminar = data.registros[0]["eliminar"];
                Consultar = data.registros[0]["consultar"];
                actualizarPrestamos();

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



    function actualizarPrestamos() {

        bloquearPantalla("Espere por favor");

        $.post("funciones/ws_prestamos.php", { accion:"actualizarPrestamosActivos" } ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");               
                desbloquearPantalla();
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
        
    }
    
    
    function mostrarUsuarios () {
      $("#tablaUsuarios  tbody tr").remove();
      $.post("funciones/ws_planilla.php", { accion: "mostrarCobradorSupervisor" }, function(data) {
        if(data.resultado)
          {

            var btnEditar = "";
            var btnEliminar = "";
            var btnPermisos = "";
            var btnConsultar = "";


            $.each(data.registros,function(key,value) {
              var activado ="Activado";
              if (value["activado"] == 0) {
                var activado ="Desactivado";
              }

              if (Modificar == 1) {
                btnEditar = " <button class='btn btn-default tooltip2' style='cursor:pointer; ' href='#' ><span class='tooltiptext'>Datos de pago</span><i class='fa fa-edit fa-lg '></i></button>";
              };

              if (Consultar == 1) {
                btnConsultar = " <button class='btn btn-default bg-lightblue tooltip2' style='cursor:pointer' href='#' ><span class='tooltiptext'>Detalle de pagos</span> <i class='fa fa-money-bill fa-lg '></i></button>";
              };


              $("<tr  rel='"+value["id"]+"'></tr>")
                .append( "<td>" + (key + 1) + "</td>" )
                .append( "<td>" + value["tipousuario"] + "</td>" )               
                .append( "<td>" + value["nombre"] + "</td>" )
                .append( "<td>" + activado + "</td>" )
               .append( $("<td></td>").append( 
                $("<div class='btn-group'></div>") 
                        
                    .append( $(btnConsultar)
                        .on("click",{ idusuario:value["id"] , nombreUsuario: value["nombre"], idtipousuario:value["idtipousuario"] } , mostrarRegistroPagos) ) 

                    .append( $(btnEditar)
                        .on("click",{ idusuario:value["id"] } , editarDatos) ) 
                        
                    )
                  )
                .appendTo("#tablaUsuarios > tbody");
            });

                $("#tablaUsuarios").DataTable({ 

                initComplete: function() {
                    $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
                },
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
                                columns: [ 0, 1, 2, 3 ]
                            }

                        },

                        {
                               extend: 'csv', 
                          orientation: 'Portrait',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            ,exportOptions: {
                                columns: [ 0, 1, 2, 3 ]
                            }

                        },
                       
                        {
                               extend: 'excel', 
                          orientation: 'Portrait',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            ,exportOptions: {
                                columns: [ 0, 1, 2, 3 ]
                            }

                        },
                        
                        {
                               extend: 'pdf', 
                          orientation: 'Portrait',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            ,exportOptions: {
                                columns: [ 0, 1, 2, 3 ]
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

    
    function editarDatos(e) {
        e.preventDefault();
    
        $("#formNuevoDatosPago")[0].reset();
        $("#formNuevoDatosPago #idusuario").val(e.data.idusuario);
        $("#divNuevoDatosPago").modal("show", {backdrop: "static"});


        $.getJSON("funciones/ws_planilla.php", { accion:"mostrar" , idusuario:e.data.idusuario }, function(data) {
          if(data.resultado)
            {
            
                $("#formNuevoDatosPago #sueldobase").val(data.registros[0]["sueldobase"]);
                $("#formNuevoDatosPago #metaclientesnuevos").val(data.registros[0]["metaclientesnuevos"]);
                $("#formNuevoDatosPago #metarenovaciones").val(data.registros[0]["metarenovaciones"]);
                $("#formNuevoDatosPago #descuentodeudas").val(data.registros[0]["descuentodeudas"]);
                $("#formNuevoDatosPago #n").val(data.registros[0]["n"]);
                $("#formNuevoDatosPago #m").val(data.registros[0]["m"]);            
                $("#contenedorRange").html("<input id='range_DD' type='text' name='range_DD' value='"+data.registros[0]["descuentodeudas"]+";100'>");        

                $('#range_DD').ionRangeSlider({
                min     : 0,
                max     : 100,
                type    : 'single',
                step    : 5,
                postfix : ' %',
                prettify: false,
                hasGrid : true,

                    onChange: function (data) {
                        $("#formNuevoDatosPago #descuentodeudas").val($('#range_DD').val());
                    }
                });


            }else{

                $("#contenedorRange").html("<input id='range_DD' type='text' name='range_DD' value='0;100'>");

                $('#range_DD').ionRangeSlider({
                min     : 0,
                max     : 100,
                type    : 'single',
                step    : 5,
                postfix : ' %',
                prettify: false,
                hasGrid : true,

                    onChange: function (data) {
                        $("#formNuevoDatosPago #descuentodeudas").val($('#range_DD').val());
                    }
                });


            }
        }, "json")
        .fail(function()
        {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });




    }



    function mostrarRegistroPagos (e) {
      e.preventDefault();

      descuentoPorDeuda = 0;

      $("#divTablaDetalle").fadeIn("slow");
      
      $("#tablaRegistroPagos  tbody tr").remove();

      $("#divtablaRegistroPagos").fadeIn("fast");

      $("#formNuevoPago #idempleado").val(e.data.idusuario);  
      $("#formNuevoPago #idtipousuario").val(e.data.idtipousuario);  
      $("#formNuevoPago #nameUser").val("Pagado por: "+e.data.nombreUsuario);  
      $("#titleNuevoPago").html("Nuevo pago a: <b>"+e.data.nombreUsuario+" </b>");  


      $.post("funciones/ws_planilla.php", { accion: "mostrarPagosPlanilla" , idusuario : e.data.idusuario}, function(data) {
        if(data.resultado)
          { 


            var tabla =
            "<h3 class='tituloH3'>Historial de pagos a: <b>"+e.data.nombreUsuario+"</b></h3><br>"+
            "<table id='tablaPagos' class='table table-striped' >"+
                "<thead>"+
                "<tr>  "+
                    "<th>No.</th>"+
                    "<th>Sueldo base</th>"+
                    "<th>Incentivo por clientes nuevos</th>"+
                    "<th>Incentivo por prestamos renovados</th>"+
                    "<th>depreciación</th>"+
                    "<th>Subtotal pagos</th>"+
                    "<th>Descuento por cuotas y moras pendientes</th>"+
                    "<th>Otros descuentos</th>"+
                    "<th>Subtotal descuentos</th>"+
                    "<th>Descripción</th>"+
                    "<th>Fecha de pago</th>"+
                    "<th>TOTAL LIQUIDO</th>"+
                    "<th></th>"+
                "</tr>"+
                "</thead>"+
                "<tbody></tbody>"+
            "</table>";
            $("#divtablaRegistroPagos").html(tabla);



            if (Consultar == 1) {
                btnConsultar = " <button class='btn btn-default bg-lightblue tooltip2' style='cursor:pointer' href='#' ><span class='tooltiptext'>Ver descuento por cuotas y moras pendientes</span> <i class='fa fa-eye fa-lg '></i></button>";
              };

              if (Eliminar == 1) {
                btnEliminar = " <button class='btn btn-default tooltip2' pCuota='1' style='cursor:pointer;' href='#' ><span class='tooltiptext'>Eliminar Pago</span> <i class='fa fa-trash fa-lg '></i></button>";                        
                
              };

            $.each(data.registros,function(key,value) {     
                
            
                if (key == 0 && value["totalliquido"] < 0) {
                    descuentoPorDeuda = parseFloat(value["totalliquido"]).toFixed(2);
                }else{
                    descuentoPorDeuda = 0;
                }
                         
                var dateTime = moment( value["fecha_pago"] );
                var full = dateTime.format('LL');

                $("<tr></tr>")
                .append( "<td >" + (key + 1) + "</td>" )
                .append( "<td >Q." + parseFloat(value["sueldobase"]).toFixed(2) + "</td>" )
                .append( "<td >Q." + parseFloat(value["incentivoclientesnuevos"]).toFixed(2) + "</td>" )
                .append( "<td >Q." + parseFloat(value["incentivorenovaciones"]).toFixed(2) + "</td>" )
                .append( "<td >Q." + parseFloat(value["depreciacion"]).toFixed(2) + "</td>" )
                .append( "<td >Q." + parseFloat(value["subtotalpagos"]).toFixed(2) + "</td>" )
                .append( "<td >Q." + parseFloat(value["descuentoPorCuotasMoras"]).toFixed(2) + "</td>" )
                .append( "<td >Q." + parseFloat(value["otrosDescuentos"]).toFixed(2) + "</td>" )
                .append( "<td >Q." + parseFloat(value["subtotaldescuentos"]).toFixed(2) + "</td>" )
                .append( "<td >" + value["observaciones"] + "</td>" )                
                .append( "<td >" + full + "</td>" )
                .append( "<td ><b>Q." + parseFloat(value["totalliquido"]).toFixed(2) + "</b></td>" )

                .append( $("<td></td>").append( 
                $("<div class='btn-group'></div>") 
                        
                    .append( $(btnConsultar)
                        .on("click",{ idplanilla:value["id"] } , mostrarPagosPrestamoPendientes) )

                        .append( $(btnEliminar)
                        .on("click",{ idplanilla:value["id"] } , eliminarPago) )       


                        
                    )
                  )

                  
                .appendTo("#tablaPagos > tbody");

            });

            $("#tablaPagos").DataTable({
                initComplete: function() {
                    $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
                }
            });
            $('#tablaPagos').ScrollTo();   


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


    function mostrarPagosPrestamoPendientes(e) {
      e.preventDefault();
      $("#divDetallleDescuentos").modal("show", {backdrop: "static"});


      $("#tablaDetallleDescuentos  tbody tr").remove();
        $.post("funciones/ws_planilla.php", { accion: "mostrarDetallleDescuentos", idplanilla:e.data.idplanilla }, function(data) {
        if(data.resultado)
            {

                if ( $.fn.dataTable.isDataTable( '#tablaDetallleDescuentos' ) ) {
                    $("#tablaDetallleDescuentos").DataTable().destroy();
                    $("#tablaDetallleDescuentos  tbody tr").remove();
                }


                
            var total = 0;

            $.each(data.registros,function(key,value) {

                total += parseFloat(value["monto"]);
                  
                $("<tr></tr>")
                .append( "<td>" + (key + 1) + "</td>" )
                .append( "<td>" + value["nombreCliente"] + "</td>" )
                .append( "<td>" + value["descripcion"] + "</td>" )
                .append( "<td>Q. " + parseFloat(value["monto"]).toFixed(2) + "</td>" )                
                .appendTo("#tablaDetallleDescuentos > tbody");

            });

            
            $("#h3Total").html("Total <b> Q. "+total.toFixed(2)+"</b>");
            $("#tablaDetallleDescuentos").DataTable({ 

                initComplete: function() {
                    $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
                },

                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.childRowImmediate,
                        type: 'none',
                        target: ''
                    }
                },
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

    /******************  MUESTRA EL FORMULARIO PARA ELIMINAR LOS REGISTROS *******************/
    function eliminarPago (e) {
      e.preventDefault();
      $("#divEliminarPago").modal("show", {backdrop: "static"});
      $("#ideliminarPago").val(e.data.idplanilla);    
      $("#claveAnulacionPago").val('');

    }


    $("#btnEliminarPago").on("click",guardarEliminarPago);
    
    function guardarEliminarPago(e){
        e.preventDefault();

        var claveAnulacionPago = $("#claveAnulacionPago").val();

        if( claveAnulacionPago.trim() == "123" || claveAnulacionPago.trim() == "123" ){                
    


            $.post("funciones/ws_planilla.php", { 
                idplanilla:$("#ideliminarPago").val() , 
                accion:"eliminarPagoEmpleado",
                idtipousuario: $("#formNuevoPago #idtipousuario").val()
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


        }else{
            toastr.error("Contraseña incorrecta para realizar anulación", "Error de Contraseña");
        }

    }





    
    /****************** MOSTRAR MODAL NUEVO REGISTRO *******************/
    $("#btnNuevoPago").on("click",mostrarModalNuevoPago);
    $("#btnCalcular").on("click",mostrarModalNuevoPago);


    $("#formNuevoPago #fechainicio").blur(function(){

        setTimeout(function(){
            $("#btnCalcular").trigger("click");
        }, 500);

    });
    
    function mostrarModalNuevoPago(e){
        e.preventDefault();

        var capturarFechaSeleccionada = $("#formNuevoPago #fechainicio").val();

        $("#formNuevoPago")[0].reset();       
        $("#formConfigurarValores")[0].reset();

        $("#formNuevoPago #fechainicio").val(capturarFechaSeleccionada);


        if (descuentoPorDeuda < 0) {
            descuentoPorDeuda *= -1;            
        }

        if (descuentoPorDeuda > 0) {
            $("#btnotrosDescuentos").prop("checked", true);
        }else{
            $("#btnotrosDescuentos").prop("checked", false);
        }


        $("#formNuevoPago #otrosDescuentos").val( descuentoPorDeuda );
        $("#divNuevoPago").modal("show", {backdrop: "static"});


        $("#contenedorTablas").html('');
        $("#contenedorBotonConfigurarValores").html('');
        $("#contenedorRestaurarvalores").html('');


        arrayPrestamosNuevos = [];
        arrayPrestamosRenovados = [];
        prestamos_pendientes = [];


        $.post("funciones/ws_planilla.php", { 
            accion: "mostrarPrestamosPendientes" , 
            idusuario : $("#formNuevoPago #idempleado").val(),
            idtipousuario: $("#formNuevoPago #idtipousuario").val(),
            fechainicio:$("#formNuevoPago #fechainicio").val(),
            }, function(data) {
            if(data.resultado)
            { 


                var sumaPrestamoNuevo = 0;
                var sumaPrestamoRenovado = 0;
                var sumaDescuento = 0;

                
                $("#contenedorTablas").html('<div class="card card-outline card-success">'+
                '                        <div class="card-header">'+
                '                            <h3 class="card-title">Tabla primer préstamo por cliente nuevo</h3>'+                
                '                            <div class="card-tools">'+
                '                                <button id="btnMinimizar1" type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>'+
                '                                </button>'+
                '                            </div>'+
                '                        </div>'+
                '                        <div id="divtablaRegistroNuevosPrestamos" class="card-body table-responsive" style="display: block;">'+
                '                        </div>'+
                '                    </div>'+                                    
                '                    <div class="card card-outline card-success">'+
                '                        <div class="card-header">'+
                '                            <h3 class="card-title">Tabla de préstamos renovados</h3>'+                
                '                            <div class="card-tools">'+
                '                                <button id="btnMinimizar2" type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>'+
                '                                </button>'+
                '                            </div>'+
                '                        </div>'+
                '                        <div id="divtablaRegistroRenovadosPrestamos" class="card-body table-responsive" style="display: block;">'+
                '                        </div>'+
                '                    </div>');


                
                ///////////////////////////TABLA PRIMER PRÉSTAMO POR CLIENTE NUEVO///////////////////////////
                var tabla =
                "<table id='tablaRegistroNuevos' class='table table-striped table-sm' style='font-size: 13px;'>"+
                "<thead>"+
                    "<tr>  "+
                    "<th>Código</th>"+
                    "<th>Nombre cliente</th>"+
                    "<th>Foto</th>"+
                    "<th>Estado</th>"+
                    "<th>Cobrador</th>"+
                    "<th>Capital préstamo</th>"+
                    "</tr>"+
                "</thead>"+
                "<tbody></tbody>"+
                "</table>";
                $("#divtablaRegistroNuevosPrestamos").html(tabla);


                $.each(data.prestamosNuevos,function(key,value) {

                    arrayPrestamosNuevos.push( value["id"] );
                
                    var estadoPrestamo = "";

                    if (value["estado"] == 0) {
                        estadoPrestamo = '<span class="badge badge-secondary">FINALIZADO</span>';                    
                    }else if ( value["cuotasPendientes"] == 0 && value["morasPendientes"] == 0) {
                        estadoPrestamo = '<span class="badge bg-success">AL DÍA</span>';
                    }else if( value["cuotasPendientes"] <= 2){
                        estadoPrestamo = '<span class="badge bg-warning">PENDIENTES: <br> '+value["cuotasPendientes"]+' CUOTAS Y '+value["morasPendientes"]+' MORAS</span>';
                    }else{
                        estadoPrestamo = '<span class="badge bg-danger">PENDIENTES: <br> '+value["cuotasPendientes"]+' CUOTAS Y '+value["morasPendientes"]+' MORAS</span>';
                    }

                    sumaPrestamoNuevo += parseFloat(value["prestamo"]);

                $("<tr></tr>")
                    .append( "<td>" + value["codigo"] + "</td>" )
                    .append( "<td> " + value["nombreCliente"] + " </td>" )
                    .append( "<td> <div class='filtr-item' data-category='1' data-sort='white sample'><a href='"+ value["foto"] +"' data-toggle='lightbox' data-title='Imagen de perfil'><img src='"+ value["foto"] +"' class='img-circle img-size-32 mr-2' alt='white sample'/></a></div> </td>" )
                    .append( "<td>" + estadoPrestamo + "</td>" )
                    .append( "<td>" + value["usuariocobrador"] + "</td>" )
                    .append( "<td>Q. "+  parseFloat(value["prestamo"]).toFixed(2) + "</td>" )               
                    .appendTo("#tablaRegistroNuevos > tbody");

                });


                $("<tr></tr>")
                    .append( "<td colspan='4'></td>" )
                    .append( "<td style=\"border-bottom: #000 solid;\"><b>SUMA TOTAL:</b></td>" )
                    .append( "<td style=\"border-bottom: #000 solid;\"><b>Q. "+  parseFloat(sumaPrestamoNuevo).toFixed(2) +" </b></td>" )
                    .appendTo("#tablaRegistroNuevos > tbody");


                ///////////////////////////TABLA DE PRÉSTAMOS RENOVADOS///////////////////////////


                var tabla =
                "<table id='tablaRegistroRenovados' class='table table-striped table-sm' style='font-size: 13px;'>"+
                "<thead>"+
                    "<tr>  "+
                    "<th>Código</th>"+
                    "<th>Nombre cliente</th>"+
                    "<th>Foto</th>"+
                    "<th>Estado</th>"+
                    "<th>Cobrador</th>"+
                    "<th>Capital préstamo</th>"+
                    "</tr>"+
                "</thead>"+
                "<tbody></tbody>"+
                "</table>";
                $("#divtablaRegistroRenovadosPrestamos").html(tabla);



                $.each(data.prestamosRenovados,function(key,value) {

                    arrayPrestamosRenovados.push( value["id"] );

                
                    var estadoPrestamo = "";

                    if (value["estado"] == 0) {
                        estadoPrestamo = '<span class="badge badge-secondary">FINALIZADO</span>';                    
                    }else if ( value["cuotasPendientes"] == 0 && value["morasPendientes"] == 0) {
                        estadoPrestamo = '<span class="badge bg-success">AL DÍA</span>';
                    }else if( value["cuotasPendientes"] <= 2){
                        estadoPrestamo = '<span class="badge bg-warning">PENDIENTES: <br> '+value["cuotasPendientes"]+' CUOTAS Y '+value["morasPendientes"]+' MORAS</span>';
                    }else{
                        estadoPrestamo = '<span class="badge bg-danger">PENDIENTES: <br> '+value["cuotasPendientes"]+' CUOTAS Y '+value["morasPendientes"]+' MORAS</span>';
                    }

                    sumaPrestamoRenovado+=parseFloat(value["prestamo"]);
            

                $("<tr></tr>")
                    .append( "<td>" + value["codigo"] + "</td>" )
                    .append( "<td> " + value["nombreCliente"] + " </td>" )
                    .append( "<td> <div class='filtr-item' data-category='1' data-sort='white sample'><a href='"+ value["foto"] +"' data-toggle='lightbox' data-title='Imagen de perfil'><img src='"+ value["foto"] +"' class='img-circle img-size-32 mr-2' alt='white sample'/></a></div> </td>" )
                    .append( "<td>" + estadoPrestamo + "</td>" )
                    .append( "<td>" + value["usuariocobrador"] + "</td>" )
                    .append( "<td>Q. "+  parseFloat(value["prestamo"]).toFixed(2) + "</td>" )               
                    .appendTo("#tablaRegistroRenovados > tbody");


                });

                $("<tr></tr>")
                    .append( "<td colspan='4'></td>" )
                    .append( "<td style=\"border-bottom: #000 solid;\"><b>SUMA TOTAL:</b></td>" )
                    .append( "<td style=\"border-bottom: #000 solid;\"><b>Q. "+  parseFloat(sumaPrestamoRenovado).toFixed(2) +" </b></td>" )
                    .appendTo("#tablaRegistroRenovados > tbody");

                ///////////////////////////TABLA DE CUOTAS Y MORAS ATRASADAS///////////////////////////


            //-------------
            //- REPORTE de Prestamos finalizados que aun siguen pendientes
            //-------------


                var tabla =
                "<table id='tablaRegistroPrestamos' class='table table-striped table-sm' style='font-size: 13px;'>"+
                "<thead>"+
                    "<tr>  "+
                    "<th>No.</th>"+
                    "<th>Cliente</th>"+
                    "<th>Foto</th>"+
                    "<th>Cobrador</th>"+
                    "<th>Cuotas Pendientes</th>"+
                    "<th>Moras Pendientes</th>"+
                    "<th>Total Pendiente</th>"+
                    "</tr>"+
                "</thead>"+
                "<tbody></tbody>"+
                "</table>";
                $("#divtablaRegistroPrestamos").html(tabla);


                prestamos_pendientes = data.reportemeta;
            
                $.each(data.reportemeta,function(key,value) {      
                    
                    sumaDescuento += parseFloat(value["cuotas_pendientes"]);
                    sumaDescuento += parseFloat(value["moras_pendientes"]);


                    $("<tr></tr>")
                        .append( "<td>" + (key + 1) + "</td>" )               
                        .append( "<td> " + value["nombreCliente"] + " </td>" )
                        .append( "<td> <div class='filtr-item' data-category='1' data-sort='white sample'><a href='"+ value["foto"] +"' data-toggle='lightbox' data-title='Imagen de perfil'><img src='"+ value["foto"] +"' class='img-circle img-size-32 mr-2' alt='white sample'/></a></div> </td>" )
                        .append( "<td> " + value["usuariocobrador"] + " </td>" )                
                        .append( "<td> Q. "+ parseFloat(value["cuotas_pendientes"]).toFixed(2) +" </td>" )
                        .append( "<td> Q. "+ parseFloat(value["moras_pendientes"]).toFixed(2) +"  </td>" )
                        .append( "<td> <b>Q. " + parseFloat(parseFloat(value["cuotas_pendientes"])+parseFloat(value["moras_pendientes"])).toFixed(2) + " </b> </td>" )              
                        .appendTo("#tablaRegistroPrestamos > tbody");
                });

                $("<tr style='border-top: 2px solid #000;'></tr>")
                    .append( "<td colspan='6' style='border-bottom: 2px solid #000;'><h5> <b>TOTAL RECAUDO PENDIENTE: </b></h5></td>" )                              
                    .append( "<td style='border-bottom: 2px solid #000;'> <h2><b>Q. " + parseFloat(sumaDescuento).toFixed(2) + " </b> </h2></td>" )              
                    .appendTo("#tablaRegistroPrestamos > tbody");      


            
                //////////////////////////////////////
                //LLENAR FORMULARIO DE CONFIGURACIÓN//
                //////////////////////////////////////


                function llenarCamposConfigurarValores() {
                            
                    $("#contenedorRangeConfig").html('');
                    if (data.datosempleados.length > 0){                    
                    
                        $("#formConfigurarValores #nclientenuevo").val(data.datosempleados[0]["n"]);
                        $("#formConfigurarValores #mclientenuevo").val(data.datosempleados[0]["m"]);
                        $("#formConfigurarValores #nclienterenovado").val(data.datosempleados[0]["n"]);
                        $("#formConfigurarValores #mclienterenovado").val(data.datosempleados[0]["m"]);
                        $("#formConfigurarValores #descuentoconfigdeudas").val(data.datosempleados[0]["descuentodeudas"]);

                        $("#formConfigurarValores #aproximacionBasica").prop("checked", false);
                        $("#formConfigurarValores #aproximacionExacta").prop("checked", true);
                        $("#contenedorRangeConfig").html("<input id='range_Config' type='text' name='range_Config' value='"+data.datosempleados[0]["descuentodeudas"]+";100'>");
                        $('#range_Config').ionRangeSlider({
                        min     : 0,
                        max     : 100,
                        type    : 'single',
                        step    : 5,
                        postfix : ' %',
                        prettify: false,
                        hasGrid : true,

                            onChange: function (data) {
                                $("#formConfigurarValores #descuentoconfigdeudas").val($('#range_Config').val());
                            }

                        });

                    }

                }

                llenarCamposConfigurarValores();


                function llenarCamposPlanilla() {
                    
                    ///////////////////////////DATOS DEL EMPLEADO PARA LOS PAGOS///////////////////////////
                    $("#contenedorprogress1").html();
                    $("#contenedorprogress2").html();

                    $("#tittleICN").html("Incentivo por clientes nuevos:");
                    $("#tittleIPR").html("Incentivo por prestamos renovados:");
                    $("#tittleDCMP").html("Descuento por cuotas y moras pendientes:");

                    $("#formNuevoPago #pagosueldobase").attr("placeholder", "0");
                    $("#formNuevoPago #pagoclientesnuevos").attr("placeholder", "0");
                    $("#formNuevoPago #pagorenovaciones").attr("placeholder", "0");

                    if (data.datosempleados.length > 0) {

                        totalDescuento = (sumaDescuento / 100) * parseInt($("#descuentoconfigdeudas").val());

                        //Devuelve el valor del número dado redondeado al entero más cercano.

                        if ( $("#descuentoconfigdeudas").val() == 100) {
                            totalDescuento = parseFloat(totalDescuento).toFixed(2);                            
                        }else{
                            totalDescuento = Math.round(totalDescuento);
                        }

                        $("#formNuevoPago #pagosueldobase").val(data.datosempleados[0]["sueldobase"]);        
                        $("#formNuevoPago #pagosueldobase").attr("placeholder", data.datosempleados[0]["sueldobase"]);
                        $("#formNuevoPago #descuentoPorCuotasMoras").val(totalDescuento);

                        var cs1 = 100;
                        var cs2 = 100;

                        if ( parseInt(data.datosempleados[0]["metaclientesnuevos"]) > sumaPrestamoNuevo ) {
                            cs1 = Math.round( (sumaPrestamoNuevo*100)/parseInt(data.datosempleados[0]["metaclientesnuevos"]) );
                        }

                        if ( parseInt(data.datosempleados[0]["metarenovaciones"]) > sumaPrestamoRenovado ) {
                            cs2 = Math.round( (sumaPrestamoRenovado*100)/parseInt(data.datosempleados[0]["metarenovaciones"]) );
                        }


                        $("#contenedorprogress1").html( '<span class="progress-text">Meta por clientes nuevos:</span>'+
                                                        '<span class="float-right"><b>Q. '+sumaPrestamoNuevo+'</b>/Q. '+data.datosempleados[0]["metaclientesnuevos"]+'</span>'+
                                                        '<div class="progress progress-sm">'+
                                                        '<div class="progress-bar bg-success" style="width: '+cs1+'%"></div>'+
                                                        '</div>');

                        
                        $("#contenedorprogress2").html( '<span class="progress-text">Meta por clientes que renuevan:</span>'+
                                                        '<span class="float-right"><b>Q. '+sumaPrestamoRenovado+'</b>/Q. '+data.datosempleados[0]["metarenovaciones"]+'</span>'+
                                                        '<div class="progress progress-sm">'+
                                                        '<div class="progress-bar bg-success" style="width: '+cs2+'%"></div>'+
                                                        '</div>');


                        $("#tittleICN").html("<span class='tooltiptext'>Incentivo "+$("#nclientenuevo").val()+" por cada "+$("#mclientenuevo").val()+" </span>Incentivo por clientes nuevos:");
                        $("#tittleIPR").html("<span class='tooltiptext'>Incentivo "+$("#nclienterenovado").val()+" por cada "+$("#mclienterenovado").val()+" </span>Incentivo por prestamos renovados:");
                        $("#tittleDCMP").html("<span class='tooltiptext'>"+$("#descuentoconfigdeudas").val()+"% de descuento</span>Descuento por cuotas y moras pendientes:");
                        

                        if ( $("#aproximacionBasica").is(':checked') ) {

                            var cantICN = Math.trunc((sumaPrestamoNuevo) / parseInt($("#mclientenuevo").val())); 
                            var incentivoporclientesnuevos = cantICN * parseInt($("#nclientenuevo").val());

                            var cantIPR = Math.trunc((sumaPrestamoRenovado) / parseInt($("#mclienterenovado").val())); 
                            var incentivoporprestamosrenovados = cantIPR * parseInt($("#nclienterenovado").val());
                            
                        }else if( $("#aproximacionExacta").is(':checked') ) {

                            var cantICN = ((sumaPrestamoNuevo) / parseInt($("#mclientenuevo").val())); 
                            var incentivoporclientesnuevos = cantICN * parseInt($("#nclientenuevo").val());

                            var cantIPR = ((sumaPrestamoRenovado) / parseInt($("#mclienterenovado").val())); 
                            var incentivoporprestamosrenovados = cantIPR * parseInt($("#nclienterenovado").val());

                        }
                        

                        $("#formNuevoPago #pagoclientesnuevos").val(parseFloat(incentivoporclientesnuevos).toFixed(2));
                        $("#formNuevoPago #pagorenovaciones").val(parseFloat(incentivoporprestamosrenovados).toFixed(2));

                        $("#formNuevoPago #pagoclientesnuevos").attr("placeholder", parseFloat(incentivoporclientesnuevos).toFixed(2));
                        $("#formNuevoPago #pagorenovaciones").attr("placeholder", parseFloat(incentivoporprestamosrenovados).toFixed(2));

                    }

                }


                llenarCamposPlanilla();


                function actualizacionTotales() {
                    
                    var btnPagoCN = $("#btnPagoCN").is(':checked') ? 1:0;
                    var btnPagoCR = $("#btnPagoCR").is(':checked') ? 1:0;
                    var btnDescuentoCM = $("#btnDescuentoCM").is(':checked') ? 1:0;
                    var btnDepreciacion = $("#btnDepreciacion").is(':checked') ? 1:0;
                    var btnotrosDescuentos = $("#btnotrosDescuentos").is(':checked') ? 1:0;
                    
                    var pagoclientesnuevos = (isNaN(parseFloat($("#formNuevoPago #pagoclientesnuevos").val())) == false ) ? parseFloat( $("#formNuevoPago #pagoclientesnuevos").val() ) : 0;
                    var pagorenovaciones = (isNaN(parseFloat($("#formNuevoPago #pagorenovaciones").val())) == false ) ? parseFloat( $("#formNuevoPago #pagorenovaciones").val() ) : 0;
                    var pagosueldobase= (isNaN(parseFloat($("#formNuevoPago #pagosueldobase").val())) == false ) ? parseFloat( $("#formNuevoPago #pagosueldobase").val() ) : 0;
                    var subtotalpagos= (isNaN(parseFloat($("#formNuevoPago #subtotalpagos").val())) == false ) ? parseFloat( $("#formNuevoPago #subtotalpagos").val() ) : 0;
                    var depreciacion = (isNaN(parseFloat($("#formNuevoPago #depreciacion").val())) == false ) ? parseFloat( $("#formNuevoPago #depreciacion").val() ) : 0;


                    var descuentoPorCuotasMoras = (isNaN(parseFloat($("#formNuevoPago #descuentoPorCuotasMoras").val())) == false ) ? parseFloat( $("#formNuevoPago #descuentoPorCuotasMoras").val() ) : 0;
                    var subtotaldescuentos= (isNaN(parseFloat($("#formNuevoPago #subtotaldescuentos").val())) == false ) ? parseFloat( $("#formNuevoPago #subtotaldescuentos").val() ) : 0;
                    var totalliquido= (isNaN(parseFloat($("#formNuevoPago #totalliquido").val())) == false ) ? parseFloat( $("#formNuevoPago #totalliquido").val() ) : 0;
                    var otrosDescuentos = (isNaN(parseFloat($("#formNuevoPago #otrosDescuentos").val())) == false ) ? parseFloat( $("#formNuevoPago #otrosDescuentos").val() ) : 0;




                    if (btnPagoCN == 0) {
                        pagoclientesnuevos = 0;
                    }
                    if (btnPagoCR == 0) {
                        pagorenovaciones = 0;
                    }
                    if (btnDescuentoCM == 0) {
                        descuentoPorCuotasMoras = 0;
                    }
                    if (btnDepreciacion == 0) {
                        depreciacion = 0;
                    }

                    if (btnotrosDescuentos == 0) {
                        otrosDescuentos = 0;
                    }

                    $("#formNuevoPago #subtotalpagos").val( pagoclientesnuevos + pagorenovaciones + pagosueldobase + depreciacion );
                    $("#formNuevoPago #subtotaldescuentos").val( descuentoPorCuotasMoras + otrosDescuentos );
                    $("#formNuevoPago #totalliquido").val( parseFloat((pagoclientesnuevos + pagorenovaciones + pagosueldobase + depreciacion) - (descuentoPorCuotasMoras + otrosDescuentos)).toFixed(2) );
                    

                }

                actualizacionTotales();

                $("#formNuevoPago #pagoclientesnuevos").keyup(function( event ) {actualizacionTotales();});
                $("#formNuevoPago #pagorenovaciones").keyup(function( event ) {actualizacionTotales();});
                $("#formNuevoPago #pagosueldobase").keyup(function( event ) {actualizacionTotales();});
                $("#formNuevoPago #depreciacion").keyup(function( event ) {actualizacionTotales();});
                $("#formNuevoPago #otrosDescuentos").keyup(function( event ) {actualizacionTotales();});
                $("#btnPagoCN").on("click",function(e){actualizacionTotales();});
                $("#btnPagoCR").on("click",function(e){actualizacionTotales();});
                $("#btnDescuentoCM").on("click",function(e){actualizacionTotales();});
                $("#btnDepreciacion").on("click",function(e){actualizacionTotales();});
                $("#btnotrosDescuentos").on("click",function(e){actualizacionTotales();});



                setTimeout(function(){
                    $("#btnMinimizar1").trigger("click");
                }, 500);

                setTimeout(function(){
                    $("#btnMinimizar2").trigger("click");
                }, 500);

                $("#contenedorBotonConfigurarValores").html('<button type="button" id="btnGuardarConfigurarValores" class="btn bg-navy">Guardar cambios</button>');
                $("#contenedorRestaurarvalores").html('<button type="button" id="btnRestaurarvalores" class="btn btn-default tooltip2"><span class="tooltiptext">Restaurar valores</span><i class="fas fa-sync-alt"></i></button>');


                $("#btnGuardarConfigurarValores").on("click",function(e){         
                    e.preventDefault();
                    $("#divConfigurarValores").modal("hide");
                    llenarCamposPlanilla();
                    actualizacionTotales();
                });
                
                
                $("#btnRestaurarvalores").on("click",function(e){         
                    e.preventDefault();
                    $("#divConfigurarValores").modal("hide");
                    llenarCamposConfigurarValores();
                    llenarCamposPlanilla();
                    actualizacionTotales();
                });



            }else{
                toastr.warning(data.mensaje,"Info");
            }
        }, "json")
        .fail(function()
        {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });

    }


    $("#configurarvalores").on("click",function(e){            

        e.preventDefault();
        $("#divConfigurarValores").modal("show", {backdrop: "static"});

    });


    /****************** GUARDAR DATOS DEL REGISTRO *******************/
    $("#btnGuardarNuevoDatosPago").on("click",guardarNuevoDatosPago);
    function guardarNuevoDatosPago(e){
      e.preventDefault();


      if($("#formNuevoDatosPago").valid()) {
          $.post("funciones/ws_planilla.php", "accion=nuevo&"+$("#formNuevoDatosPago").serialize() ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");
                $("#divNuevoDatosPago").modal("hide");
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





    
    



    /****************** GUARDAR DATOS DEL REGISTRO *******************/
    $("#btnGuardarNuevoPago").on("click",guardarPago);

    function guardarPago(e){
      e.preventDefault();

        var btnPagoCN = $("#btnPagoCN").is(':checked') ? 1:0;
        var btnPagoCR = $("#btnPagoCR").is(':checked') ? 1:0;
        var btnDescuentoCM = $("#btnDescuentoCM").is(':checked') ? 1:0;
        var btnDepreciacion = $("#btnDepreciacion").is(':checked') ? 1:0;
        var btnotrosDescuentos = $("#btnotrosDescuentos").is(':checked') ? 1:0;


      if($("#formNuevoPago").valid()) {    

        
        $.post("funciones/ws_planilla.php", ($("#formNuevoPago").serialize()+"&"+ 
            $.param({ accion: "nuevoPagoEmpleado", 
                    btnPagoCN:btnPagoCN,
                    btnPagoCR:btnPagoCR,
                    btnDescuentoCM:btnDescuentoCM,
                    btnDepreciacion:btnDepreciacion,
                    btnotrosDescuentos:btnotrosDescuentos,
                    arrayPrestamosNuevos:JSON.stringify(arrayPrestamosNuevos),                               
                    arrayPrestamosRenovados:JSON.stringify(arrayPrestamosRenovados),
                    prestamos_pendientes:JSON.stringify(prestamos_pendientes),
                    porcentajeDescuento:$("#descuentoconfigdeudas").val(),
                    fechainicio:$("#formNuevoPago #fechainicio").val(),
                    descripcionPago:$("#formNuevoPago #nameUser").val()  
            })) ,function(data) {
        if(data.resultado){
            toastr.success(data.mensaje, "Exito");
            $("#divNuevoPago").modal("hide");
            setTimeout(function(){ratPack.refresh();},300);
        }
        else{
            toastr.warning(data.mensaje,"Info");
        }
        }, "json")
        .fail(function() {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });


      }else{
        toastr.warning("Datos faltantes","Info");
      }
    }
    

  });
</script>