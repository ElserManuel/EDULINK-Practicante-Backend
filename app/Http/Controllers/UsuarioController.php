<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UsuarioController extends Controller 
{
    public function export()
    {
        try {
            // Crear el writer (ya no necesita opciones)
            $writer = new Writer();
            
            // Crear nombre de archivo con timestamp
            $timestamp = now()->format('Y-m-d_His');
            $fileName = "usuarios_{$timestamp}.xlsx";
            
            // Crear directorio temporal si no existe
            $tempDir = public_path('temp');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0777, true);
            }
            
            $filePath = $tempDir . '/' . $fileName;

            // Abrir archivo para escritura
            $writer->openToFile($filePath);

            // Escribir encabezados
            $headers = ['ID', 'Nombre', 'Apellido', 'Teléfono', 'Dirección', 'DNI', 'Estado', 'Fecha de Registro'];
            $writer->addRow(Row::fromValues($headers));

            // Escribir datos
            Usuarios::where('state', 'A')
                ->orderBy('created_at', 'desc')
                ->chunk(100, function ($usuarios) use ($writer) {
                    foreach ($usuarios as $usuario) {
                        $writer->addRow(Row::fromValues([
                            $usuario->id,
                            $usuario->nombre,
                            $usuario->apellido,
                            $usuario->telefono ?? 'No registrado',
                            $usuario->direccion ?? 'No registrada',
                            $usuario->dni,
                            $usuario->state === 'A' ? 'Activo' : 'Inactivo',
                            $usuario->created_at->format('d/m/Y H:i:s')
                        ]));
                    }
                });

            // Cerrar el writer
            $writer->close();

            // Retornar el archivo para descarga y eliminarlo después
            return response()->download($filePath, $fileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Error en exportación de usuarios: ' . $e->getMessage());
            return back()->with('error', 'Error al exportar usuarios.');
        }
    }

    public function index(Request $request)
    {
        try {
            $query = Usuarios::where('state', 'A');

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'LIKE', "%{$search}%")
                        ->orWhere('apellido', 'LIKE', "%{$search}%")
                        ->orWhere('dni', 'LIKE', "%{$search}%");
                });
            }

            $usuarios = $query->orderBy('created_at', 'desc')->paginate(10);
            
            if ($request->has('search')) {
                $usuarios->appends(['search' => $request->search]);
            }

            return view('usuarios.index', compact('usuarios'));
        } catch (\Exception $e) {
            Log::error('Error en listado de usuarios: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar la lista de usuarios.');
        }
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
        try {
            $usuario = Usuarios::findOrFail($id);
            $action = route('usuarios.update', $usuario->id);
            $method = 'PUT';
            $title = 'Editar Usuario';
            
            return view('usuarios.form', compact('usuario', 'action', 'method', 'title'));
        } catch (\Exception $e) {
            Log::error('Error al editar usuario: ' . $e->getMessage());
            return redirect()->route('usuarios.index')
                           ->withErrors(['error' => 'Usuario no encontrado']);
        }
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