<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuarios;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class UsuarioController extends Controller 
{
    public function index(Request $request)
    {
        $query = Usuarios::where('state', 'A');

        // Aplicar filtros si existen
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('apellido', 'LIKE', "%{$search}%")
                  ->orWhere('dni', 'LIKE', "%{$search}%");
            });
        }

        $usuarios = $query->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function store(Request $request) 
    {
        try {
            Log::info('Datos recibidos en store:', $request->all());

            $validated = $request->validate([
                'nombre' => 'required|string|max:100',
                'apellido' => 'required|string|max:100',
                'telefono' => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:255',
                'dni' => 'required|string|max:20|unique:usuarios,dni',
            ]);
            
            $usuario = new Usuarios();
            $usuario->id = (string) Str::uuid();
            $usuario->nombre = $validated['nombre'];
            $usuario->apellido = $validated['apellido'];
            $usuario->telefono = $validated['telefono'] ?? null;
            $usuario->direccion = $validated['direccion'] ?? null;
            $usuario->dni = $validated['dni'];
            $usuario->state = 'A'; // Estado por defecto
            
            if (!$usuario->save()) {
                Log::error('Error al guardar usuario en la BD');
                return redirect()->route('usuarios.index')->withErrors(['error' => 'No se pudo crear el usuario']);
            }
            
            return redirect()->route('usuarios.index');
        } catch (\Exception $e) {
            Log::error('Error al crear usuario: ' . $e->getMessage());
            return redirect()->route('usuarios.index')
                           ->withErrors(['error' => 'No se pudo crear el usuario']);
        }
    }

    public function update(Request $request, $id) 
    {
        try {
            $usuario = Usuarios::findOrFail($id);
            
            $validated = $request->validate([
                'nombre' => 'required|string|max:100',
                'apellido' => 'required|string|max:100',
                'telefono' => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:255',
                'dni' => 'required|string|max:20|unique:usuarios,dni,' . $id,
            ]);

            $usuario->update($validated);
            
            return redirect()->route('usuarios.index');
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

    public function edit($id)
    {
        $usuario = Usuarios::findOrFail($id);
        return view('usuarios.edit', compact('usuario'));
    }

    public function show($id)
    {
        $usuario = Usuarios::findOrFail($id);
        return view('usuarios.show', compact('usuario'));
    }
}
