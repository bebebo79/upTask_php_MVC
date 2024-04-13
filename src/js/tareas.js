////// IIFE //////
(function() {

    //llamamos a la funcion obtener tareas
    obtenerTareas();

    // para el virtual Dom creamos esta global
    let tareas = [];
    let filtradas = [];

    //boton para crear una ventana modal para agregar tareas
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', function(){
        mostrarFormulario();
    });

    //filtros de busqueda
    const filtros = document.querySelectorAll('#filtros input[type="radio"');
        // iteramos para mandar un eventl a cada input
    filtros.forEach( radio => {
        radio.addEventListener('input', filtrarTareas)
    });


    // FUNCIONES ///
    function filtrarTareas(e){
        const filtro = e.target.value;

        if(filtro !== ""){
            filtradas = tareas.filter( tarea=> tarea.estado === filtro);
            
        }else {
           filtradas = [];
        }
        mostrarTareas();
    }   
    
    async function obtenerTareas(){
        try {
            const id= obtenerProyecto();
            const url = `/api/tareas?id=${id}`;
            //conectar con el servidor
            const respuesta = await fetch(url);
            //obtener el resultado de la consulta
            const resultado = await respuesta.json();
            // obtenemos la varible tareas
            tareas =  resultado.tareas;
            
            // llamammos a la funcion de mostrarTareas
            mostrarTareas();
            
        } catch (error) {
            console.log(error);
            
        }
    }

    


    function mostrarTareas(){
        limpiarTareas();
        totalPendientes();
        totalCompletadas();
        
        // creamos el array para mostrar todas en el caso que filtradas este []
        const arrayTareas = filtradas.length ? filtradas : tareas;
        
        // en el caso que no haya tareas para ese proyecto
        if(arrayTareas.length === 0){
            const contenedorTareas = document.querySelector('#listado-tareas');
            const textNoTareas = document.createElement('LI');
            textNoTareas.textContent = "No hay tareas";
            textNoTareas.classList.add('no-tareas');
            contenedorTareas.appendChild(textNoTareas);
            return;
        }

        const estados = {
            0 : 'Pendiente',
            1 : 'Completada'
        }
        // en el caso que si tengamos tareas asignadas a este proyecto iteramos el array de tareas
        arrayTareas.forEach(tarea => {
            //creamos una lista con un indice
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add('tarea');
            
            // nombramos cada tarea en un parrafo
            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;
            nombreTarea.ondblclick = function(){
                mostrarFormulario(true, {...tarea});
            };
            
            //construimos un div para las opciones
            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            //creamos unos botones para confirmar tarea realizada
            const btnEstadoTarea= document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function(){
                cambiarEstadoTarea({...tarea});
            }


            //creamos el boton de eliminar la tarea por su id
            const btnEliminar = document.createElement('BUTTON');
            btnEliminar.classList.add('eliminar-tarea');
            btnEliminar.dataset.tareaId = tarea.id;
            btnEliminar.textContent = "Eliminar";
            btnEliminar.ondblclick = function(){
                confirmarEliminacionTarea({...tarea});
            }

            //para llevarlo a la interfaz
            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminar);

            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            //seleccionamos el espacio en el html para todo el typscritp
            const listadoTareas = document.querySelector('#listado-tareas');
            listadoTareas.appendChild(contenedorTarea);


            
        });

        
    }
    // para deshabilitar el radio de pendientes o completas
    function totalPendientes(){
        const totalPendientes = tareas.filter( tarea => tarea.estado === "0");
        const pendientesRadio = document.querySelector('#pendientes');
        
        if(totalPendientes.length===0){
            pendientesRadio.disabled =true;
        }else {
            pendientesRadio.disabled = false;
        }
        
    }
    function totalCompletadas(){
        const totalCompletadas = tareas.filter( tarea => tarea.estado === "1");
        const completadasRadio = document.querySelector('#completadas');
        
        if(totalCompletadas.length===0){
            completadasRadio.disabled =true;
        }else {
            completadasRadio.disabled = false;
        }
        
    }

    function mostrarFormulario(editar = false, tarea = {}){
        console.log(editar);
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>${editar ? 'Editar Tarea' : 'Añadir Nueva Tarea'}</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input type="text"
                           name="tarea" 
                           placeholder="${editar ? 'Edita la tarea' : "Añadir nueva tarea al proyecto"}"
                           id="tarea"
                           value = "${editar ? tarea.nombre :"" }">
                    </input>                   
                </div>
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="${editar ? 'Guardar Cambios' : 'Añadir tarea'}"/>
                    <button type ="button" class="cerrar-modal">Cancelar</button>    
                </div>                               
            
            </form>

        
        `;
        
        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
            
        },0);

        modal.addEventListener('click', function(e){
            e.preventDefault();
            if(e.target.classList.contains('cerrar-modal')){
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();
                }, 500);         
            }
            if(e.target.classList.contains('submit-nueva-tarea')){
                const nombreTarea = document.querySelector('#tarea').value.trim();
                if(nombreTarea === ''){
                    mostrarAlerta('El nombre de la tarea es Obligatorio', 'error', document.querySelector('.formulario legend'));
                return;
                }
    
                if(editar){
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea);
                }else {
                    agregarTarea(nombreTarea);
                }    
                
            }
            
        }) 

        document.querySelector('.dashboard').appendChild(modal);
       

        
    }

    

    /// funcion para mostrar alertas en la interfaz
    function mostrarAlerta(mensaje, tipo, referencia) {
        // para no duplicar alertas
        const alertaPrevia = document.querySelector('.alerta');
        if(alertaPrevia){
            alertaPrevia.remove();
        }

        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;
        
        //colocar la alerta antes del legend cuando no hay un div
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        //eliminamos la alerta despues de 3 segundos
        setTimeout(() => {
            alerta.remove();
        }, 3000);


    }

    //conectar con el servidor para poder agregar una tarea
    async function agregarTarea(tarea){
        //CONSTRUIR LA PETICION
        const datos = new FormData();
        datos.append('nombre', tarea);
        datos.append('proyectoId', obtenerProyecto());

      
        try {
            const url = '/api/tarea';
            const respuesta = await fetch(url, {
                method:'POST',
                body : datos

            });
            const resultado = await respuesta.json();
            

            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario legend'));

            if(resultado.tipo === 'exito'){
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                    
                }, 3000);  
                
                //agregar el Objeto tarea a la global de tareas
                const tareaObj = {
                    id:String(resultado.id),
                    nombre:tarea,
                    estado: "0",
                    proyectoId: resultado.proyectoId
                }
                // para agregar el objeto al array de tareas
                tareas = [...tareas, tareaObj];

                mostrarTareas();
            }
            
        } catch (error) {
            console.log(error);
            
        }
        

    }

    function obtenerProyecto(){
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id;
    }

    function limpiarTareas(){
        const listadoTareas = document.querySelector('#listado-tareas');
        
        while(listadoTareas.firstChild){
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
    }

    function cambiarEstadoTarea(tarea){
        
        const nuevoEstado = tarea.estado === "1" ? "0" : "1";
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);
       
    }
    async function actualizarTarea(tarea){
      
        const {estado, id, nombre, proyectoId} = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        try {
            //conexion de la API
            const url  = '/api/tarea/actualizar';
            const respuesta = await fetch(url,{
                method:"POST",
                body:datos
            });
            const resultado = await respuesta.json();
            if(resultado.respuesta.tipo === 'exito'){
                Swal.fire(
                    'Actualizando',
                    resultado.respuesta.mensaje,
                    'success'
                );
                const ventanaModal = document.querySelector('.modal');
                if(ventanaModal){
                    ventanaModal.remove();
                }    
            }

            //iteramos el array de tareas en una en memoria
            tarea = tareas.map(tareaMemoria =>{
                //confirmar que la tarea es la que queremos modificar
                if(tareaMemoria.id === id){
                    tareaMemoria.estado = estado;
                    tareaMemoria.nombre  = nombre;
                }
                return tareaMemoria;
                
            });
            
            // de esta manera se muestra el cambio en la interfaz
            mostrarTareas();
            
        } catch (error) {
            console.log(error);
            
        }




    }

    function confirmarEliminacionTarea(tarea){
        Swal.fire({
            title: "¿ Eliminar tarea ?",
            showCancelButton: true,
            confirmButtonText: "SI",
            cancelButtonText: "NO"
        }).then((result) => {
           
            if (result.isConfirmed) {
                eliminarTarea(tarea);

              
            }
        });
        
    } 
    async function eliminarTarea(tarea){
        const {id, nombre, estado} = tarea;
        const datos = new FormData()
        
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());


        try {
            const url = '/api/tarea/eliminar';
            const respuesta = await fetch( url, {
                method:'POST',
                body: datos
            });
            
            const resultado = await respuesta.json();
            if(resultado.resultado){
                // 
                Swal.fire('Eliminado', resultado.mensaje,'success');

                tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id);
                mostrarTareas(tarea)
            }
            
            
        } catch (error) {
            console.log(error);
            
        }

    }

   

})();