<?php
session_start();
require_once ("../funciones/classSQL.php");
$conexion = new conexion();
if($conexion->permisos($_SESSION['idtipousuario'],"4","acceso"))
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
                <h2 class='titulo'>Reporte de cartera</h2>
            </div>           
        </div>
    </div><!-- /.container-fluid -->
</section>


<!-- Main content -->
<section id="divTablaDetalle" class="content" style="display:none;">
    <div class="card">


    <div class="card-header row">
                

                    


         
    
                <div class="form-group col-sm-6">

                <label>Usuarios:</label>

                   

                    <?php
                        if ($_SESSION['idtipousuario'] == 4 || $_SESSION['idtipousuario'] == 5) {
                            echo "<select disabled class='form-control select2-list select2-success' data-dropdown-css-class='select2-success' name='usuarioseleccionado' id='usuarioseleccionado' data-placeholder='Seleccione Usuario' required>";
                        }else{
                            echo "<select class='form-control select2-list select2-success' data-dropdown-css-class='select2-success' name='usuarioseleccionado' id='usuarioseleccionado' data-placeholder='Seleccione Usuario' required>";
                        }

                            echo "<option value='1000' >TODOS</option>";   
                            $usuarios = $conexion->sql("SELECT id, nombre FROM usuarios WHERE idtipousuario = 4 OR idtipousuario = 5");
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


        <div class="card card-success card-outline">


            <div class="row">
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <h5 class="description-header">

                        <div class="icheck-success d-inline">
                            <input type="checkbox" id="checkTODOS" checked>
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
                                <input type="checkbox" id="checkPENDIENTES">
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
                                <input type="checkbox" id="checkDIA">
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
                                <input type="checkbox" id="checkFinalizado">
                                <label for="checkFinalizado">
                                </label>
                            </div>

                        </h5>
                        <span class="description-text">Préstamos finalizados</span>
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

            <br>

            <h3 id="h3TotalCA" >CARTERA ACTIVA</h3> 
            <h3 id="h3TotalCT" >CARTERA TOTAL</h3> 


        </div>
    <!-- /.card-body -->
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->





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
        $.post("funciones/ws_usuarios.php", {accion:"consultarPermisos" , idmodulo:"4"} ,function(data)
        {
            if(data.resultado){
                Acceso = data.registros[0]["acceso"];
                Crear = data.registros[0]["crear"];
                Modificar = data.registros[0]["modificar"];
                Eliminar = data.registros[0]["eliminar"];
                Consultar = data.registros[0]["consultar"];

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
    

    $("#checkTODOS").on("click",function(e){
        $("#checkFinalizado").prop("checked", false);

        mostrarRegistroPrestamos();
    });

    $("#checkPENDIENTES").on("click",function(e){
        $("#checkFinalizado").prop("checked", false);

        mostrarRegistroPrestamos();
    });

    $("#checkDIA").on("click",function(e){
        $("#checkFinalizado").prop("checked", false);

        mostrarRegistroPrestamos();
    });

    $("#checkFinalizado").on("click",function(e){

        $("#checkTODOS").prop("checked", false);
        $("#checkPENDIENTES").prop("checked", false);
        $("#checkDIA").prop("checked", false);

        mostrarRegistroPrestamos();
        
    });
    

    function mostrarRegistroPrestamos () {


        var checkTODOS = $("#checkTODOS").is(':checked') ? 1:0;
        var checkPENDIENTES = $("#checkPENDIENTES").is(':checked') ? 1:0;
        var checkDIA = $("#checkDIA").is(':checked') ? 1:0;
        var checkFinalizado = $("#checkFinalizado").is(':checked') ? 1:0;


        $("#contenedorbarChart").html('');



        $("#h3TotalCA").html("");
        $("#h3TotalCT").html("");


      $("#divTablaPagosDetalle").fadeOut("fast");

      $("#divTablaDetalle").fadeIn("slow");
      
      $("#tablaRegistroPrestamos  tbody tr").remove();
      $("#contenedorprogress1").html();


      $.post("funciones/ws_reportes.php", { 
        accion: "reporteCartera", 
        checkTODOS:checkTODOS,
        checkPENDIENTES:checkPENDIENTES,
        checkDIA:checkDIA,
        checkFinalizado:checkFinalizado,
        id_usuario:$("#usuarioseleccionado").val()
        }, function(data) {
        if(data.resultado)
          { 



            
            
            var totalCA = 0;
            var totalCT = 0;

           
            var tabla =
            "<table id='tablaRegistroPrestamos' class='table table-striped table-sm' >"+
              "<thead>"+
                "<tr>  "+
                   "<th>No.</th>"+                 
                   "<th>Nombre cliente</th>"+
                   "<th>Foto</th>"+
                   "<th>Estado</th>"+
                   "<th>Usuario registró</th>"+
                   "<th>Nombre cobrador</th>"+
                   "<th>Préstamo</th>"+            
                   "<th>Préstamo + interes</th>"+            
                "</tr>"+
              "</thead>"+
              "<tbody></tbody>"+
            "</table>";
            $("#divtablaRegistroPrestamos").html(tabla);
           
            var scroll_left = 0;
            var det_name_prest = Array();
            var det_morasPagadas = Array();
            var det_morasPendientes = Array();
            var det_morasExoneradas = Array();

            $.each(data.registros,function(key,value) {


                totalCA += parseFloat(value["prestamo"]);
                totalCT += parseFloat(value["prestamoMasInteres"]);


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

             

              $("<tr></tr>")
                .append( "<td>" + (key + 1) + "</td>" )               
                .append( "<td> " + value["nombreCliente"] + " </td>" )
                .append( "<td> <div class='filtr-item' data-category='1' data-sort='white sample'><a href='"+ value["foto"] +"' data-toggle='lightbox' data-title='Imagen de perfil'><img src='"+ value["foto"] +"' class='img-circle img-size-32 mr-2' alt='white sample'/></a></div> </td>" )
                .append( "<td>" + estadoPrestamo + "</td>" )
                .append( "<td>" + value["usuarioentrego"] + "</td>" )
                .append( "<td> " + value["usuariocobrador"] + " </td>" )
                .append( "<td>Q." +  parseFloat(value["prestamo"]).toFixed(2) + "</td>" )
                .append( "<td>Q." +  parseFloat(value["prestamoMasInteres"]).toFixed(2) + "</td>" )                        
                .appendTo("#tablaRegistroPrestamos > tbody");
            });

            $("#tablaRegistroPrestamos").DataTable({ 

                initComplete: function() {
                    $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
                },

            


                dom: 'Blfrtip',
                buttons: [

                        {
                               extend: 'copy', 
                          orientation: 'Portrait',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            
                        },

                        {
                               extend: 'csv', 
                          orientation: 'Portrait',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                           

                        },
                       
                        {
                               extend: 'excel', 
                          orientation: 'Portrait',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                           

                        },
                        
                        {
                               extend: 'pdf', 
                          orientation: 'landscape',// Portrait o landscape
                             pageSize: 'LEGAL', //tamaño hoja
                                title: 'Sistema de créditos: Reporte generado por sinfosistemas.com' //nombre para descargar
                            
                        }  ,
                        'print'
                    ],
                "sPaginationType": "full_numbers",
                
            });


            var resultCA = totalCA.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            var resultCT = totalCT.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

            $("#h3TotalCA").html("Cartera Activa: <b> Q. "+resultCA+"</b>");
            $("#h3TotalCT").html("Cartera Total: <b> Q. "+resultCT+"</b>");
                        


            $('#tablaRegistroPrestamos').ScrollTo();

            //-------------
            //- BAR CHART -
            //-------------

            $("#contenedorbarChart").html(' <div class="card card-success">'+
                                                '<div class="card-header bg-olive">'+
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


    $("#usuarioseleccionado").on("change",function(e){
        e.preventDefault();
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





  });
</script>