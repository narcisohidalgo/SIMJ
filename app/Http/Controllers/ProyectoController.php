<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class ProyectoController extends Controller
{

    public function index()
    {
        $usuarios = User::all(); // o los que desees mostrar
        $proyectos = Proyecto::all();
        return view('proyectos.index', compact('usuarios','proyectos'));
        //return view('proyectos.index'); // Vista con modal y listado por AJAX
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $proyecto = Proyecto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['success' => true, 'proyecto' => $proyecto,'message' => 'Proyecto creado correctamente.']);
    }

    public function list()
    {
        $proyectos = Proyecto::with('user')->get();
        //$proyectos = Proyecto::with('creador')->latest()->get();
        return view('proyectos.list', compact('proyectos'));
    }

    public function show($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        return response()->json($proyecto);
    }

    public function update(Request $request, $id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->update($request->only(['nombre']));
        return response()->json(['success' => true,'message' => 'Proyecto editado con éxito.']);
    }

    public function destroy($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->delete();

        return response()->json(['message' => 'Proyecto eliminado correctamente.']);
    }
}
