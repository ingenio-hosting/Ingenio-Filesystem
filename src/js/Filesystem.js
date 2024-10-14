function SeleccionarRuta(ruta){
    //alert(ruta);
    document.getElementById('rutah').innerHTML = '<input type="hidden" name="ruta" value="'+ruta+'" />';
}
function MostraTexto(valor){
   
    if(valor==1){
        document.getElementById('contenido').style.display = "none";
        document.getElementById('btn-files').value = "ABRIR FICHERO";
    }

    if(valor==2){
        document.getElementById('contenido').style.display = "block";
        document.getElementById('btn-files').value = "GRABAR FICHERO";
    }
}
function MostrasRem(){   
    $('.btn-fichero').toggle();
    if($('#form-direc').css('display') == 'none'){
        $('#form-direc').css({'display':'inline-block'})
        $('#alert-ruta').css({'display':'none'}); 
    }
    else
    {
        $('#form-direc').css({'display':'none'})
        $('#alert-ruta').css({'display':'inline-block'}); 
    }
    
    
}
function mostrarInput(ruta,fichero){
    var valor = $('#btn-rename-'+fichero).css('display');
 
    if(valor == 'none'){
        $('#span-'+fichero).css({'display':'none'}) 
        $('#btn-rename-'+fichero).css({'display':'inline-block'})
    }
    else
    {        
        $('#span-'+fichero).css({'display':'inline-block'}) 
        $('#btn-rename-'+fichero).css({'display':'none'})
    }
   
}
function ocultarInput(fichero){
  
    $('#span-'+fichero).css({'display':'inline-block'}) 
    $('#btn-rename-'+fichero).css({'display':'none'})
}
function Rename(nuevo,ruta_completa,ruta){
    Datos = {
        'nuevo':nuevo,
        'ruta_completa':ruta_completa,
        'ruta':ruta,
        'action':'RENOMBRAR_FICHERO'
    }
    $.post('controller/controller.php',Datos,function(res){
        resp = JSON.parse(res);
        if(resp.valor == true){
            $('#res').html('<div class="alert alert-success">'+resp.msn+'</div>')
        }
        else
        {
            $('#res').html('<div class="alert alert-success">'+resp.msn+'</div>')
        }
        startCounter()
    })
}
function RemoveDir(ruta_completa){
    Datos = {
        'ruta_completa':ruta_completa,
        'action':'BORRAR_DIR'
    }
    $.post('controller/controller.php',Datos,function(res){
        resp = JSON.parse(res);
        if(resp.valor == true){
            $('#res').html('<div class="alert alert-success">'+resp.msn+'</div>')
        }
        else
        {
            $('#res').html('<div class="alert alert-success">'+resp.msn+'</div>')
        }
        startCounter()
    })

}
function RemoveFile(ruta_completa){
    alert("hla");
    Datos = {
        'ruta_completa':ruta_completa,
        'action':'BORRAR_FILE'
    }
    $.post('controller/controller.php', Datos, function(res){
        resp = JSON.parse(res);
         
        if(resp.valor == true){
            $('#res').html('<div class="alert alert-success">'+resp.msn+'</div>')
        }
        else
        {
            $('#res').html('<div class="alert alert-success">'+resp.msn+'</div>')
        }
        startCounter(); 
    })

}
function startCounter() {
   
        setTimeout(() => {
            location.reload();  
        }, 4000);
   
}

function SeleccionarFichero(rutaw,ruta, fichero){    
    $('#nomfile').val(fichero)
    $('#rutafile').val(ruta)
    //alert(rutaw+fichero);
    //alert(ruta+fichero);
    if(esFoto(fichero)){
        $('#visor-foto').attr('src',rutaw+fichero)
        $('#visor_datos').html("")
    }
}
function esFoto(nombreArchivo) {
    // Expresión regular que comprueba las extensiones de imagen comunes
    const extensionesPermitidas = /\.(jpg|jpeg|png|gif|bmp|webp|tiff|raw)$/i;
    
    // Retorna true si el nombre del archivo coincide con las extensiones de imagen
    return extensionesPermitidas.test(nombreArchivo);
}
let draggedElement = null; // Variable para guardar el elemento arrastrado
// Evento que se dispara cuando comienza el arrastre
function dragStartHandler(event) {
    draggedElement = event.target; // Guardamos el elemento que está siendo arrastrado
    event.dataTransfer.effectAllowed = 'move'; // Permitimos el movimiento
    event.target.style.opacity = 0.5; // Cambiamos la opacidad del elemento arrastrado
}
// Evento que se dispara cuando el elemento está sobre una zona donde puede soltarse
function dragOverHandler(event) {
    event.preventDefault(); // Necesario para permitir el drop
    event.dataTransfer.dropEffect = 'move'; // Definimos el tipo de acción (mover)
}
// Evento que se dispara cuando el elemento es soltado
function dropHandler(event) {
    event.preventDefault();

    // Si el elemento no es el mismo que estamos arrastrando, procedemos a moverlo
    if (draggedElement !== event.target) {
        // Insertar el elemento arrastrado antes del que fue soltado
        if (event.target.tagName === 'LI') {
            event.target.parentNode.insertBefore(draggedElement, event.target.nextSibling);
        }
    }
    
    // Restaurar el estilo del elemento arrastrado
    draggedElement.style.opacity = 1;
    draggedElement = null; // Limpiar la referencia al elemento arrastrado
}