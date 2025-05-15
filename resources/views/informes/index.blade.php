@extends('layouts.app2')

@section('content')
<div class="container">
    <h3>Generar informe de tareas</h3>
    <form method="POST" action="{{ route('informes.generar') }}">
        @csrf
        <div class="form-group">
            <label>Proyecto</label>
            <select name="proyecto_id" class="form-control">
                <option value="">Todos</option>
                @foreach($proyectos as $p)
                    <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Usuario</label>
            <select name="user_id" class="form-control">
                <option value="">Todos</option>
                @foreach($usuarios as $u)
                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Desde</label>
            <input type="date" name="fecha_desde" class="form-control">
        </div>
        <div class="form-group">
            <label>Hasta</label>
            <input type="date" name="fecha_hasta" class="form-control">
        </div>
        <button class="btn btn-primary mt-2" type="submit">Generar PDF</button>
    </form>
</div>
@endsection