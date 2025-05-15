@extends('layouts.app2')

@section('content')
<!-- Modal de creación de tarea -->
<div class="modal fade" id="tareaModal" tabindex="-1" role="dialog" aria-labelledby="tareaModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="form-tarea">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Crear Tarea</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="inicio" id="inicio">
          <div class="form-group">
            <label for="proyecto_id">Proyecto</label>
            <select name="proyecto_id" id="proyecto_id" class="form-control">
              @foreach ($proyectos as $proyecto)
                <option value="{{ $proyecto->id }}">{{ $proyecto->nombre }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" class="form-control" name="titulo" id="titulo" required>
          </div>
          <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" name="descripcion" id="descripcion"></textarea>
          </div>
          <div class="form-group">
            <label for="fin">Fecha Fin</label>
            <input type="date" class="form-control" name="fin" id="fin" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const selectedUser = document.getElementById('user-select');

    let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        editable: false,
        events: `/tareas/ajax/${selectedUser.value}`,
        dateClick: function(info) {
            document.getElementById('inicio').value = info.dateStr;
            $('#tareaModal').modal('show');
        }
    });

    calendar.render();

    selectedUser.addEventListener('change', function () {
        calendar.refetchEvents();
        calendar.setOption('events', `/tareas/ajax/${this.value}`);
    });

    // Enviar tarea vía AJAX
    document.getElementById('form-tarea').addEventListener('submit', function (e) {
        e.preventDefault();
        const data = new FormData(this);
        data.append('_token', '{{ csrf_token() }}');

        fetch('/tareas', {
            method: 'POST',
            body: data
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                $('#tareaModal').modal('hide');
                calendar.refetchEvents();
            } else {
                alert('Error al guardar la tarea');
            }
        });
    });
});
</script>
@endsection