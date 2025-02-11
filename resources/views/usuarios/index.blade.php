<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Lista de Usuarios</h3>

                <button type="button" onclick="openModal('create')" class="inline-block px-6 py-3 bg-black text-white font-semibold text-lg rounded-lg shadow-md 
                hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-75 transition duration-300">
                    Crear Usuario
                </button>
                <a href="{{ route('usuarios.export') }}" 
   class="inline-block px-6 py-3 text-white font-semibold text-lg rounded-lg shadow-md transition duration-300 ease-in-out ml-4"
   style="background-color: #28a745; border: 2px solid #218838; color: white;"
   onmouseover="this.style.backgroundColor='#218838'"
   onmouseout="this.style.backgroundColor='#28a745'">
    Exportar Excel
</a>




                <div class="overflow-x-auto mt-6">
                    <table class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg shadow-md">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider border-b">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider border-b">Apellido</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider border-b">Teléfono</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider border-b">Dirección</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider border-b">DNI</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider border-b">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($usuarios as $usuario)
                            <tr class="text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $usuario->nombre }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $usuario->apellido }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $usuario->telefono }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $usuario->direccion }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $usuario->dni }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button type="button" onclick="openModal('edit', {{ json_encode($usuario) }})" 
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                        Editar
                                    </button>
                                    <button type="button" onclick="deleteUser('{{ $usuario->id }}')" 
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-6">
                        {{ $usuarios->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('usuarios.formusuario')

    @push('scripts')
    <script>
        function openModal(mode, usuario = null) {
            const modal = document.getElementById('userModal');
            const form = document.getElementById('userForm');
            const title = document.getElementById('modalTitle');
            const methodInput = document.getElementById('formMethod');
            
            if (mode === 'edit' && usuario) {
                title.textContent = 'Editar Usuario';
                form.action = `/usuarios/${usuario.id}`;
                methodInput.value = 'PUT';
                
                document.getElementById('nombre').value = usuario.nombre || '';
                document.getElementById('apellido').value = usuario.apellido || '';
                document.getElementById('telefono').value = usuario.telefono || '';
                document.getElementById('direccion').value = usuario.direccion || '';
                document.getElementById('dni').value = usuario.dni || '';
            } else {
                title.textContent = 'Crear Usuario';
                form.action = '{{ route("usuarios.store") }}';
                methodInput.value = 'POST';
                form.reset();
            }
            
            modal.classList.remove('hidden');
        }

        function closeModal() {
            event.preventDefault();
            document.getElementById('userModal').classList.add('hidden');
        }

        function deleteUser(userId) {
            if (confirm('¿Está seguro de que desea eliminar este usuario?')) {
                fetch(`/usuarios/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Error al eliminar el usuario');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar el usuario');
                });
            }
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('userForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    this.submit();
                });
            }
        });
    </script>
    @endpush
</x-app-layout>