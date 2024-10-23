<?php
session_start();
require_once ("../funciones/classSQL.php");
$conexion = new conexion();
if($conexion->permisos($_SESSION['idtipousuario'],"2","acceso"))
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
                <h2 class='titulo'>Clientes</h2>
            </div>            
           
        </div>
    </div><!-- /.container-fluid -->
</section>


<!-- Main content -->
<section class="content">
    <div class="card">

    

        <div class="card-header">
                
            <?php if($conexion->permisos($_SESSION['idtipousuario'],"2","crear")) { ?>
                <button type="button" id="btnNuevoCliente" data-toggle="modal" class="btn bg-navy btn-lg">Nuevo Cliente</button>
            <?php } ?>
                
        </div>
        <!-- /.card-header -->
        <div class="card-body" style="overflow-x: scroll;">
           
            <div  class="table-responsive " >
                <table id="tablaClientes" class="table table-striped" >
                    <thead>
                    <tr>  
                        <th>No.</th>
                        <th>CÓDIGO</th>
                        <th>NOMBRE</th>
                        <th>FOTO</th>
                        <th>DPI</th>
                        <th>DIRECCIÓN</th>
                        <th>TELÉFONO</th>
                        <th>TIPO NEGOCIO</th>
                        <th>DIRECCIÓN NEGOCIO</th>
                        <th>REFERENCIA PERSONAL</th>
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
        

        <div class="card-header" style="padding: 0px;">        
            <div class="card card-widget widget-user" style="margin: 0px;">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-lightblue">
                <h3 class="widget-user-username" id="nameUser"></h3>
                <h5 class="widget-user-desc" id="dirUser"></h5>
              </div>
              <div class="widget-user-image" id="imgUser">
                
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-3 border-right">
                    <div class="description-block">
                      <h5 class="description-header" id="prestamosRealizados">0</h5>
                      <span class="description-text">Préstamos Realizados</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-2 border-right">
                    <div class="description-block">
                      <h5 class="description-header" id="morasPagadas">0</h5>
                      <span class="description-text">Moras pagadas</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-2 border-right">
                    <div class="description-block">
                      <h5 class="description-header" id="morasPendientes">0</h5>
                      <span class="description-text">Moras pendientes</span>
                    </div>
                    <!-- /.description-block -->
                  </div><!-- /.col -->
                  <div class="col-sm-2 border-right">
                    <div class="description-block">
                      <h5 class="description-header" id="morasExoneradas">0</h5>
                      <span class="description-text">Moras exoneradas</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3">
                    <div class="description-block">
                        <h5 class="description-header" id="idEstado"> 
                             
                        </h5>
                      <span class="description-text">Tipo de cliente</span>
                      <span class="users-list-date" id="txt_tipo_cliente"> Cliente registrado como: </span>

                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
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
                
                <?php if($conexion->permisos($_SESSION['idtipousuario'],"2","crear")) { ?>                   

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


<div id="divNuevoCliente" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: auto;">
    <div class="modal-dialog">
        <form id='formNuevoCliente' class="form form-validate"  role="form"   method="post" >
            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Nuevo Cliente</h4>

                &nbsp;&nbsp;
                <div class="btn-group">
                    <button type="button" style="cursor: pointer;" id="btnFoto" class="btn btn-default tooltip2">
                        <span class='tooltiptext'>Tomar foto</span> 
                        <i class="fas fa-camera"></i>
                    </button>
                </div>


                <button type="button" id="btnCancelarNuevoClientex" class="close" data-dismiss="modal" aria-label="Close">
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
                            <img src="upload/user.png" id="uploaded_image" class="img-responsive img-circle" />
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
                        <label for="newCodigo">Código:</label>
                        <input type="number" class="form-control" id="newCodigo" name="newCodigo" placeholder="Ingrese Código" required >
                    </div>

                    <div class="form-group col-md-12">
                        <label for="newNombre">Nombre:</label>
                        <input type="text" class="form-control" id="newNombre" name="newNombre" placeholder="Ingrese Nombre del cliente" required >
                    </div>

                    <div class="form-group col-md-12">
                        <label for="newDPI">DPI:</label>
                        <input type="text" class="form-control" id="newDPI" name="newDPI" placeholder="Ingrese el DPI" required >
                    </div>

                    <div class="form-group col-md-12">
                        <label for="newTelefono">Teléfono:</label>
                        <input type="text" class="form-control" id="newTelefono" name="newTelefono" placeholder="Ingrese Teléfono del cliente" required >
                    </div>
                   
                    <div class="form-group col-md-12">
                        <label for="newDireccion">Dirección:</label>
                        <input type="text" class="form-control" id="newDireccion" name="newDireccion" placeholder="Ingrese Dirección del cliente" required >
                    </div>

                    <div class="form-group col-md-12">
                        <label for="newAlquila">Alquila o es propio donde vive: </label>
                        <select class="form-control select2-list" id="newAlquila" name="newAlquila" data-placeholder="Seleccione una opción" required> 
                        <option value=""> </option>
                        <option value="1"> Alquila</option>
                        <option value="2"> Propio</option>
                        <option value="3"> Otros</option>
                        </select><div class="form-control-line"></div>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="newTipoNegocio">Negocio que tiene:</label>
                        <input type="text" class="form-control" id="newTipoNegocio" name="newTipoNegocio" placeholder="Ingrese Negocio" required >
                    </div>

                    <div class="form-group col-md-12">
                        <label for="newDireccionNegocio">En donde tiene su negocio:</label>
                        <input type="text" class="form-control" id="newDireccionNegocio" name="newDireccionNegocio" placeholder="Ingrese Dirección del Negocio" required >
                    </div>


                    <div class="form-group col-md-8">
                        <label for="newReferenciacredito1">Referencia de credito 1:</label>
                        <input type="text" class="form-control" id="newReferenciacredito1" name="newReferenciacredito1" placeholder="Ingrese Referencia de credito" required >
                    </div>

                    <div class="form-group col-md-4">
                        <label for="newTelefono1">Teléfono:</label>
                        <input type="text" class="form-control" id="newTelefono1" name="newTelefono1" placeholder="Ingrese Teléfono" required >
                    </div>


                    <div class="form-group col-md-8">
                        <label for="newReferenciapersonal1">Referencia Personal (No familiar) 1:</label>
                        <input type="text" class="form-control" id="newReferenciapersonal1" name="newReferenciapersonal1" placeholder="Ingrese Referencia Personal" required >
                    </div>

                    <div class="form-group col-md-4">
                        <label for="newTelefono2">Teléfono:</label>
                        <input type="text" class="form-control" id="newTelefono2" name="newTelefono2" placeholder="Ingrese Teléfono" required >
                    </div>

                    <div class="form-group col-md-12">
                        <label for="newprestamosanteriores">Préstamos anteriores:</label>
                        <input type="number" class="form-control" id="newprestamosanteriores" name="newprestamosanteriores" value="0" placeholder="Ingrese cantidad préstamos anteriores" required >
                    </div>

                    <div class="form-group col-md-12">
                        <label for="newtipoCliente">Tipo de cliente: </label>
                        <select class="form-control select2-list" id="newtipoCliente" name="newtipoCliente" data-placeholder="seleccione el tipo de cliente" required> 
                        <option value=""> </option>
                        <option value="1"> Bueno</option>
                        <option value="2"> Regular</option>
                        <option value="3"> Malo</option>
                        </select><div class="form-control-line"></div>
                    </div>

                    <div class="form-group col-md-12">
                       
                        <div class="icheck-success">
                            <input type="checkbox" id="multprestamos">
                            <label for="multprestamos">Múltiples préstamos</label>
                            <input type="hidden" class="form-control" name="newMultiple" id="newMultiple" />
                        </div>

                    </div>


                    <div class="form-group col-md-12">
                       
                        <div class="icheck-danger">
                            <input type="checkbox" id="newcheckpermitirprestamos">
                            <label for="newcheckpermitirprestamos">No permitir más préstamos</label>
                            <input type="hidden" class="form-control" name="newpermitirprestamos" id="newpermitirprestamos" />
                        </div>

                    </div>

                    
                </div>

                <div class="modal-footer">
                    <div class="response"></div>
                    <button type="button" id="btnGuardarNuevoCliente" class="btn bg-navy">Guardar</button>
                    <button type="button" id="btnCancelarNuevoCliente" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>  
    </div>
</div>


<div id="divEditarCliente" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: auto;">
    <div class="modal-dialog">
    <form id='formEditarCliente' class="form form-validate"  role="form"   method="post" >
        <div class="modal-content  panel panel-warning">           

            <div class="modal-header">
              <h4 class="modal-title">Editar Cliente</h4>

              &nbsp;&nbsp;
            <div class="btn-group">
                <button type="button" style="cursor: pointer;" id="btnEditFoto" class="btn btn-default tooltip2">
                    <span class='tooltiptext'>Tomar foto</span> 
                    <i class="fas fa-camera"></i>
                </button>
            </div>


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
                <br>
                <!--NUEVO CODIGO-->

        
                <div class="form-group col-md-12">
                    <label for="editCodigo">Código:</label>
                    <input type="hidden" class="form-control" name="idcliente" id="idcliente" />
                    <input type="number" class="form-control" id="editCodigo" name="editCodigo" placeholder="Ingrese Código" required >
                </div>

                <div class="form-group col-md-12">
                    <label for="editNombre">Nombre:</label>
                    <input type="text" class="form-control" id="editNombre" name="editNombre" placeholder="Ingrese Nombre del cliente" required >
                </div>

                <div class="form-group col-md-12">
                    <label for="editDPI">DPI:</label>
                    <input type="text" class="form-control" id="editDPI" name="editDPI" placeholder="Ingrese el DPI" required >
                </div>

                <div class="form-group col-md-12">
                    <label for="editTelefono">Teléfono:</label>
                    <input type="text" class="form-control" id="editTelefono" name="editTelefono" placeholder="Ingrese Teléfono del cliente" required >
                </div>
                
                <div class="form-group col-md-12">
                    <label for="editDireccion">Dirección:</label>
                    <input type="text" class="form-control" id="editDireccion" name="editDireccion" placeholder="Ingrese Dirección del cliente" required >
                </div>

                <div class="form-group col-md-12">
                    <label for="editAlquila">Alquila o es propio donde vive: </label>
                    <select class="form-control select2-list" id="editAlquila" name="editAlquila" data-placeholder="Seleccione una opción" required> 
                    <option value=""> </option>
                    <option value="1"> Alquila</option>
                    <option value="2"> Propio</option>
                    <option value="3"> Otros</option>
                    </select><div class="form-control-line"></div>
                </div>

                <div class="form-group col-md-12">
                    <label for="editTipoNegocio">Negocio que tiene:</label>
                    <input type="text" class="form-control" id="editTipoNegocio" name="editTipoNegocio" placeholder="Ingrese Negocio" required >
                </div>

                <div class="form-group col-md-12">
                    <label for="editDireccionNegocio">En donde tiene su negocio:</label>
                    <input type="text" class="form-control" id="editDireccionNegocio" name="editDireccionNegocio" placeholder="Ingrese Dirección del Negocio" required >
                </div>


                <div class="form-group col-md-8">
                    <label for="editReferenciacredito1">Referencia de credito 1:</label>
                    <input type="text" class="form-control" id="editReferenciacredito1" name="editReferenciacredito1" placeholder="Ingrese Referencia de credito" required >
                </div>

                <div class="form-group col-md-4">
                    <label for="editTelefono1">Teléfono:</label>
                    <input type="text" class="form-control" id="editTelefono1" name="editTelefono1" placeholder="Ingrese Teléfono" required >
                </div>


                <div class="form-group col-md-8">
                    <label for="editReferenciapersonal1">Referencia Personal (No familiar) 1:</label>
                    <input type="text" class="form-control" id="editReferenciapersonal1" name="editReferenciapersonal1" placeholder="Ingrese Referencia Personal" required >
                </div>

                <div class="form-group col-md-4">
                    <label for="editTelefono2">Teléfono:</label>
                    <input type="text" class="form-control" id="editTelefono2" name="editTelefono2" placeholder="Ingrese Teléfono" required >
                </div>

                <div class="form-group col-md-12">
                    <label for="editprestamosanteriores">Préstamos anteriores:</label>
                    <input type="number" class="form-control" id="editprestamosanteriores" name="editprestamosanteriores" placeholder="Ingrese cantidad préstamos anteriores" required >
                </div>

                <div class="form-group col-md-12">
                    <label for="edittipoCliente">Tipo de cliente: </label>
                    <select class="form-control select2-list" id="edittipoCliente" name="edittipoCliente" data-placeholder="seleccione el tipo de cliente" required> 
                    <option value=""> </option>
                    <option value="1"> Bueno</option>
                    <option value="2"> Regular</option>
                    <option value="3"> Malo</option>
                    </select><div class="form-control-line"></div>
                </div>

                <div class="form-group col-md-12">
                       
                    <div class="icheck-success">
                        <input type="checkbox" id="edit_multprestamos">
                        <label for="edit_multprestamos">Múltiples préstamos</label>
                        <input type="hidden" class="form-control" name="editMultiple" id="editMultiple" />
                    </div>

                </div>


                <div class="form-group col-md-12">
                       
                    <div class="icheck-danger">
                        <input type="checkbox" id="editcheckpermitirprestamos">
                        <label for="editcheckpermitirprestamos">No permitir más préstamos</label>
                        <input type="hidden" class="form-control" name="editpermitirprestamos" id="editpermitirprestamos" />
                    </div>

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
                        <button type="button" id="btnGuardarEditarCliente" class="btn bg-navy">Guardar</button>
                        <button type="button" id="btnCancelarEditarCliente" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>                  

                </div>


            </div>

        </div>
    </form>  
    </div>
</div>


<div id="divEliminarCliente" class="modal fade show" aria-modal="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar Cliente</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label class="col-form-label" for="claveAnulacionCliente"><i class="far fa-bell"></i> Contraseña de anulación </label>
                    <input type="password" class="form-control is-invalid form-control-lg" autocomplete="off" id="claveAnulacionCliente" >
                </div>


                <input type="hidden" name="ideliminarcliente" id="ideliminarcliente" class="form-control" />
                <p><h4>¿Desea eliminar el registro?</h4></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnEliminarCliente">Si estoy seguro</button>
                <button type="button" class="btn btn-default" id="btnCancelarEliminarCliente" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div id="divNuevoPago" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form id='formNuevoPago' class="form form-validate"  role="form"   method="post" >
            <input type="hidden" class="form-control" name="idprestamo" id="idprestamo" />

            <input type="hidden" class="form-control" name="tipoPlan" id="tipoPlan" />

            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                <h4 class="modal-title">Nuevo Pago</h4>
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
                        <input type="checkbox" id="btnPrimeroMorasPendientes">
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


<div id="divNuevaFoto" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: auto;">
    <div class="modal-dialog" style="max-width: 700px">
        <form id='formNuevaFoto' class="form form-validate"  role="form"   method="post" >
            <div class="modal-content  panel panel-primary">
                <div class="modal-header">
                    <h4 class="modal-title">Tomar Foto</h4>
                    <div>
                        <select name="listaDeDispositivos" id="listaDeDispositivos" style="margin-top: 7px;margin-left: 7px;"></select>
                        <p id="estado"></p>
                    </div>
                    <button type="button" id="btnCancelarNuevaFotox" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="card-body">

                    <video muted="muted" id="video"></video>
                    <canvas id="canvas" style="display: none;"></canvas>
                    
                </div>

                <div class="modal-footer">
                    <div class="response"></div>
                    <button type="button" id="boton" class="btn bg-navy">Tomar foto</button>
                    <button type="button" id="btnCancelarNuevaFoto" class="btn btn-default" data-dismiss="modal">Cancelar</button>
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

//Para tomar la foto
$.getScript("upload/script.js");

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

    //timepicker2
    $('#timepicker2').datetimepicker({
        pickTime: false, format: 'YYYY-MM-DD'
    });

    //timepicker1
    $('#timepicker1').datetimepicker({
        pickTime: false, format: 'YYYY-MM-DD'
    });
    
    function verficarPermisos () {
        $.post("funciones/ws_usuarios.php", {accion:"consultarPermisos" , idmodulo:"2"} ,function(data)
        {
            if(data.resultado){
                Acceso = data.registros[0]["acceso"];
                Crear = data.registros[0]["crear"];
                Modificar = data.registros[0]["modificar"];
                Eliminar = data.registros[0]["eliminar"];
                Consultar = data.registros[0]["consultar"];
                mostrarClientes();
            }
            else
              toastr.warning(data.mensaje,"Info");
        }, "json")
        .fail(function()
        {
            toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });
    }
    



    
    function mostrarClientes () {
      $("#tablaClientes  tbody tr").remove();
      $.post("funciones/ws_clientes.php", { accion: "mostrar" }, function(data) {
        if(data.resultado)
          {



            var btnEditar = "";
            var btnEliminar = "";
            var btnConsultar = "";

            $.each(data.registros,function(key,value) {
              var activado ="Activado";
              if (value["activado"] == 0) {
                var activado ="Desactivado";
              }

              if (Modificar == 1) {
                btnEditar = " <button class='btn btn-default bg-lightblue tooltip2' style='cursor:pointer; ' href='#' ><span class='tooltiptext'>Editar Cliente</span><i class='fa fa-edit fa-lg '></i></button>";
              };

              if (Eliminar == 1) {
                btnEliminar = " <button class='btn btn-default tooltip2' style='cursor:pointer;' href='#' ><span class='tooltiptext'>Eliminar Cliente</span> <i class='fa fa-trash fa-lg '></i></button>";
              };

              if (Consultar == 1) {
                btnConsultar = " <button class='btn btn-default bg-lightblue disabled tooltip2' style='cursor:pointer' href='#' ><span class='tooltiptext'>Registro de prestamos</span> <i class='fa fa-money-bill fa-lg '></i></button>";
              };


        

              $("<tr></tr>")
                .append( "<td>" + (key + 1) + "</td>" )
                .append( "<td>" + value["codigo"] + "</td>" )
                .append( "<td>" + value["nombre"] + "</td>" )
                .append( "<td> <div class='filtr-item' data-category='1' data-sort='white sample'><a href='"+ value["foto"] +"' data-toggle='lightbox' data-title='Imagen de perfil'><img src='"+ value["foto"] +"' class='img-circle img-size-32 mr-2' alt='white sample'/></a></div> </td>" )
                .append( "<td>" + value["dpi"] + "</td>" )
                .append( "<td>" + value["direccionvive"] + "</td>" )
                .append( "<td>" + value["telefono"] + "</td>" )
                .append( "<td>" + value["tiponegocio"] + "</td>" )
                .append( "<td>" + value["direccionnegocio"] + "</td>" )
                .append( "<td>" + value["referenciapersonal1"] + "</td>" )
               .append( $("<td></td>").append( 
                $("<div class='btn-group'></div>") 
                        
                    .append( $(btnEditar)
                        .on("click",{ idcliente:value["id"] } , EditarCliente) ) 
                    .append( $(btnEliminar)
                        .on("click",{ idcliente:value["id"] } , eliminarCliente) )  
                    .append( $(btnConsultar)
                        .on("click",{ idcliente:value["id"] , nombreCliente: value["nombre"], fotoCliente: value["foto"], dirUser:value["direccionvive"], prestamosanteriores:value["prestamosanteriores"], tipoCliente:value["tipoCliente"] } , mostrarRegistroPrestamos) ) 
                          
                    )
                  )
                .appendTo("#tablaClientes > tbody");
            });

            $("#tablaClientes a").tooltip(); 
            $("#tablaClientes").DataTable({ 

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
                                columns: [ 0, 1, 2, 3, 4, 5 , 6 , 7 , 8 , 9 ]
                            }

                        },

                        {
                               extend: 'csv', 
                          orientation: 'Portrait',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            ,exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5 , 6 , 7 , 8 , 9 ]
                            }

                        },
                       
                        {
                               extend: 'excel', 
                          orientation: 'Portrait',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            ,exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5 , 6 , 7 , 8 , 9  ]
                            }

                        },
                        
                        {
                               extend: 'pdf', 
                          orientation: 'landscape',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            ,exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5 , 6 , 7 , 8 , 9 ]
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



    function mostrarRegistroPrestamos (e) {
      e.preventDefault();

        $("#morasPagadas").html('');
        $("#morasPendientes").html('');
        $("#morasExoneradas").html('');
        $("#prestamosRealizados").html('');
        $("#idEstado").html('');
        $("#contenedorbarChart").html('');


        var txt_tipo_cliente = "";

        if (e.data.tipoCliente == 1) {
            txt_tipo_cliente = "Cliente registrado como: Bueno";
        }else if(e.data.tipoCliente == 2){
            txt_tipo_cliente = "Cliente registrado como: Regular";
        }else if(e.data.tipoCliente == 3){
            txt_tipo_cliente = "Cliente registrado como: Malo";
        }


        $("#txt_tipo_cliente").html(txt_tipo_cliente);
    
      $("#imgUser").html('<img class="img-circle elevation-2" src="'+e.data.fotoCliente+'" alt="User Avatar">');
      $("#nameUser").html(e.data.nombreCliente);
      $("#dirUser").html(e.data.dirUser);


      $("#divTablaPagosDetalle").fadeOut("fast");

      $("#divTablaDetalle").fadeIn("slow");
      
      $("#tablaRegistroPrestamos  tbody tr").remove();

      $("#divtablaRegistroPrestamos").fadeIn("fast");


      $.post("funciones/ws_prestamos.php", { accion: "mostrarRP" , idcliente : e.data.idcliente}, function(data) {
        if(data.resultado)
          { 

            
            $("#morasPagadas").html(data.morasPagadas);
            $("#morasPendientes").html(data.morasPendientes);
            $("#morasExoneradas").html(data.morasExoneradas);
            $("#prestamosRealizados").html( parseInt(data.prestamosRealizados) + parseInt(e.data.prestamosanteriores) );

            if (parseInt(data.prestamosRealizados) > 0) {          
                
                /*
                
                Sumar todos los tipos de moras y se dividen dentro del número de prestamos

                    Si es <= 0.3 entonces es bueno
                    Si es <= 2 entonces regular
                    Si es mayor a dos entonces malo

                */
                var promedioMoras = (parseInt(data.morasPagadas) + parseInt(data.morasPendientes) + parseInt(data.morasExoneradas)) / parseInt(data.prestamosRealizados);

                if (promedioMoras <= 0.3) {
                    $("#idEstado").html('<span class="badge badge-success"> <h6 style="margin: 0;">Bueno</h6> </span> <br> Posible aumento de capital');                    
                }else if(promedioMoras <= 2){
                    $("#idEstado").html('<span class="badge badge-warning"> <h6 style="margin: 0;">Regular</h6> </span> ');
                }else{
                    $("#idEstado").html('<span class="badge badge-danger"> <h6 style="margin: 0;">Malo</h6> </span> <br> Sanción con menos capital');                    
                }

            }else{
                $("#idEstado").html('<span class="badge badge-success"> <h6 style="margin: 0;">Bueno</h6> </span> ');                    
            }



            var tabla =
            "<table id='tablaRegistroPrestamos' class='table order-column hover' >"+
              "<thead>"+
                "<tr>  "+
                   "<th>No.</th>"+
                   "<th>Usuario registró</th>"+
                   "<th>Fecha entrega</th>"+
                   "<th>Estado</th>"+
                   "<th>Código</th>"+
                   "<th>Préstamo</th>"+
                   "<th>Plan</th>"+
                   "<th>Cuota</th>"+
                   "<th>Faltante</th>"+
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

                det_name_prest.push("Prést. "+(key +1)+" ["+full+"]");
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
                btnImprimir = " <button class='btn btn-default bg-lightblue disabled tooltip2' style='cursor:pointer' href='#' ><span class='tooltiptext'>Imprimir Pagos</span> <i class='fa fa-print fa-lg '></i></button>";
              };
              
             

              $("<tr></tr>")
                .append( "<td>" + (key + 1) + "</td>" )
                .append( "<td>" + value["usuarioentrego"] + "</td>" )
                .append( "<td>" + value["fechaentregado"] + "</td>" )
                .append( "<td>" + estadoPrestamo + "</td>" )
                .append( "<td>" + value["codigo"] + "</td>" )
                .append( "<td>Q." +  parseFloat(value["prestamo"]).toFixed(2) + "</td>" )
                .append( "<td>" + value["cuotas"] + "</td>" )
                .append( "<td>Q." +  parseFloat(value["resumenpagos"]).toFixed(2) + "</td>" )
                .append( "<td>Q. "+  parseFloat(value["pendiente"]).toFixed(2) +" </td>" )
               .append( $("<td></td>").append( 
                $("<div class='btn-group'></div>") 
                        
                    .append( $(btnConsultar)
                        .on("click",{ idprestamo:value["id"], valor_prestamo:value["prestamo"], tipoPlan:value["tipoPlan"] } , mostrarPagosPrestamo) )
                        
                    .append( $(btnImprimir)
                        .on("click",{ idprestamo:value["id"] } , imprimirRegistroPrestamos) )
                    )
                  )
                .appendTo("#tablaRegistroPrestamos > tbody");
            });

            $("#tablaRegistroPrestamos").DataTable({ 

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
                                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                            }

                        },

                        {
                               extend: 'csv', 
                          orientation: 'Portrait',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            ,exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                            }

                        },
                       
                        {
                               extend: 'excel', 
                          orientation: 'Portrait',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            ,exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                            }

                        },
                        
                        {
                               extend: 'pdf', 
                          orientation: 'landscape',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            ,exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                            }

                        }  ,
                        'print'
                    ],
                "sPaginationType": "full_numbers",

                
            });


            $('#tablaRegistroPrestamos').ScrollTo();            

            if (Eliminar == 0) {
                $("#divtablaRegistroPrestamos").fadeOut("fast");
            }

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

            /*setTimeout(function(){
                $("#btnMinimizar").trigger("click");
            }, 500);*/


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
    

    function mostrarPagosPrestamo (e) {
      e.preventDefault();
      var id_prestamo = e.data.idprestamo;

      $("#formNuevoPago #idprestamo").val(e.data.idprestamo);  


      $("#formNuevaMora #totalPrestamo").val(e.data.valor_prestamo);
      $("#formNuevoPago #tipoPlan").val(e.data.tipoPlan);
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
    $("#btnNuevoCliente").on("click",mostrarModalNuevoCliente);
    
    function mostrarModalNuevoCliente(e){
        e.preventDefault();
        $("#formNuevoCliente")[0].reset();
        $("#formNuevoCliente input").removeClass("dirty");
        modalActivo = 1;
        $('#formNuevoCliente #uploaded_image').attr('src', 'upload/user.png');


        $.post("funciones/ws_clientes.php", { accion:"codigosiguiente" } ,function(data) {
        if(data.resultado){

            $('#formNuevoCliente #newCodigo').val(data.codigosiguiente);

        }
        else{
            toastr.warning(data.mensaje,"Info");
        }
        }, "json")
        .fail(function() {
        toastr.error("no se pudo conectar al servidor", "Error Conexión");
        });



        $("#divNuevoCliente").modal("show", {backdrop: "static"});
    }


    /****************** GUARDAR DATOS DEL REGISTRO *******************/
    $("#btnGuardarNuevoCliente").on("click",guardarNuevoCliente);
    function guardarNuevoCliente(e){
      e.preventDefault();

      var multprestamos = $("#multprestamos").is(':checked') ? 1:0;
      $("#newMultiple").val(multprestamos);


      var valpermitirprestamos = $("#newcheckpermitirprestamos").is(':checked') ? 1:0;
      $("#newpermitirprestamos").val(valpermitirprestamos);


      if($("#formNuevoCliente").valid()) {
          //console.log($("#formNuevoCliente").serialize());
          $.post("funciones/ws_clientes.php", "accion=nuevo&ruta="+rutaActual+"&"+$("#formNuevoCliente").serialize() ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");
                $("#divNuevoCliente").modal("hide");
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
    function EditarCliente (e) {
        e.preventDefault();
        $.post("funciones/ws_clientes.php", { accion:"mostrar" , id:e.data.idcliente }, function(data) {
          if(data.resultado)
            {

              $("#formEditarCliente")[0].reset();
              $("#divEditarCliente").modal("show", {backdrop: "static"});
              $("#formEditarCliente input").addClass("dirty");
              
              modalActivo = 2;
              
              $("#formEditarCliente #idcliente").val(data.registros[0]["id"]);      
              $("#formEditarCliente #editCodigo").val(data.registros[0]["codigo"]);      
              $("#formEditarCliente #editNombre").val(data.registros[0]["nombre"]);      
              $("#formEditarCliente #editDPI").val(data.registros[0]["dpi"]);      
              $("#formEditarCliente #editTelefono").val(data.registros[0]["telefono"]);      
              $("#formEditarCliente #editDireccion").val(data.registros[0]["direccionvive"]);                 
              $('#formEditarCliente #editAlquila').val(data.registros[0]["alquila"]).trigger('change.select2');              
              $("#formEditarCliente #editDireccionNegocio").val(data.registros[0]["direccionnegocio"]);      
              $("#formEditarCliente #editTipoNegocio").val(data.registros[0]["tiponegocio"]);      
              $("#formEditarCliente #editReferenciacredito1").val(data.refC1[0]);      
              $("#formEditarCliente #editTelefono1").val(data.refC1[1]);      
              $("#formEditarCliente #editReferenciapersonal1").val(data.refP1[0]);      
              $("#formEditarCliente #editTelefono2").val(data.refP1[1]);        
              $("#formEditarCliente #editprestamosanteriores").val(data.registros[0]["prestamosanteriores"]);      
              $('#formEditarCliente #edittipoCliente').val(data.registros[0]["tipoCliente"]).trigger('change.select2');  
              

              if(data.registros[0]["ruta"] == ""){
                $('#formEditarCliente #uploaded_image_2').attr('src', 'upload/user.png');
                $("#contEliminarImagen").attr("style","display:none;");
              }else{
                $('#formEditarCliente #uploaded_image_2').attr('src', data.registros[0]["ruta"]);
                $("#contEliminarImagen").attr("style","display:block;");
              }

              if (data.registros[0]["multprestamos"] == 1) {
                $("#edit_multprestamos").prop("checked", true);
              }

              if (data.registros[0]["permitirprestamos"] == 1) {
                $("#editcheckpermitirprestamos").prop("checked", true);
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
    $("#btnGuardarEditarCliente").on("click",guardarEditarCliente);

    function guardarEditarCliente(e){
      e.preventDefault();


      var edit_multprestamos = $("#edit_multprestamos").is(':checked') ? 1:0;
      $("#editMultiple").val(edit_multprestamos);


      var valpermitirprestamos = $("#editcheckpermitirprestamos").is(':checked') ? 1:0;
      $("#editpermitirprestamos").val(valpermitirprestamos);


      if($("#formEditarCliente").valid()) {
          $.post("funciones/ws_clientes.php", "accion=editar&ruta="+rutaActual+"&"+$("#formEditarCliente").serialize() ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");;
                $("#divEditarCliente").modal("hide");
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
    function eliminarCliente (e) {
      e.preventDefault();
      $("#divEliminarCliente").modal("show", {backdrop: "static"});
      $("#ideliminarcliente").val(e.data.idcliente);
      $("#claveAnulacionCliente").val('');
    }


    $("#btnEliminarCliente").on("click",guardarEliminarCliente);
    
    function guardarEliminarCliente(e){
        e.preventDefault();

        var claveAnulacionCliente = $("#claveAnulacionCliente").val();

        if( claveAnulacionCliente.trim() == "123" || claveAnulacionCliente.trim() == "123" ){


            $.post("funciones/ws_clientes.php", { idcliente:$("#ideliminarcliente").val() , accion:"eliminar" } ,function(data) {
            if(data.resultado){
                toastr.success(data.mensaje, "Exito");
                $("#divEliminarCliente").modal("hide");
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
                            .on("click",{ id:value["id"], monto:value["mora"], full:full, cantidadn:value["cantidadn"]  } , editarMora) )                                                       
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


    $("#btnCancelarNuevoCliente").click(function(){  
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


    $("#btnCancelarNuevoClientex").click(function(){  

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
        $.post("funciones/ws_clientes.php", "accion=quitarImagen&"+$("#formEditarCliente").serialize() ,function(data) {
        if(data.resultado){

            $('#formEditarCliente #uploaded_image_2').attr('src', 'upload/user.png');
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