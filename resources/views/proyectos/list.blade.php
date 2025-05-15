@foreach($proyectos as $proyecto)
    <div class="col-md-3 mb-3">
        <div class="card proyecto-draggable" 
             data-id="{{ $proyecto->id }}" 
             data-nombre="{{ $proyecto->nombre }}" 
             style="cursor: move;">
            <div class="card-body d-flex flex-column">
                {{-- Título arriba --}}
                <h5 class="card-title">{{ $proyecto->nombre }}</h5>

                {{-- Autor y fecha al fondo --}}
                <small class="text-muted mt-auto">
                    Creado por: {{ $proyecto->user->name ?? 'Desconocido' }}<br>
                    el día: {{ $proyecto->created_at->format('d/m/Y') }}
                </small>
                 @if(auth()->check() && auth()->user()->is_admin)
                    <div class="mt-2">
                        <button class="btn btn-sm btn-warning btn-editar" data-id="{{ $proyecto->id }}">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button class="btn btn-sm btn-danger btn-eliminar" data-id="{{ $proyecto->id }}">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endforeach