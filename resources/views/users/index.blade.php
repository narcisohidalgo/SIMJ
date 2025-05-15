@extends('layouts.app2')

@section('content')
@if (session()->has('success'))
<div id="alerta" class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<div class="container">
    <h1>Usuarios</h1>
    <table class="table table-bordered" id="users-table">
        <thead class="encabezado-usuarios">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Administrador</th>
            @if (auth()->user()->is_admin)
                <th>Acciones</th>
            @endif
        </tr>
        </thead>
    </table>
</div>
@endsection

@push('scripts')
<script>
    const esAdmin = @json(auth()->check() && auth()->user()->is_admin);

    let table;

    $(function() {
        const columns = [
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            {
                data: 'is_admin',
                render: function(data, type, row) {
                    if (esAdmin) {
                        return `
                            <label>
                                <input type="checkbox" class="toggle-admin" data-id="${row.id}" ${data ? 'checked' : ''}>
                                <span class="admin-label">${data ? 'Sí' : 'No'}</span>
                            </label>
                        `;
                    } else {
                        return data ? 'Sí' : 'No';
                    }
                }
            }
        ];

        @if(auth()->check() && auth()->user()->is_admin)
            columns.push({
                data: 'action',
                orderable: false,
                searchable: false
            });
        @endif

        table = $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("usuarios.ajax") }}',
            columns: columns
        });
    });

    //AJAX para eliminar usuario
    $(document).on('click', '.btn-eliminar', function(e) {
    e.preventDefault();
    const userId = $(this).data('id');

    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/usuarios/${userId}`,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire('Eliminado', response.message, 'success');
                    table.ajax.reload(null, false); // Recargar la tabla sin reiniciar paginación
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'No se pudo eliminar el usuario', 'error');
                }
            });
        }
    });
});






    // AJAX para cambiar rol admin
    $(document).on('change', '.toggle-admin', function() {
        const checkbox = $(this);
        const userId = checkbox.data('id');
        const isAdmin = checkbox.is(':checked') ? 1 : 0;

        $.ajax({
            url: `/usuarios/${userId}/toggle-admin`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                is_admin: isAdmin
            },
            success: function(response) {
                Swal.fire('Éxito', response.message, 'success');
                // Actualizar solo el texto "Sí" o "No" junto al checkbox
                checkbox.closest('label').find('.admin-label').text(isAdmin ? 'Sí' : 'No');
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON.message || 'No autorizado', 'error');
                // Revertir el checkbox en caso de error
                checkbox.prop('checked', !isAdmin);
            }
        });
    });
</script>
@endpush