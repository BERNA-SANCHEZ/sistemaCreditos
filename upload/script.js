//Aquí guardaremos el stream globalmente
var stream;

$("#btnFoto").on("click",function(e){
    $("#divNuevaFoto").modal("show", {backdrop: "static"});    
        
    // Comenzamos viendo si tiene soporte, si no, nos detenemos
    if (!tieneSoporteUserMedia()) {        
        toastr.warning("Parece que tu navegador no soporta esta característica. Intenta actualizarlo.","Info");
        return;
    }
    
    // Comenzamos pidiendo los dispositivos
    obtenerDispositivos()
        .then(dispositivos => {
            // Vamos a filtrarlos y guardar aquí los de vídeo
            const dispositivosDeVideo = [];

            // Recorrer y filtrar
            dispositivos.forEach(function(dispositivo) {
                const tipo = dispositivo.kind;
                if (tipo === "videoinput") {
                    dispositivosDeVideo.push(dispositivo);
                }
            });

            // Vemos si encontramos algún dispositivo, y en caso de que si, entonces llamamos a la función
            // y le pasamos el id de dispositivo
            if (dispositivosDeVideo.length > 0) {
                // Mostrar stream con el ID del primer dispositivo, luego el usuario puede cambiar
                mostrarStream(dispositivosDeVideo[0].deviceId);
            }
        });

    mostrarStream = idDeDispositivo => {
        _getUserMedia({
                video: {
                    // Justo aquí indicamos cuál dispositivo usar
                    deviceId: idDeDispositivo,
                }
            },
            (streamObtenido) => {
                // Aquí ya tenemos permisos, ahora sí llenamos el select,
                // pues si no, no nos daría el nombre de los dispositivos
                llenarSelectConDispositivosDisponibles();

                // Escuchar cuando seleccionen otra opción y entonces llamar a esta función
                $listaDeDispositivos.onchange = () => {
                    // Detener el stream
                    if (stream) {
                        stream.getTracks().forEach(function(track) {
                            track.stop();
                        });
                    }
                    // Mostrar el nuevo stream con el dispositivo seleccionado
                    mostrarStream($listaDeDispositivos.value);
                }

                // Simple asignación
                stream = streamObtenido;

                // Mandamos el stream de la cámara al elemento de vídeo
                $video.srcObject = stream;
                $video.play();

                //Escuchar el click del botón para tomar la foto
                $boton.addEventListener("click", function() {

                    //Pausar reproducción
                    $video.pause();

                    //Obtener contexto del canvas y dibujar sobre él
                    let contexto = $canvas.getContext("2d");
                    $canvas.width = $video.videoWidth;
                    $canvas.height = $video.videoHeight;
                    contexto.drawImage($video, 0, 0, $canvas.width, $canvas.height);

                    let foto = $canvas.toDataURL(); //Esta es la foto, en base 64

                    var $modal = $('#modal');
                    var image = document.getElementById('sample_image');                   
                    image.src = foto;
                    $modal.modal('show');
                    $("#divNuevaFoto").modal("hide");

                    if (stream) {
                        stream.getTracks().forEach(function(track) {
                            track.stop();
                        });
                    }
                    
                });
                
            }, (error) => {
                console.log("Permiso denegado o error: ", error);
                toastr.warning("No se puede acceder a la cámara, o no diste permiso.","Info");
            });
    }
});

$("#btnEditFoto").on("click",function(e){
    $("#divNuevaFoto").modal("show", {backdrop: "static"});    
    
    // Comenzamos viendo si tiene soporte, si no, nos detenemos
    if (!tieneSoporteUserMedia()) {        
        toastr.warning("Parece que tu navegador no soporta esta característica. Intenta actualizarlo.","Info");
        return;
    }    

    // Comenzamos pidiendo los dispositivos
    obtenerDispositivos()
        .then(dispositivos => {
            // Vamos a filtrarlos y guardar aquí los de vídeo
            const dispositivosDeVideo = [];

            // Recorrer y filtrar
            dispositivos.forEach(function(dispositivo) {
                const tipo = dispositivo.kind;
                if (tipo === "videoinput") {
                    dispositivosDeVideo.push(dispositivo);
                }
            });

            // Vemos si encontramos algún dispositivo, y en caso de que si, entonces llamamos a la función
            // y le pasamos el id de dispositivo
            if (dispositivosDeVideo.length > 0) {
                // Mostrar stream con el ID del primer dispositivo, luego el usuario puede cambiar
                mostrarStream(dispositivosDeVideo[0].deviceId);
            }
        });

    mostrarStream = idDeDispositivo => {
        _getUserMedia({
                video: {
                    // Justo aquí indicamos cuál dispositivo usar
                    deviceId: idDeDispositivo,
                }
            },
            (streamObtenido) => {
                // Aquí ya tenemos permisos, ahora sí llenamos el select,
                // pues si no, no nos daría el nombre de los dispositivos
                llenarSelectConDispositivosDisponibles();

                // Escuchar cuando seleccionen otra opción y entonces llamar a esta función
                $listaDeDispositivos.onchange = () => {
                    // Detener el stream
                    if (stream) {
                        stream.getTracks().forEach(function(track) {
                            track.stop();
                        });
                    }
                    // Mostrar el nuevo stream con el dispositivo seleccionado
                    mostrarStream($listaDeDispositivos.value);
                }

                // Simple asignación
                stream = streamObtenido;

                // Mandamos el stream de la cámara al elemento de vídeo
                $video.srcObject = stream;
                $video.play();

                //Escuchar el click del botón para tomar la foto
                $boton.addEventListener("click", function() {

                    //Pausar reproducción
                    $video.pause();

                    //Obtener contexto del canvas y dibujar sobre él
                    let contexto = $canvas.getContext("2d");
                    $canvas.width = $video.videoWidth;
                    $canvas.height = $video.videoHeight;
                    contexto.drawImage($video, 0, 0, $canvas.width, $canvas.height);

                    let foto = $canvas.toDataURL(); //Esta es la foto, en base 64

                    var $modal = $('#modal');
                    var image = document.getElementById('sample_image');                   
                    image.src = foto;
                    $modal.modal('show');
                    $("#divNuevaFoto").modal("hide");

                    if (stream) {
                        stream.getTracks().forEach(function(track) {
                            track.stop();
                        });
                    }
                   
                });
                
            }, (error) => {
                console.log("Permiso denegado o error: ", error);
                toastr.warning("No se puede acceder a la cámara, o no diste permiso.","Info");
            });
    }
});

$("#btnCancelarNuevaFotox").click(function(){  
    if (stream) {
        stream.getTracks().forEach(function(track) {
            track.stop();
        });
    }
});

$("#btnCancelarNuevaFoto").click(function(){  
    if (stream) {
        stream.getTracks().forEach(function(track) {
            track.stop();
        });
    }
});


tieneSoporteUserMedia = () =>
!!(navigator.getUserMedia || (navigator.mozGetUserMedia || navigator.mediaDevices.getUserMedia) || navigator.webkitGetUserMedia || navigator.msGetUserMedia)
_getUserMedia = (...arguments) =>
(navigator.getUserMedia || (navigator.mozGetUserMedia || navigator.mediaDevices.getUserMedia) || navigator.webkitGetUserMedia || navigator.msGetUserMedia).apply(navigator, arguments);

// Declaramos elementos del DOM
$video = document.querySelector("#video"),
$canvas = document.querySelector("#canvas"),
$boton = document.querySelector("#boton"),
$listaDeDispositivos = document.querySelector("#listaDeDispositivos");

limpiarSelect = () => {
for (let x = $listaDeDispositivos.options.length - 1; x >= 0; x--)
    $listaDeDispositivos.remove(x);
};
obtenerDispositivos = () => navigator
.mediaDevices
.enumerateDevices();

// La función que es llamada después de que ya se dieron los permisos
// Lo que hace es llenar el select con los dispositivos obtenidos
llenarSelectConDispositivosDisponibles = () => {

limpiarSelect();
obtenerDispositivos()
    .then(dispositivos => {
        const dispositivosDeVideo = [];
        dispositivos.forEach(dispositivo => {
            const tipo = dispositivo.kind;
            if (tipo === "videoinput") {
                dispositivosDeVideo.push(dispositivo);
            }
        });

        // Vemos si encontramos algún dispositivo, y en caso de que si, entonces llamamos a la función
        if (dispositivosDeVideo.length > 0) {
            // Llenar el select
            dispositivosDeVideo.forEach(dispositivo => {
                const option = document.createElement('option');
                option.value = dispositivo.deviceId;
                option.text = dispositivo.label;
                $listaDeDispositivos.appendChild(option);
            });
        }
    });
}
