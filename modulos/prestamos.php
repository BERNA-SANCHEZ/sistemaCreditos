<?php
session_start();
require_once ("../funciones/classSQL.php");
$conexion = new conexion();
if($conexion->permisos($_SESSION['idtipousuario'],"3","acceso"))
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
                <h2 class='titulo'>Préstamos</h2>
            </div>
           
        </div>
    </div><!-- /.container-fluid -->
</section>


<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-header">
                
            <?php if($conexion->permisos($_SESSION['idtipousuario'],"3","crear")) { ?>
                <button type="button" id="btnNuevoPrestamo" data-toggle="modal" class="btn bg-navy btn-lg">Nuevo Préstamo</button>
            <?php } ?>
                
        </div>
        <!-- /.card-header -->
        <div class="card-body" style="overflow-x: scroll;">
           
            <div  class="table-responsive " >
                <table id="tablaPrestamos" class="table table-striped" >
                    <thead>
                    <tr>  
                        <th>No.</th>
                        <th>Usuario que registró</th>
                        <th>Nombre Cliente</th>
                        <th>Código</th>
                        <th>Fecha Entrega</th>
                        <th>Capital Préstamo</th>
                        <th>Plan</th>
                        <th>Cuotas</th>
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




<div id="divNuevoPrestamo" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: auto;">
    <div class="modal-dialog modal-lg">
        <form id='formNuevoPrestamo' class="form form-validate"  role="form"   method="post" >
            <div class="modal-content  panel panel-success">
                <div class="modal-header">
                <h4 class="modal-title">Nuevo Préstamo</h4>

                &nbsp;&nbsp;
                <div class="btn-group">
                    <button type="button" style="cursor: pointer;" id="btnCalendario" class="btn btn-default tooltip2">
                        <span class='tooltiptext'>Calendario</span> 
                        <i class="fas fa-calendar-alt"></i>
                    </button>
                </div>


                &nbsp;&nbsp;
                <div class="btn-group">
                    <button type="button" style="cursor: pointer;" id="btnPendientesClientes" class="btn btn-default tooltip2">
                        <span class='tooltiptext'>Saldo pendiente</span> 
                        <i class="fas fa-hourglass-start"></i>
                    </button>
                </div>




                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body row">

                    <div class="form-group col-lg-4 col-md-6">
                        <label for="newNombre">Nombre del cliente:</label>
                        <input type="hidden" class="form-control" name="idcliente" id="idcliente" />
                        <input type="hidden" class="form-control" name="idplan" id="idplan" />
                        <input type="hidden" class="form-control" name="cuotaSeleccionada" id="cuotaSeleccionada" />
                        <input style="cursor:pointer;" type="text" class="form-control " id="newNombre" name="newNombre" placeholder="Seleccione el cliente" required readonly>
                    </div>


                    <div class="form-group col-lg-4 col-md-6">

                        <label for="listadocobradores">Cobrador:</label>

                        <select class="form-control select2-list select2-success" data-dropdown-css-class="select2-success" name="idcobrador" id='listadocobradores' data-placeholder="Seleccione cobrador" required>
                        <?php         
                            echo "<option value='' ></option>";   
                            $usuarios = $conexion->sql("SELECT id, nombre FROM usuarios where idtipousuario = 4");
                            foreach ($usuarios as $key => $value) {
                                echo "<option value='".$value['id']."' >".$value["nombre"]."</option>";
                            }
                        ?>                    
                        </select>
                    
                    </div>


                    <div class="form-group col-lg-4 col-md-6">

                        <label for="listadosupervisores">Supervisor:</label>

                        <select class="form-control select2-list select2-success" data-dropdown-css-class="select2-success" name="idsupervisor" id='listadosupervisores' data-placeholder="Seleccione Supervisor" required>
                        <?php         
                            echo "<option value='' ></option>";   
                            $usuarios = $conexion->sql("SELECT id, nombre FROM usuarios where idtipousuario = 5");
                            foreach ($usuarios as $key => $value) {
                                echo "<option value='".$value['id']."' >".$value["nombre"]."</option>";
                            }
                        ?>                    
                        </select>
                    
                    </div>                    

                    <div class="col-lg-4 col-md-6" style="margin-bottom: 17px;">

                        <label for="newCapital">Capital:</label>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Q</span>
                            </div>

                            <input type="number" class="form-control " id="newCapital" name="newCapital" placeholder="Ingrese Capital" required style="border-color: #28a745;">

                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>

                        </div>

                    </div>

                    <div class="col-lg-4 col-md-6" style="margin-bottom: 17px;">

                        <label for="cobroDiasFestivos">Por días festivos (-)</label>

                        <div class="input-group">
                            
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="padding: 0px 0px 0px 10px;">

                                    <div class="icheck-success">
                                        <input type="checkbox" id="btnCDF" checked>
                                        <label for="btnCDF"></label>
                                    </div>

                                </span>
                            </div>

                            <input type="number" class="form-control " id="cobroDiasFestivos" name="cobroDiasFestivos" readonly>

                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>

                        </div>

                    </div>

                    <div class="col-lg-4 col-md-6" style="margin-bottom: 17px;">

                        <label for="cobroPapeleria">Cobro por papelería (-)</label>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                
                            
                                <span class="input-group-text" style="padding: 0px 0px 0px 10px;">

                                    <div class="icheck-success">
                                        <input type="checkbox" id="btnCobroPapeleria" checked>
                                        <label for="btnCobroPapeleria"></label>
                                    </div>

                                </span>

                            </div>

                            <input type="number" class="form-control " id="cobroPapeleria" name="cobroPapeleria">

                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>

                        </div>

                    </div>

                    <div class="col-lg-4 col-md-6" style="margin-bottom: 17px;">

                        <label for="capitalEntregado">Total capital entregado</label>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Q</span>
                            </div>

                            <input type="number" class="form-control " id="capitalEntregado" name="capitalEntregado" readonly>

                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>

                        </div>

                    </div>



                    <div class="form-group col-lg-4 col-md-6">

                        <label for="saldoPendiente">Saldo pendiente (-)</label>

                        <div class="input-group">

                            <div class="input-group-prepend">
                                <span class="input-group-text">Q</span>
                            </div>
                            <input type="number" id="saldoPendiente" name="saldoPendiente" class="form-control" readonly>


                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>


                        </div>

                    </div>


                    

                    <div class="form-group col-lg-4 col-md-6">

                        <label for="pagarPrimeraCuota">Pagar primera Cuota (-)</label>

                        <div class="input-group">

                            <div class="input-group-prepend">
                                <span class="input-group-text" style="padding: 0px 0px 0px 10px;">

                                    <div class="icheck-success">
                                        <input type="checkbox" id="btnPrimeraCuota" checked>
                                        <label for="btnPrimeraCuota"></label>
                                    </div>

                                </span>
                            </div>
                            <input type="number" id="pagarPrimeraCuota" name="pagarPrimeraCuota" class="form-control" readonly>


                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>


                        </div>

                    </div>


                    <div class="form-group col-lg-4 col-md-6">
                        <label for="newCodigo">Código:</label>

                        <?php         
                          
                            $buscarCodigo = $conexion->sql("SELECT codigo FROM prestamos ORDER BY id DESC LIMIT 1");
                            $buscarCodigo[0]["codigo"]++;
                            echo '<input type="number" class="form-control " id="newCodigo" name="newCodigo" placeholder="Ingrese Código" value="'.$buscarCodigo[0]["codigo"].'" required >';
                        ?> 
                        
                    </div>






                    <div class="form-group col-lg-4 col-md-6">
                        <label>Hora de pago:</label>

                        <div class="input-group date" id="timepicker" data-target-input="nearest">
                        <input type="text" class="form-control  datetimepicker-input" name="horapago" data-target="#timepicker" required>
                        <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                        </div>
                        </div>
                        <!-- /.input group -->
                    </div>


                    <div class="form-group col-lg-4 col-md-6">
                        <label>Fecha inicio:</label>

                        <div class="input-group date" id="timepicker2" data-target-input="nearest">
                        <input type="text" class="form-control  datetimepicker-input" name="fechainicio" id='fechainicio' data-target="#timepicker2" required value=<?php echo date("Y-m-d") ?> />
                        <div class="input-group-append" data-target="#timepicker2" data-toggle="datetimepicker">
                            <div class="input-group-text" ><i class="far fa-calendar"></i></div>
                        </div>
                        </div>
                        <!-- /.input group -->
                    </div>
                   


                    <div class="col-md-12">
                        <div class="info-box bg-light">
                            <div class="info-box-content row">
                               
                                
                                <div class="col-md-1">
                                    <label for="newn" style="margin-top: 5px;" >MORA:</label>
                                </div>


                                <div class="form-group col-md-3">

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Q</span>
                                        </div>

                                        <input type="number" class="form-control " id="newn" name="newn">

                                        <div class="input-group-append">
                                            <span class="input-group-text">.00</span>
                                        </div>

                                    </div>


                                </div>

                                <div class="col-md-1" style="padding: 0;">
                                    <label for="newm" style="margin-top: 5px;">POR CADA</label>
                                </div>

                                <div class="form-group col-md-3">

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Q</span>
                                        </div>

                                        <input type="number" class="form-control " id="newm" name="newm">

                                        <div class="input-group-append">
                                            <span class="input-group-text">.00</span>
                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-1" style="text-align: center;">
                                    <label for="montoTotalMora" style="margin-top: 5px;">=</label>
                                </div>


                                <div class="form-group col-md-3">

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Q</span>
                                        </div>

                                        <input type="number" class="form-control " id="montoTotalMora" name="montoTotalMora" required>

                                        <div class="input-group-append">
                                            <span class="input-group-text">.00</span>
                                        </div>

                                    </div>

                                </div>




                                <div class="form-group col-md-7 tooltip2">
                                    <span class='tooltiptext'> Si se selecciona esta opción: Las moras se irán acumulando por cuota  </span>
                       
                                    <div class="icheck-success">
                                        <input type="radio" name="radiomora" id="moraincrementable">
                                        <label for="moraincrementable">Aplicar moras incrementables por cuota atrasada </label>
                                        
                                    </div>

                                </div>


                                <div class="form-group col-md-7 tooltip2">
                                    <span class='tooltiptext'> Si se selecciona esta opción: Se generará 1 mora por cuota atrasada  </span>
                       
                                    <div class="icheck-success">
                                        <input type="radio" name="radiomora" id="moranoincrementable">
                                        <label for="moranoincrementable">Aplicar 1 mora por cuota atrasada </label>
                                        
                                    </div>

                                </div>


                                <div class="form-group col-md-7 tooltip2">
                                    <span class='tooltiptext'> Si se selecciona esta opción: Las moras se irán acumulando por día, en la primera cuota no pagada  </span>
                       
                                    <div class="icheck-success">
                                        <input type="radio" name="radiomora" id="incrementablexdia" checked>
                                        <label for="incrementablexdia">Aplicar moras incrementables por día atrasado</label>
                                        
                                    </div>

                                </div>




                            </div>
                        </div>
                    </div>


                    <div class="form-group col-md-12">

                        <div class="select2-success">
                            
                            <label >Días festivos: </label>
                            <select disabled class="form-control select2-list select2-success" multiple="multiple" id="selectDF" data-placeholder="Listado de días festivos" data-dropdown-css-class="select2-success">                           
                            </select><div class="form-control-line"></div>

                        </div>


                    </div>






                    <div class="col-md-12">

                        <div class="card card-success card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Planes</h3>  
                                
                                <div class="card-tools">
                                    <div class="input-group input-group-sm">
                                        
                                    
                                    



                                    <div class="form-group clearfix">

                                        <div class="icheck-success d-inline">
                                            <input type="radio" name="r3" checked="" id="aproximacion5">
                                            <label for="aproximacion5">
                                                Aproximación +5

                                            </label>
                                        </div>
                                        <div class="icheck-success d-inline">
                                            <input type="radio" name="r3" id="aproximacion1">
                                            <label for="aproximacion1">
                                            Aproximación +1

                                            </label>
                                        </div>

                                        <div class="icheck-success d-inline">
                                            <input type="radio" name="r3" id="aproximacionmenos1">
                                            <label for="aproximacionmenos1">
                                            Aproximación -1

                                            </label>
                                        </div>
                                        
                                    </div>

                    

                                    </div>

                                </div>


                            </div>


                            


                            <div class="card-body p-0">
                            
                                <div class="table-responsive mailbox-messages">

                                    <table class="table table-hover table-striped" id="planesPrestamo">

                                        <tbody>
                                        </tbody>

                                    </table>
                                        
                                </div>
                                    
                            </div>
                            
                        </div>

                    </div>



                    
                </div>

                <div class="modal-footer">
                    <div class="response"></div>
                    <button type="button" id="btnGuardarNuevoPrestamo" class="btn bg-navy">Guardar</button>
                    <button type="button" id="btnCancelarNuevoPrestamo" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>

<div id="divSeleccionarCliente" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="overflow-y: auto;">
    <div class="modal-dialog modal-lg">
        <form id='formSeleccionarCliente' class="form form-validate"  role="form"   method="post" >
        <div class="modal-content  panel panel-success">
            
            <div class="modal-header">
                <h4 class="modal-title">Seleccionar Cliente</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>



            <div class="card-body">

                    <div  class="table-responsive ">
                    <table id="tablaClientes" class="table order-column hover" style="width: 100%;">
                        <thead>
                        <tr>  
                            <th>#</th>
                            <th>CÓDIGO</th>
                            <th>NOMBRE</th>
                            <th>DIRECCIÓN</th>
                            <th>TELÉFONO</th>
                            <th>PRÉSTAMOS ACTIVOS</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    </div>        

            </div>
        </div>
        </form>  
    </div>
</div>

<div id="divEliminarPrestamo" class="modal fade show" aria-modal="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar Préstamo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                
                <div class="form-group">
                    <label class="col-form-label" for="claveAnulacionPrestamo"><i class="far fa-bell"></i> Contraseña de anulación </label>
                    <input type="password" class="form-control is-invalid form-control-lg" autocomplete="off" id="claveAnulacionPrestamo" >
                </div>


                <input type="hidden" name="idEliminarPrestamo" id="idEliminarPrestamo" class="form-control" />
                <p><h4>¿Desea eliminar el registro?</h4></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnEliminarPrestamo">Si estoy seguro</button>
                <button type="button" class="btn btn-default" id="btnCancelarEliminarPrestamo" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div id="divNuevoCalendario" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form id='formNuevoCalendario' class="form form-validate"  role="form"   method="post" >
            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Calendario</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body row">


                    <div class="col-md-12">

                        <div class="btn-group btn-group-toggle" data-toggle="buttons" style="width: 100%;">

                            <label class="btn btn-default text-center">
                                <input type="radio" autocomplete="off">
                                Día actual
                                <br>
                                <i class="fas fa-circle fa-2x text-orange" style="color: #fcf8e3; background: #444444;border-radius: 20px;padding: 2px;"></i>
                            </label>
                            <label class="btn btn-default text-center">
                                <input type="radio" autocomplete="off" checked="">
                                Día de cobro
                                <br>
                                <i class="fas fa-circle fa-2x text-green" style="color: #def5da; background: #444444;border-radius: 20px;padding: 2px;"></i>
                            </label>                           
                            <label class="btn btn-default text-center active">
                                <input type="radio" autocomplete="off">
                                Día festivo
                                <br>
                                <i class="fas fa-circle fa-2x text-red" style="color: #ffe7e7; background: #444444;border-radius: 20px;padding: 2px;"></i>
                            </label>
                            
                        </div>

                    </div>




                    <div id="contenedorCalendario"></div>


                    <div class="card" style="width: 100%; margin-top: 30px;">

                        <div class="card-header">
                            <h3 class="card-title">Descuento por días festivos</h3>
                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table id="tablaDiasFestivos" class="table table-sm">      
                                    
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha </th>
                                            <th>Cuota</th>
                                        </tr>
                                    </thead>

                                    <tbody></tbody>
                                </table>

                            </div>

                        </div>

                    </div>
                    


                </div>

                <div class="modal-footer">
                    <div class="response"></div>
                    <button type="button" id="btnGuardarNuevoCalendario" class="btn bg-navy">Guardar cambios</button>
                    <button type="button" id="btnCancelarNuevoCalendario" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>



<div id="divGarantias" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: auto;">
    <div class="modal-dialog modal-lg">
        <form id='formGarantias' class="form form-validate"  role="form"   method="post" >
            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Garantías</h4>

                &nbsp;&nbsp;
                <div class="btn-group">
                    <button type="button" style="cursor: pointer;" id="btnNuevaGarantia" class="btn btn-default tooltip2">
                        <span class='tooltiptext'>Nueva Garantía</span> 
                        <i class="fas fa-plus-circle"></i>
                    </button>
                </div>


                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body">

                                    
                    <div  class="table-responsive " >
                        <table id="tablaGarantias" class="table table-striped" style="width: 100%;">
                            <thead>
                            <tr>  
                                <th>No.</th>
                                <th>NOMBRE</th>
                                <th>VALUACIÓN</th>
                                <th>FOTO</th>
                                <th>ESTADO</th>
                                <th>DESCRIPCIÓN</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>

                <div class="modal-footer">
                    <div class="response"></div>                    
                    <button type="button" id="btnCancelarGarantias" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>


<div id="divReconocimientos" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: auto;">
    <div class="modal-dialog modal-lg">
        <form id='formReconocimientos' class="form form-validate"  role="form"   method="post" >

            <input type="hidden" class="form-control" name="idRecPrestamo" id="idRecPrestamo" />



            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                    <h4 class="modal-title">Reconocimientos</h4>    
                    
                    
                    &nbsp;&nbsp;
                    <div class="btn-group">
                        <button type="button" style="cursor: pointer;" id="btnRL" class="btn btn-default tooltip2">
                            <span class='tooltiptext'>Nuevo representante</span> 
                            <i class="fas fa-plus-circle"></i>
                        </button>
                    </div>

                    &nbsp;&nbsp;
                    <div class="btn-group">
                        <button type="button" style="cursor: pointer;" id="btnAbogado" class="btn btn-default tooltip2">
                            <span class='tooltiptext'>Nuevo Abogado</span> 
                            <i class="fas fa-plus-circle"></i>
                        </button>
                    </div>



                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="card-body" style="padding: 0;margin: 0;">



                    <div class="card card-primary card-tabs" style="box-shadow: none;">
                    <div class="card-header bg-lightblue p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-one-newReco-tab" data-toggle="pill" href="#custom-tabs-one-newReco" role="tab" aria-controls="custom-tabs-one-newReco" aria-selected="true">Generación de reconocimiento</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-RepresentanteLegal-tab" data-toggle="pill" href="#custom-tabs-one-RepresentanteLegal" role="tab" aria-controls="custom-tabs-one-RepresentanteLegal" aria-selected="false">Ver representante legal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-Abogado-tab" data-toggle="pill" href="#custom-tabs-one-Abogado" role="tab" aria-controls="custom-tabs-one-Abogado" aria-selected="false">Ver abogado</a>
                        </li>
                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade active show" id="custom-tabs-one-newReco" role="tabpanel" aria-labelledby="custom-tabs-one-newReco-tab">


                            <div class="row">


                                <div class="form-group col-lg-4 col-md-6">
                                    <label>Fecha:</label>

                                    <div class="input-group date" id="timepicker03" data-target-input="nearest">
                                        <input type="text" class="form-control  datetimepicker-input" name="fechaReconocimiento" id="fechaReconocimiento" data-target="#timepicker03" value=<?php echo date("Y-m-d") ?> required>
                                        <div class="input-group-append" data-target="#timepicker03" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    <!-- /.input group -->
                                </div>




                                <div class="form-group col-lg-4 col-md-6">

                                    <label for="idselectrepresentantelegal">Representante legal:</label>

                                    <select class="form-control select2-list select2-success" data-dropdown-css-class="select2-success" name="idselectrepresentantelegal" id='idselectrepresentantelegal' data-placeholder="Seleccione cobrador" required>
                                    <?php         
                                        echo "<option value='' ></option>";   
                                        $usuarios = $conexion->sql("SELECT id, nombre FROM representantelegal");
                                        foreach ($usuarios as $key => $value) {
                                            echo "<option value='".$value['id']."' >".$value["nombre"]."</option>";
                                        }
                                    ?>                    
                                    </select>

                                </div>


                                 <div class="form-group col-lg-4 col-md-6">

                                    <label for="idselectabogado">Abogado:</label>

                                    <select class="form-control select2-list select2-success" data-dropdown-css-class="select2-success" name="idselectabogado" id='idselectabogado' data-placeholder="Seleccione cobrador" required>
                                    <?php         
                                        echo "<option value='' ></option>";   
                                        $usuarios = $conexion->sql("SELECT id, nombre FROM abogado");
                                        foreach ($usuarios as $key => $value) {
                                            echo "<option value='".$value['id']."' >".$value["nombre"]."</option>";
                                        }
                                    ?>                    
                                    </select>

                                </div>

                                <div class="form-group col-lg-4 col-md-6">


                                    <div class="form-group col-md-12">
                                        <label for="newEdadCliente">Edad del cliente:</label>
                                        <input type="number" class="form-control" id="newEdadCliente" name="newEdadCliente" placeholder="Ingrese la edad del cliente" required >
                                    </div>



                                </div>


                                <div class="form-group col-lg-4 col-md-6" style="margin-top: 22px;">
                                    <button type="button" id="btnGenerarReconocimiento" data-toggle="modal" class="btn bg-navy btn-lg btn-block">Generar reconocimiento</button>
                                </div>                    

                            </div>

                            </div>
                            <div class="tab-pane fade" id="custom-tabs-one-RepresentanteLegal" role="tabpanel" aria-labelledby="custom-tabs-one-RepresentanteLegal-tab">

                        

                            <div  class="table-responsive " >
                                <table id="tablaRepresentantes" class="table table-striped" style="width: 100%;">
                                    <thead>
                                    <tr>  
                                        <th>No.</th>
                                        <th>Nombre  </th>
                                        <th>Nacimiento</th>
                                        <th>Estado civil</th>
                                        <th>Nacionalidad</th>
                                        <th>Oficio / Profesión</th>
                                        <th>DPI</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            
                            
                            
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-one-Abogado" role="tabpanel" aria-labelledby="custom-tabs-one-Abogado-tab">
                                

                            <div  class="table-responsive " >
                                <table id="tablaAbogados" class="table table-striped" style="width: 100%;">
                                    <thead>
                                    <tr>  
                                        <th>No.</th>
                                        <th>Nombre  </th>
                                        <th>Colegiado</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>



                            
                            
                            </div>
                            
                      
                        </div>
                    </div>
                    <!-- /.card -->
                    </div>




                                    
                
                </div>

                <div class="modal-footer">
                    <div class="response"></div>                    
                    <button type="button" id="btnCancelarReconocimientos" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>




<div id="divNuevoRepresentanteLegal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: auto;">
    <div class="modal-dialog">
        <form id='formNuevoRepresentanteLegal' class="form form-validate"  role="form"   method="post" >
            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Nuevo Representante Legal</h4>        

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body row">

                                   
                    <div class="form-group col-md-12">
                        <label for="newNameRL">Nombre:</label>
                        <input type="text" class="form-control" id="newNameRL" name="newNameRL" placeholder="Ingrese Nombre del Representante Legal" required >
                    </div>


                    <div class="form-group col-md-12">
                        <label>Fecha de nacimiento:</label>

                        <div class="input-group date" id="timepicker01" data-target-input="nearest">
                        <input type="text" class="form-control  datetimepicker-input" name="newNacimiento" id='newNacimiento' data-target="#timepicker01" required value=<?php echo date("Y-m-d") ?> />
                        <div class="input-group-append" data-target="#timepicker01" data-toggle="datetimepicker">
                            <div class="input-group-text" ><i class="far fa-calendar"></i></div>
                        </div>
                        </div>
                        
                    </div>                

                    <div class="form-group col-md-12">
                        <label for="newEstadoCivil">Estado civil: </label>
                        <select class="form-control select2-list" id="newEstadoCivil" name="newEstadoCivil" data-placeholder="Seleccione una opción" required> 
                        <option value=""> </option>
                        <option value="1"> Soltero</option>
                        <option value="2"> Casado</option>
                        </select><div class="form-control-line"></div>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="newNacionalidad">Nacionalidad:</label>
                        <input type="text" class="form-control" id="newNacionalidad" name="newNacionalidad" placeholder="Ingrese la Nacionalidad" required >
                    </div>

                    <div class="form-group col-md-12">
                        <label for="newOficio">Oficio:</label>
                        <input type="text" class="form-control" id="newOficio" name="newOficio" placeholder="Ingrese el oficio o profesión" required >
                    </div>

                    <div class="form-group col-md-12">
                        <label for="newDPI">DPI:</label>
                        <input type="text" class="form-control" id="newDPI" name="newDPI" placeholder="Ingrese el DPI" required >
                    </div>            
                    
                </div>

                <div class="modal-footer">
                    <div class="response"></div>
                    <button type="button" id="btnGuardarNuevoRepresentanteLegal" class="btn bg-navy">Guardar</button>
                    <button type="button" id="btnCancelarNuevoRepresentanteLegal" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>



<div id="divEditarRepresentanteLegal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: auto;">
    <div class="modal-dialog">
        <form id='formEditarRepresentanteLegal' class="form form-validate"  role="form"   method="post" >
            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Editar Representante Legal</h4>        

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body row">

                                   
                    <div class="form-group col-md-12">
                        <label for="editNameRL">Nombre:</label>
                        <input type="text" class="form-control" id="editNameRL" name="editNameRL" placeholder="Ingrese Nombre del Representante Legal" required >
                        <input type="hidden" class="form-control" name="idrepresentantelegal" id="idrepresentantelegal" />

                    </div>


                    <div class="form-group col-md-12">
                        <label>Fecha de nacimiento:</label>

                        <div class="input-group date" id="timepicker02" data-target-input="nearest">
                        <input type="text" class="form-control  datetimepicker-input" name="editNacimiento" id='editNacimiento' data-target="#timepicker02" required value=<?php echo date("Y-m-d") ?> />
                        <div class="input-group-append" data-target="#timepicker02" data-toggle="datetimepicker">
                            <div class="input-group-text" ><i class="far fa-calendar"></i></div>
                        </div>
                        </div>
                        
                    </div>                

                    <div class="form-group col-md-12">
                        <label for="editEstadoCivil">Estado civil: </label>
                        <select class="form-control select2-list" id="editEstadoCivil" name="editEstadoCivil" data-placeholder="Seleccione una opción" required> 
                        <option value=""> </option>
                        <option value="1"> Soltero</option>
                        <option value="2"> Casado</option>
                        </select><div class="form-control-line"></div>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="editNacionalidad">Nacionalidad:</label>
                        <input type="text" class="form-control" id="editNacionalidad" name="editNacionalidad" placeholder="Ingrese la Nacionalidad" required >
                    </div>

                    <div class="form-group col-md-12">
                        <label for="editOficio">Oficio:</label>
                        <input type="text" class="form-control" id="editOficio" name="editOficio" placeholder="Ingrese el oficio o profesión" required >
                    </div>

                    <div class="form-group col-md-12">
                        <label for="editDPI">DPI:</label>
                        <input type="text" class="form-control" id="editDPI" name="editDPI" placeholder="Ingrese el DPI" required >
                    </div>            
                    
                </div>

                <div class="modal-footer">
                    <div class="response"></div>
                    <button type="button" id="btnGuardarEditarRepresentanteLegal" class="btn bg-navy">Guardar</button>
                    <button type="button" id="btnCancelarEditarRepresentanteLegal" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>




<div id="divNuevoAbogado" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: auto;">
    <div class="modal-dialog">
        <form id='formNuevoAbogado' class="form form-validate"  role="form"   method="post" >
            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Nuevo Abogado</h4>        

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body row">

                                   
                    <div class="form-group col-md-12">
                        <label for="nuevo_nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nuevo_nombre" name="nuevo_nombre" placeholder="Ingrese Nombre del abogado" required >
                    </div>

                    <div class="form-group col-md-12">
                        <label for="nuevo_colegiado">Colegiado:</label>
                        <input type="text" class="form-control" id="nuevo_colegiado" name="nuevo_colegiado" placeholder="Ingrese el Colegiado" required >
                    </div>

                </div>

                <div class="modal-footer">
                    <div class="response"></div>
                    <button type="button" id="btnGuardarNuevoAbogado" class="btn bg-navy">Guardar</button>
                    <button type="button" id="btnCancelarNuevoAbogado" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>



<div id="diveditarAbogado" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: auto;">
    <div class="modal-dialog">
        <form id='formeditarAbogado' class="form form-validate"  role="form"   method="post" >
            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Editar Abogado</h4>        

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body row">

                                   
                    <div class="form-group col-md-12">
                        <label for="editar_nombre">Nombre:</label>
                        <input type="text" class="form-control" id="editar_nombre" name="editar_nombre" placeholder="Ingrese Nombre del abogado" required >
                        <input type="hidden" class="form-control" name="idabogado" id="idabogado" />

                    </div>

                    <div class="form-group col-md-12">
                        <label for="editar_colegiado">Colegiado:</label>
                        <input type="text" class="form-control" id="editar_colegiado" name="editar_colegiado" placeholder="Ingrese el Colegiado" required >
                    </div>

                </div>

                <div class="modal-footer">
                    <div class="response"></div>
                    <button type="button" id="btnGuardareditarAbogado" class="btn bg-navy">Guardar</button>
                    <button type="button" id="btnCancelareditarAbogado" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>




<div id="divEliminarRepresentante" class="modal fade show" aria-modal="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar Representante Legal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label class="col-form-label" for="claveAnulacionRepresentante"><i class="far fa-bell"></i> Contraseña de anulación </label>
                    <input type="password" class="form-control is-invalid form-control-lg" autocomplete="off" id="claveAnulacionRepresentante" >
                </div>


                <input type="hidden" name="ideliminarRepresentante" id="ideliminarRepresentante" class="form-control" />
                <p><h4>¿Desea eliminar el registro?</h4></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnEliminarRepresentante">Si estoy seguro</button>
                <button type="button" class="btn btn-default" id="btnCancelarEliminarRepresentante" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>





<div id="divEliminarAbogado" class="modal fade show" aria-modal="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar Abogado</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label class="col-form-label" for="claveAnulacionAbogado"><i class="far fa-bell"></i> Contraseña de anulación </label>
                    <input type="password" class="form-control is-invalid form-control-lg" autocomplete="off" id="claveAnulacionAbogado" >
                </div>


                <input type="hidden" name="ideliminarAbogado" id="ideliminarAbogado" class="form-control" />
                <p><h4>¿Desea eliminar el registro?</h4></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnEliminarAbogado">Si estoy seguro</button>
                <button type="button" class="btn btn-default" id="btnCancelarEliminarAbogado" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>






<div id="divNuevaGarantia" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: auto;">
    <div class="modal-dialog">
        <form id='formNuevaGarantia' class="form form-validate"  role="form"   method="post" >
            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Nueva Garantía</h4>
              

                <button type="button" id="btnCancelarNuevaGarantiax" class="close" data-dismiss="modal" aria-label="Close">
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
                            <label for="upload_image">
                            <img src="upload/opcion.png" id="uploaded_image" class="img-responsive img-circle" />
                            <div class="newoverlay">
                                <div class="text">Ver Galería</div>
                            </div>
                                <input type="file" name="image" class="image" id="upload_image" style="display:none">
                            </label>
                        </form>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-4 col-2">&nbsp;</div>
                    

                    </div>	
                    <br>
                    <!--NUEVO CODIGO-->
                   

                    <div class="form-group col-md-12">
                        <label for="new_Nombre">Nombre:</label>
                        <input type="text" class="form-control" id="new_Nombre" name="new_Nombre" placeholder="Ingrese Nombre" required >
                        <input type="hidden" class="form-control" name="id_prestamo" id="id_prestamo" />
                    </div>

                    <div class="form-group col-md-12">
                        <label for="new_Serie">Serie:</label>
                        <input type="text" class="form-control" id="new_Serie" name="new_Serie" placeholder="Ingrese Serie" required >                   
                    </div>

                    <div class="form-group col-md-12">
                        <label for="new_Modelo">Modelo:</label>
                        <input type="text" class="form-control" id="new_Modelo" name="new_Modelo" placeholder="Ingrese Modelo" required >                   
                    </div>

                    <div class="form-group col-md-12">
                        <label for="new_Marca">Marca:</label>
                        <input type="text" class="form-control" id="new_Marca" name="new_Marca" placeholder="Ingrese Marca" required >                   
                    </div>


                    <div class="form-group col-md-12">
                        <label for="newValuacion">Valuación:</label>
                        <input type="number" class="form-control" id="newValuacion" name="newValuacion" placeholder="Ingrese la Valuación" required >
                    </div>

                    <div class="form-group col-md-12">
                        <label for="newEstado">Estado: </label>
                        <select class="form-control select2-list" id="newEstado" name="newEstado" data-placeholder="Seleccione una opción" required> 
                        <option value=""> </option>
                        <option value="0"> No cobrada</option>
                        <option value="1"> Si cobrada</option>
                        </select><div class="form-control-line"></div>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="newDescripcion">Descripción</label>
                        <textarea class="form-control" id="newDescripcion" name="newDescripcion" rows="6" placeholder="Digite la información ..."></textarea>
                    </div>
            
                    
                </div>

                <div class="modal-footer">
                    <div class="response"></div>
                    <button type="button" id="btnGuardarNuevaGarantia" class="btn bg-navy">Guardar</button>
                    <button type="button" id="btnCancelarNuevaGarantia" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>


<div id="divEditarGarantia" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: auto;">
    <div class="modal-dialog">
        <form id='formEditarGarantia' class="form form-validate"  role="form"   method="post" >
            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Editar Garantía</h4>
              

                <button type="button" id="btnCancelarEditarGarantiax" class="close" data-dismiss="modal" aria-label="Close">
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
                            <img src="upload/opcion.png" id="uploaded_image_2" class="img-responsive img-circle" />
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
                    <br>
                    <!--NUEVO CODIGO-->

                    <div class="form-group col-md-12">
                        <label for="edit_Nombre">Nombre:</label>
                        <input type="text" class="form-control" id="edit_Nombre" name="edit_Nombre" placeholder="Ingrese Nombre" required >    
                        <input type="hidden" class="form-control" name="idgarantias" id="idgarantias" />                    
                    </div>


                    <div class="form-group col-md-12">
                        <label for="edit_Serie">Serie:</label>
                        <input type="text" class="form-control" id="edit_Serie" name="edit_Serie" placeholder="Ingrese Serie" required >                   
                    </div>

                    <div class="form-group col-md-12">
                        <label for="edit_Modelo">Modelo:</label>
                        <input type="text" class="form-control" id="edit_Modelo" name="edit_Modelo" placeholder="Ingrese Modelo" required >                   
                    </div>

                    <div class="form-group col-md-12">
                        <label for="edit_Marca">Marca:</label>
                        <input type="text" class="form-control" id="edit_Marca" name="edit_Marca" placeholder="Ingrese Marca" required >                   
                    </div>



                    <div class="form-group col-md-12">
                        <label for="editValuacion">Valuación:</label>
                        <input type="number" class="form-control" id="editValuacion" name="editValuacion" placeholder="Ingrese la Valuación" required >
                    </div>

                    <div class="form-group col-md-12">
                        <label for="editEstado">Estado: </label>
                        <select class="form-control select2-list" id="editEstado" name="editEstado" data-placeholder="Seleccione una opción" required> 
                        <option value=""> </option>
                        <option value="0"> No cobrada</option>
                        <option value="1"> Si cobrada</option>
                        </select><div class="form-control-line"></div>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="editDescripcion">Descripción</label>
                        <textarea class="form-control" id="editDescripcion" name="editDescripcion" rows="6" placeholder="Digite la información ..."></textarea>
                    </div>
            
                    
                </div>

                <div class="modal-footer">
                    <div class="response"></div>


                    <div class="row" style="width: 100%;">

                        <div class="col-md-1 col-sm-1 col-xs-1 col-1">

                            <div id="contEliminarImagen" style="display:none;" class="btn-group">
                                <button style='cursor:pointer' class="btn btn-default tooltip2" id="btnEliminarImagen">
                                    <span class='tooltiptext'>Eliminar Imagen</span> 
                                    <i class='fa fa-trash fa-lg'></i>
                                </button> 
                            </div>                    
                            
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-6 col-3"></div>


                        <div class="col-md-5 col-sm-5 col-xs-5 col-8">
                            <button type="button" id="btnGuardarEditarGarantia" class="btn bg-navy">Guardar</button>
                            <button type="button" id="btnCancelarEditarGarantia" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>                  

                    </div>



                    
                </div>
            </div>
        </form>  
    </div>
</div>



<div id="divEliminarGarantia" class="modal fade show" aria-modal="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar Garantía</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label class="col-form-label" for="claveAnulacionGarantia"><i class="far fa-bell"></i> Contraseña de anulación </label>
                    <input type="password" class="form-control is-invalid form-control-lg" autocomplete="off" id="claveAnulacionGarantia" >
                </div>


                <input type="hidden" name="ideliminarGarantia" id="ideliminarGarantia" class="form-control" />
                <p><h4>¿Desea eliminar el registro?</h4></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnEliminarGarantia">Si estoy seguro</button>
                <button type="button" class="btn btn-default" id="btnCancelarEliminarGarantia" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



<div id="divDocumento" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: auto;">
    <div class="modal-dialog">
        <form id='formDocumento' class="form form-validate"  role="form"   method="post" >

        <input type="hidden" class="form-control" name="idImprimirPrestamo" id="idImprimirPrestamo" />



            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Documento</h4>

                


                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>

                <div class="card-body">



                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                        <span id="d1" class="info-box-icon bg-info elevation-1" style='cursor:pointer'><i class="fas fa-square"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Tabla completa</span>
                            <span class="info-box-number">
                            1 tabla
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="info-box mb-3">
                        <span id="d2" class="info-box-icon bg-info elevation-1" style='cursor:pointer'><i class="fas fa-clone"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Tabla completa</span>
                            <span class="info-box-number">2 tablas</span>
                        </div>
                        <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->


                    <div class="col-md-6">
                        <div class="info-box mb-3">
                        <span id="d3" class="info-box-icon bg-success elevation-1" style='cursor:pointer'> <i class="fas fa-square"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Tabla compacta</span>
                            <span class="info-box-number">1 tabla</span>
                        </div>
                        <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="info-box mb-3">
                        <span id="d4" class="info-box-icon bg-success elevation-1" style='cursor:pointer'><i class="fas fa-clone"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Tabla compacta</span>
                            <span class="info-box-number">2 tablas</span>
                        </div>
                        <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                </div>


                              
                

                </div>

                <div class="modal-footer">
                    <div class="response"></div>                    
                    <button type="button" id="btnCancelarDocumento" class="btn btn-default" data-dismiss="modal">Cancelar</button>
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
                <h4 class="modal-title">Saldo pendiente del cliente</h4>

             


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
<!--NUEVO CODIGO-->





<?php 
}
?>


<script type="text/javascript">

    var det_dias = Array();
    var det_temp_dias = Array();
    var prestamos_pendientes = Array();

    var date_act = new Date();

    var day_act = date_act.getDate()
    var month_act = date_act.getMonth() + 1
    var year_act = date_act.getFullYear()

    var fechaActual;

    var conteo_inicio=0;


    if(month_act < 10){      

        if (day_act < 10) {
            fechaActual = `${year_act}-0${month_act}-0${day_act}`;            
        }else{
            fechaActual = `${year_act}-0${month_act}-${day_act}`;
        }

    }else{

        if (day_act < 10) {
            fechaActual = `${year_act}-${month_act}-0${day_act}`;
        }else{
            fechaActual = `${year_act}-${month_act}-${day_act}`;
        }

    }


  $(document).ready(function() {


    
    
    var Acceso = 0;
    var Crear = 0;
    var Modificar = 0;
    var Eliminar = 0;
    var Consultar = 0;
    var detInputPlanes = Array();
    var inputValorCuota = "";
    var porPapeleria = 25; //Cantidad que se descuenta por cada 1000 de préstamo    
    var rutaActual = ""; 

    var modalActivo = 0;  //1 =Modal Nueva Garantía, 2 =Modal Editar Garantía


    verficarPermisos();
    $(".select2-list").select2({ allowClear: true });

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT',  language: 'es'
    });


    $('#timepicker2').datetimepicker({
        pickTime: false, format: 'YYYY-MM-DD'
    });

    $('#timepicker01').datetimepicker({
        pickTime: false, format: 'YYYY-MM-DD'
    });

    $('#timepicker02').datetimepicker({
        pickTime: false, format: 'YYYY-MM-DD'
    });

    $('#timepicker03').datetimepicker({
        pickTime: false, format: 'YYYY-MM-DD'
    });
    

    

    

    
    
    function verficarPermisos () {
        $.post("funciones/ws_usuarios.php", {accion:"consultarPermisos" , idmodulo:"3"} ,function(data)
        {
            if(data.resultado){
                Acceso = data.registros[0]["acceso"];
                Crear = data.registros[0]["crear"];
                Modificar = data.registros[0]["modificar"];
                Eliminar = data.registros[0]["eliminar"];
                Consultar = data.registros[0]["consultar"];
                mostrarPlanes();
                mostrarPrestamos();
                mostrarListadoRepresentante();
                mostrarListadoAbogados();
            }
            else
              toastr.warning(data.mensaje,"Info");
        }, "json")
        .fail(function()
        {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });
    }
    


    function mostrarPrestamos(){

        $("#tablaPrestamos  tbody tr").remove();
      $.post("funciones/ws_prestamos.php", { accion: "mostrar" }, function(data) {
        if(data.resultado)
          {

            var btnEliminar = "";
            var btnConsultar = "";
            var btnGarantias = "";

            $.each(data.registros,function(key,value) {

             
              if (Eliminar == 1) {
                btnEliminar = " <button class='btn btn-default tooltip2' style='cursor:pointer' href='#' ><span class='tooltiptext'>Eliminar Registro</span> <i class='fa fa-trash fa-lg '></i></button>";
              };

              if (Consultar == 1) {
                btnConsultar = " <button class='btn btn-default bg-lightblue disabled tooltip2' style='cursor:pointer' href='#' ><span class='tooltiptext'>Imprimir Pagos</span> <i class='fa fa-print fa-lg '></i></button>";
              };

              if (Consultar == 1) {
                btnGarantias = " <button class='btn btn-default bg-lightblue tooltip2' style='cursor:pointer; ' href='#' ><span class='tooltiptext'>Ver Garantías</span><i class='fa fa-cubes fa-lg '></i></button>";
                btnReconocimientos = " <button class='btn btn-default bg-lightblue tooltip2' style='cursor:pointer; ' href='#' ><span class='tooltiptext'>Generar Reconocimientos</span><i class='fa fa-file fa-lg '></i></button>";
              };

              $("<tr></tr>")
                .append( "<td>" + (key + 1) + "</td>" )
                .append( "<td>" + value["usuarioentrego"] + "</td>" )
                .append( "<td>" + value["nombreCliente"] + "</td>" )
                .append( "<td>" + value["codigo"] + "</td>" )
                .append( "<td>" + value["fechaentregado"] + "</td>" )
                .append( "<td>Q." + value["prestamo"] + "</td>" )
                .append( "<td>" + value["cuotas"] + "</td>" )
                .append( "<td>Q." + value["resumenpagos"] + "</td>" )
               .append( $("<td></td>").append( 
                $("<div class='btn-group'></div>") 

                    .append( $(btnGarantias)
                        .on("click",{ idprestamo:value["id"] } , mostrarGarantias) )
                    .append( $(btnReconocimientos)
                        .on("click",{ idprestamo:value["id"] } , mostrarReconocimientos) )
                    .append( $(btnEliminar)
                        .on("click",{ idprestamo:value["id"] } , eliminarPrestamo) )                     
                    .append( $(btnConsultar)
                        .on("click",{ idprestamo:value["id"] } , imprimirRegistroPrestamos) )                    
                        
                    )
                  )
                .appendTo("#tablaPrestamos > tbody");
            });

            $("#tablaPrestamos a").tooltip(); 
            $("#tablaPrestamos").DataTable({ 

                initComplete: function() {
                    $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
                },

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
                                columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                            }

                        },

                        {
                               extend: 'csv', 
                          orientation: 'Portrait',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            ,exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                            }

                        },
                       
                        {
                               extend: 'excel', 
                          orientation: 'Portrait',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            ,exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                            }

                        },
                        
                        {
                               extend: 'pdf', 
                          orientation: 'landscape',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            ,exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
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


    function mostrarReconocimientos (e) {
        e.preventDefault();
        $("#formReconocimientos")[0].reset();
        $("#formReconocimientos #idRecPrestamo").val(e.data.idprestamo);

        $("#divReconocimientos").modal("show", {backdrop: "static"});
    }

    function mostrarGarantias (e) {
        e.preventDefault();
        $("#formGarantias")[0].reset();
        $("#formNuevaGarantia #id_prestamo").val(e.data.idprestamo);

        mostrarListadoGarantias(e.data.idprestamo);

        $("#divGarantias").modal("show", {backdrop: "static"});
    }


    function imprimirRegistroPrestamos (e) {
        e.preventDefault();
        $("#formDocumento #idImprimirPrestamo").val(e.data.idprestamo);
        $("#divDocumento").modal("show", {backdrop: "static"});

    }

    $("#d1").on("click",function(e){            
        window.open('funciones/imprimereporte.php?id='+$("#formDocumento #idImprimirPrestamo").val()+'&tipo='+1);    
    });

    $("#d2").on("click",function(e){            
        window.open('funciones/imprimereporte.php?id='+$("#formDocumento #idImprimirPrestamo").val()+'&tipo='+2);    
    });

    $("#d3").on("click",function(e){            
        window.open('funciones/imprimereporte.php?id='+$("#formDocumento #idImprimirPrestamo").val()+'&tipo='+3);    
    });

    $("#d4").on("click",function(e){            
        window.open('funciones/imprimereporte.php?id='+$("#formDocumento #idImprimirPrestamo").val()+'&tipo='+4);    
    });


    $("#btnGenerarReconocimiento").on("click",function(e){           
        if($("#formReconocimientos").valid()){
            window.open('funciones/pdfReconocimiento.php?'+$("#formReconocimientos").serialize());    
        }
    });



    function mostrarPlanes () {
      $("#planesPrestamo  tbody tr").remove();
      $.post("funciones/ws_planes.php", { accion: "mostrar" }, function(data) {
        if(data.resultado)
          {


            var diasSemana = ["","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado","Domingo"];

        
            $.each(data.registros,function(key,value) {


                var index = detInputPlanes.length;
                detInputPlanes[index] =  new Object();
                detInputPlanes[index].idElemento = "input"+key;
                detInputPlanes[index].interes = value["interes"];
                detInputPlanes[index].cuotas = value["cuotas"];
                detInputPlanes[index].tipo = value["tipo"];

                var tipo_plan ="";
                var tooltipRight = "";
                var tooltiptextRight ="";

              if (value["tipo"] == 1) {
                var tipo_plan ="PAGOS DIARIOS";
                         
                var arrayDias = value["dias"].split(';');
                var diasPago = "";

                $.each(arrayDias,function(key,value) {                   
                    diasPago += diasSemana[value]+", "
                });  

                tooltipRight = " style='cursor:pointer;' class='tooltipRight' ";
                tooltiptextRight = "<span class='tooltiptextRight'>"+diasPago+"</span>"; 

              }else if (value["tipo"] == 2) {
                var tipo_plan ="PAGOS SEMANALES";
              }else if (value["tipo"] == 3) {
                var tipo_plan ="PAGOS QUINCENALES";
              }else if (value["tipo"] == 4) {
                var tipo_plan ="PAGOS MENSUALES (Interés + capital)";
              }else if (value["tipo"] == 5) {
                var tipo_plan ="PAGOS MENSUALES (Por interés)";
              }

                $("<tr></tr>")
                .append( "<td><div class='icheck-success'><input class='icheckPlanes' type='radio' value='"+value["id"]+"' name='radioPlanes' nombreinput='input"+key+"' id='check"+key+"'><label for='check"+key+"'></label></div></td>" )
                .append( "<td class='mailbox-star'><div class='input-group' style='min-width: 160px;'><div class='input-group-prepend'><span class='input-group-text'>Q</span></div><input type='number' class='form-control' id='input"+key+"' name='input"+key+"' disabled style='min-width: 70px;'><div class='input-group-append'><span class='input-group-text'>.00</span></div></div></td>" )
                .append( "<td class='mailbox-subject'><b "+tooltipRight+">" + value["nombre"] + " "+tooltiptextRight+" </b> <br>"+tipo_plan+"</td>" )
                .append( "<td class='mailbox-name'><a href='#/prestamos' class='text-success'>" + value["cuotas"] + " Cuotas </a></td>" )
                .append( "<td class='mailbox-date'>Interés " + value["interes"] + "%</td>" )                                                        
                .appendTo("#planesPrestamo > tbody");

            });


            $(".icheckPlanes").on("click",function(e){
                $("#formNuevoPrestamo #idplan").val($(e.target).closest('input').attr('value'));
                inputValorCuota = "#"+$(e.target).closest('input').attr('nombreinput');            
                $("#formNuevoPrestamo #cuotaSeleccionada").val($("#formNuevoPrestamo "+inputValorCuota).val());       
                            

                if ($("#btnPrimeraCuota").is(':checked')) {

                    $("#pagarPrimeraCuota").val( $("#formNuevoPrestamo #cuotaSeleccionada").val() );                    

                    if (inputValorCuota != '' && $("#formNuevoPrestamo #newCapital").val() != '' && $("#formNuevoPrestamo #fechainicio").val() != '') {
                        habilitarCalendario();                     
                    }else{
                        deshabilitarCalendario();
                    }

                }else{
                    $("#pagarPrimeraCuota").val('');

                    if (inputValorCuota != '' && $("#formNuevoPrestamo #newCapital").val() != '' && $("#formNuevoPrestamo #fechainicio").val() != '') {
                        habilitarCalendario();                     
                    }else{
                        deshabilitarCalendario();
                    }

                }


                if ( $("#formNuevoPrestamo #idplan").val() != '') {

                    $.post("funciones/ws_planes.php", { id:$("#formNuevoPrestamo #idplan").val() , accion:"mostrar" } ,function(data) {
                        if(data.resultado){
                            $("#formNuevoPrestamo #newn").val(data.registros[0]["n"]);
                            $("#formNuevoPrestamo #newm").val(data.registros[0]["m"]);    
                            
                            
                            calcularMora();


                        }
                    }, "json")
                    .fail(function() {
                        toastr.error("no se pudo conectar al servidor", "Error Conexión");
                    });

                }
                


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
    $("#btnNuevoPrestamo").on("click",mostrarModalNuevoPrestamo);
    
    function mostrarModalNuevoPrestamo(e){
        e.preventDefault();
        $("#formNuevoPrestamo")[0].reset();

        $("#formNuevoPrestamo #listadocobradores option[value='']").attr("selected","selected");
        $(".select2-list").select2({ allowClear: true });


        $("#formNuevoPrestamo #idplan").val('');
        $("#formNuevoPrestamo #cuotaSeleccionada").val('');        
        $("#formNuevoPrestamo #idcliente").val('');
        inputValorCuota = "";

        deshabilitarCalendario();

        $("#selectDF").html('');
        $("#formNuevoPrestamo input").removeClass("dirty");



        $.post("funciones/ws_prestamos.php", { accion:"codigosiguiente" } ,function(data) {
        if(data.resultado){

            $('#formNuevoPrestamo #newCodigo').val(data.codigosiguiente);

        }
        else{
            toastr.warning(data.mensaje,"Info");
        }
        }, "json")
        .fail(function() {
        toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });


        prestamos_pendientes = [];

        $("#divNuevoPrestamo").modal("show", {backdrop: "static"});
    }



    $("#formNuevoPrestamo #newNombre").on("click",function(){
      mostrarClientes();
      $("#divNuevoPrestamo").modal("hide");
      $("#divSeleccionarCliente").modal({backdrop: 'static', keyboard: false});
    });


    function mostrarClientes () {
        bloquearPantalla("Espere por favor");
       
        table = $("#tablaClientes").DataTable( {
                        "destroy":true,
                        "dom": 'T<"clear">lfrtip',
                        "ajax": {
                          "url": "funciones/ws_clientes.php?accion=mostrar",
                          "type": "POST"
                        },
                        "columns":[
                          {"data":"id"},                         
                          {"data":"codigo"},
                          {"data":"nombre"},
                          {"data":"direccionvive"},
                          {"data":"telefono"},
                          {"data":"prestamosActivos"},
                          {
                            "data": null,
                            "mRender": function(data, type, value){
                                    if ((data.prestamosActivos > 0 && data.multprestamos == 0) || data.permitirprestamos == 1) {
                                        return "<button disabled type='button' class='btn btn-default bg-lightblue tooltip2 botonSeleccionarCliente'><span class='tooltiptext'>Nuevo préstamo</span><i class='fa fa-check fa-lg'></i></button>"                                        
                                    }else{
                                        return "<button type='button' class='btn btn-default bg-lightblue tooltip2 botonSeleccionarCliente'><span class='tooltiptext'>Nuevo préstamo</span><i class='fa fa-check fa-lg'></i></button>"
                                    }
                                }
                          },

                          {
                            "data": null,
                            "mRender": function(data, type, value){
                                    if (data.prestamosActivos > 0) {
                                        return "<button type='button' class='btn btn-default bg-navy tooltip2 botonRenovarPrestamo'><span class='tooltiptext'>Renovar préstamo</span><i class='fa fa-check fa-lg'></i></button>"                                        
                                    }else{
                                        return "<button disabled type='button' class='btn btn-default bg-navy tooltip2 botonRenovarPrestamo'><span class='tooltiptext'>Renovar préstamo</span><i class='fa fa-check fa-lg'></i></button>"
                                    }
                                }
                          }

                          
                        ]                  
                  });


                  if (conteo_inicio == 0) {
                                      
                    $("#tablaClientes tbody").on("click", "button.botonSeleccionarCliente", function () {                                                

                        var fila = table.row( $(this).parents('tr') ).data();
                        $("#formNuevoPrestamo #idcliente").val(fila.id);
                        $("#formNuevoPrestamo #newNombre").val(fila.nombre);
                        $("#divSeleccionarCliente").modal("hide");
                        $("#divNuevoPrestamo").modal("show", {backdrop: "static"});

                        $("#formNuevoPrestamo #saldoPendiente").val('');


                        $("#formNuevoPrestamo #newCapital").val('');
                        setTimeout(function() { $('#formNuevoPrestamo #newCapital').focus() }, 1500);

                        prestamos_pendientes = [];

                    });              
                                        
                    $("#tablaClientes tbody").on("click", "button.botonRenovarPrestamo", function () {

                        prestamos_pendientes = [];

                        var fila = table.row( $(this).parents('tr') ).data();
                        $("#formNuevoPrestamo #idcliente").val(fila.id);
                        $("#formNuevoPrestamo #newNombre").val(fila.nombre);
                        $("#divSeleccionarCliente").modal("hide");
                        $("#divNuevoPrestamo").modal("show", {backdrop: "static"});

                        $.post("funciones/ws_prestamos.php", { idcliente:$("#formNuevoPrestamo #idcliente").val() , accion:"prestamosPendientesClientes" } ,function(data) {
                            if(data.resultado){

                                prestamos_pendientes = [];

                                var sumaTotalMeta = 0;
                                $.each(data.reportemeta,function(key,value) {                                  
                                    sumaTotalMeta += parseFloat(value["cuotas_pendientes"]);
                                    sumaTotalMeta += parseFloat(value["moras_pendientes"]);
                                });


                                prestamos_pendientes = data.reportemeta;

                                $("#formNuevoPrestamo #saldoPendiente").val(sumaTotalMeta);

                            }
                            else{
                                toastr.warning(data.mensaje,"Info");
                            }
                        }, "json")
                        .fail(function() {
                            toastr.error("no se pudo conectar al servidor", "Error Conexión");
                        });


                        $("#formNuevoPrestamo #newCapital").val('');
                        setTimeout(function() { $('#formNuevoPrestamo #newCapital').focus() }, 1500);
                        

                    }); 

                    conteo_inicio++;
                }
      
        desbloquearPantalla();
    }


    $("#formNuevoPrestamo #newCapital").keyup(function(){         
        
        if (isNaN(parseFloat($("#formNuevoPrestamo #newCapital").val())) == false ){

            var capital =  $("#formNuevoPrestamo #newCapital").val();

            for (var i = 0; i < detInputPlanes.length; i++) {    
                
                
                var monto =  ((capital/100) * detInputPlanes[i]["interes"]) + parseFloat(capital) ;
                monto /= detInputPlanes[i]["cuotas"];
                //monto = parseFloat(monto).toFixed(2); //Valor exacto sin aproximación
                

                if ( $("#aproximacion5").is(':checked') ) {
                    monto = round(monto);
                }else if( $("#aproximacion1").is(':checked') ){
                    monto = Math.round( parseFloat(monto) );
                }else if( $("#aproximacionmenos1").is(':checked') ){
                    monto = Math.trunc( parseFloat(monto) );
                }
                

                var montoSoloInteres = ((capital/100) * detInputPlanes[i]["interes"]);
                montoSoloInteres = round(montoSoloInteres);
                
                
                if (detInputPlanes[i]["tipo"] == '5') {//PLAN MENSUAL (Por interés)
                    $("#"+detInputPlanes[i]["idElemento"]).val(montoSoloInteres);                    
                }else{//Cualquier otro plan
                    $("#"+detInputPlanes[i]["idElemento"]).val(monto);
                }
                
                if (inputValorCuota != '') {
                    $("#formNuevoPrestamo #cuotaSeleccionada").val($("#formNuevoPrestamo "+inputValorCuota).val());                    
                }                                     

            }            

        }else{
            for (var i = 0; i < detInputPlanes.length; i++) {                           
                $("#"+detInputPlanes[i]["idElemento"]).val('');
            }
        }

        if ($("#btnPrimeraCuota").is(':checked')) {            
            $("#pagarPrimeraCuota").val( $("#formNuevoPrestamo #cuotaSeleccionada").val() );
        }else{
            $("#pagarPrimeraCuota").val('');
        }

        descuentoPorPapeleria();
        
        if (inputValorCuota != '' && $("#formNuevoPrestamo #newCapital").val() != '' && $("#formNuevoPrestamo #fechainicio").val() != '') {
            habilitarCalendario();                     
        }else{
            deshabilitarCalendario();
        }

        calcularMora();

    });

    

    $("#formNuevoPrestamo #cobroPapeleria").keyup(function(){         
    
        if (inputValorCuota != '' && $("#formNuevoPrestamo #newCapital").val() != '' && $("#formNuevoPrestamo #fechainicio").val() != '') {
            habilitarCalendario();                     
        }else{
            deshabilitarCalendario();            
            
            var totalcapital =  $("#formNuevoPrestamo #newCapital").val();
           
            var total_Papeleria = 0;
            var total_saldo_pendiente = 0;

            if ($("#formNuevoPrestamo #btnCobroPapeleria").is(':checked')) {
                total_Papeleria = $("#formNuevoPrestamo #cobroPapeleria").val();                  
            }


            if ( $("#formNuevoPrestamo #saldoPendiente").val() != '' ) {
                total_saldo_pendiente = $("#formNuevoPrestamo #saldoPendiente").val();
            }


            $("#formNuevoPrestamo #capitalEntregado").val((totalcapital - total_Papeleria - total_saldo_pendiente));

                            
        } 

    });




    $("#formNuevoPrestamo #btnCobroPapeleria").on("click",function(e){

        if (!$("#formNuevoPrestamo #btnCobroPapeleria").is(':checked')) {
            $("#formNuevoPrestamo #cobroPapeleria").val(0);
        }else{
            
            if (isNaN(parseFloat($("#formNuevoPrestamo #newCapital").val())) == false ){
                var capital =  $("#formNuevoPrestamo #newCapital").val();
                var cant = Math.trunc((capital - 1) / 1000); 
                cant++;
                totalPapeleria = cant * porPapeleria;
                $("#formNuevoPrestamo #cobroPapeleria").val(totalPapeleria);
            }
        }
        

        if (inputValorCuota != '' && $("#formNuevoPrestamo #newCapital").val() != '' && $("#formNuevoPrestamo #fechainicio").val() != '') {
            habilitarCalendario();                     
        }else{
            deshabilitarCalendario();            
            
            var totalcapital =  $("#formNuevoPrestamo #newCapital").val();
            var total_Papeleria = $("#formNuevoPrestamo #cobroPapeleria").val();

            var total_saldo_pendiente = 0;

            if ( $("#formNuevoPrestamo #saldoPendiente").val() != '' ) {
                total_saldo_pendiente = $("#formNuevoPrestamo #saldoPendiente").val();
            }
            $("#formNuevoPrestamo #capitalEntregado").val((totalcapital - total_Papeleria - total_saldo_pendiente));
                            
        }


    });



    $("#formNuevoPrestamo #fechainicio").blur(function(){

        if (inputValorCuota != '' && $("#formNuevoPrestamo #newCapital").val() != '' && $("#formNuevoPrestamo #fechainicio").val() != '') {           
            habilitarCalendario();        
    
        }else{
            deshabilitarCalendario();
        }
        
    });

    $("#formNuevoPrestamo #fechainicio").keyup(function(){    
        if (inputValorCuota != '' && $("#formNuevoPrestamo #newCapital").val() != '' && $("#formNuevoPrestamo #fechainicio").val() != '') {
            habilitarCalendario();                     
        }else{
            deshabilitarCalendario();
        }
    });


    function round(x) {
        return Math.ceil(x / 5) * 5;
    }


    function descuentoPorPapeleria() {
        if (isNaN(parseFloat($("#formNuevoPrestamo #newCapital").val())) == false ){

            var capital =  $("#formNuevoPrestamo #newCapital").val();

            var cant = Math.trunc((capital - 1) / 1000); 
            cant++;

            totalPapeleria = cant * porPapeleria;
            var total_saldo_pendiente = 0;

            if (!$("#formNuevoPrestamo #btnCobroPapeleria").is(':checked')) {
                totalPapeleria = 0;       
            }

            if ( $("#formNuevoPrestamo #saldoPendiente").val() != '' ) {
                total_saldo_pendiente = $("#formNuevoPrestamo #saldoPendiente").val();
            }

            $("#formNuevoPrestamo #cobroPapeleria").val(totalPapeleria);
            $("#formNuevoPrestamo #capitalEntregado").val((capital - totalPapeleria - total_saldo_pendiente));

        }else{
            $("#formNuevoPrestamo #cobroPapeleria").val('');
            $("#formNuevoPrestamo #capitalEntregado").val('');
            $("#formNuevoPrestamo #cobroDiasFestivos").val('');  
        }
    } 


    /****************** GUARDAR DATOS DEL REGISTRO *******************/
    $("#btnGuardarNuevoPrestamo").on("click",guardarNuevoPrestamo);

    function guardarNuevoPrestamo(e){
      e.preventDefault();
      if($("#formNuevoPrestamo").valid()) {

        if ( $("#formNuevoPrestamo #cuotaSeleccionada").val() == ''){
            toastr.warning("Debe seleccionar un Plan","Info");
        }else{


            bloquearBotonNuevoPrestamo();
           
            var det_festivos = Array();

            $.each(det_dias,function(key,value) {  
                if (value['color'] == '#FFAEAE') {                                                
                    det_festivos.push(value['start']);
                }
            });

            var cobrarPrimeraCuota = 0;
            var cobrarPapeleria = 0;

            if ($("#btnPrimeraCuota").is(':checked')) {        
                det_festivos.push( $("#formNuevoPrestamo #fechainicio").val());
                cobrarPrimeraCuota = 1;
            }


            if ($("#btnCobroPapeleria").is(':checked')) {        
                cobrarPapeleria = 1;
            }


            var moraincrementable;

            if ($("#moraincrementable").is(':checked')) {
                moraincrementable = 1;
            }else if($("#moranoincrementable").is(':checked')){
                moraincrementable = 0;
            }else if($("#incrementablexdia").is(':checked')){
                moraincrementable = 2;
            }


            
        
            if (prestamos_pendientes.length > 0) {

                $.post("funciones/ws_prestamos.php", 
                {   
                accion:"cambiarEstadosPrestamo",
                capitalEntregado:$("#formNuevoPrestamo #capitalEntregado").val(),
                det_prestamos_pendientes:JSON.stringify(prestamos_pendientes)
                } ,function(data) {
                    if(data.resultado){
                        console.log(data.mensaje);

                        $.each(prestamos_pendientes,function(key,value) {  

                            if (value["tipoPlan"] == '5') {

                                //Pago de interés, plan 5
                                $.post("funciones/ws_prestamos.php", 
                                { 
                                    idprestamo:value["id"], 
                                    monto:value["total_pendiente"], 
                                    fechainicio:fechaActual, 
                                    accion:"pagoInteres_plan5",
                                    btnPrimeroMorasPendientes:1
                                } ,function(data) {
                                if(data.resultado){      
                                    console.log(data.mensaje);
                                    desbloquearBotonNuevoPrestamo();

                                }
                                else{
                                    console.log(data.mensaje);
                                    desbloquearBotonNuevoPrestamo();

                                }
                                }, "json")
                                .fail(function() {
                                    toastr.error("no se pudo conectar al servidor", "Error Conexión");
                                    desbloquearBotonNuevoPrestamo();

                                });

                            }else{
                                
                                //Pago de cuotas normales, de cualquier tipo de plan (menos plan 5)
                                $.post("funciones/ws_prestamos.php", 
                                { 
                                    idprestamo:value["id"], 
                                    monto:value["total_pendiente"], 
                                    fechainicio:fechaActual, 
                                    accion:"nuevoPago",
                                    btnPrimeroMorasPendientes:1
                                } ,function(data) {
                                if(data.resultado){
                                    console.log(data.mensaje);
                                    desbloquearBotonNuevoPrestamo();
                                    
                                }
                                else{                           
                                    console.log(data.mensaje);
                                    desbloquearBotonNuevoPrestamo();

                                }
                                }, "json")
                                .fail(function() {
                                    toastr.error("no se pudo conectar al servidor", "Error Conexión");
                                    desbloquearBotonNuevoPrestamo();

                                });
                            }                                        
                        });  
                                            
                    }
                    else{
                        console.log(data.mensaje);             
                        desbloquearBotonNuevoPrestamo();

                    }
                }, "json")
                .fail(function() {
                    toastr.error("no se pudo conectar al servidor", "Error Conexión");
                    desbloquearBotonNuevoPrestamo();

                });
            }


            

            $.post("funciones/ws_prestamos.php", ($("#formNuevoPrestamo").serialize()+"&"+ 
              $.param({ accion: "nuevo", 
                        det_festivos:  JSON.stringify(det_festivos),
                        cobrarPrimeraCuota: cobrarPrimeraCuota,
                        moraincrementable:moraincrementable,
                        cobrarPapeleria:cobrarPapeleria
              })) ,function(data) {


            if(data.resultado){
                toastr.success(data.mensaje, "Exito");
                $("#divNuevoPrestamo").modal("hide");
                setTimeout(function(){ratPack.refresh();},300);
                desbloquearBotonNuevoPrestamo();
            }
            else{
                toastr.warning(data.mensaje,"Info");
                desbloquearBotonNuevoPrestamo();

            }
          }, "json")
          .fail(function() {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
            desbloquearBotonNuevoPrestamo();

          });






        }
      }else{
        toastr.warning("Todos los campos son requeridos","Info");
      }
    }




    function bloquearBotonNuevoPrestamo(){
        $("#btnGuardarNuevoPrestamo").prop("disabled",true);
        $("#btnGuardarNuevoPrestamo").html('Guardando<i class="fas fa-1x fa-sync-alt fa-spin"></i>');
    }

    function desbloquearBotonNuevoPrestamo(){
        $("#btnGuardarNuevoPrestamo").prop("disabled",false);
        $("#btnGuardarNuevoPrestamo").html('Guardar');    
    }



   


    /******************  MUESTRA EL FORMULARIO PARA ELIMINAR LOS REGISTROS *******************/
    function eliminarPrestamo (e) {
      e.preventDefault();
      $("#divEliminarPrestamo").modal("show", {backdrop: "static"});
      $("#idEliminarPrestamo").val(e.data.idprestamo);

      $("#claveAnulacionPrestamo").val('')
    }

    
    $("#btnEliminarPrestamo").on("click",guardarEliminarPrestamo);
    
    function guardarEliminarPrestamo(e){
        e.preventDefault();


        var claveAnulacionPrestamo = $("#claveAnulacionPrestamo").val();

        if( claveAnulacionPrestamo.trim() == "123" || claveAnulacionPrestamo.trim() == "123" ){

            $.post("funciones/ws_prestamos.php", { idprestamo:$("#idEliminarPrestamo").val() , accion:"eliminar" } ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");
                $("#divEliminarPrestamo").modal("hide");
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



    $("#btnCDF").on("click",function(e){
        
        if (inputValorCuota != '' && $("#formNuevoPrestamo #newCapital").val() != '' && $("#formNuevoPrestamo #fechainicio").val() != '') {
            habilitarCalendario();                     
        }else{
            deshabilitarCalendario();
        }                

    });


    
    $("#btnPrimeraCuota").on("click",function(e){
      

        if ($("#btnPrimeraCuota").is(':checked')) {            
        

            if (inputValorCuota != '' && $("#formNuevoPrestamo #newCapital").val() != '' && $("#formNuevoPrestamo #fechainicio").val() != '') {
                $("#pagarPrimeraCuota").val( $("#formNuevoPrestamo #cuotaSeleccionada").val() );
                habilitarCalendario();                     
            }else{
                $("#pagarPrimeraCuota").val('');
                deshabilitarCalendario();
            }

        }else{


            $("#pagarPrimeraCuota").val('');

            if (inputValorCuota != '' && $("#formNuevoPrestamo #newCapital").val() != '' && $("#formNuevoPrestamo #fechainicio").val() != '') {
                habilitarCalendario();                     
            }else{
                deshabilitarCalendario();
            }
            

        }        

    });




    $("#btnPendientesClientes").on("click",function(e){
        e.preventDefault();


        $("#tablaReporteMeta  tbody tr").remove();


        if ($("#formNuevoPrestamo #idcliente").val() != '') {
            

            $.post("funciones/ws_prestamos.php", { idcliente:$("#formNuevoPrestamo #idcliente").val() , accion:"prestamosPendientesClientes" } ,function(data) {
                    if(data.resultado){
                        
                        $("#divReporteMeta").modal("show", {backdrop: "static"});
                                    
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
                                    

                    }
                    else{
                        toastr.warning(data.mensaje,"Info");
                    }
                }, "json")
                .fail(function() {
                    toastr.error("no se pudo conectar al servidor", "Error Conexión");
                });


                




        }else{
            toastr.warning("Por favor seleccione al cliente","Info");
        }
        
    });






    ///////////////////////////////////////////////////////////////////
    ///////////////////INTEGRACIÓN DE CALENDARIO WEB///////////////////
    ///////////////////////////////////////////////////////////////////





    $("#btnCalendario").on("click",mostrarModalNuevoCalendario);    
    function mostrarModalNuevoCalendario(e){
        e.preventDefault();
        $("#formNuevoCalendario")[0].reset();
        $("#divNuevoCalendario").modal("show", {backdrop: "static"});

        $.post("funciones/ws_diasFestivos.php", "accion=llenarCalendario&"+$("#formNuevoPrestamo").serialize() ,function(data) {
        if(data.resultado){

            var det_detalle_festivos = Array();

            $.each(data.buscarDiasFestivos,function(key,value) {         
                var fecha = value["fecha"];
                fecha = fecha.substr(5, 9);                            
                det_detalle_festivos.push(fecha);
            });


            $("#contenedorCalendario").html('');
            $("#contenedorCalendario").html('<div id="CalendarioWeb"></div>');

            var det_detalle_dias = Array();
            
            $.each(data.diasMarcados,function(key,value) {  

                det_detalle_dias[key] =  new Object();
                det_detalle_dias[key].start = value;
                det_detalle_dias[key].end = value;
                det_detalle_dias[key].overlap = false;
                det_detalle_dias[key].rendering = 'background';

                if(det_detalle_festivos.includes(value.substr(5, 9)) && $("#btnCDF").is(':checked')){

                    if ( value.substr(5, 9) == fechaActual.substr(5, 9) && $("#btnPrimeraCuota").is(':checked') ) {                        
                        det_detalle_dias[key].color = '#8fdf82';
                    }else{
                        det_detalle_dias[key].color = '#FFAEAE';
                    }

                }else{
                    det_detalle_dias[key].color = '#8fdf82';                    
                }

            });

            mostrarTabla_DiasFestivos();
            function mostrarTabla_DiasFestivos() {
                $("#tablaDiasFestivos  tbody tr").remove();
                var contador = 1;
                var totalDiasFest = 0;
                
                $.each(det_detalle_dias,function(key,value) {  

                    if (value['color'] == '#FFAEAE') {

                        var dateTime = moment( value["start"] );
                        var full = dateTime.format('LL');

                        $("<tr></tr>")
                        .append( "<td>" + (contador) + "</td>" )
                        .append( "<td>" + full + "</td>" )
                        .append( "<td>Q. " + parseFloat($("#formNuevoPrestamo #cuotaSeleccionada").val()).toFixed(2) + "</td>" )
                        .appendTo("#tablaDiasFestivos > tbody");
                        
                        totalDiasFest += parseFloat($("#formNuevoPrestamo #cuotaSeleccionada").val());
                        contador++;
                    }

                });

                $("<tr style='border-top: 1pt solid #887b7b;'></tr>")
                .append( "<td></td>" )
                .append( "<td>TOTAL:</td>" )
                .append( "<td>Q. " + parseFloat(totalDiasFest).toFixed(2) + "</td>" )
                .appendTo("#tablaDiasFestivos > tbody");

                det_temp_dias = [];
                det_temp_dias = det_detalle_dias;
                
            }


           
            $("#CalendarioWeb").fullCalendar({
                events: det_detalle_dias,
                
                dayClick:function(date,jsEvent,view){

                    if (data.diasMarcados.includes(date.format())) {

                        var pos = -1;
                        for (var i = 0; i < det_detalle_dias.length ; i++) {
                            if (det_detalle_dias[i]['start'] == date.format()) {                                
                                pos = i;
                                break;
                            }
                        }

                        if (pos != -1) {
                            if(det_detalle_dias[pos]['color'] == '#FFAEAE') {
                                det_detalle_dias[pos]['color'] = '#8fdf82';
                            }else{
                                det_detalle_dias[pos]['color'] = '#FFAEAE';                                
                            }                             
                        }

                        $("#CalendarioWeb").fullCalendar('removeEvents'); 
                        $("#CalendarioWeb").fullCalendar('addEventSource', det_detalle_dias); 
                        $("#CalendarioWeb").fullCalendar('render');
                        mostrarTabla_DiasFestivos();
                    }

                },

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
        .fail(function() {
        toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });

          
    }


    function deshabilitarCalendario() {
        $("#btnCalendario").prop("disabled",true);
    }

    function habilitarCalendario() {
        $("#btnCalendario").prop("disabled",false);        

        //Calcular los días festivos        
    
        $.post("funciones/ws_diasFestivos.php", "accion=llenarCalendario&"+$("#formNuevoPrestamo").serialize() ,function(data) {
        if(data.resultado){

            var det_detalle_festivos = Array();

            $.each(data.buscarDiasFestivos,function(key,value) {         
                var fecha = value["fecha"];
                fecha = fecha.substr(5, 9);                            
                det_detalle_festivos.push(fecha);
            });

            var det_detalle_dias = Array();
            
            $.each(data.diasMarcados,function(key,value) {  

                det_detalle_dias[key] =  new Object();
                det_detalle_dias[key].start = value;
                det_detalle_dias[key].end = value;
                det_detalle_dias[key].overlap = false;
                det_detalle_dias[key].rendering = 'background';

                if(det_detalle_festivos.includes(value.substr(5, 9)) && $("#btnCDF").is(':checked')){
                    
                    if ( value.substr(5, 9) == fechaActual.substr(5, 9) && $("#btnPrimeraCuota").is(':checked') ) {                        
                        det_detalle_dias[key].color = '#8fdf82';
                    }else{
                        det_detalle_dias[key].color = '#FFAEAE';
                    }

                }else{
                    det_detalle_dias[key].color = '#8fdf82';                    
                }

            });

            mostrarTabla_DiasFestivos();

            function mostrarTabla_DiasFestivos() {

                $("#selectDF").html('');
                var totalDiasFest = 0;
                
                $.each(det_detalle_dias,function(key,value) {  
                    if (value['color'] == '#FFAEAE') {                                                
                        totalDiasFest += parseFloat($("#formNuevoPrestamo #cuotaSeleccionada").val());   
                        
                        var diaFestivo = moment( value['start'] );
                        diaFestivo = diaFestivo.format('LL');

                        $("#selectDF").append("<option value='"+diaFestivo+"' selected='selected'>"+diaFestivo+"</option>");
                    }
                });

                if (totalDiasFest == 0) {
                    $("#formNuevoPrestamo #cobroDiasFestivos").val('');           
                }else{
                    $("#formNuevoPrestamo #cobroDiasFestivos").val(totalDiasFest);
                }
                

                var totalPPCuota = $("#pagarPrimeraCuota").val();
                var totalcapital =  $("#formNuevoPrestamo #newCapital").val();
                var total_Papeleria = 0
                var total_saldo_pendiente = 0;

                if ($("#formNuevoPrestamo #btnCobroPapeleria").is(':checked')) {
                    total_Papeleria = $("#formNuevoPrestamo #cobroPapeleria").val();                  
                }

                if ( $("#formNuevoPrestamo #saldoPendiente").val() != '' ) {
                    total_saldo_pendiente = $("#formNuevoPrestamo #saldoPendiente").val();
                }

                
                            
                $("#formNuevoPrestamo #capitalEntregado").val((totalcapital - total_Papeleria - totalDiasFest - totalPPCuota - total_saldo_pendiente));

                det_dias = [];
                det_dias = det_detalle_dias;
                
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


    $("#btnGuardarNuevoCalendario").on("click",function(){
        det_dias = [];
        det_dias = det_temp_dias;
        $("#selectDF").html('');

        var totalDiasFest = 0;
                
        $.each(det_dias,function(key,value) {  
            if (value['color'] == '#FFAEAE') {                                                
                totalDiasFest += parseFloat($("#formNuevoPrestamo #cuotaSeleccionada").val());       

                var diaFestivo = moment( value['start'] );
                diaFestivo = diaFestivo.format('LL');

                $("#selectDF").append("<option value='"+diaFestivo+"' selected='selected'>"+diaFestivo+"</option>");
            }
        });

        if (totalDiasFest == 0) {
            $("#formNuevoPrestamo #cobroDiasFestivos").val('');           
        }else{
            $("#formNuevoPrestamo #cobroDiasFestivos").val(totalDiasFest);
        }
        
        var totalPPCuota = $("#pagarPrimeraCuota").val();
        var totalcapital =  $("#formNuevoPrestamo #newCapital").val();

        var total_Papeleria = 0
        var total_saldo_pendiente = 0;

        if ($("#formNuevoPrestamo #btnCobroPapeleria").is(':checked')) {
            total_Papeleria = $("#formNuevoPrestamo #cobroPapeleria").val();                  
        }
        
        if ( $("#formNuevoPrestamo #saldoPendiente").val() != '' ) {
            total_saldo_pendiente = $("#formNuevoPrestamo #saldoPendiente").val();
        }

        $("#formNuevoPrestamo #capitalEntregado").val((totalcapital - total_Papeleria - totalDiasFest - totalPPCuota - total_saldo_pendiente));

        $('#divNuevoCalendario').modal("hide");

    });

    ///////////////////////////////////////////////////////////////////
    ///////////////////INTEGRACIÓN DE CALENDARIO WEB///////////////////
    ///////////////////////////////////////////////////////////////////


















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


    $("#btnCancelarNuevaGarantia").click(function(){  
        if(rutaActual != ''){
        $.post("funciones/ws_clientes.php", { accion:"eliminarImagen", ruta:rutaActual } ,function(data) {
        if(data.resultado){
            //console.log(data.mensaje);

            rutaActual = "";
            mostrarListadoGarantias( $("#formNuevaGarantia #id_prestamo").val() );

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


    $("#btnCancelarNuevaGarantiax").click(function(){  

        if(rutaActual != ''){
        $.post("funciones/ws_clientes.php", { accion:"eliminarImagen", ruta:rutaActual } ,function(data) {
        if(data.resultado){
            //console.log(data.mensaje);

            rutaActual = "";
            mostrarListadoGarantias( $("#formNuevaGarantia #id_prestamo").val() );

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


    $("#btnCancelarEditarGarantia").click(function(){  
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


    $("#btnCancelarEditarGarantiax").click(function(){  

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




    /****************** ELIMINAR IMAGEN DEL MODAL EDITAR *******************/
    $("#btnEliminarImagen").on("click",funcionEliminarImagen);

    function funcionEliminarImagen(e){
        e.preventDefault();

        //Borramos la imagen si ha seleccionado alguna
        if(rutaActual != ''){
        $.post("funciones/ws_clientes.php", { accion:"eliminarImagen", ruta:rutaActual } ,function(data) {
        if(data.resultado){
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

        

        //Borramos la que ya esta en la BD
        $.post("funciones/ws_garantias.php", "accion=quitarImagen&"+$("#formEditarGarantia").serialize() ,function(data) {
        if(data.resultado){

            $('#formEditarGarantia #uploaded_image_2').attr('src', 'upload/opcion.png');
            $("#contEliminarImagen").attr("style","display:none;");


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


    function mostrarListadoGarantias(id_prestamo) {


        $("#tablaGarantias  tbody tr").remove();
        $.post("funciones/ws_garantias.php", { accion: "mostrar", id_prestamo:id_prestamo }, function(data) {
        if(data.resultado)
            {

                if ( $.fn.dataTable.isDataTable( '#tablaGarantias' ) ) {
                    $("#tablaGarantias").DataTable().destroy();
                    $("#tablaGarantias  tbody tr").remove();
                }

            var btnEditar = "";
            var btnEliminar = "";

            $.each(data.registros,function(key,value) {
                var estado ="Si pagado";
                if (value["estado"] == 0) {
                    var estado ="No pagado";
                }

                if (Modificar == 1) {
                btnEditar = " <button class='btn btn-default bg-lightblue tooltip2' style='cursor:pointer; ' href='#' ><span class='tooltiptext'>Editar Garantía</span><i class='fa fa-edit fa-lg '></i></button>";
                };

                if (Eliminar == 1) {
                btnEliminar = " <button class='btn btn-default tooltip2' style='cursor:pointer;' href='#' ><span class='tooltiptext'>Eliminar Garantía</span> <i class='fa fa-trash fa-lg '></i></button>";
                };



        

                $("<tr></tr>")
                .append( "<td>" + (key + 1) + "</td>" )
                .append( "<td>" + value["nombre"] + "</td>" )
                .append( "<td>Q. " + parseFloat(value["valuacion"]).toFixed(2) + "</td>" )
                .append( "<td> <div class='filtr-item' data-category='1' data-sort='white sample'><a href='"+ value["foto"] +"' data-toggle='lightbox' data-title='Imagen de perfil'><img src='"+ value["foto"] +"' class='img-circle img-size-32 mr-2' alt='white sample'/></a></div> </td>" )
                .append( "<td>" + estado + "</td>" )
                .append( "<td>" + value["descripcion"] + "</td>" )
                .append( $("<td></td>").append( 
                $("<div class='btn-group'></div>") 
                        
                    .append( $(btnEditar)
                        .on("click",{ idgarantias:value["id"] } , editarGarantia) ) 
                    .append( $(btnEliminar)
                        .on("click",{ idgarantias:value["id"] } , eliminarGarantia) )  

                            
                    )
                )
                .appendTo("#tablaGarantias > tbody");
            });

            
            $("#tablaGarantias").DataTable({ 

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

    $("#btnNuevaGarantia").on("click",mostrarModalNuevaGarantia);

    function mostrarModalNuevaGarantia(e){
        e.preventDefault();

        modalActivo = 1;
        $("#formNuevaGarantia")[0].reset();
        $('#formNuevaGarantia #uploaded_image').attr('src', 'upload/opcion.png');
        $("#formNuevaGarantia #newEstado option[value='0']").attr("selected","selected");
        $(".select2-list").select2({ allowClear: true });
        $("#divNuevaGarantia").modal("show", {backdrop: "static"});

    }


    /****************** GUARDAR DATOS DEL REGISTRO *******************/
    $("#btnGuardarNuevaGarantia").on("click",guardarNuevaGarantia);
    function guardarNuevaGarantia(e){
      e.preventDefault();

      if($("#formNuevaGarantia").valid()) {
          //console.log($("#formNuevaGarantia").serialize());
          $.post("funciones/ws_garantias.php", "accion=nuevo&ruta="+rutaActual+"&"+$("#formNuevaGarantia").serialize() ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");
                $("#divNuevaGarantia").modal("hide");
                rutaActual = ""; 
                mostrarListadoGarantias( $("#formNuevaGarantia #id_prestamo").val() );

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




    function editarGarantia (e) {
        e.preventDefault();
        $.post("funciones/ws_garantias.php", { accion:"mostrar" , id:e.data.idgarantias }, function(data) {
          if(data.resultado)
            {

              $("#formEditarGarantia")[0].reset();
              $("#divEditarGarantia").modal("show", {backdrop: "static"});
              $("#formEditarGarantia input").addClass("dirty");
              
              modalActivo = 2;
              
              $("#formEditarGarantia #idgarantias").val(data.registros[0]["id"]);      
              $("#formEditarGarantia #edit_Nombre").val(data.registros[0]["nombre"]);      
              $("#formEditarGarantia #editValuacion").val(data.registros[0]["valuacion"]);      
              $("#formEditarGarantia #editDescripcion").val(data.registros[0]["descripcion"]);                         
              $("#formEditarGarantia #edit_Serie").val(data.registros[0]["serie"]);      
              $("#formEditarGarantia #edit_Modelo").val(data.registros[0]["modelo"]);      
              $("#formEditarGarantia #edit_Marca").val(data.registros[0]["marca"]);      



              $('#formEditarGarantia #editEstado').val(data.registros[0]["estado"]).trigger('change.select2');

              
              if(data.registros[0]["ruta"] == ""){
                $('#formEditarGarantia #uploaded_image_2').attr('src', 'upload/opcion.png');
                $("#contEliminarImagen").attr("style","display:none;");
              }else{
                $('#formEditarGarantia #uploaded_image_2').attr('src', data.registros[0]["ruta"]);
                $("#contEliminarImagen").attr("style","display:block;");
              }

              if (data.registros[0]["multprestamos"] == 1) {
                $("#edit_multprestamos").prop("checked", true);
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
    $("#btnGuardarEditarGarantia").on("click",guardarEditarGarantia);

    function guardarEditarGarantia(e){
      e.preventDefault();

      if($("#formEditarGarantia").valid()) {
          $.post("funciones/ws_garantias.php", "accion=editar&ruta="+rutaActual+"&"+$("#formEditarGarantia").serialize() ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");
                $("#divEditarGarantia").modal("hide");
                rutaActual = ""; 

                mostrarListadoGarantias( $("#formNuevaGarantia #id_prestamo").val() );
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
    function eliminarGarantia (e) {
      e.preventDefault();
      $("#divEliminarGarantia").modal("show", {backdrop: "static"});
      $("#ideliminarGarantia").val(e.data.idgarantias);
      $("#claveAnulacionGarantia").val('')
    }


    $("#btnEliminarGarantia").on("click",guardarEliminarGarantia);
    
    function guardarEliminarGarantia(e){
        e.preventDefault();

        var claveAnulacionGarantia = $("#claveAnulacionGarantia").val();

        if( claveAnulacionGarantia.trim() == "123" || claveAnulacionGarantia.trim() == "123" ){


            $.post("funciones/ws_garantias.php", { idgarantia:$("#ideliminarGarantia").val() , accion:"eliminar" } ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");
                $("#divEliminarGarantia").modal("hide");
                mostrarListadoGarantias( $("#formNuevaGarantia #id_prestamo").val() );
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



    $("#aproximacion5").on("click",function(e){
        $("#formNuevoPrestamo #newCapital").val('');
        setTimeout(function() { $('#formNuevoPrestamo #newCapital').focus() }, 500);
    });


    $("#aproximacion1").on("click",function(e){
        $("#formNuevoPrestamo #newCapital").val('');
        setTimeout(function() { $('#formNuevoPrestamo #newCapital').focus() }, 500);
    });


    $("#aproximacionmenos1").on("click",function(e){
        $("#formNuevoPrestamo #newCapital").val('');
        setTimeout(function() { $('#formNuevoPrestamo #newCapital').focus() }, 500);
    });

    
    function calcularMora() {
        var num_n = 0;
        var num_m = 1;
        var num_capi = 0;

        if (isNaN(parseInt($("#formNuevoPrestamo #newn").val())) == false ){            
            num_n = parseInt($("#formNuevoPrestamo #newn").val());
        }

        if (isNaN(parseInt($("#formNuevoPrestamo #newm").val())) == false ){            
            num_m =  parseInt($("#formNuevoPrestamo #newm").val());
        }

        if (isNaN(parseInt($("#formNuevoPrestamo #newCapital").val())) == false ){            
            num_capi =  parseInt($("#formNuevoPrestamo #newCapital").val());
        }


        var totalMora = (num_capi/num_m)*num_n;

        $("#formNuevoPrestamo #montoTotalMora").val( totalMora );



    }


    
    $("#formNuevoPrestamo #newn").keyup(function(){         
        calcularMora();
    });

    $("#formNuevoPrestamo #newm").keyup(function(){         
        calcularMora();
    });



        
    /****************** MOSTRAR MODAL NUEVO REGISTRO *******************/
    $("#btnRL").on("click",mostrarModalNRL);
    
    function mostrarModalNRL(e){
        e.preventDefault();
        $("#formNuevoRepresentanteLegal")[0].reset();
        $("#divNuevoRepresentanteLegal").modal("show", {backdrop: "static"});
    }



    /****************** MOSTRAR MODAL NUEVO REGISTRO *******************/
    $("#btnAbogado").on("click",mostrarModalNA);
    
    function mostrarModalNA(e){
        e.preventDefault();
        $("#formNuevoAbogado")[0].reset();
        $("#divNuevoAbogado").modal("show", {backdrop: "static"});
    }



    /****************** GUARDAR DATOS DEL REGISTRO *******************/
    $("#btnGuardarNuevoRepresentanteLegal").on("click",guardarNRL);
        function guardarNRL(e){
        e.preventDefault();

        if($("#formNuevoRepresentanteLegal").valid()) {
            $.post("funciones/ws_representanteLegal.php", "accion=nuevo&"+$("#formNuevoRepresentanteLegal").serialize() ,function(data) {
                if(data.resultado){
                    toastr.success(data.mensaje, "Exito");
                    $("#divNuevoRepresentanteLegal").modal("hide");     
                    mostrarListadoRepresentante();       
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
        $("#btnGuardarNuevoAbogado").on("click",guardarNA);
        function guardarNA(e){
        e.preventDefault();

        if($("#formNuevoAbogado").valid()) {
            $.post("funciones/ws_abogados.php", "accion=nuevo&"+$("#formNuevoAbogado").serialize() ,function(data) {
                if(data.resultado){
                    toastr.success(data.mensaje, "Exito");
                    $("#divNuevoAbogado").modal("hide");                    
                    mostrarListadoAbogados();
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






    function mostrarListadoRepresentante() {


        $("#tablaRepresentantes  tbody tr").remove();
        $.post("funciones/ws_representanteLegal.php", { accion: "mostrar" }, function(data) {
        if(data.resultado)
            {

                if ( $.fn.dataTable.isDataTable( '#tablaRepresentantes' ) ) {
                    $("#tablaRepresentantes").DataTable().destroy();
                    $("#tablaRepresentantes  tbody tr").remove();
                }

                var btnEditar = "";
                var btnEliminar = "";

            $.each(data.registros,function(key,value) {
                var estado ="Casado";
                if (value["estadocivil"] == 1) {
                    var estado ="Soltero";
                }

                if (Modificar == 1) {
                btnEditar = " <button class='btn btn-default bg-lightblue tooltip2' style='cursor:pointer; ' href='#' ><span class='tooltiptext'>Editar Representante legal</span><i class='fa fa-edit fa-lg '></i></button>";
                };

                if (Eliminar == 1) {
                btnEliminar = " <button class='btn btn-default tooltip2' style='cursor:pointer;' href='#' ><span class='tooltiptext'>Eliminar Representante legal</span> <i class='fa fa-trash fa-lg '></i></button>";
                };

                $("<tr></tr>")
                .append( "<td>" + (key + 1) + "</td>" )
                .append( "<td>" + value["nombre"] + "</td>" )
                .append( "<td>" + value["nacimiento"] + "</td>" )
                .append( "<td>" + estado + "</td>" )
                .append( "<td>" + value["nacionalidad"] + "</td>" )
                .append( "<td>" + value["oficio"] + "</td>" )
                .append( "<td>" + value["dpi"] + "</td>" )
                .append( $("<td></td>").append( 
                $("<div class='btn-group'></div>") 
                        
                    .append( $(btnEditar)
                        .on("click",{ idrepresentantelegal:value["id"] } , editarRepresentanteLegal) ) 
                    .append( $(btnEliminar)
                        .on("click",{ idrepresentantelegal:value["id"] } , eliminarRepresentante) ) 

                            
                    )
                )
                .appendTo("#tablaRepresentantes > tbody");
            });

            
            $("#tablaRepresentantes").DataTable({ 

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


    function mostrarListadoAbogados() {


        $("#tablaAbogados  tbody tr").remove();
        $.post("funciones/ws_abogados.php", { accion: "mostrar" }, function(data) {
        if(data.resultado)
            {

                if ( $.fn.dataTable.isDataTable( '#tablaAbogados' ) ) {
                    $("#tablaAbogados").DataTable().destroy();
                    $("#tablaAbogados  tbody tr").remove();
                }

                var btnEditar = "";
                var btnEliminar = "";

            $.each(data.registros,function(key,value) {
                

                if (Modificar == 1) {
                btnEditar = " <button class='btn btn-default bg-lightblue tooltip2' style='cursor:pointer; ' href='#' ><span class='tooltiptext'>Editar Abogado</span><i class='fa fa-edit fa-lg '></i></button>";
                };

                if (Eliminar == 1) {
                btnEliminar = " <button class='btn btn-default tooltip2' style='cursor:pointer;' href='#' ><span class='tooltiptext'>Eliminar Abogado</span> <i class='fa fa-trash fa-lg '></i></button>";
                };

                $("<tr></tr>")
                .append( "<td>" + (key + 1) + "</td>" )
                .append( "<td>" + value["nombre"] + "</td>" )
                .append( "<td>" + value["colegiado"] + "</td>" )
                .append( $("<td></td>").append( 
                $("<div class='btn-group'></div>") 
                        
                    .append( $(btnEditar)
                        .on("click",{ idabogado:value["id"] } , editarAbogado) ) 
                    .append( $(btnEliminar)
                        .on("click",{ idabogado:value["id"] } , eliminarAbogado) ) 

                            
                    )
                )
                .appendTo("#tablaAbogados > tbody");
            });

            
            $("#tablaAbogados").DataTable({ 

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




    function editarRepresentanteLegal (e) {
        e.preventDefault();
        $.post("funciones/ws_representanteLegal.php", { accion:"mostrar" , idrepresentantelegal:e.data.idrepresentantelegal }, function(data) {
          if(data.resultado)
            {

              $("#formEditarRepresentanteLegal")[0].reset();
              $("#divEditarRepresentanteLegal").modal("show", {backdrop: "static"});
                            
              $("#formEditarRepresentanteLegal #idrepresentantelegal").val(data.registros[0]["id"]);      
              $("#formEditarRepresentanteLegal #editNameRL").val(data.registros[0]["nombre"]);      
              $("#formEditarRepresentanteLegal #editNacimiento").val(data.registros[0]["nacimiento"]);      
              $("#formEditarRepresentanteLegal #editNacionalidad").val(data.registros[0]["nacionalidad"]);                         
              $("#formEditarRepresentanteLegal #editOficio").val(data.registros[0]["oficio"]);      
              $("#formEditarRepresentanteLegal #editDPI").val(data.registros[0]["dpi"]);

              $('#formEditarRepresentanteLegal #editEstadoCivil').val(data.registros[0]["estadocivil"]).trigger('change.select2');
                           
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


    function editarAbogado (e) {
        e.preventDefault();
        $.post("funciones/ws_abogados.php", { accion:"mostrar" , idabogado:e.data.idabogado }, function(data) {
          if(data.resultado)
            {

              $("#formeditarAbogado")[0].reset();
              $("#diveditarAbogado").modal("show", {backdrop: "static"});
                            
              $("#formeditarAbogado #idabogado").val(data.registros[0]["id"]);      
              $("#formeditarAbogado #editar_nombre").val(data.registros[0]["nombre"]);      
              $("#formeditarAbogado #editar_colegiado").val(data.registros[0]["colegiado"]);      
                           
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
    $("#btnGuardarEditarRepresentanteLegal").on("click",guardarERL);

    function guardarERL(e){     
      e.preventDefault();
      if($("#formEditarRepresentanteLegal").valid()) {
          $.post("funciones/ws_representanteLegal.php", "accion=editar&"+$("#formEditarRepresentanteLegal").serialize() ,function(data) {
            if(data.resultado){              
                toastr.success(data.mensaje, "Exito");;
                $("#divEditarRepresentanteLegal").modal("hide");
                mostrarListadoRepresentante();
            }else{
                toastr.warning(data.mensaje,"Info");
            }
          }, "json")
          .fail(function() {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
          });
      }

    }




    /****************** MODIFICAR DATOS DEL REGISTRO *******************/
    $("#btnGuardareditarAbogado").on("click",guardarEA);

    function guardarEA(e){     
      e.preventDefault();
      if($("#formeditarAbogado").valid()) {
          $.post("funciones/ws_abogados.php", "accion=editar&"+$("#formeditarAbogado").serialize() ,function(data) {
            if(data.resultado){              
                toastr.success(data.mensaje, "Exito");;
                $("#diveditarAbogado").modal("hide");
                mostrarListadoAbogados();
            }else{
                toastr.warning(data.mensaje,"Info");
            }
          }, "json")
          .fail(function() {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
          });
      }

    }


    /******************  MUESTRA EL FORMULARIO PARA ELIMINAR LOS REGISTROS *******************/
    function eliminarRepresentante (e) {
      e.preventDefault();
      $("#divEliminarRepresentante").modal("show", {backdrop: "static"});
      $("#ideliminarRepresentante").val(e.data.idrepresentantelegal);
      $("#claveAnulacionRepresentante").val('');
    }


    $("#btnEliminarRepresentante").on("click",guardarEliminarRepresentante);
    
    function guardarEliminarRepresentante(e){
        e.preventDefault();

        var claveAnulacionRepresentante = $("#claveAnulacionRepresentante").val();

        if( claveAnulacionRepresentante.trim() == "123" || claveAnulacionRepresentante.trim() == "123" ){


            $.post("funciones/ws_representanteLegal.php", { idrepresentantelegal:$("#ideliminarRepresentante").val() , accion:"eliminar" } ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");
                $("#divEliminarRepresentante").modal("hide");
                mostrarListadoRepresentante();
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



    /******************  MUESTRA EL FORMULARIO PARA ELIMINAR LOS REGISTROS *******************/
    function eliminarAbogado (e) {
      e.preventDefault();
      $("#divEliminarAbogado").modal("show", {backdrop: "static"});
      $("#ideliminarAbogado").val(e.data.idabogado);
      $("#claveAnulacionAbogado").val('');
    }


    $("#btnEliminarAbogado").on("click",guardarEliminarAbogado);
    
    function guardarEliminarAbogado(e){
        e.preventDefault();

        var claveAnulacionAbogado = $("#claveAnulacionAbogado").val();

        if( claveAnulacionAbogado.trim() == "123" || claveAnulacionAbogado.trim() == "123" ){


            $.post("funciones/ws_abogados.php", { idabogado:$("#ideliminarAbogado").val() , accion:"eliminar" } ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");
                $("#divEliminarAbogado").modal("hide");
                mostrarListadoAbogados();
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