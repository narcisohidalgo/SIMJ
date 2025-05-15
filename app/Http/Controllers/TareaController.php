<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tarea;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\Proyecto;
use Carbon\Carbon;

class TareaController extends Controller
{

    public function index()
    { {
            $usuarios = User::all();
            $proyectos = Proyecto::orderBy('updated_at', 'desc')->get();
            return view('tareas.index', compact('usuarios', 'proyectos'));
        }
    }

    public function ajax(Request $request)
    {
        $userId = $request->usuario_id ?? Auth::id();

        return Tarea::where('user_id', $userId)->with('proyecto')->get()->map(function ($tarea) {
            return [
                'title' => $tarea->titulo,
                'start' => $tarea->inicio,
                'end' => $tarea->fin,
                'project' => $tarea->proyecto->nombre,
            ];
        });
    }

    public function store(Request $request)
    {

        $request->validate([
            'proyecto_id' => 'required|exists:proyectos,id',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'inicio' => 'required|date',
            'fin' => 'required|date|after_or_equal:inicio',
        ]);

        $tarea = Tarea::create([
            'proyecto_id' => $request->proyecto_id,
            'user_id' => Auth::id(),
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'inicio' => $request->inicio,
            'fin' => $request->fin,
        ]);


        return response()->json(['success' => true, 'tarea' => $tarea]);
    }


    public function getByUser(Request $request)
    {
        $userId = $request->user_id ?? Auth::id();
        $tareas = Tarea::with('proyecto')
            ->where('user_id', $userId)
            ->get();

        return response()->json($tareas);
    }

    public function pdf(Request $request)
    {
        $query = Tarea::query()->with('proyecto', 'user');

        if ($request->proyecto_id) {
            $query->where('proyecto_id', $request->proyecto_id);
        }

        if ($request->usuario_id) {
            $query->where('user_id', $request->usuario_id);
        }

        if ($request->desde) {
            $query->where('inicio', '>=', $request->desde);
        }

        if ($request->hasta) {
            $query->where('fin', '<=', $request->hasta);
        }

        $tareas = $query->get();

        $totalTiempo = $tareas->sum(function ($t) {
            return $t->inicio->diffInMinutes($t->fin);
        });

        $pdf = Pdf::loadView('tareas.pdf', compact('tareas', 'totalTiempo'));
        return $pdf->download('informe_tareas.pdf');
    }

    public function getTareas(User $user)
    {

        return Tarea::with('proyecto')
            ->where('user_id', $user->id)
            ->get()
            ->map(function ($tarea) {
                return [
                    'title' => $tarea->titulo,
                    'start' => $tarea->inicio,
                    'end' => $tarea->fin,
                ];
            });
    }

    public function calendario(Request $request)
    {
        $userId = $request->user_id;

        $tareas = Tarea::where('user_id', $userId)
            ->with('proyecto')
            ->get();

        $eventos = $tareas->map(function ($tarea) {
            return [
                'id' => $tarea->id,
                'title' => $tarea->titulo,
                'start' => $tarea->inicio->format('Y-m-d\TH:i:s'),
                'end' => $tarea->fin->addSecond()->format('Y-m-d\TH:i:s'),
                'extendedProps' => [
                    // Eliminar el salto de línea al principio
                    'descripcion' => trim($tarea->proyecto->descripcion . "\n" . ($tarea->descripcion ?? '')),
                    'proyecto_id' => $tarea->proyecto_id,
                ]
            ];
        });

        return response()->json($eventos);
    }
}

/*
public function calendario(Request $request)
{
    $userId = $request->input('user_id');
    $tareas = Tarea::where('user_id', $userId)->get();

    $eventos = $tareas->map(function ($tarea) {
        return [
            'id' => $tarea->id,
            'title' => $tarea->titulo,
            'start' => $tarea->inicio,
            'end' => $tarea->fin,
        ];
    });

    return response()->json($eventos);
}
*/
/*
public function calendario(Request $request)
{
    $userId = Auth::id(); // Ignora lo que venga por GET

    $tareas = Tarea::where('user_id', $userId)->get();

    return response()->json($tareas->map(function ($tarea) {
        return [
            'id' => $tarea->id,
            'title' => $tarea->titulo,
            'start' => $tarea->inicio,
            'end' => $tarea->fin,
            'extendedProps' => [
                'descripcion' => $tarea->descripcion,
                'proyecto' => $tarea->proyecto->nombre ?? ''
            ]
        ];
    }));
}
*/
/*
public function calendario(Request $request)
{
    $userId = $request->user_id;

    $tareas = Tarea::where('user_id', $userId)
        ->with('proyecto')
        ->get();

    $eventos = $tareas->map(function ($tarea) {
        return [
            'id' => $tarea->id,
            'title' => $tarea->titulo,
            'start' => $tarea->inicio,
            'end' => $tarea->fin,
            'extendedProps' => [
                'descripcion' => $tarea->proyecto->descripcion ?? $tarea->descripcion,
                'proyecto_id' => $tarea->proyecto_id,
            ]
        ];
    });

    return response()->json($eventos);
}

*/

  /* 
    return Tarea::where('user_id', $user->id)->get()->map(function ($tarea) {
        return [
            'title' => $tarea->proyecto->nombre ?? 'Sin Proyecto',
            'start' => $tarea->fecha,
        ];
    });
    */

    /*
$proyecto = Proyecto::firstOrCreate(['nombre' => $request->proyecto]);
    
$tarea = new Tarea();
$tarea->user_id = $request->user_id;
$tarea->proyecto_id = $proyecto->id;
$tarea->fecha = $request->fecha;
$tarea->save();

return response()->json(['status' => 'ok']);

*/
  /* 
        {
            $usuarios = User::all();
            return view('tareas.index', compact('usuarios'));
        }
            */

        //return view('tareas.index'); // Vista con calendario y selector de usuario