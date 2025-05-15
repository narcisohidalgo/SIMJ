@extends('layouts.app2')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-4">
            <!-- Columna izquierda: Proyectos -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="mb-0">Control de proyectos</h4>
                <div>
                    <!-- Botón nuevo proyecto -->
                    <button class="btn btn-sm btn-custom-blue mr-2 px-4" data-toggle="modal" data-target="#modalProyecto">
                        <i class="fas fa-plus"></i>
                    </button>

                    <!-- Botón PDF -->
                    <button class="btn btn-sm btn-custom-blue mr-2 px-4" data-toggle="modal" data-target="#modalFiltroPDF">
                        <i class="fas fa-file-pdf"></i>
                    </button>
                </div>
            </div>
            <hr>

            <!-- Listado de proyectos -->
            <div id="listaProyectos" class="overflow-auto pr-2" style="max-height: 600px;">
                <!-- Cargado por AJAX -->
            </div>
        </div>

        <!-- Columna derecha: Calendario -->
        <div class="col-md-8">
            <select id="user_select" class="form-control mb-3 w-50">
                @foreach ($usuarios as $usuario)
                <option value="{{ $usuario->id }}" {{ auth()->id() == $usuario->id ? 'selected' : '' }}>
                    {{ auth()->id() == $usuario->id ? 'Mi calendario (' . $usuario->name . ')' : $usuario->name }}
                </option>
                @endforeach
            </select>
            <div class="card">
                <div class="card-body p-2">

                    <div id="calendar" style="min-height: 600px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para nuevo proyecto -->
<div class="modal fade" id="modalProyecto" tabindex="-1" role="dialog" aria-labelledby="modalProyectoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formProyecto">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Proyecto</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre del Proyecto</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Proyecto -->
<div class="modal fade" id="modalEditarProyecto" tabindex="-1" role="dialog" aria-labelledby="modalEditarProyectoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formEditarProyecto">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Proyecto</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_nombre">Nombre del Proyecto</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>






<!-- Modal Nueva Tarea -->
<div class="modal fade" id="modalNuevaTarea" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="formNuevaTarea">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nueva Tarea</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <input type="hidden" name="proyecto_id" id="proyecto_id">
                    <div class="form-group">
                        <label>Título</label>
                        <input type="text" name="titulo" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Inicio</label>
                        <input type="datetime-local" name="inicio" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Texto informativo</label>
                        <textarea name="descripcion" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Fin</label>
                        <input type="datetime-local" name="fin" class="form-control">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Filtros PDF -->
<div class="modal fade" id="modalFiltroPDF" tabindex="-1" role="dialog" aria-labelledby="modalFiltroPDFLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('informes.pdf') }}" method="GET" target="_blank" class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="modalFiltroPDFLabel">Opciones del informe</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Proyecto</label>
                    <select name="proyecto_id" class="form-control">
                        <option value="">Todos los proyectos</option>
                        @foreach ($proyectos as $proyecto)
                        <option value="{{ $proyecto->id }}">{{ $proyecto->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Usuario</label>
                    <select name="usuario_id" class="form-control">
                        <option value="">Todos los usuarios</option>
                        @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Desde</label>
                    <input type="date" name="desde" class="form-control">
                </div>
                <div class="form-group">
                    <label>Hasta</label>
                    <input type="date" name="hasta" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-file-pdf"></i> Generar PDF
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
        </form>
    </div>
</div>





@endsection

@push('scripts')
<script>
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: @json(session('success'))
    });
    @endif
    $(document).ready(function() {
        cargarProyectos();

        // Click en botón "Editar"
        $(document).on('click', '.btn-editar', function() {
            const id = $(this).data('id');

            // Obtener datos del proyecto
            $.get(`/proyectos/${id}`, function(proyecto) {
                $('#edit_id').val(proyecto.id);
                $('#edit_nombre').val(proyecto.nombre);
                $('#modalEditarProyecto').modal('show');
            });
        });

        // Enviar formulario de edición
        $('#formEditarProyecto').submit(function(e) {
            e.preventDefault();

            const id = $('#edit_id').val();
            const data = {
                _token: $('input[name=_token]').val(),
                _method: 'PUT',
                nombre: $('#edit_nombre').val()
            };

            $.ajax({
                url: `/proyectos/${id}`,
                method: 'POST',
                data: data,
                success: function(response) {
                    $('#modalEditarProyecto').modal('hide');
                    cargarProyectos();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message
                    });
                },
                error: function(xhr) {
                    alert('Error al actualizar: ' + xhr.responseText);
                }
            });
        });

        // Click en botón Eliminar 
        $(document).on('click', '.btn-eliminar', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: '¿Eliminar proyecto?',
                text: 'Esta acción no se puede deshacer. ¿Estás seguro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/proyectos/${id}`,
                        method: 'POST',
                        data: {
                            _token: $('input[name=_token]').val(),
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            cargarProyectos();
                            Swal.fire({
                                icon: 'success',
                                title: '¡Proyecto eliminado!',
                                text: response.message
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo eliminar el proyecto.'
                            });
                        }
                    });
                }
            });
        });


        // Cargar proyectos por AJAX
        function cargarProyectos() {
            $.get("{{ route('proyectos.list') }}", function(data) {
                $('#listaProyectos').html(data);

                // Hacer los proyectos arrastrables
                new FullCalendar.Draggable(document.getElementById('listaProyectos'), {
                    itemSelector: '.proyecto-draggable',
                    eventData: function(el) {
                        return {
                            title: el.getAttribute('data-nombre'),
                            extendedProps: {
                                proyecto_id: el.getAttribute('data-id'),
                                descripcion: el.querySelector('.card-text')?.textContent || ''
                            }
                        };
                    }
                });
            });
        }

        // Envío del formulario de nuevo proyecto
        $('#formProyecto').submit(function(e) {
            e.preventDefault();
            $.post("{{ route('proyectos.store') }}", $(this).serialize(), function(response) {
                $('#modalProyecto').modal('hide');
                $('#formProyecto')[0].reset();
                cargarProyectos();
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.message
                });
            }).fail(function(xhr) {
                alert('Error al guardar: ' + xhr.responseJSON.message);
            });
        });

        // Guardar nueva tarea
        $('#formNuevaTarea').submit(function(e) {
            e.preventDefault();

            const formData = $(this).serialize();

            $.post("{{ route('tareas.store') }}", formData, function(tarea) {
                $('#modalNuevaTarea').modal('hide');
                $('#formNuevaTarea')[0].reset();

                // Añadir el nuevo evento al calendario directamente
                calendar.addEvent({
                    id: tarea.id,
                    title: tarea.titulo,
                    start: tarea.inicio,
                    end: tarea.fin,
                    extendedProps: {
                        descripcion: tarea.descripcion,
                        proyecto_id: tarea.proyecto_id
                    }
                });
            }).fail(function(xhr) {
                alert('Error al guardar tarea: ' + xhr.responseText);
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const userSelect = document.getElementById('user_select');

        window.calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridDay',
            locale: 'es',
            themeSystem: 'bootstrap',
            height: 600,
            allDaySlot: false,
            slotMinTime: "08:00:00",
            slotMaxTime: "20:00:00",
            slotDuration: '00:30:00',
            slotLabelInterval: '00:30:00',
            slotLabelFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: false
            },
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
            },
            eventContent: function(arg) {
                const container = document.createElement('div');
                container.classList.add('fc-event-custom');

                const title = document.createElement('div');
                title.classList.add('fc-event-title-custom');
                title.innerHTML = `<strong>${arg.event.title}</strong>`;

                const desc = document.createElement('div');
                desc.classList.add('fc-event-desc-custom');
                desc.textContent = arg.event.extendedProps.descripcion || '';

                container.appendChild(title);
                container.appendChild(desc);

                return {
                    domNodes: [container]
                };
            },
            eventDidMount: function(info) {
                let titulo = `<strong>${info.event.title}</strong>`;
                let descripcion = info.event.extendedProps.descripcion ?
                    `<br>${info.event.extendedProps.descripcion.replace(/\n/g, '<br>')}` :
                    '';

                // Atributo title aún es necesario para accesibilidad
                info.el.setAttribute('title', info.event.title);

                // Tooltip Bootstrap con HTML
                $(info.el).tooltip({
                    title: titulo + descripcion,
                    container: 'body',
                    placement: 'top',
                    trigger: 'hover',
                    html: true
                });
            },

            events: function(fetchInfo, successCallback, failureCallback) {
                fetch(`/tareas/calendario?user_id=${userSelect.value}`)
                    //fetch(`/tareas/calendario?user_id={{ auth()->id() }}`)
                    .then(response => response.json())
                    .then(data => successCallback(data))
                    .catch(error => failureCallback(error));
            },
            droppable: true,
            drop: function(info) {
                const proyectoNombre = info.draggedEl.getAttribute('data-nombre');
                const proyectoId = info.draggedEl.getAttribute('data-id') || info.event.extendedProps.proyecto_id;

                // Fecha UTC → local
                const fechaUTC = new Date(info.dateStr);
                const offset = fechaUTC.getTimezoneOffset();
                const fechaLocalInicio = new Date(fechaUTC.getTime() - offset * 60000);

                // Fin = inicio + 30 min
                const fechaLocalFin = new Date(fechaLocalInicio.getTime() + 30 * 60000);

                // Formatear fechas para datetime-local
                const formato = (fecha) => fecha.toISOString().slice(0, 16);

                // Rellenar campos del formulario
                $('#modalNuevaTarea input[name="titulo"]').val(proyectoNombre);
                $('#modalNuevaTarea input[name="inicio"]').val(formato(fechaLocalInicio));
                $('#modalNuevaTarea input[name="fin"]').val(formato(fechaLocalFin));
                $('#proyecto_id').val(proyectoId);

                $('#modalNuevaTarea').modal('show');
            }
        });


        calendar.render();

        userSelect.addEventListener('change', function() {
            calendar.refetchEvents();
        });
    });
</script>
@endpush