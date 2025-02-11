<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UsuarioController extends Controller 
{
    public function index(Request $request)
    {
        $query = Usuarios::where('state', 'A');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('apellido', 'LIKE', "%{$search}%")
                  ->orWhere('dni', 'LIKE', "%{$search}%");
            });
        }

        $usuarios = $query->paginate(10);
        
        if ($request->has('search')) {
            $usuarios->appends(['search' => $request->search]);
        }

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $action = route('usuarios.store');
        $method = 'POST';
        $title = 'Crear Usuario';
        
        return view('usuarios.form', compact('action', 'method', 'title'));
    }

    public function store(Request $request) 
    {
        try {
            $validated = $this->validateUsuario($request);
            
            $usuario = Usuarios::create($validated);
            
            return redirect()->route('usuarios.index')
                           ->with('success', 'Usuario creado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al crear usuario: ' . $e->getMessage());
            return redirect()->route('usuarios.index')
                           ->withErrors(['error' => 'No se pudo crear el usuario']);
        }
    }

    public function edit($id)
    {
        $usuario = Usuarios::findOrFail($id);
        $action = route('usuarios.update', $usuario->id);
        $method = 'PUT';
        $title = 'Editar Usuario';
        
        return view('usuarios.form', compact('usuario', 'action', 'method', 'title'));
    }

    public function update(Request $request, $id) 
    {
        try {
            $usuario = Usuarios::findOrFail($id);
            $validated = $this->validateUsuario($request, $id);
            
            $usuario->update($validated);
            
            return redirect()->route('usuarios.index')
                           ->with('success', 'Usuario actualizado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al actualizar usuario: ' . $e->getMessage());
            return redirect()->route('usuarios.index')
                           ->withErrors(['error' => 'No se pudo actualizar el usuario']);
        }
    }

    public function destroy($id) 
    {
        try {
            $usuario = Usuarios::findOrFail($id);
            $usuario->state = 'I';
            $usuario->save();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar usuario: ' . $e->getMessage());
            return response()->json(['error' => 'No se pudo eliminar el usuario'], 500);
        }
    }

    private function validateUsuario(Request $request, $id = null)
    {
        return $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'dni' => 'required|string|max:20|unique:usuarios,dni,' . $id,
        ]);
    }
}