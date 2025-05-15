<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;



class UserController extends Controller
{
   
    public function index()
    {
        return view('users.index');
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'is_admin' => 'required|boolean',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->is_admin,
        ]);

        return response()->json(['success' => 'Usuario creado con éxito.']);
    }

    public function ajax(Request $request)
    {
        $data = User::select(['id', 'name', 'email', 'is_admin']);

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                /** @var User $usuario */
                $usuario = Auth::user();
                if ($usuario && $usuario->is_admin) {
                    $editBtn = '<a href="' . route('usuarios.edit', $row->id) . '" class="btn btn-sm btn-primary">Editar</a>';
                    $deleteBtn = '
    <button class="btn btn-sm btn-danger btn-eliminar" data-id="' . $row->id . '">
        Eliminar
    </button>';
                    return $editBtn . ' ' . $deleteBtn;
                } else {
                    return ''; // No muestra nada si no es admin
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(User $user)
    {
        /** @var User $usuario */
        $usuario = Auth::user();
        abort_unless($usuario?->is_admin, 403);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {

        /** @var User $usuario */
        $usuario = Auth::user();
        abort_unless($usuario?->is_admin, 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'is_admin' => 'boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => $request->has('is_admin'),
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        if (request()->ajax()) {
            return response()->json(['message' => 'Usuario eliminado correctamente.']);
        }

        return redirect("/usuarios")->with('success', 'Usuario eliminado correctamente');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function registro()
    {
        return view('auth.register');
    }

    public function toggleAdmin(Request $request, User $user)
    {
        abort_unless(Auth::user()?->is_admin, 403);

        // Prevenir que un admin se desactive a sí mismo
        if (Auth::id() === $user->id) {
            return response()->json(['message' => 'No puedes modificarte a ti mismo.'], 403);
        }

        $user->is_admin = $request->is_admin;
        $user->save();

        return response()->json(['message' => 'Permisos actualizados correctamente.']);
    }
}
