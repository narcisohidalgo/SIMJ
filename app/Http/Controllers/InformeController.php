<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class InformeController extends Controller
{
    public function index()
    {
        return view('informes.index', [
            'proyectos' => Proyecto::all(),
            'usuarios' => User::all(),
        ]);
    }

    public function generar(Request $request)
    {
        $request->validate([
            'proyecto_id' => 'nullable|exists:proyectos,id',
            'user_id' => 'nullable|exists:users,id',
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date|after_or_equal:fecha_desde',
        ]);

        $tareas = Tarea::query();

        if ($request->proyecto_id) {
            $tareas->where('proyecto_id', $request->proyecto_id);
        }

        if ($request->user_id) {
            $tareas->where('user_id', $request->user_id);
        }

        if ($request->fecha_desde) {
            $tareas->whereDate('inicio', '>=', $request->fecha_desde);
        }

        if ($request->fecha_hasta) {
            $tareas->whereDate('fin', '<=', $request->fecha_hasta);
        }

        $tareas = $tareas->with(['proyecto', 'user'])->get();

        $totalHoras = $tareas->reduce(function ($carry, $tarea) {
            return $carry + \Carbon\Carbon::parse($tarea->inicio)->diffInMinutes($tarea->fin);
        }, 0);

        $pdf = Pdf::loadView('informes.pdf', [
            'tareas' => $tareas,
            'totalHoras' => round($totalHoras / 60, 2)
        ]);

        return $pdf->download('informe_tareas.pdf');
    }

    public function generarInforme(Request $request)
    {
        $query = Tarea::with('usuario', 'proyecto');

        if ($request->filled('desde')) {
            $query->whereDate('inicio', '>=', $request->desde);
            $desdeFormateado = Carbon::parse($request->desde)->format('d/m/Y');
        } else {
            $desdeFormateado = '-';
        }

        if ($request->filled('hasta')) {
            $query->whereDate('fin', '<=', $request->hasta);
            $hastaFormateado = Carbon::parse($request->hasta)->format('d/m/Y');
        } else {
            $hastaFormateado = '-';
        }

        if ($request->filled('proyecto_id')) {
            $query->where('proyecto_id', $request->proyecto_id);
            $proyectoNombre = Proyecto::find($request->proyecto_id)?->nombre;
        } else {
            $proyectoNombre = null;
        }

        if ($request->filled('usuario_id')) {
            $query->where('user_id', $request->usuario_id);
            $usuarioNombre = User::find($request->usuario_id)?->name;
        } else {
            $usuarioNombre = null;
        }

        $tareas = $query->get();

        $pdf = Pdf::loadView('pdf.tareas', [
            'tareas' => $tareas,
            'desde' => $desdeFormateado,
            'hasta' => $hastaFormateado,
            'proyectoNombre' => $proyectoNombre,
            'usuarioNombre' => $usuarioNombre
        ]);

        return $pdf->stream('informe_tareas.pdf');
    }
}