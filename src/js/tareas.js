(function() {

    obtenerTareas();
    let tareas = [];
    let filtradas = []
    //Boton para mostrar el modal de Agregar Tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', function() {
        mostrarFormulario(false);
    });

    function mostrarFormulario(editar = false, tarea = {}) {
        
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>${editar ? 'Editar Tarea' : 'Añade una nueva tarea'}</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input
                        type="text"
                        name="tarea"
                        placeholder="${tarea.nombre ? 'Edita la Tarea': 'Añadir Tarea al Proyecto Actual'}"
                        id="tarea"
                        value="${tarea.nombre ? tarea.nombre: ''}"
                    />
                </div>
                <div class="opciones">
                    <input
                        type="submit"
                        class="submit-nueva-tarea"
                        value="${tarea.nombre ? 'Guardar cambios': 'Añadir Tarea'}"
                        
                        />
                    <button type="button" class="cerrar-modal" >Cerrar</button>
                </div>
            </form>
            `;

            setTimeout(() => {
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('animar');
            }, 0);

            modal.addEventListener('click', function(e) {
                e.preventDefault();

                if(e.target.classList.contains('cerrar-modal') || e.target.classList.contains('modal')) {
                    const formulario = document.querySelector('.formulario');
                    formulario.classList.add('cerrar');
                    setTimeout(() => {
                        modal.remove();
                    }, 500);
                }

                if(e.target.classList.contains('submit-nueva-tarea')){
                    const nombretarea = document.querySelector('#tarea').value.trim();

                    if(nombretarea === '') {
                        //Mostrar una alerta de error
                        mostrarAlerta('El nombre de la tarea es obligatorio', 'error', document.querySelector('.formulario legend'));
                        return;
                    }

                    if(editar) {
                        tarea.nombre = nombretarea;
                        actualizarTarea(tarea);
                    } else {
                        agregarTarea(nombretarea);
                    }
                }
                
            });
        document.querySelector('.dashboard').appendChild(modal);
    
    }
    //Muestra un mensaje en la interfaz
    function mostrarAlerta(mensaje, tipo, referencia) {
        //Previene la creacion de multiples alertas
        const alertaPrevia = document.querySelector('.alerta');
        if(alertaPrevia){
            alertaPrevia.remove();
        }


        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;

        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        //Eliminar la alerta despues de 5s
        setTimeout(() => {
            alerta.remove();
        },5000);
    }

    async function agregarTarea(tarea) {
        //Contruir la peticion
        const datos = new FormData();
        datos.append('nombre', tarea);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = '/api/tarea';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            }); 
            const resultado = await respuesta.json();
            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario legend'));

            if(resultado.tipo === 'exito') {
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 3000);
                //Agregar el objeto de tareas al global de tareas
                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoId: resultado.proyectoId
                }
                tareas = [...tareas, tareaObj];
                mostrarTareas();
            }

        } catch (error) {
            console.log(error);
        }
    }
    function cambiarEstadoTarea(tarea) {
        const nuevoEstado = tarea.estado === "1" ? "0" : "1";
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);
    }

    async function actualizarTarea(tarea) {
        const {estado, id, nombre, proyectoId} = tarea;
        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = '/api/tarea/actualizar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
            if(resultado.respuesta.tipo === 'exito') {
                Swal.fire(
                    resultado.respuesta.mensaje,
                    resultado.respuesta.mensaje,
                    'success'
                );

                const modal = document.querySelector('.modal');
                if(modal){
                    modal.remove();
                }
                
            }
            tareas = tareas.map(tareaMemoria => {
                if(tareaMemoria.id === id) {
                    tareaMemoria.estado = estado;
                    tareaMemoria.nombre = nombre;
                }
                return tareaMemoria;
            });
            mostrarTareas();
        } catch (error) {
            console.log(error)
        }

    }
    function confirmarEliminarTarea(tarea){
        Swal.fire({
            title: "¿Estas seguro?",
            text: "¡No podrás revertir esto!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, ¡eliminalo!"
            }).then((result) => {
            if (result.isConfirmed) {
                console.log('eliminando');
                eliminarTarea(tarea);
            }
            });
    }
    async function eliminarTarea(tarea) {
        const {estado, id, nombre} = tarea;
        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = '/api/tarea/eliminar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
            if(resultado.resultado) {
                Swal.fire({
                    title: "Eliminado!",
                    text: resultado.mensaje,
                    icon: "success"
                });

                tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id);
                mostrarTareas();
            }
        } catch (error) {
            console.log(error);
        }
    }
    function obtenerProyecto() {
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id;
    }

    //Filtros de busqueda
    const filtros = document.querySelectorAll('.filtros input[type="radio"]');
    filtros.forEach(radio => {
        radio.addEventListener('input', filtrarTareas);
    })

    function filtrarTareas(e) {
        const filtro = e.target.value;

        if(filtro !== ''){
            filtradas = tareas.filter(tarea=> tarea.estado === filtro);
        } else {
            filtradas = [];
        }
        
        mostrarTareas();
        
    }

    async function obtenerTareas() {
        try {
            const id = obtenerProyecto();
            const url = `/api/tareas?id=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            tareas = resultado.tareas;
            mostrarTareas();
        } catch (error) {
            
        }
    }

    function mostrarTareas(){
        limpiarTareas();
        totalPendientes();
        totalCompletas();
        const arrayTareas = filtradas.length ? filtradas: tareas;
        if(arrayTareas.lenght === 0) {
            const contenedorTareas = document.querySelector('#listado-tareas');
            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No hay Tareas';
            textoNoTareas.classList.add('no-tareas');
            contenedorTareas.appendChild(textoNoTareas);
            return;
        }

        const estados = {
            0: 'Pendiente',
            1: 'Completa'
        }

        arrayTareas.forEach(tarea => {
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add('tarea');
            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;
            nombreTarea.ondblclick = function() {
                mostrarFormulario(true, {...tarea} );
            }
            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            //Botones
            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function() {
                cambiarEstadoTarea( {...tarea} );
            }
            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.ondblclick = function() {
                confirmarEliminarTarea( {...tarea} );
            }

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            const listadoTareas = document.querySelector('#listado-tareas');
            listadoTareas.appendChild(contenedorTarea);

        });

    }
    function totalPendientes() {
        const totalPendientes = tareas.filter(tarea => tarea.estado === "0");
        const pendiestesRadio = document.querySelector('#pendientes');

        if(totalPendientes.length === 0 ){
            pendiestesRadio.disabled = true;
        } else {
            pendiestesRadio.disabled = false;
        }
    }
    function totalCompletas() {
        const totalCompletas = tareas.filter(tarea => tarea.estado === "1");
        const completasRadio = document.querySelector('#completadas');

        if(totalCompletas.length === 0 ){
            completasRadio.disabled = true;
        } else {
            completasRadio.disabled = false;
        }
    }

    function limpiarTareas() {
        const listadoTareas = document.querySelector('#listado-tareas');
        
        while(listadoTareas.firstChild) {
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
    }

})();