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

                <button onclick="openModal('create')" class="inline-block px-6 py-3 bg-black text-white font-semibold text-lg rounded-lg shadow-md 
                hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-75 transition duration-300">
                    Crear Usuario
                </button>

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
                                    <button onclick="openModal('edit', {{ json_encode($usuario) }})" 
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                        Editar
                                    </button>
                                    <button onclick="deleteUser('{{ $usuario->id }}')" 
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="userModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-900 p-8 rounded-lg shadow-lg w-full max-w-2xl">
            <h2 id="modalTitle" class="text-xl font-bold mb-4 text-black dark:text-white">Crear Usuario</h2>
            <form id="userForm" action="{{ route('usuarios.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <div class="grid grid-cols-1 gap-4">
                    <input class="w-full p-3 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600" 
                           type="text" name="nombre" placeholder="Nombre" required>
                    <input class="w-full p-3 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600" 
                           type="text" name="apellido" placeholder="Apellido" required>
                    <input class="w-full p-3 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600" 
                           type="text" name="telefono" placeholder="Teléfono" required>
                    <input class="w-full p-3 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600" 
                           type="text" name="direccion" placeholder="Dirección" required>
                    <input class="w-full p-3 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600" 
                           type="text" name="dni" placeholder="DNI" required>
                </div>
                <div class="mt-4 flex justify-end space-x-3">
                    <button type="submit" class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-700 transition-colors">
                        Guardar
                    </button>
                    <button onclick="closeModal()" type="button" 
                            class="px-4 py-2 bg-gray-200 text-black rounded-lg hover:bg-red-500 hover:text-white transition-colors">
                        Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .table-container {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .table-row-hover:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        .modal-backdrop {
            backdrop-filter: blur(5px);
        }
        
        .form-input {
            transition: border-color 0.2s ease;
        }
        
        .form-input:focus {
            border-color: #4a5568;
            outline: none;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
        }
        
        @media (max-width: 640px) {
            .table-responsive {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>

    <script>
        function openModal(mode, usuario = null) {
            event.preventDefault();
            const modal = document.getElementById('userModal');
            const form = document.getElementById('userForm');
            const title = document.getElementById('modalTitle');
            
            if (mode === 'edit' && usuario) {
                title.textContent = 'Editar Usuario';
                form.action = `/usuarios/${usuario.id}`;
                form._method.value = 'PUT';
                
                // Fill form with user data
                form.nombre.value = usuario.nombre;
                form.apellido.value = usuario.apellido;
                form.telefono.value = usuario.telefono;
                form.direccion.value = usuario.direccion;
                form.dni.value = usuario.dni;
            } else {
                title.textContent = 'Crear Usuario';
                form.action = '{{ route("usuarios.store") }}';
                form._method.value = 'POST';
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
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
    </script>
</x-app-layout>