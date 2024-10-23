function bloquearPantalla() { 
  $.blockUI({ 
    message: $('#mensaje'),
    css: { 
      border: 'none', 
      padding: '15px', 
      
      backgroundColor:'transparent', 
      '-webkit-border-radius': '10px', 
      '-moz-border-radius': '10px', 
       color: '#fff' 
      } 
  });                             
}

function desbloquearPantalla()  {
    setTimeout($.unblockUI, 500); 
}


    var id_empleado;
    var nombre_empleado;

    var id_compras;
    var doc_vale;
    var doc_factura;
    var tipo_compra;
    var time;

    var id_categoria;
    var descripcion_categoria;

    var id_producto;
    var descripcion_producto;

    var id_opcion;
    var descripcion_opcion;




