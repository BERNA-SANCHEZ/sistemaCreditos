<?php
session_start();
require_once ("../funciones/classSQL.php");
$conexion = new conexion();
if($conexion->permisos($_SESSION['idtipousuario'],"6","acceso"))
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
        <!--<div class="row mb-2">
            <div class="col-sm-12">
                <h2 class='titulo'>Cobradores</h2>
            </div>           
        </div>-->
    </div><!-- /.container-fluid -->
</section>


<!-- Main content -->
<section id="divTablaDetalle" class="content" style="display:none;">
    <div class="card">
        

        <div class="card-header" style="padding: 0px;">        
            <div class="card card-widget widget-user" style="margin: 0px;">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-lightblue">
                <!--<h3 class="widget-user-username" > <?php //echo $_SESSION["nombre"] ?> </h3> -->
                
                <h5 class="widget-user-username" >
                    <?php
                        if ($_SESSION['idtipousuario'] == 4 || $_SESSION['idtipousuario'] == 5) {
                            echo "<select disabled class='form-control select2-list select2-success' data-dropdown-css-class='select2-success' name='usuarioseleccionado' id='usuarioseleccionado' data-placeholder='Seleccione Usuario' required>";
                        }else{
                            echo "<select class='form-control select2-list select2-success' data-dropdown-css-class='select2-success' name='usuarioseleccionado' id='usuarioseleccionado' data-placeholder='Seleccione Usuario' required>";
                        }

                            echo "<option value='' ></option>";   
                            $usuarios = $conexion->sql("SELECT id, nombre FROM usuarios");
                            foreach ($usuarios as $key => $value) {
                                if ($value['id'] == $_SESSION['idusuario']) {
                                    echo "<option value='".$value['id']."' selected>".$value["nombre"]."</option>";
                                }else{
                                    echo "<option value='".$value['id']."' >".$value["nombre"]."</option>";
                                }
                            }
                        echo "</select>";
                    ?>
                </h5>

                <h5 class="widget-user-desc" id="txttipousuario"> <?php echo $_SESSION["tipousuario"] ?> </h5>
              </div>

              <div class="widget-user-image">


                <img class="img-circle elevation-2" src="upload/user.png" alt="User Avatar">         
                
                
              </div>


              <div class="card-footer">


                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                        <div class="inner">
                            <h3 id="prestamosRealizados"></h3>

                            <p>Préstamos Activos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-flag fa-lg mr-2"></i>
                        </div>
                        
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                        <div class="inner">
                            <h3 id="morasPagadas"></h3>

                            <p>Moras Cobradas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hand-holding-usd fa-lg mr-2"></i>

                            
                        </div>
                        
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                        <div class="inner">
                            <h3 id="morasPendientes"></h3>

                            <p>Moras pendientes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hourglass-start fa-lg mr-2"></i>

                        </div>
                        
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                        <div class="inner">
                            <h3 id="morasExoneradas"></h3>

                            <p>Moras exoneradas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-handshake fa-lg mr-2"></i>
                            
                        </div>
                        
                        </div>
                    </div>
                </div>



              </div>
              
            </div>

            
        </div>


        




            <div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">

                <div class="btn btn-success btn-lg btn-flat" id="verMeta">
                    <i class="fas fa-flag fa-lg mr-2"></i> 
                    META
                </div>

                
                <div class="btn btn-outline-success tooltip2" id="btnActualizar">
                    <span class='tooltiptext'>ACTUALIZAR DATOS</span>
                    <i class="fas fa-sync-alt fa-lg mr-2 fa-2x" style="margin-top: 5px;"></i> 
                </div>

            
              </div>

            


            


        <div class="card card-success card-outline">


            <div class="row">
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <h5 class="description-header">

                        <div class="icheck-success d-inline">
                            <input type="checkbox" id="checkTODOS">
                            <label for="checkTODOS">
                            </label>
                        </div>

                        </h5>
                        <span class="description-text">Todos los préstamos</span>
                    </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <h5 class="description-header">

                        
                            <div class="icheck-success d-inline">
                                <input type="checkbox" id="checkPENDIENTES" checked>
                                <label for="checkPENDIENTES">
                                </label>
                            </div>


                        </h5>
                        <span class="description-text">Préstamos pendientes</span>
                    </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <h5 class="description-header">
                                
                            <div class="icheck-success d-inline">
                                <input type="checkbox" id="checkDIA" checked>
                                <label for="checkDIA">
                                </label>
                            </div>

                        </h5>
                        <span class="description-text">Prestamos del día</span>
                    </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->

                <div class="col-sm-3">
                    <div class="description-block">
                        <h5 class="description-header">
                                
                            <div class="icheck-success d-inline">
                                <input type="checkbox" id="checkNoVerificado">
                                <label for="checkNoVerificado">
                                </label>
                            </div>

                        </h5>
                        <span class="description-text">Pagos no verificados</span>
                    </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->



                <div class="col-sm-3" style="display: none;">
                    <div class="description-block">
                        <h5 class="description-header">                            
                        
                            <button type="button" id="btnActualizarPrestamos" class="btn bg-gradient-primary btn-xs"> <i class="fas fa-sync-alt fa-spin"></i>  </button>

                        </h5>
                        <span class="description-text">Actualizar moras</span>
                    </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->


            </div>


        </div>
        

        <!-- BAR CHART -->
        <div id="contenedorbarChart"></div>
        <!-- BAR CHART -->

        <div class="card-body">
            <div  id="divtablaRegistroPrestamos" class="table-responsive " ></div>
        </div>
    <!-- /.card-body -->
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->

<br>
<br>

<!-- Main content -->
<section id="divTablaPagosDetalle" class="content" style="display:none;">


    <div class="card">


        <div class="card card-widget widget-user-2">
            <div class="widget-user-header bg-navy">
            <div class="widget-user-image" id="imgUser">
                <img class="img-circle elevation-2" src="upload/user.png" alt="User Avatar">
            </div>
            <h3 class="widget-user-username" id="nameUser" ></h3>
            <h5 class="widget-user-desc" id="dirUser"></h5>
            </div>                            
        </div>


       

            <div class="card">
                <div class="card-header bg-lightblue">
                    <h3 class="card-title">Pendiente hasta hoy <?php echo date("d/m/Y") ?></h3>

                    <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    </div>
                </div>
             
              
                <div class="card-body" style="padding: 0px;">

                    <div class=" table-responsive">
                        <table class="table table-sm" id="tablaPendienteActual"></table>
                    </div>
        
                </div>

            </div>

            <div class="card">
                <div class="card-header bg-lightblue">
                    <h3 class="card-title">Cuotas para pagar de último. Antes del <?php echo date("d/m/Y") ?></h3>

                    <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    </div>
                </div>
             
              
                <div class="card-body" style="padding: 0px;">

                    <div class=" table-responsive">
                        <table class="table table-sm" id="tablaPendienteFinal"></table>
                    </div>
        
                </div>

            </div>






            <div class="card-header">
                
                <?php if($conexion->permisos($_SESSION['idtipousuario'],"6","crear")) { ?>

                    <div class="row">

                        <div class="col-md-4" style="margin-bottom: 5px;">
                        <button type="button" id="btnNuevoPago" data-toggle="modal" class="btn bg-navy btn-lg btn-block">Nuevo Pago</button>

                        </div>

                        <div class="col-md-4" style="margin-bottom: 5px;">
                        <button type="button" id="btnNuevaMora" data-toggle="modal" class="btn btn-default bg-lightblue btn-lg btn-block">Ver moras</button>

                        </div>

                        <div class="col-md-4" style="margin-bottom: 5px;">
                        <button type="button" id="btnFinalizarPrestamo" data-toggle="modal" class="btn btn-danger btn-lg btn-block">Finalizar préstamo</button>

                        </div>

                    </div>




                <?php } ?>
                    
            </div>


        
        <div class="card-body" style="padding-top: 0px;">

            <div class="row">

                <div  id="divtablaPagosRegistroPrestamos" class="table-responsive col-md-6 "></div>
                <div  id="divtablaPagosRealizados" class="table-responsive col-md-6 "></div>
                <div  id="divtablaPagosCapital" class="table-responsive col-md-6 "></div>
                <div  id="divInformePrestamo" class="col-md-12" style="margin-top: 40px;"></div>

            </div>


        </div>
    <!-- /.card-body -->
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->




<div id="divNuevoPago" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form id='formNuevoPago' class="form form-validate"  role="form"   method="post" >
            <input type="hidden" class="form-control" name="idprestamo" id="idprestamo" />

            <input type="hidden" class="form-control" name="tipoPlan" id="tipoPlan" />

            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title" id="tituloNuevoPago">Nuevo Pago</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <label>Fecha de pago:</label>

                        <div class="input-group date" id="timepicker2" data-target-input="nearest">
                        <input type="text" class="form-control form-control-lg datetimepicker-input" name="fechainicio" id="fechainicio" data-target="#timepicker2" value=<?php echo date("Y-m-d") ?> readonly required>
                        <div class="input-group-append" data-target="#timepicker2" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="far fa-calendar"></i></div>
                        </div>
                        </div>
                        <!-- /.input group -->
                    </div>

                    <div class="form-group" id="selectTP" style="display:none;">

                        <label for="tipopago">Tipo de pago</label>
                        <select class="form-control select2-list select2-success" data-dropdown-css-class="select2-success" name="tipopago" id='tipopago' data-placeholder="Seleccione el Tipo de pago" required>                                          
                            <option value="1">Pago de interés</option>                            
                            <option value="2">Pago de capital</option>
                        </select>
                    
                    </div>


                    <div class="form-group">
                        <label for="newCantidad">Cantidad:</label>
                        <input type="number" class="form-control form-control-lg" id="newCantidad" name="newCantidad" placeholder="Ingrese Cantidad" required >
                    </div>
                    

                    <div class="icheck-success" id="contenedorCheckAPMP">
                        <input type="checkbox" id="btnPrimeroMorasPendientes" >
                        <label for="btnPrimeroMorasPendientes">Abonar primero las moras pendientes.</label>
                    </div>


                </div>

                <div class="modal-footer">
                    <div class="response"></div>
                    <button type="button" id="btnGuardarNuevoPago" class="btn bg-navy btn-lg">Guardar</button>
                    <button type="button" id="btnCancelarNuevoPago" class="btn btn-default btn-lg" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>


<div id="divNuevaMora" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form id='formNuevaMora' class="form form-validate"  role="form"   method="post" >

            <input type="hidden" class="form-control" name="totalPrestamo" id="totalPrestamo" />
            <input type="hidden" class="form-control" name="total_Mora" id="total_Mora" />
           
            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Exonerar moras</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body row">

                    <br>
                    <br>

                    <div  class="table-responsive">
                        <table class="table table-sm" id="tablaDetalleMora">
                            <thead>
                                <tr> 
                                <th>#</th>
                                <th>Dia a cobrar mora</th>
                                <th>Valor</th>
                                <!--<th></th>-->
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div> 

                    <br>
                    
                  <!--<h3 id="h3TotalMora" style="margin-top: 15px;" >Total Mora Q. 0.00 </h3> -->
                    
                </div>

                <div class="modal-footer">
                    <div class="response"></div>
                    <button type="button" id="btnExonerarNuevaMora" class="btn btn-danger" style="display: none;">Exonerar moras</button>
                    <button type="button" id="btnCancelarNuevaMora" class="btn btn-default" data-dismiss="modal">Cancelar</button>
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
                    <label for="justificacion">Justificar anulación:</label>
                    <input type="text" class="form-control form-control-lg" autocomplete="off" id="justificacion" name="justificacion"  >
                </div>

                <div class="form-group">
                    <label class="col-form-label" for="claveAnulacionPago"><i class="far fa-bell"></i> Contraseña de anulación </label>
                    <input type="password" class="form-control is-invalid form-control-lg" autocomplete="off" id="claveAnulacionPago" >
                </div>


                <input type="hidden" name="ideliminarPago" id="ideliminarPago" class="form-control" />
                <input type="hidden" name="pagocuota" id="pagocuota" class="form-control" />
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



<div id="divEliminarMora" class="modal fade show" aria-modal="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Exonerar Moras</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">    
                
                <div class="callout callout-danger">
                  <h4>¿Desea exonerar las fechas seleccionadas?</h4>

                    <div class="form-group">
                        <label class="col-form-label" for="claveExoneracionMoras"><i class="far fa-bell"></i> Contraseña de confirmación </label>
                        <input type="password" class="form-control is-invalid form-control-lg" autocomplete="off" id="claveExoneracionMoras" >
                    </div>

                  <div id="fechasSeleccionadas"></div>
                  

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnEliminarMora">Si estoy seguro</button>
                <button type="button" class="btn btn-default" id="btnCancelarEliminarMora" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>




<div id="divPagarMora" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form id='formPagarMora' class="form form-validate"  role="form"   method="post" >

            <input type="hidden" class="form-control" name="iddetprestamos" id="iddetprestamos" />
            <input type="hidden" class="form-control" name="id_prestamo" id="id_prestamo" />
           
            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Pagar Moras</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body row">

                    <div class="form-group col-md-12">
                        <label>Fecha pago moras:</label>

                        <div class="input-group date" id="timepicker1" data-target-input="nearest">
                        <input type="text" class="form-control  datetimepicker-input" name="fechaPagarMora" id="fechaPagarMora" data-target="#timepicker1" value=<?php echo date("Y-m-d") ?> readonly required>
                        <div class="input-group-append" data-target="#timepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="far fa-calendar"></i></div>
                        </div>
                        </div>
                        
                    </div>


                    <div class="form-group col-md-12">

                        <label id="etiquetaMora" for="montomora"></label>

                        <div class="input-group">

                      

                            <div class="input-group-prepend">
                                <span class="input-group-text">Q</span>
                            </div>

                            <input type="number" class="form-control " name="montomora" id='montomora' readonly required>

                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>

                        </div>


                    
                    </div>

                </div>

                <div class="modal-footer">
                    <div class="response"></div>
                    <button type="button" id="btnPagarMora" class="btn bg-navy">Registrar cobro</button>
                    <button type="button" id="btnCancelarPagarMora" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>



<div id="divEliminarCapital" class="modal fade show" aria-modal="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar Capital</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label class="col-form-label" for="claveAnulacionCapital"><i class="far fa-bell"></i> Contraseña de anulación </label>
                    <input type="password" class="form-control is-invalid form-control-lg" autocomplete="off" id="claveAnulacionCapital" >
                </div>

                
                <input type="hidden" name="ideliminarCapital" id="ideliminarCapital" class="form-control" />        
                <p><h4>¿Desea eliminar el registro?</h4></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnEliminarCapital">Si estoy seguro</button>
                <button type="button" class="btn btn-default" id="btnCancelarEliminarCapital" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



<div id="divFinalizarPrestamo" class="modal fade show" aria-modal="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Finalizar Préstamo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                
                <div class="form-group">
                    <label class="col-form-label" for="claveAnulacionPrestamo"><i class="far fa-bell"></i> Contraseña de finalización </label>
                    <input type="password" class="form-control is-invalid form-control-lg" autocomplete="off" id="claveAnulacionPrestamo" >
                </div>


                <input type="hidden" name="idFinalizarPrestamo" id="idFinalizarPrestamo" class="form-control" />
                <p><h4>¿Desea Finalizar el registro?</h4></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="botonFinalizarPrestamo">Si estoy seguro</button>
                <button type="button" class="btn btn-default" id="btnCancelarFinalizarPrestamo" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>






<div id="divDejarCuotaPendiente" class="modal fade show" aria-modal="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Dejar pendiente de cobro</h4>
                <button type="button" class="close" data-dismiss="modal" id="btnCancelarDejarCuotaPendientex" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                
                <div class="form-group">
                    <label class="col-form-label" for="claveCuotaPendiente"><i class="far fa-bell"></i> Contraseña de confirmación </label>
                    <input type="password" class="form-control is-invalid form-control-lg" autocomplete="off" id="claveCuotaPendiente" >
                </div>


                <input type="hidden" name="idDejarCuotaPendiente" id="idDejarCuotaPendiente" class="form-control" />
                <input type="hidden" name="idcheckpendiente" id="idcheckpendiente" class="form-control" />
                <p><h4>¿Desea Confirmar la acción?</h4></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="botonDejarCuotaPendiente">Si estoy seguro</button>
                <button type="button" class="btn btn-default" id="btnCancelarDejarCuotaPendiente" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>




<div id="divPagarNuevoPendiente" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form id='formPagarNuevoPendiente' class="form form-validate"  role="form"   method="post" >
            

            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title" id="tituloPagarNuevoPendiente">Nuevo Pago</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btnCancelarPagarNuevoPendientex">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <label>Fecha de pago:</label>

                        <div class="input-group date" id="timepicker3" data-target-input="nearest">
                        <input type="text" class="form-control form-control-lg datetimepicker-input" name="fechapendienteinicio" id="fechapendienteinicio" data-target="#timepicker3" value=<?php echo date("Y-m-d") ?> readonly required>
                        <div class="input-group-append" data-target="#timepicker3" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="far fa-calendar"></i></div>
                        </div>
                        </div>
                        <!-- /.input group -->
                    </div>

                    <div class="form-group">
                        <label for="newPendienteCantidad">Cantidad:</label>
                        <input type="number" class="form-control form-control-lg" id="newPendienteCantidad" name="newPendienteCantidad" placeholder="Ingrese Cantidad" required >
                        <input type="hidden" name="idDejarCuotaPendientx" id="idDejarCuotaPendientx" class="form-control" />                        
                        <input type="hidden" name="idcheckpendientx" id="idcheckpendientx" class="form-control" />


                        <input type="hidden" name="valorCuotaPendiente" id="valorCuotaPendiente" class="form-control" />
                        <input type="hidden" name="valorMoraPendiente" id="valorMoraPendiente" class="form-control" />


                    </div>
                    


                </div>

                <div class="modal-footer">
                    <div class="response"></div>
                    <button type="button" id="btnGuardarPagarNuevoPendiente" class="btn bg-navy btn-lg">Guardar</button>
                    <button type="button" id="btnCancelarPagarNuevoPendiente" class="btn btn-default btn-lg" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>




<!--
<div id="divNuevoResumen" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form id='formNuevoResumen' class="form form-validate"  role="form"   method="post" >
            <input type="hidden" class="form-control" name="idprestamo" id="idprestamo" />

            <input type="hidden" class="form-control" name="tipoPlan" id="tipoPlan" />

            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Resumen de pago</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body">

                     <div  class="table-responsive " >
                        <table id="tablaResumen" class="table table-striped" style="width: 100%;">
                            <thead>
                            <tr>  
                                <th width="80%">Abonos realizados</th>
                                <th>Subtotal</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    
                </div>

                <div class="modal-footer">
                    <div class="response"></div>     
                    <button type="button" id="btnCancelarNuevoResumen" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>
-->




<div id="divConfirmarCambio" class="modal fade show" aria-modal="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Abonar moras pendientes</h4>
                <button id="btnCancelarConfirmarCambiox" type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                
                <div class="form-group">
                    <label class="col-form-label" for="claveConfirmarCambio"><i class="far fa-bell"></i> Contraseña de confirmación </label>
                    <input type="password" class="form-control is-invalid form-control-lg" autocomplete="off" id="claveConfirmarCambio" >
                </div>


                <p><h4>¿Desea abonar primero las moras pendientes?</h4></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnConfirmarCambio">Si estoy seguro</button>
                <button type="button" class="btn btn-default" id="btnCancelarConfirmarCambio" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



<div id="divEditarCliente" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: auto;">
    <div class="modal-dialog">
    <form id='formEditarCliente' class="form form-validate"  role="form"   method="post" >

    <input type="hidden" class="form-control" name="idcliente" id="idcliente" />


    
        <div class="modal-content  panel panel-warning">           

            <div class="modal-header">
              <h4 class="modal-title">Editar Cliente</h4>

            


              <button type="button" id="btnCancelarEditarClientex" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>

            <div class="card-body row">

                <!--NUEVO CODIGO-->
                <div class="row">

                <div class="col-md-4 col-sm-4 col-xs-4 col-2">&nbsp;</div>

                <div class="col-md-4 col-sm-4 col-xs-4 col-8">
                    <div class="image_area">
                    <form method="post">
                        <label for="upload_image_2">
                        <img src="upload/user.png" id="uploaded_image_2" class="img-responsive img-circle" />
                        <div class="newoverlay">
                            <div class="text">Ver Galería</div>
                        </div>
                            <input type="file" name="image" class="image" id="upload_image_2" style="display:none">
                        </label>
                    </form>
                    </div>
                </div>

                <div class="col-md-4 col-sm-4 col-xs-4 col-2">&nbsp;</div>
                

                </div>	
              



            </div>
            

            <div class="modal-footer">
                <div class="response"></div>

            
               
                <button type="button" id="btnGuardarEditarCliente" class="btn bg-navy">Guardar</button>
                <button type="button" id="btnCancelarEditarCliente" class="btn btn-default" data-dismiss="modal">Cancelar</button>


            </div>

        </div>
    </form>  
    </div>
</div>



<div id="divReporteMeta" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: auto;">
    <div class="modal-dialog modal-lg">
        <form id='formReporteMeta' class="form form-validate"  role="form"   method="post" >
            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Meta del día  (<?php echo date("d/m/Y") ?>)</h4>

             


                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body">

                                    
                    <div  class="table-responsive " >
                        <table id="tablaReporteMeta" class="table table-striped" style="width: 100%;">
                            <thead>
                            <tr>  
                                <th>No.</th>
                                <th>Cliente</th>
                                <th>Foto</th>
                                <th>Cobrador</th>
                                <th>Cuotas Pendientes</th>
                                <th>Moras Pendientes</th>
                                <th>Total Pendiente</th>                            
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                    
                    </div>

                    <div class="table-responsive ">


                        <table id="tablaReporteCaja" class="table table-striped" style="width: 100%;" >
                            <thead>
                            <tr>  
                                <th>No.</th>
                                <th>USUARIO REGISTRÓ</th>
                                <th>CLIENTE</th>
                                <th>FOTO</th>
                                <th>FECHA PAGO</th>
                                <th>DESCRIPCIÓN</th>
                                <th>CUOTA</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>


                        
                    </div>


                    <br>
                        <div class="progress-group" id="contenedorprogress1"></div>

                </div>

                <div class="modal-footer">
                    <div class="response"></div>                    
                    <button type="button" id="btnCancelarReporteMeta" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
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




<div id="divCambiarRuta" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: auto;">
    <div class="modal-dialog">
        <form id='formCambiarRuta' class="form form-validate"  role="form"   method="post" >
        <input type="hidden" class="form-control" name="idprestamoCambiar" id="idprestamoCambiar" />




            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Cambiar Ruta</h4>

                


                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body">




                <div class="form-group">

                    <label>Préstamo en ruta de:</label>
            
                    <?php
                            echo "<select class='form-control select2-list select2-success' data-dropdown-css-class='select2-success' name='rutaseleccionada' id='rutaseleccionada' data-placeholder='Seleccione Usuario' required>";                        
                            $usuarios = $conexion->sql("SELECT id, nombre FROM usuarios WHERE idtipousuario = 4");
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

                <div class="modal-footer">
                    <div class="response"></div>           
                    <button type="button" id="btnGuardarCambiarRuta" class="btn bg-navy">Guardar Cambios</button>         
                    <button type="button" id="btnCancelarCambiarRuta" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>


<!--NUEVO CODIGO-->



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
    var morasAnuladas = Array();
    var fechaAnuladas = Array();
    var rutaActual = ""; 
    var modalActivo = 0;  //1 =Modal Nuevo Cliente, 2 =Modal Editar Cliente








    verficarPermisos();
    $(".select2-list").select2({ allowClear: true });

    $('#timepicker3').datetimepicker({
        pickTime: false, format: 'YYYY-MM-DD'
    });

    //timepicker2
    $('#timepicker2').datetimepicker({
        pickTime: false, format: 'YYYY-MM-DD'
    });

    //timepicker1
    $('#timepicker1').datetimepicker({
        pickTime: false, format: 'YYYY-MM-DD'
    });
    
    function verficarPermisos () {
        $.post("funciones/ws_usuarios.php", {accion:"consultarPermisos" , idmodulo:"6"} ,function(data)
        {
            if(data.resultado){
                Acceso = data.registros[0]["acceso"];
                Crear = data.registros[0]["crear"];
                Modificar = data.registros[0]["modificar"];
                Eliminar = data.registros[0]["eliminar"];
                Consultar = data.registros[0]["consultar"];
                actualizarPrestamos();

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

    $("#checkTODOS").on("click",function(e){
        $("#checkNoVerificado").prop("checked", false);

        mostrarRegistroPrestamos();
    });

    $("#checkPENDIENTES").on("click",function(e){
        $("#checkNoVerificado").prop("checked", false);

        mostrarRegistroPrestamos();
    });

    $("#checkDIA").on("click",function(e){
        $("#checkNoVerificado").prop("checked", false);

        mostrarRegistroPrestamos();
    });

    $("#checkNoVerificado").on("click",function(e){

        $("#checkTODOS").prop("checked", false);
        $("#checkPENDIENTES").prop("checked", false);
        $("#checkDIA").prop("checked", false);

        mostrarRegistroPrestamos();
        
    });
    

    function mostrarRegistroPrestamos () {


        var checkTODOS = $("#checkTODOS").is(':checked') ? 1:0;
        var checkPENDIENTES = $("#checkPENDIENTES").is(':checked') ? 1:0;
        var checkDIA = $("#checkDIA").is(':checked') ? 1:0;
        var checkNoVerificado = $("#checkNoVerificado").is(':checked') ? 1:0;


        $("#morasPagadas").html('');
        $("#morasPendientes").html('');
        $("#morasExoneradas").html('');
        $("#prestamosRealizados").html('');
        $("#contenedorbarChart").html('');



      $("#divTablaPagosDetalle").fadeOut("fast");

      $("#divTablaDetalle").fadeIn("slow");
      
      $("#tablaRegistroPrestamos  tbody tr").remove();
      $("#tablaReporteMeta  tbody tr").remove();
      $("#tablaReporteCaja  tbody tr").remove();
      $("#contenedorprogress1").html();


      $.post("funciones/ws_prestamos.php", { 
        accion: "mostrarPrestamosCobrador", 
        checkTODOS:checkTODOS,
        checkPENDIENTES:checkPENDIENTES,
        checkDIA:checkDIA,
        checkNoVerificado:checkNoVerificado,
        id_usuario:$("#usuarioseleccionado").val()
        }, function(data) {
        if(data.resultado)
          { 



            
            $("#morasPagadas").html(data.morasPagadas);
            $("#morasPendientes").html(data.morasPendientes);
            $("#morasExoneradas").html(data.morasExoneradas);
            $("#prestamosRealizados").html(data.prestamosRealizados);
            $("#txttipousuario").html(data.txttipousuario);

           
            var tabla =
            "<table id='tablaRegistroPrestamos' class='table table-striped table-sm' >"+
              "<thead>"+
                "<tr>  "+
                   "<th>No.</th>"+                 
                   "<th>Nombre cliente</th>"+
                   "<th>Foto</th>"+
                   "<th>Dirección</th>"+
                   "<th>Hora cobro</th>"+
                   "<th>Estado</th>"+
                   "<th>Usuario registró</th>"+
                   "<th>Fecha entrega</th>"+
                   "<th>Nombre cobrador</th>"+
                   "<th>Código</th>"+
                   "<th>Préstamo</th>"+
                   "<th>Plan</th>"+
                   "<th>Cuota</th>"+
                   "<th></th>"+
                "</tr>"+
              "</thead>"+
              "<tbody></tbody>"+
            "</table>";
            $("#divtablaRegistroPrestamos").html(tabla);

            
            var btnConsultar = "";
            var btnImprimir = "";

            var scroll_left = 0;
            var det_name_prest = Array();
            var det_morasPagadas = Array();
            var det_morasPendientes = Array();
            var det_morasExoneradas = Array();

            $.each(data.registros,function(key,value) {

                scroll_left += 50;

                var dateTime = moment( value["fechaentregado"] );
                var full = dateTime.format('L');

                det_name_prest.push(value["nombreCliente"]+" ["+full+"]");
                det_morasPagadas.push(value["morasPagadas"]);
                det_morasPendientes.push(value["morasPendientes"]);
                det_morasExoneradas.push(value["morasExoneradas"]);

                var estadoPrestamo = "";

                if (value["estado"] == 0) {
                    estadoPrestamo = '<span class="badge badge-secondary">FINALIZADO</span>';                    
                }else if ( value["cuotasPendientes"] == 0 && value["morasPendientes"] == 0) {
                    estadoPrestamo = '<span class="badge bg-success">AL DÍA</span>';
                }else if( value["cuotasPendientes"] <= 2){
                    estadoPrestamo = '<span class="badge bg-warning">PENDIENTES: <br> '+value["cuotasPendientes"]+' CUOTAS <br> Y '+value["morasPendientes"]+' MORAS</span>';
                }else{
                    estadoPrestamo = '<span class="badge bg-danger">PENDIENTES: <br> '+value["cuotasPendientes"]+' CUOTAS <br> Y '+value["morasPendientes"]+' MORAS</span>';
                }


                
              if (Consultar == 1) {
                btnConsultar = " <button class='btn btn-default bg-lightblue tooltip2' style='cursor:pointer' href='#' ><span class='tooltiptext'>Pagos a préstamo</span> <i class='fa fa-eye fa-lg '></i></button>";
                btnCambiarRuta = " <button class='btn btn-default tooltip2' style='cursor:pointer' href='#' ><span class='tooltiptext'>Cambiar Ruta</span> <i class='fa fa-exchange-alt fa-lg '></i></button>";

              };

              if (Eliminar == 1) {
                btnImprimir = " <button class='btn btn-default bg-lightblue disabled tooltip2' style='cursor:pointer' href='#' ><span class='tooltiptext'>Imprimir Pagos</span> <i class='fa fa-print fa-lg '></i></button>";
              };


                      
             

              $("<tr></tr>")
                .append( "<td>" + (key + 1) + "</td>" )
               
                .append( "<td> " + value["nombreCliente"] + " </td>" )
                .append( "<td> <div class='filtr-item' data-category='1' data-sort='white sample'><a href='"+ value["foto"] +"' data-toggle='lightbox' data-title='Imagen de perfil'><img src='"+ value["foto"] +"' class='img-circle img-size-32 mr-2' alt='white sample'/></a></div> </td>" )
                .append( "<td> " + value["direccionvive"] + " </td>" )
                .append( "<td> " + value["horapago"] + " </td>" )                
                .append( "<td>" + estadoPrestamo + "</td>" )
                .append( "<td>" + value["usuarioentrego"] + "</td>" )
                .append( "<td>" + full + "</td>" )
                .append( "<td> " + value["usuariocobrador"] + " </td>" )
                .append( "<td>" + value["codigo"] + "</td>" )
                .append( "<td>Q." +  parseFloat(value["prestamo"]).toFixed(2) + "</td>" )
                .append( "<td>" + value["cuotas"] + "</td>" )
                .append( "<td>Q." +  parseFloat(value["resumenpagos"]).toFixed(2) + "</td>" )
               .append( $("<td></td>").append( 
                $("<div class='btn-group'></div>") 
                        
                    .append( $(btnConsultar)
                        .on("click",{ idprestamo:value["id"], valor_prestamo:value["prestamo"], tipoPlan:value["tipoPlan"], nombreCliente: value["nombreCliente"], fotoCliente: value["foto"], dirUser:value["direccionvive"], idcliente:value["idcliente"] } , mostrarPagosPrestamo) )
                        
                    .append( $(btnImprimir)
                        .on("click",{ idprestamo:value["id"] } , imprimirRegistroPrestamos) )

                    .append( $(btnCambiarRuta)
                        .on("click",{ idprestamo:value["id"], idcobrador:value["idcobrador"] } , funcionCambiarRuta) )   

                    )
                  )
                .appendTo("#tablaRegistroPrestamos > tbody");
            });

            $("#tablaRegistroPrestamos").DataTable({ 

                initComplete: function() {
                    $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
                },

                responsive: true,
                "aLengthMenu": [100],
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
                                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8,9,10,11,12 ]
                            }

                        },

                        {
                               extend: 'csv', 
                          orientation: 'Portrait',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            ,exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8,9,10,11,12 ]
                            }

                        },
                       
                        {
                               extend: 'excel', 
                          orientation: 'Portrait',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            ,exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8,9,10,11,12 ]
                            }

                        },
                        
                        {
                               extend: 'pdf', 
                          orientation: 'landscape',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            ,exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8,9,10,11,12 ]
                            }

                        }  ,
                        'print'
                    ],
                "sPaginationType": "full_numbers",
                
            });


            $('#tablaRegistroPrestamos').ScrollTo();

            //-------------
            //- BAR CHART -
            //-------------

            $("#contenedorbarChart").html(' <div class="card card-success">'+
                                                '<div class="card-header bg-lightblue">'+
                                                    '<h3 class="card-title">Gráfica de moras</h3>'+

                                                    '<div class="card-tools">'+
                                                        '<button type="button" id="btnMinimizar" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="card-body">'+
                                                    '<div class="chartWrapper">'+
                                                        '<div class="chartAreaWrapper">'+
                                                            '<div class="chartAreaWrapper2">'+
                                                                '<canvas id="myChart"></canvas>'+
                                                            '</div>'+
                                                        '</div>'+     

                                                        '<canvas id="myChartAxis" height="300" width="0"></canvas>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>');


                
            var ctx = document.getElementById("myChart").getContext("2d");

            var chart = {
                    options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {                                
                                onComplete: function(animation) {
                                    var sourceCanvas = myLiveChart.chart.canvas;
                                    var copyWidth = myLiveChart.scales['y-axis-0'].width - 10;
                                    var copyHeight = myLiveChart.scales['y-axis-0'].height + myLiveChart.scales['y-axis-0'].top + 10;
                                    var targetCtx = document.getElementById("myChartAxis").getContext("2d");
                                    targetCtx.canvas.width = copyWidth;
                                    targetCtx.drawImage(sourceCanvas, 0, 0, copyWidth, copyHeight, 0, 0, copyWidth, copyHeight);
                                }
                            }
                    },
                    type: 'bar',
                    data: {
                        labels: det_name_prest,
                        datasets: [
                            {
                                label               : 'Moras exoneradas',
                                backgroundColor     : 'rgba(245, 105, 84, 1)',
                                borderColor         : 'rgba(245, 105, 84, 1)',
                                pointRadius         : false,
                                pointColor          : 'rgba(245, 105, 84, 1)',
                                pointStrokeColor    : '#c1c7d1',
                                pointHighlightFill  : '#fff',
                                pointHighlightStroke: 'rgba(220,220,220,1)',
                                data                : det_morasExoneradas
                                },

                                {
                                label               : 'Moras pendientes',
                                backgroundColor     : 'rgba(210, 214, 222, 1)',
                                borderColor         : 'rgba(210, 214, 222, 1)',
                                pointRadius         : false,
                                pointColor          : 'rgba(210, 214, 222, 1)',
                                pointStrokeColor    : '#c1c7d1',
                                pointHighlightFill  : '#fff',
                                pointHighlightStroke: 'rgba(220,220,220,1)',
                                data                : det_morasPendientes
                                },

                                {
                                label               : 'Moras pagadas',
                                backgroundColor     : 'rgba(60,141,188,0.9)',
                                borderColor         : 'rgba(60,141,188,0.8)',
                                pointRadius          : false,
                                pointColor          : '#3b8bba',
                                pointStrokeColor    : 'rgba(60,141,188,1)',
                                pointHighlightFill  : '#fff',
                                pointHighlightStroke: 'rgba(60,141,188,1)',
                                data                : det_morasPagadas
                                },
                        ]
                    }};

            var myLiveChart = new Chart(ctx, chart);
        
            var newwidth = $('.chartAreaWrapper2').width() + scroll_left;
            $('.chartAreaWrapper2').width(newwidth);
            $('.chartAreaWrapper').animate({scrollLeft:newwidth});


             //-------------
            //- BAR CHART -
            //-------------





            //-------------
            //- REPORTE DE META -
            //-------------

            var sumaTotalMeta = 0;

            $.each(data.reportemeta,function(key,value) {      
                
                sumaTotalMeta += parseFloat(value["cuotas_pendientes"]);
                sumaTotalMeta += parseFloat(value["moras_pendientes"]);


              $("<tr></tr>")
                .append( "<td>" + (key + 1) + "</td>" )               
                .append( "<td> " + value["nombreCliente"] + " </td>" )
                .append( "<td> <div class='filtr-item' data-category='1' data-sort='white sample'><a href='"+ value["foto"] +"' data-toggle='lightbox' data-title='Imagen de perfil'><img src='"+ value["foto"] +"' class='img-circle img-size-32 mr-2' alt='white sample'/></a></div> </td>" )
                .append( "<td> " + value["usuariocobrador"] + " </td>" )                
                .append( "<td> Q. "+ parseFloat(value["cuotas_pendientes"]).toFixed(2) +" </td>" )
                .append( "<td> Q. "+ parseFloat(value["moras_pendientes"]).toFixed(2) +"  </td>" )
                .append( "<td> <b>Q. " + parseFloat(parseFloat(value["cuotas_pendientes"])+parseFloat(value["moras_pendientes"])).toFixed(2) + " </b> </td>" )              
                .appendTo("#tablaReporteMeta > tbody");
            });

            $("<tr style='border-top: 2px solid #000;'></tr>")
                .append( "<td colspan='6' style='border-bottom: 2px solid #000;'><h5> <b>TOTAL RECAUDO PENDIENTE: </b></h5></td>" )                              
                .append( "<td style='border-bottom: 2px solid #000;'> <h2><b>Q. " + parseFloat(sumaTotalMeta).toFixed(2) + " </b> </h2></td>" )              
                .appendTo("#tablaReporteMeta > tbody");        
                
                


            var totalCobrado = 0;


            $.each(data.registroPagosRealizados,function(key,value) {

                var dateTime = moment( value["fechapago"] );
                var full = dateTime.format('LL');
                totalCobrado += parseFloat(value["monto"]);

              $("<tr></tr>")
                .append( "<td>" + (key + 1) + "</td>" )
                .append( "<td>" + value["usuarioRecibio"] + "</td>" )
                .append( "<td>" + value["nombreCliente"] + "</td>" )
                .append( "<td> <div class='filtr-item' data-category='1' data-sort='white sample'><a href='"+ value["foto"] +"' data-toggle='lightbox' data-title='Imagen de perfil'><img src='"+ value["foto"] +"' class='img-circle img-size-32 mr-2' alt='white sample'/></a></div> </td>" )                
                .append( "<td>" + full + "</td>" )
                .append( "<td>" + value["descripcion"] + "</td>" )
                .append( "<td> <b> Q." + parseFloat(value["monto"]).toFixed(2) + " </b></td>" )
                .appendTo("#tablaReporteCaja > tbody");
            });



            $("<tr style='border-top: 2px solid #000;'></tr>")
                .append( "<td colspan='6' style='border-bottom: 2px solid #000;'><h5> <b>TOTAL RECAUDO: </b></h5></td>" )                              
                .append( "<td style='border-bottom: 2px solid #000;'> <h2><b>Q. " + parseFloat(totalCobrado).toFixed(2) + " </b> </h2></td>" )              
                .appendTo("#tablaReporteCaja > tbody");      

                var cs1 = 100;
                cs1 = Math.round( (totalCobrado*100)/parseInt(totalCobrado+sumaTotalMeta) );

                $("#contenedorprogress1").html( '<span class="progress-text">Meta del día:</span>'+
                                                    '<span class="float-right"><b>Q. '+parseFloat(totalCobrado).toFixed(2)+'/Q. '+parseFloat(totalCobrado+sumaTotalMeta).toFixed(2)+' </b></span>'+
                                                    '<div class="progress" style="height: 2rem;">'+
                                                    '<div class="progress-bar bg-success progress-bar-striped" style="width: '+cs1+'%"></div>'+
                                                    '</div>');
           

            setTimeout(function(){
                $("#btnMinimizar").trigger("click");
            }, 500);


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

    function imprimirRegistroPrestamos (e) {
        e.preventDefault();
        window.open('funciones/imprimereporte.php?id='+e.data.idprestamo);
    }
    
    

    function funcionCambiarRuta (e) {
        e.preventDefault();
        $("#formCambiarRuta")[0].reset();
        $("#divCambiarRuta").modal("show", {backdrop: "static"});
        $("#formCambiarRuta #idprestamoCambiar").val(e.data.idprestamo);
        $('#formCambiarRuta #rutaseleccionada').val(e.data.idcobrador).trigger('change.select2');  
    }

  
    /****************** GUARDAR DATOS DEL REGISTRO *******************/
    $("#btnGuardarCambiarRuta").on("click",guardarCambiarRuta);
    
    function guardarCambiarRuta(e){
        e.preventDefault();

        if($("#formCambiarRuta").valid()) {
            $.post("funciones/ws_prestamos.php", "accion=cambiarruta&"+$("#formCambiarRuta").serialize() ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");
                $("#divCambiarRuta").modal("hide");
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



    function mostrarPagosPrestamo (e) {
      e.preventDefault();
      var id_prestamo = e.data.idprestamo;

      $("#formNuevoPago #idprestamo").val(e.data.idprestamo);  

      $("#formNuevaMora #totalPrestamo").val(e.data.valor_prestamo);
      $("#formNuevoPago #tipoPlan").val(e.data.tipoPlan);


      $("#imgUser").html('<img class="img-circle elevation-2 selecCliente" rel="'+e.data.idcliente+'" style="cursor:pointer;" src="'+e.data.fotoCliente+'" alt="User Avatar">');      
      $("#nameUser").html(e.data.nombreCliente);
      $("#dirUser").html(e.data.dirUser);


        $(".selecCliente").on("click", function (e) {

            idcliente = $(e.target).closest('img').attr('rel');
            $("#formEditarCliente #idcliente").val(idcliente); 

            $.post("funciones/ws_clientes.php", { accion:"mostrar" , id:idcliente }, function(data) {
            if(data.resultado)
                {

                $("#formEditarCliente")[0].reset();
                $("#divEditarCliente").modal("show", {backdrop: "static"});
             
                
                modalActivo = 2;
                           

                    if(data.registros[0]["ruta"] == ""){
                        $('#formEditarCliente #uploaded_image_2').attr('src', 'upload/user.png');
                        $("#contEliminarImagen").attr("style","display:none;");
                    }else{
                        $('#formEditarCliente #uploaded_image_2').attr('src', data.registros[0]["ruta"]);
                        $("#contEliminarImagen").attr("style","display:block;");
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

        }); 


      $("#tituloNuevoPago").html("Nuevo Pago de: <b>"+e.data.nombreCliente+"</b>");
      $("#tituloPagarNuevoPendiente").html("Nuevo Pago de: <b>"+e.data.nombreCliente+"</b>");
      mostrarDetallePagosPrestamo(id_prestamo);      
    }

    

    
    function mostrarDetallePagosPrestamo(id_prestamo) {

        //$("#formNuevoPago #idprestamo").val(id_prestamo);   
        $("#idFinalizarPrestamo").val(id_prestamo);   
        $("#formPagarMora #id_prestamo").val(id_prestamo);   
        $("#divTablaPagosDetalle").fadeIn("slow");      
        $("#tablaPagosRegistroPrestamos  tbody tr").remove();
        $("#tablaPagosRealizados  tbody tr").remove();
        $("#divtablaPagosCapital").html('');
        $("#divInformePrestamo").html('');

        $.post("funciones/ws_prestamos.php", { accion: "mostrarDetPrestamo" , idprestamo : id_prestamo}, function(data) {
        if(data.resultado)
            { 


                if (data.informePrestamo[0]["estado"] == 0) {
                    $("#btnNuevoPago").prop("disabled",true);
                    $("#btnNuevaMora").prop("disabled",true);
                    $("#btnFinalizarPrestamo").prop("disabled",true);
                }else{
                    $("#btnNuevoPago").prop("disabled",false);
                    $("#btnNuevaMora").prop("disabled",false);
                    $("#btnFinalizarPrestamo").prop("disabled",false);
                }

                

            var tabla =
            "<h3 class='tituloH3'></h3><br>"+
            "<table id='tablaPagosRegistroPrestamos' class='table table-bordered table-sm table-striped' >"+
                "<thead>"+
                "<tr>  "+
                    "<th>No.</th>"+
                    "<th></th>"+
                    "<th>Saldo</th>"+
                    "<th>Valor</th>"+
                    "<th>Fecha</th>"+
                    "<th>Abono cuotas</th>"+
                    "<th>Mora</th>"+
                    "<th>Abono moras</th>"+
                "</tr>"+
                "</thead>"+
                "<tbody></tbody>"+
            "</table>";
            $("#divtablaPagosRegistroPrestamos").html(tabla);

            var suma_monto = 0;
            var suma_mora = 0;
            var cuotaRecuperada = 0;
            var moraRecuperada = 0;

            var permitirEliminacion = 1;

            $.each(data.registros,function(key,value) {         
                suma_monto += parseFloat(value["monto"]);

                if (value["morapagada"] != 2) {
                    suma_mora += parseFloat(value["mora"]);                    
                }
                
                if (value["pagado"] == 1) {
                    cuotaRecuperada+=parseFloat(value["monto"]);
                }

                if (value["morapagada"] == 1) {
                    moraRecuperada += parseFloat(value["mora"]);                    
                }

                if (value["tipo"] == 3 && (value["abonomora"] > 0 || value["abono"] > 0)) {
                    permitirEliminacion = 0;
                }

                moraRecuperada += parseFloat(value["abonomora"]);
                cuotaRecuperada+=parseFloat(value["abono"]);

            });

            var suma_total_pagar = suma_monto;
            var abono_informe = 0;
            var cont_Atrasadas = 0;
            var suma_Atrasadas = 0;
            var cont_Pagadas = 0;
            var suma_Pagadas = 0;

            var btnPendiente = "";


            $.each(data.registros,function(key,value) {        

                
                var dateTime = moment( value["fecha"] );
                var full = dateTime.format('LL');

                var colorFila = value["pagado"] == 0 ? "" : " style='background: #F8F19F;' ";
                var abono = value["abono"] == 0 ? value["fechapago_formateada"] : "Q."+parseFloat(value["abono"]).toFixed(2);
                var interes = value["mora"] == 0 ? "" : "Q."+parseFloat(value["mora"]).toFixed(2);
                var colorFilaInteres = value["morapagada"] == 1 ? " background: #F8F19F; " : "";      
                
                var abonomora = value["abonomora"] > 0 ? "Q."+parseFloat(value["abonomora"]).toFixed(2):'';

                if (value["morapagada"] == 2) {               
                    colorFilaInteres = " text-decoration: line-through;color: #a3a8af;font-weight: 500; ";
                }

                if (value["abono"] > 0) {
                    abono_informe = value["abono"];
                }

                if (value["pagado"] == 1) {
                    cont_Pagadas++;
                    suma_Pagadas+=parseFloat(value["monto"]);
                }

                if (value["pagado"] == 0 && value["mora"] > 0) {
                    cont_Atrasadas++;
                    suma_Atrasadas+=parseFloat(value["monto"]);
                }

                var moraPendiente = 0;

                var checked = "";
                if (value["tipo"] == 3) {
                    checked = "checked";
                    colorFila = " style='background: #FDEDEC;' ";

                    if (value["morapagada"] == 2) {               
                        colorFilaInteres = " background: #FDEDEC;  text-decoration: line-through;color: #a3a8af;font-weight: 500; ";
                    }else{
                        colorFilaInteres = " background: #FDEDEC; ";
                        moraPendiente += parseFloat(value["mora"]) - parseFloat(value["abonomora"]);
                    }
                }                
                
                
                if (data.informePrestamo[0]["estado"] == 1){

                
        
                    if ((value["pagado"] == 0 && value["abono"] >= 0 
                    && (value["morapagada"] == 0 || value["morapagada"] == 2 || value["morapagada"] == 3 || value["morapagada"] == 4)
                    && value["abonomora"] >= 0) || value["tipo"] == 3) {

                        var txtPendienteDeCobro = "Dejar pendiente de cobro";

                        if (value["tipo"] == 3) {
                            txtPendienteDeCobro = "Generar pago";
                        }
                        btnPendiente = " <div class='form-group clearfix'><div class='icheck-success d-inline'><input type='checkbox' "+checked+" value='"+value["id"]+"' totalCuota='"+(parseFloat(value["monto"]) - parseFloat(value["abono"]))+"' moraPendiente='"+moraPendiente+"' class='icheckPendiente' id='check_Pen"+key+"'><label for='check_Pen"+key+"' class='tooltip2'><span class='tooltiptext'>"+txtPendienteDeCobro+"</span></label></div></div>  ";                    
                    }else{
                        btnPendiente = "";
                    }

                }else{
                    btnPendiente = "";
                }
                

                $("<tr></tr>")
                .append( "<td "+colorFila+">" + (key + 1) + "</td>" )
                .append( "<td "+colorFila+"> "+ btnPendiente +" </td>" )
                .append( "<td "+colorFila+">Q." + parseFloat(suma_monto).toFixed(2) + "</td>" )
                .append( "<td "+colorFila+">Q." + parseFloat(value["monto"]).toFixed(2) + "</td>" )
                .append( "<td "+colorFila+">" + full + "</td>" )
                .append( "<td "+colorFila+">" + abono + "</td>" )
                .append( "<td style='text-align: center; "+colorFilaInteres+"'>" + interes + "</td>" )
                .append( "<td style='text-align: center; "+colorFilaInteres+"'>" + abonomora + "</td>" )

                .appendTo("#tablaPagosRegistroPrestamos > tbody");

                suma_monto -= parseFloat(value["monto"]);

            });

            $('#tablaPagosRegistroPrestamos').ScrollTo();

            var tabla =
            "<h3 class='tituloH3'></h3><br>"+
            "<table id='tablaPagosRealizados' class='table table-bordered table-sm table-striped' >"+
                "<thead>"+
                "<tr>  "+
                    "<th>No.</th>"+
                    "<th>Fecha pago</th>"+
                    "<th>Concepto</th>"+
                    "<th>Usuario registró</th>"+    
                    "<th>Valor</th>"+
                    "<th>Estado</th>"+
                    "<th>X</th>"+
                "</tr>"+
                "</thead>"+
                "<tbody></tbody>"+
            "</table>";
            $("#divtablaPagosRealizados").html(tabla);

            var btnEliminar = "";
            var btnVerificado = "";

            $.each(data.pagosrealizados,function(key,value) {     
                
                if (Eliminar == 1 && value["estado"] != 3  && data.informePrestamo[0]["estado"] == 1) {                    

                    if ( value["estado"] == 1 ) {
                        btnEliminar = " <button class='btn btn-default tooltip2' pCuota='1' style='cursor:pointer;' href='#' ><span class='tooltiptext'>Eliminar Pago</span> <i class='fa fa-trash fa-lg '></i></button>";                        
                    }else if(value["estado"] == 4){
                        btnEliminar = " <button class='btn btn-default tooltip2' pCuota='4' style='cursor:pointer;' href='#' ><span class='tooltiptext'>Eliminar Moras</span> <i class='fa fa-trash fa-lg '></i></button>";                        
                    }else if(value["estado"] == 5){
                        btnEliminar = " <button class='btn btn-default tooltip2' pCuota='5' style='cursor:pointer;' href='#' ><span class='tooltiptext'>Eliminar Moras</span> <i class='fa fa-trash fa-lg '></i></button>";                        
                    }else{
                        btnEliminar = "";
                    }

                    if (permitirEliminacion == 0) {
                        btnEliminar = "";                        
                    }

                    var checked = "";
                    if (value["verificado"] == 1) {
                        checked = "checked";
                    }

                    btnVerificado = " <div class='form-group clearfix'><div class='icheck-success d-inline'><input type='checkbox' "+checked+" value='"+value["id"]+"' class='icheckVerificado' id='checkVer"+key+"'><label for='checkVer"+key+"'>Verificado</label></div></div>  ";
                
                }else{
                    btnEliminar = "";
                    btnVerificado = "";
                }


                var colorFila = "";
                if (value["verificado"] == 1 || value["estado"] == 3) {
                    colorFila = " style='background: #F8F19F;' ";
                }

                
                var dateTime = moment( value["fechapago"] );
                var full = dateTime.format('LL');

                $("<tr></tr>")
                .append( "<td "+colorFila+">" + (key + 1) + "</td>" )
                .append( "<td "+colorFila+">" + full + "</td>" )
                .append( "<td "+colorFila+">" + value["descripcion"] + "</td>" )
                .append( "<td "+colorFila+">" + value["usuariocobro"] + "</td>" )
                .append( "<td "+colorFila+">Q." + parseFloat(value["monto"]).toFixed(2) + "</td>" )
                .append( "<td "+colorFila+"> "+ btnVerificado +" </td>" )
                .append( $("<td "+colorFila+"></td>").append( 
                    $("<div class='btn-group'></div>")                         
                    .append( $(btnEliminar)
                        .on("click",{ idpagosrealizados:value["id"] } , eliminarPago) )                           
                    )
                  )
                .appendTo("#tablaPagosRealizados > tbody");
            });


            $(".icheckVerificado").on("click",function(e){

                var idpagosrealizados = $(e.target).closest('input').attr('value'); //idpagosrealizados
                var id = $(e.target).closest('input').attr('id');

                var dato = 0;          
                if ($("#"+ id ).is(':checked')) {
                    dato = 1;
                }

                $.post("funciones/ws_prestamos.php", { dato:dato, id:idpagosrealizados , accion:"verificarPago" } ,function(data) {
                    if(data.resultado){
                        toastr.success(data.mensaje, "Exito");
                        $("#divFinalizarPrestamo").modal("hide");
                        mostrarDetallePagosPrestamo($("#formNuevoPago #idprestamo").val());
                    }
                    else{
                        toastr.warning(data.mensaje,"Info");
                    }
                }, "json")
                .fail(function() {
                    toastr.error("no se pudo conectar al servidor", "Error Conexión");
                });

            });



            $(".icheckPendiente").on("click",function(e){

                var iddetprestamospendiente = $(e.target).closest('input').attr('value'); //iddetprestamospendiente
                var id = $(e.target).closest('input').attr('id');        
                var totalCuota = $(e.target).closest('input').attr('totalCuota');        
                var moraPendiente = $(e.target).closest('input').attr('moraPendiente');        
                
                if ($("#"+ id ).is(':checked')) {

                    $("#idDejarCuotaPendiente").val(iddetprestamospendiente);
                    $("#idcheckpendiente").val(id);                    
                    $("#claveCuotaPendiente").val('')
                    $("#divDejarCuotaPendiente").modal("show", {backdrop: "static"});

                }else{
                
                    $("#formPagarNuevoPendiente")[0].reset();
                    $("#idDejarCuotaPendientx").val(iddetprestamospendiente);
                    $("#formPagarNuevoPendiente #valorCuotaPendiente").val(totalCuota);
                    $("#formPagarNuevoPendiente #valorMoraPendiente").val(moraPendiente);
                    $("#formPagarNuevoPendiente #newPendienteCantidad").attr("placeholder", parseFloat(parseFloat(totalCuota)+parseFloat(moraPendiente)));
                    $("#idcheckpendientx").val(id);                    
                    $("#divPagarNuevoPendiente").modal("show", {backdrop: "static"});

                    setTimeout(function() { $('#formPagarNuevoPendiente #newPendienteCantidad').focus() }, 500);

                }

            });



            if ($("#formNuevoPago #tipoPlan").val() == '5')  {

                var tabla =
                "<h3 class='tituloH3'>Pago de capital</h3><br>"+
                "<table id='tablaPagosCapital' class='table table-bordered table-sm table-striped' >"+
                    "<thead>"+
                    "<tr>  "+
                        "<th>No.</th>"+
                        "<th>Fecha pago</th>"+
                        "<th>Concepto</th>"+
                        "<th>Valor</th>"+
                        "<th>X</th>"+
                    "</tr>"+
                    "</thead>"+
                    "<tbody></tbody>"+
                "</table>";
                $("#divtablaPagosCapital").html(tabla);

                $.each(data.pagoscapital,function(key,value) {       
                    
                    if (Eliminar == 1 && data.informePrestamo[0]["estado"] == 1) {                    
                        btnEliminar_Capital = " <button class='btn btn-default tooltip2' style='cursor:pointer;' href='#' ><span class='tooltiptext'>Eliminar Capital</span> <i class='fa fa-trash fa-lg '></i></button>";                                
                    }else{
                        btnEliminar_Capital = "";
                    }
                    
                    var dateTime = moment( value["fechapago"] );
                    var full = dateTime.format('LL');

                    $("<tr></tr>")
                    .append( "<td>" + (key + 1) + "</td>" )
                    .append( "<td>" + full + "</td>" )
                    .append( "<td>" + value["descripcion"] + "</td>" )
                    .append( "<td>Q." + parseFloat(value["monto"]).toFixed(2) + "</td>" )
                    .append( $("<td></td>").append( 
                        $("<div class='btn-group'></div>")                         
                            .append( $(btnEliminar_Capital)
                                .on("click",{ idpagoscapital:value["id"] } , eliminar_Capital)                     
                            )
                        )
                    )
                    .appendTo("#tablaPagosCapital > tbody");
                });
                
            }



            var tipo_plan ="";

            if (data.informePrestamo[0]["tipo"] == 1) {
            var tipo_plan ="PAGOS DIARIOS";
            }else if (data.informePrestamo[0]["tipo"] == 2) {
            var tipo_plan ="PAGOS SEMANALES";
            }else if (data.informePrestamo[0]["tipo"] == 3) {
            var tipo_plan ="PAGOS QUINCENALES";
            }else if (data.informePrestamo[0]["tipo"] == 4) {
            var tipo_plan ="PAGOS MENSUALES (Interés + capital)";
            }else if (data.informePrestamo[0]["tipo"] == 5) {
            var tipo_plan ="PAGOS MENSUALES (Por interés)";
            }





            $("#divInformePrestamo").html('<div class="card card-primary card-tabs">'+
                                            '<div class="card-header p-0 pt-1 bg-lightblue">'+
                                                '<ul class="nav nav-tabs" role="tablist">'+
                                                    '<li class="nav-item">'+
                                                        '<a class="nav-link active" id="resPagos" data-toggle="pill" href="#res_Pagos" role="tab" aria-controls="res_Pagos" aria-selected="false">Resumen pagos</a>'+
                                                    '</li>'+
                                                    '<li class="nav-item">'+
                                                        '<a class="nav-link" id="totalAPagar" data-toggle="pill" href="#total_A_Pagar" role="tab" aria-controls="total_A_Pagar" aria-selected="true">Informe préstamo</a>'+
                                                    '</li>'+
                                                    '<li class="nav-item">'+
                                                        '<a class="nav-link" id="datosPrestamo" data-toggle="pill" href="#datos_De_Prestamo" role="tab" aria-controls="datos_De_Prestamo" aria-selected="true">Resumen</a>'+
                                                    '</li>'+
                                                '</ul>'+
                                            '</div>'+
                                            '<div class="card-body">'+
                                                '<div class="tab-content">'+
                                                    '<div class="tab-pane fade active show" id="res_Pagos" role="tabpanel" aria-labelledby="resPagos">'+
                                                    
                                                    '<table>'+
                                                        '<tbody>'+
                                                            '<tr><td style="width:25px;"></td><td style="width: 205px;"><h3>Cuotas Atrasadas</h3></td> <td style="width: 90px;"></td> <td></td></tr>'+
                                                            '<tr><td style="width:25px;">'+ cont_Atrasadas +'</td><td style="width: 205px;"> Cuota(s) Atrasadas de </td> <td>Q. '+ parseFloat(data.informePrestamo[0]["cuota"]).toFixed(2) +'</td><td>Q. '+ parseFloat(suma_Atrasadas).toFixed(2) +'</td> <td></td></tr>'+
                                                            '<tr><td style="width:25px;"> -</td><td style="width: 205px;"> Abono </td> <td style="width: 90px;"></td> <td style="width: 90px;">Q. '+ parseFloat(abono_informe).toFixed(2) +'</td> <td><b>Q. '+ (parseFloat(suma_Atrasadas) - parseFloat(abono_informe)).toFixed(2) +'</b></td></tr>'+
                                                            '<tr><td style="width:25px;"></td><td style="width: 205px;"></td> <td></td> <td></td></tr><tr><td style="width:25px;"></td><td style="width: 205px;"></td> <td></td> <td></td></tr>'+
                                                            '<tr><td style="width:25px;"></td><td style="width: 205px;"><h3>Cuotas Pagadas</h3></td> <td style="width: 90px;"></td> <td></td></tr><tr><td style="width:25px;"></td><td style="width: 205px;"></td> <td></td> </tr>'+
                                                            '<tr><td style="width:25px;"></td><td style="width: 205px;"></td> <td></td> </tr><tr><td style="width:25px;"> '+ cont_Pagadas +' </td><td style="width: 205px;"> Cuota(s) Pagadas de </td> <td style="width: 90px;">Q. '+ parseFloat(data.informePrestamo[0]["cuota"]).toFixed(2) +'</td> <td>Q. '+ parseFloat(suma_Pagadas).toFixed(2) +'</td> <td></td></tr>'+
                                                            '<tr><td style="width:25px;"> +</td><td style="width: 205px;"> Abono </td> <td style="width: 90px;"></td> <td style="width: 90px;">Q. '+ parseFloat(abono_informe).toFixed(2) +'</td> <td><b>Q. '+ (parseFloat(suma_Pagadas) + parseFloat(abono_informe)).toFixed(2) +'</b></td></tr>'+
                                                        '</tbody>'+
                                                    '</table>'+



                                                    '</div>'+
                                                    '<div class="tab-pane fade" id="total_A_Pagar" role="tabpanel" aria-labelledby="totalAPagar">'+                                
                                                        
                                                        '<br><h3>Total a pagar</h3>'+        
                                                        
                                                        
                                                        '<div  class="table-responsive " >'+
                                                            '<table class="table table-bordered" >'+

                                                                '<thead>'+
                                                                    '<tr> '+
                                                                        '<th> </th><th>Abono esperado </th> <th> Abono recuperado</th><th> TOTAL PENDIENTE </th>'+
                                                                    '</tr>'+
                                                                '</thead>'+


                                                                
                                                                '<tbody>'+

                                                                '<tr>'+                                                                   
                                                                    '<td>TOTAL CUOTAS</td>'+
                                                                    '<td> <b> Q. '+ parseFloat(suma_total_pagar).toFixed(2) +' </b> </td>'+
                                                                    '<td> <b> Q. '+ parseFloat(cuotaRecuperada).toFixed(2) +' </b>  </td>'+
                                                                    '<td> <b> Q. '+ parseFloat( parseFloat(suma_total_pagar) - parseFloat(cuotaRecuperada) ).toFixed(2) +' </b></td>'+

                                                                '</tr>'+

                                                                '<tr style="border-bottom: 2px solid #000;">'+                                                                   
                                                                    '<td>TOTAL MORAS</td>'+
                                                                    '<td> <b> Q. '+ parseFloat(suma_mora).toFixed(2) +' </b>  </td>'+
                                                                    '<td>  <b> Q. '+ parseFloat(moraRecuperada).toFixed(2) +' </b> </td>'+
                                                                    '<td> <b> Q. '+ parseFloat( parseFloat(suma_mora) - parseFloat(moraRecuperada) ).toFixed(2) +' </b> </td>'+

                                                                '</tr>'+


                                                                '<tr>'+                                                                   
                                                                    '<td>SUMA</td>'+
                                                                    '<td> <b> Q. '+ parseFloat( parseFloat(suma_total_pagar) + parseFloat(suma_mora) ).toFixed(2) +' </b> </td>'+
                                                                    '<td> <b> Q. '+ parseFloat( parseFloat(cuotaRecuperada) + parseFloat(moraRecuperada) ).toFixed(2) +' </b> </td>'+
                                                                    '<td style="border-bottom: 2px solid #000;"> <h2> <b> Q. '+ parseFloat( ( parseFloat(suma_total_pagar) + parseFloat(suma_mora) ) - (parseFloat(cuotaRecuperada) + parseFloat(moraRecuperada)) ).toFixed(2) +' </b> </h2> </td>'+

                                                                '</tr>'+


                                                                
                                                                '</tbody>'+
                                                            '</table>'+
                                                        '</div>'+



                                                    '</div>'+

                                                    '<div class="tab-pane fade" id="datos_De_Prestamo" role="tabpanel" aria-labelledby="datosPrestamo">'+                                
                                                        
                                                       
                                                    '<div class="card-footer">'+
                                                    '  <div class="row">'+
                                                    '    <div class="col-sm-3 col-6">'+
                                                    '      <div class="description-block border-right">'+
                                                    '        <h3 class="description-header"><b> Q. '+ parseFloat( $("#formNuevaMora #totalPrestamo").val() ).toFixed(2) +' </b></h3>'+
                                                    '        <span class="description-text">PRÉSTAMO</span>'+
                                                    '      </div>'+
                                                    '    </div>'+
                                                    '    <div class="col-sm-3 col-6">'+
                                                    '      <div class="description-block border-right">'+
                                                    '        <h3 class="description-header"><b> Q. '+ parseFloat(suma_total_pagar).toFixed(2) +' </b></h3>'+
                                                    '        <span class="description-text">PRÉSTAMO + INTERES</span>'+
                                                    '      </div>'+
                                                    '    </div>'+
                                                    '    <div class="col-sm-3 col-6">'+
                                                    '      <div class="description-block border-right">'+
                                                    '        <h3 class="description-header"><b>'+tipo_plan+'</b></h3>'+
                                                    '        <span class="description-text">PLAN</span>'+
                                                    '      </div>'+
                                                    '    </div>'+
                                                    '    <div class="col-sm-3 col-6">'+
                                                    '      <div class="description-block">'+
                                                    '         <h3 class="description-header"><b> Q. '+ parseFloat(data.informePrestamo[0]["cuota"]).toFixed(2) +' </b></h3>'+
                                                    '      <span class="description-text">CUOTA</span>'+
                                                    '     </div>'+
                                                    '    </div>'+
                                                    ' </div>'+
                                                    '</div>'+



                                                    '</div>'+


                                                '</div>'+
                                            '</div>'+
                                        '</div>');


                var smo = 0;
                var sma = 0;
                var crda = 0;
                var mrda = 0;

                var cuotaParaUltima = 0;
                var moraParaUltima = 0;

                $.each(data.pendienteactual,function(key,value) {         

                    if (value["tipo"] != 3) {
                        smo += parseFloat(value["monto"]);         
                        mrda += parseFloat(value["abonomora"]);
                        crda += parseFloat(value["abono"]);               
                    }else{
                        cuotaParaUltima += parseFloat(value["monto"]);         
                        
                        cuotaParaUltima -= parseFloat(value["abono"]);
                        if (value["morapagada"] != 2) {
                            moraParaUltima += parseFloat(value["mora"]);                    
                            moraParaUltima -= parseFloat(value["abonomora"]);                    
                        }
                    }


                    if (value["morapagada"] != 2 && value["morapagada"] != 4) {
                        sma += parseFloat(value["mora"]);                    
                    }

    
                    
                    if (value["pagado"] == 1) {
                        crda+=parseFloat(value["monto"]);
                    }

                    if (value["morapagada"] == 1) {
                        mrda += parseFloat(value["mora"]);                    
                    }

                    

                });




                $("#tablaPendienteActual").html('<thead>'+
                                                    '<tr> '+
                                                        '<th> </th><th>Abono esperado </th> <th> Abono recuperado</th><th> TOTAL PENDIENTE </th>'+
                                                    '</tr>'+
                                                '</thead>'+

                                                '<tbody>'+

                                                '<tr>'+                                                                   
                                                    '<td>TOTAL CUOTAS</td>'+
                                                    '<td> <b> Q. '+ parseFloat(smo).toFixed(2) +' </b> </td>'+
                                                    '<td> <b> Q. '+ parseFloat(crda).toFixed(2) +' </b>  </td>'+
                                                    '<td> <h2> <b> Q. '+ parseFloat( parseFloat(smo) - parseFloat(crda) ).toFixed(2) +' </b> </h2> </td>'+

                                                '</tr>'+

                                                '<tr style="border-bottom: 2px solid #000;">'+                                                                   
                                                    '<td>TOTAL MORAS</td>'+
                                                    '<td> <b> Q. '+ parseFloat(sma).toFixed(2) +' </b>  </td>'+
                                                    '<td>  <b> Q. '+ parseFloat(mrda).toFixed(2) +' </b> </td>'+
                                                    '<td> <h2> <b> Q. '+ parseFloat( parseFloat(sma) - parseFloat(mrda) ).toFixed(2) +' </b> </h2> </td>'+

                                                '</tr>'+

                                                '<tr>'+                                                                   
                                                    '<td>SUMA</td>'+
                                                    '<td> <b> Q. '+ parseFloat( parseFloat(smo) + parseFloat(sma) ).toFixed(2) +' </b> </td>'+
                                                    '<td> <b> Q. '+ parseFloat( parseFloat(crda) + parseFloat(mrda) ).toFixed(2) +' </b> </td>'+
                                                    '<td style="border-bottom: 2px solid #000;"> <b> Q. '+ parseFloat( ( parseFloat(smo) + parseFloat(sma) ) - (parseFloat(crda) + parseFloat(mrda)) ).toFixed(2) +' </b> </td>'+

                                                '</tr>'+
                                                
                                            '</tbody>');


            $("#tablaPendienteFinal").html('');
            if (cuotaParaUltima != 0 || moraParaUltima != 0) {
                

                $("#tablaPendienteFinal").html('<thead>'+
                                                    '<tr> '+
                                                        '<th> </th><th>Abono esperado </th>'+
                                                    '</tr>'+
                                                '</thead>'+

                                                '<tbody>'+

                                                '<tr>'+                                                                   
                                                    '<td>TOTAL CUOTAS</td>'+
                                                    '<td> <b> Q. '+ parseFloat(cuotaParaUltima).toFixed(2) +' </b> </td>'+
                                                '</tr>'+

                                                '<tr style="border-bottom: 2px solid #000;">'+                                                                   
                                                    '<td>TOTAL MORAS</td>'+
                                                    '<td> <b> Q. '+ parseFloat(moraParaUltima).toFixed(2) +' </b>  </td>'+
                                                '</tr>'+
                                                '<tr>'+                                                                   
                                                    '<td>SUMA</td>'+
                                                    '<td> <h2> <b> Q. '+ parseFloat( parseFloat(cuotaParaUltima) + parseFloat(moraParaUltima) ).toFixed(2) +' </b> </h2> </td>'+
                                                '</tr>'+
                                                
                                            '</tbody>');

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




    $("#botonDejarCuotaPendiente").on("click",confirmarDejarPendiente);
    
    function confirmarDejarPendiente(e){
        e.preventDefault();

        var claveCuotaPendiente = $("#claveCuotaPendiente").val();

        if( claveCuotaPendiente.trim() == "123" || claveCuotaPendiente.trim() == "123" ){

            $.post("funciones/ws_prestamos.php", { id:$("#idDejarCuotaPendiente").val() , accion:"dejarPendienteCobro" } ,function(data) {
                if(data.resultado){
                    toastr.success(data.mensaje, "Exito");
                    $("#divDejarCuotaPendiente").modal("hide");
                    mostrarDetallePagosPrestamo($("#formNuevoPago #idprestamo").val());                    
                }
                else{
                    toastr.warning(data.mensaje,"Info");
                }
            }, "json")
            .fail(function() {
                toastr.error("no se pudo conectar al servidor", "Error Conexión");
            });

        }else{
            toastr.error("Contraseña incorrecta para realizar acción", "Error de Contraseña");
        }

    }


    $("#btnCancelarDejarCuotaPendiente").on("click",function(e){    
        $("#"+$("#idcheckpendiente").val()).prop("checked", false);    
    });

    $("#btnCancelarDejarCuotaPendientex").on("click",function(e){    
        $("#"+$("#idcheckpendiente").val()).prop("checked", false);    
    });


    $("#btnCancelarPagarNuevoPendiente").on("click",function(e){    
        $("#"+$("#idcheckpendientx").val()).prop("checked", true);    
    });

    $("#btnCancelarPagarNuevoPendientex").on("click",function(e){    
        $("#"+$("#idcheckpendientx").val()).prop("checked", true);    
    });



    $("#btnGuardarPagarNuevoPendiente").on("click",guardarNuevoPendiente);
    
    function guardarNuevoPendiente(e){
        e.preventDefault();

        if($("#formPagarNuevoPendiente").valid()){

            var n1 = parseFloat($("#formPagarNuevoPendiente #valorCuotaPendiente").val());
            var n2 = parseFloat($("#formPagarNuevoPendiente #valorMoraPendiente").val());
            var n3 = parseFloat($("#formPagarNuevoPendiente #newPendienteCantidad").val());                

            if (n3 == (n1+n2)) {

                $.post("funciones/ws_prestamos.php", "accion=pagarPendientes&idprestamo="+$("#formNuevoPago #idprestamo").val()+"&"+$("#formPagarNuevoPendiente").serialize()  ,function(data) {
                    if(data.resultado){
                        toastr.success(data.mensaje, "Exito");
                        $("#divPagarNuevoPendiente").modal("hide");
                        mostrarDetallePagosPrestamo($("#formNuevoPago #idprestamo").val());
                    }
                    else{
                        toastr.warning(data.mensaje,"Info");
                    }
                }, "json")
                .fail(function() {
                    toastr.error("no se pudo conectar al servidor", "Error Conexión");
                });

            }else{
                toastr.warning("El valor ingresado es diferente de lo pendiente en la cuota","Info");
            }


        }
        

    }








    /******************  MUESTRA EL FORMULARIO PARA ELIMINAR LOS REGISTROS *******************/
    function eliminarPago (e) {
      e.preventDefault();
      $("#divEliminarPago").modal("show", {backdrop: "static"});
      $("#ideliminarPago").val(e.data.idpagosrealizados);
      $("#pagocuota").val($(this).attr("pcuota"));
      $("#claveAnulacionPago").val('');
      $("#justificacion").val('');
    }

    function eliminar_Capital (e) {
      e.preventDefault();
      $("#divEliminarCapital").modal("show", {backdrop: "static"});
      $("#ideliminarCapital").val(e.data.idpagoscapital);

      $("#claveAnulacionCapital").val('')
    }

    $("#btnEliminarPago").on("click",guardarEliminarPago);
    
    function guardarEliminarPago(e){
        e.preventDefault();


        var claveAnulacionPago = $("#claveAnulacionPago").val();
        var justificacion = $("#justificacion").val();


        if( justificacion.trim() != "" ){        

            if( claveAnulacionPago.trim() == "123" || claveAnulacionPago.trim() == "123" ){
                
            

                if ($("#pagocuota").val() == 1 && $("#formNuevoPago #tipoPlan").val() == '5') {

                    //Eliminando pago de interés, plan 5
                    $.post("funciones/ws_prestamos.php", { 
                        idpago:$("#ideliminarPago").val() , 
                        justificacion:$("#justificacion").val(), 
                        accion:"eliminarInteres_plan5" 
                    } ,function(data) {
                    if(data.resultado){
                        toastr.success(data.mensaje, "Exito");
                        $("#divEliminarPago").modal("hide");
                        mostrarDetallePagosPrestamo($("#formNuevoPago #idprestamo").val());
                    }
                    else{
                        toastr.warning(data.mensaje,"Info");
                    }
                    }, "json")
                    .fail(function() {
                    toastr.error("no se pudo conectar al servidor", "Error Conexión");
                    });
                    
                }else{

                    //Eliminando pago de cuota de cualquier otro plan, (menos plan 5)
                    $.post("funciones/ws_prestamos.php", { 
                        idpago:$("#ideliminarPago").val() , 
                        justificacion:$("#justificacion").val(),
                        accion:"eliminarPago", 
                        pagocuota:$("#pagocuota").val(),
                        tipoPlan:$("#formNuevoPago #tipoPlan").val() 
                    } ,function(data) {
                    if(data.resultado){
                        toastr.success(data.mensaje, "Exito");
                        $("#divEliminarPago").modal("hide");
                        mostrarDetallePagosPrestamo($("#formNuevoPago #idprestamo").val());
                    }
                    else{
                        toastr.warning(data.mensaje,"Info");
                    }
                    }, "json")
                    .fail(function() {
                    toastr.error("no se pudo conectar al servidor", "Error Conexión");
                    });

                }


            }else{
                toastr.error("Contraseña incorrecta para realizar anulación", "Error de Contraseña");
            }

        }else{
            toastr.warning("Debe ingresar la justificación de la anulación","Info");

        }

    }


    $("#btnEliminarCapital").on("click",guardarEliminarCapital);

    function guardarEliminarCapital(e){
        e.preventDefault();

        var claveAnulacionCapital = $("#claveAnulacionCapital").val();

        if( claveAnulacionCapital.trim() == "123" || claveAnulacionCapital.trim() == "123" ){
            
            $.post("funciones/ws_prestamos.php", { idpagoscapital:$("#ideliminarCapital").val() , accion:"eliminarCapital_plan5" } ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");
                $("#divEliminarCapital").modal("hide");
                mostrarDetallePagosPrestamo($("#formNuevoPago #idprestamo").val());
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
    
    function mostrarModalNuevoPago(e){
        e.preventDefault();

        if ($("#formNuevoPago #tipoPlan").val() == '5') {
            $("#selectTP").fadeIn("fast");            
            $('#tipopago').val(1).trigger('change.select2');
        }else{
            $("#selectTP").fadeOut("fast");
            $('#tipopago').val(1).trigger('change.select2');
        }


        $("#contenedorCheckAPMP").fadeIn("fast");


        $("#formNuevoPago")[0].reset();       
        $("#divNuevoPago").modal("show", {backdrop: "static"});

        setTimeout(function() { $('#formNuevoPago #newCantidad').focus() }, 500);
    }

    /****************** MOSTRAR MODAL NUEVO REGISTRO *******************/
    $("#btnNuevaMora").on("click",mostrarModalNuevaMora);
    
    function mostrarModalNuevaMora(e){
        e.preventDefault();
        $("#formNuevaMora")[0].reset();    
        $("#divNuevaMora").modal("show", {backdrop: "static"});
        $("#formPagarMora #iddetprestamos").val('');
        buscarMoras();   
    }


    function buscarMoras() {

        morasAnuladas = [];
        fechaAnuladas = [];
        $("#btnExonerarNuevaMora").fadeOut("fast");
        $("#fechasSeleccionadas").html("");
        
        $("#tablaDetalleMora  tbody tr").remove();
        $.post("funciones/ws_prestamos.php", { accion:"mostrarMorasXprestamo", idprestamo:$("#formNuevoPago #idprestamo").val() } ,function(data)
        {            
            if(data.resultado){
                

                var btnEditar = "";
                var btnEliminar = "";

                var total = 0;

                $.each(data.registros,function(key,value) {

                    total += parseFloat(value["mora"]);

                    if (Modificar == 1) {
                        btnEditar = " <button id='btn"+value["id"]+"' class='btn btn-default bg-lightblue tooltip2' style='cursor:pointer; ' href='#' ><span class='tooltiptext'>Pagar Mora</span><i class='fas fa-money-bill fa-lg '></i></button>";
                    };
                    
                    var dateTime = moment( value["fecha"] );
                    var full = dateTime.format('LL');

                    $("<tr id='fila"+value["id"]+"' class='sender'></tr>")
                    .append( "<td><div class='icheck-success'><input type='checkbox' value='"+value["id"]+"' class='icheckMoras' id='check"+key+"'><label for='check"+key+"'></label></div></td>" )
                    .append( "<td>" + full + " <input type='hidden' class='form-control' id='fecha"+value["id"]+"' value='" + full + "'> </td>" )
                    .append( "<td>" + "Q."+ parseFloat(value["mora"]).toFixed(2) +"</td>" )
                    .append( $("<td></td>").append( 
                        $("<div class='btn-group'></div>") 
                            
                        .append( $(btnEditar)
                            .on("click",{ id:value["id"], monto:value["mora"], full:full, cantidadn:value["cantidadn"] } , editarMora) )                                                       
                        )

                    )
                    .appendTo("#tablaDetalleMora > tbody");

                });

                //$("#h3TotalMora").html("Total Mora Q. "+total.toFixed(2));
                $("#tablaDetalleMora a").tooltip(); 

                $(".icheckMoras").on("click",function(e){

                    var id = $(e.target).closest('input').attr('id');
                    var idfila = $(e.target).closest('input').attr('value'); //iddetprestamos
                    var txtfecha = $("#fecha"+idfila).val();



                    var pos = -1;
                    for (var i = 0; i < morasAnuladas.length ; i++) {
                        if (morasAnuladas[i] == idfila) {
                            pos = i;
                            break;
                        }
                    }

                    var pfecha = -1;
                    for (var i = 0; i < fechaAnuladas.length ; i++) {
                        if (fechaAnuladas[i] == txtfecha) {
                            pfecha = i;
                            break;
                        }
                    }


                    if ($("#"+ id ).is(':checked')) {

                        morasAnuladas.push(parseInt(idfila));
                        fechaAnuladas.push(txtfecha);

                        $("#fila"+idfila).addClass("tachado");
                        $("#btn"+idfila).prop("disabled",true);
                        $("#btn"+idfila).removeClass("bg-lightblue");
                    }else{

                        if (pos != -1) {
                            morasAnuladas.splice(pos,1);                            
                        }

                        if (pfecha != -1) {
                            fechaAnuladas.splice(pfecha,1);                            
                        }



                        $("#fila"+idfila).removeClass("tachado");
                        $("#btn"+idfila).prop("disabled",false);      
                        $("#btn"+idfila).addClass("bg-lightblue");
                    }


                    if (morasAnuladas.length > 0) {
                        $("#btnExonerarNuevaMora").fadeIn("fast");
                    }else{
                        $("#btnExonerarNuevaMora").fadeOut("fast");
                    }


                });


            }     
            
        }, "json")
        .fail(function()
        {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });
    }


    $("#btnExonerarNuevaMora").on("click",guardarExonerarNuevaMora);
    
    function guardarExonerarNuevaMora(e){
        e.preventDefault();        
        $("#divNuevaMora").modal("hide");

        var listado = "";
        listado += "<ul class='list-group'>";        
            $.each(fechaAnuladas,function(key,value) {                
                listado +=
                "<li class='list-group-item list-group-item-danger'>"+
                "<small class='pull-right'></small>"+
                "<h5 style=\" display: inline; margin: 0px; padding: 0px;\">"+value+" &nbsp; &nbsp; </h5>";

                listado +="</li>";
            });

            listado +=
        "</ul>";
        
        $("#fechasSeleccionadas").html(listado);


        $("#divEliminarMora").modal("show", {backdrop: "static"});
        $("#claveExoneracionMoras").val('');
    }


    $("#btnEliminarMora").on("click",guardarEliminarMora);
    
    function guardarEliminarMora(e){
        e.preventDefault();

        if ( morasAnuladas.length != 0 ) {

            var claveExoneracionMoras = $("#claveExoneracionMoras").val();

            if( claveExoneracionMoras.trim() == "123" || claveExoneracionMoras.trim() == "123" ){
                
                $.post("funciones/ws_prestamos.php", 
                { 
                    morasAnuladas:JSON.stringify(morasAnuladas),                               
                    accion:"exonerarMora" 
                } ,function(data) {
                if(data.resultado){
                    toastr.success(data.mensaje, "Exito");
                    $("#divEliminarMora").modal("hide");
                    mostrarDetallePagosPrestamo($("#formNuevoPago #idprestamo").val());
                }
                else{
                    toastr.warning(data.mensaje,"Info");
                }
                }, "json")
                .fail(function() {
                toastr.error("no se pudo conectar al servidor", "Error Conexión");
                });


            }else{
                toastr.error("Contraseña incorrecta para realizar exoneración", "Error de Contraseña");
            }


        }else{
            toastr.warning("No hay moras para cobrar","Info");
        }

    }



    function editarMora(e) {
        e.preventDefault();
        $("#divNuevaMora").modal("hide");
        $("#formPagarMora")[0].reset();    
        $("#formPagarMora #iddetprestamos").val(e.data.id);            
        $("#divPagarMora").modal("show", {backdrop: "static"});
        $("#etiquetaMora").html("Mora del día ( "+e.data.full+"):");
        $("#montomora").val(e.data.monto);

    }
    


    $("#btnPagarMora").on("click",guardarPagarMora);
    
    function guardarPagarMora(e){
        e.preventDefault();

        if($("#formPagarMora").valid()){

            $.post("funciones/ws_prestamos.php", "accion=pagarMora&"+$("#formPagarMora").serialize()  ,function(data) {
                if(data.resultado){
                    toastr.success(data.mensaje, "Exito");
                    $("#divPagarMora").modal("hide");
                    mostrarDetallePagosPrestamo($("#formNuevoPago #idprestamo").val());
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


    

    $("#btnGuardarNuevoPago").on("click",guardarNuevoPago);
    
    function guardarNuevoPago(e){
        e.preventDefault();
        bloquearBotonNuevoPago();
        //Si es 1 primero se abonan las moras pendientes
        //Si es 0 entonces solo se abonan las cuotas

        var btnPrimeroMorasPendientes = $("#btnPrimeroMorasPendientes").is(':checked') ? 1:0;        


        if ($("#formNuevoPago #newCantidad").val() != '' && $("#formNuevoPago #newCantidad").val() != 0) {

            if ($("#formNuevoPago #newCantidad").val() > 0) {   
                
                if ($("#formNuevoPago #tipoPlan").val() == '5' && $("#formNuevoPago #tipopago").val() == '2') {

                    //Pago de capital, plan 5
                    $.post("funciones/ws_prestamos.php", 
                    { 
                        idprestamo:$("#formNuevoPago #idprestamo").val(), 
                        monto:$("#formNuevoPago #newCantidad").val(), 
                        fechainicio:$("#formNuevoPago #fechainicio").val(), 
                        accion:"pagoCapital_plan5"
                    } ,function(data) {
                    if(data.resultado){
                        toastr.success(data.mensaje, "Exito");
                        $("#divNuevoPago").modal("hide");
                        mostrarDetallePagosPrestamo($("#formNuevoPago #idprestamo").val());
                        desbloquearBotonNuevoPago();
                    }
                    else{
                        toastr.warning(data.mensaje,"Info");
                        desbloquearBotonNuevoPago();
                    }
                    }, "json")
                    .fail(function() {
                        toastr.error("no se pudo conectar al servidor", "Error Conexión");
                        desbloquearBotonNuevoPago();
                    });

                }else if ($("#formNuevoPago #tipoPlan").val() == '5' && $("#formNuevoPago #tipopago").val() == '1') {

                    //Pago de interés, plan 5
                    $.post("funciones/ws_prestamos.php", 
                    { 
                        idprestamo:$("#formNuevoPago #idprestamo").val(), 
                        monto:$("#formNuevoPago #newCantidad").val(), 
                        fechainicio:$("#formNuevoPago #fechainicio").val(), 
                        accion:"pagoInteres_plan5",
                        btnPrimeroMorasPendientes:btnPrimeroMorasPendientes
                    } ,function(data) {
                    if(data.resultado){
                        toastr.success(data.mensaje, "Exito");
                        $("#divNuevoPago").modal("hide");
                        mostrarDetallePagosPrestamo($("#formNuevoPago #idprestamo").val());
                        desbloquearBotonNuevoPago();
                    }
                    else{
                        toastr.warning(data.mensaje,"Info");
                        desbloquearBotonNuevoPago();
                    }
                    }, "json")
                    .fail(function() {
                        toastr.error("no se pudo conectar al servidor", "Error Conexión");
                        desbloquearBotonNuevoPago();
                    });

                }else{
                    
                    //Pago de cuotas normales, de cualquier tipo de plan (menos plan 5)
                    $.post("funciones/ws_prestamos.php", 
                    { 
                        idprestamo:$("#formNuevoPago #idprestamo").val(), 
                        monto:$("#formNuevoPago #newCantidad").val(), 
                        fechainicio:$("#formNuevoPago #fechainicio").val(), 
                        accion:"nuevoPago",
                        btnPrimeroMorasPendientes:btnPrimeroMorasPendientes
                    } ,function(data) {
                    if(data.resultado){
                        toastr.success(data.mensaje, "Exito");
                        $("#divNuevoPago").modal("hide");
                        mostrarDetallePagosPrestamo($("#formNuevoPago #idprestamo").val());
                        desbloquearBotonNuevoPago();
                    }
                    else{
                        toastr.warning(data.mensaje,"Info");
                        desbloquearBotonNuevoPago();
                    }
                    }, "json")
                    .fail(function() {
                        toastr.error("no se pudo conectar al servidor", "Error Conexión");
                        desbloquearBotonNuevoPago();
                    });

                }
                

            }else{
                toastr.warning("El pago no puede ser negativo","Info");
                desbloquearBotonNuevoPago();
            }

        }else{
            toastr.warning("Ingrese la cantidad","Info");
            desbloquearBotonNuevoPago();
        }

    }


    function bloquearBotonNuevoPago(){
        $("#btnGuardarNuevoPago").prop("disabled",true);
        $("#btnGuardarNuevoPago").html('Guardando<i class="fas fa-1x fa-sync-alt fa-spin"></i>');
    }

    function desbloquearBotonNuevoPago(){
        $("#btnGuardarNuevoPago").prop("disabled",false);
        $("#btnGuardarNuevoPago").html('Guardar');    
    }



    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox({
        alwaysShowClose: true
        });
    });


    $("#btnFinalizarPrestamo").on("click",guardarFinalizarPrestamo);

    function guardarFinalizarPrestamo (e) {
      e.preventDefault();
      $("#divFinalizarPrestamo").modal("show", {backdrop: "static"});
      $("#claveAnulacionPrestamo").val('');
    }



    $("#botonFinalizarPrestamo").on("click",aceptarFinalizarPrestamo);
    
    function aceptarFinalizarPrestamo(e){
        e.preventDefault();

        var claveAnulacionPrestamo = $("#claveAnulacionPrestamo").val();

        if( claveAnulacionPrestamo.trim() == "123" || claveAnulacionPrestamo.trim() == "123" ){

            $.post("funciones/ws_prestamos.php", { idprestamo:$("#idFinalizarPrestamo").val() , accion:"finalizarPrestamo" } ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");
                $("#divFinalizarPrestamo").modal("hide");
                mostrarDetallePagosPrestamo($("#formNuevoPago #idprestamo").val());
            }
            else{
                toastr.warning(data.mensaje,"Info");
            }
            }, "json")
            .fail(function() {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
            });

        }else{
            toastr.error("Contraseña incorrecta para realizar finalización", "Error de Contraseña");
        }

    }



    $("#btnActualizarPrestamos").on("click",function(e){

        bloquearPantalla("Espere por favor");

        $.post("funciones/ws_prestamos.php", { accion:"actualizarPrestamosActivos" } ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");               
                desbloquearPantalla();
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


    });


    
    $("#usuarioseleccionado").on("change",function(e){
        e.preventDefault();
        mostrarRegistroPrestamos();
    });

    $("#btnPrimeroMorasPendientes").on("click",function(e){    
        
        if ($("#btnPrimeroMorasPendientes").is(':checked')) {
            $("#divConfirmarCambio").modal("show", {backdrop: "static"});
            $("#claveConfirmarCambio").val('');
        }

    });

    $("#btnConfirmarCambio").on("click",function(e){    

        var claveConfirmarCambio = $("#claveConfirmarCambio").val();

        if( claveConfirmarCambio.trim() == "123" || claveConfirmarCambio.trim() == "123" ){

            $("#btnPrimeroMorasPendientes").prop("checked", true);
            $("#divConfirmarCambio").modal("hide");

        }else{
            toastr.error("Contraseña incorrecta", "Error de Contraseña");
        }
        
    });

    $("#btnCancelarConfirmarCambio").on("click",function(e){    
        $("#btnPrimeroMorasPendientes").prop("checked", false);    
    });

    $("#btnCancelarConfirmarCambiox").on("click",function(e){    
        $("#btnPrimeroMorasPendientes").prop("checked", false);    
    });



    $("#formNuevoPago #tipopago").on("change",function(e){
        e.preventDefault();

        if ($("#formNuevoPago #tipopago").val() == 1) {
            $("#contenedorCheckAPMP").fadeIn("fast");
            
        }else if($("#formNuevoPago #tipopago").val() == 2){
            $("#contenedorCheckAPMP").fadeOut("fast");
            
        }

    });




    
    
///////////////////////////////////////////////////////////////
////////////////////CÓDIGO PARA SUBIR IMAGEN///////////////////
///////////////////////////////////////////////////////////////

var $modal = $('#modal');
    var image = document.getElementById('sample_image');
    var cropper;
   

    $('#upload_image_2').change(function(event){
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
                    //console.log(data);

                    var nuevaruta = data.substr(3, data.length);
                    //console.log(nuevaruta);

                    rutaActual = nuevaruta;

                    if (data == '../upload/user.png') {
                        rutaActual = '';
                        toastr.warning("La imagen no se cargo correctamente, por favor recarga la pagina o intenta con otra imagen","Info");
                    }

                    

                    //console.log(rutaActual);

                    $modal.modal('hide');

                    if(modalActivo == 1){
                        $('#uploaded_image').attr('src', nuevaruta);
                    }else if(modalActivo == 2){
                        $('#uploaded_image_2').attr('src', nuevaruta);
                    }
                    
                    //alert("success upload image");
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




    $("#btnCancelarEditarCliente").click(function(){  
        if(rutaActual != ''){
        $.post("funciones/ws_clientes.php", { accion:"eliminarImagen", ruta:rutaActual } ,function(data) {
        if(data.resultado){
            //console.log(data.mensaje);

            rutaActual = "";
            
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


    $("#btnCancelarEditarClientex").click(function(){  

        if(rutaActual != ''){
        $.post("funciones/ws_clientes.php", { accion:"eliminarImagen", ruta:rutaActual } ,function(data) {
        if(data.resultado){
            //console.log(data.mensaje);

            rutaActual = "";
            
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
    


/****************** MODIFICAR DATOS DEL REGISTRO *******************/
$("#btnGuardarEditarCliente").on("click",guardarEditarCliente);

function guardarEditarCliente(e){
  e.preventDefault();


  bloquearBotonNuevoFotoCliente();


  if($("#formEditarCliente").valid()) {
      $.post("funciones/ws_clientes.php", "accion=editarFoto&ruta="+rutaActual+"&"+$("#formEditarCliente").serialize() ,function(data) {
        if(data.resultado){
            toastr.success(data.mensaje, "Exito");
            $("#divEditarCliente").modal("hide");            
            setTimeout(function(){ratPack.refresh();},300);

            //mostrarDetallePagosPrestamo($("#formNuevoPago #idprestamo").val());       
            
            desbloquearBotonNuevoFotoCliente();
        }
        else{
            toastr.warning(data.mensaje,"Info");

            rutaActual = "";

            desbloquearBotonNuevoFotoCliente();

        }
      }, "json")
      .fail(function() {
        toastr.error("no se pudo conectar al servidor", "Error Conexión");

        desbloquearBotonNuevoFotoCliente();
      });
  }
}


    function bloquearBotonNuevoFotoCliente(){
        $("#btnGuardarEditarCliente").prop("disabled",true);
        $("#btnGuardarEditarCliente").html('Guardando<i class="fas fa-1x fa-sync-alt fa-spin"></i>');
    }

    function desbloquearBotonNuevoFotoCliente(){
        $("#btnGuardarEditarCliente").prop("disabled",false);
        $("#btnGuardarEditarCliente").html('Guardar');    
    }








    $("#verMeta").on("click",function(e){                        
        $("#divReporteMeta").modal("show", {backdrop: "static"});
    });


    $("#btnActualizar").on("click",function(e){               
        actualizarPrestamos();

        mostrarRegistroPrestamos();
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