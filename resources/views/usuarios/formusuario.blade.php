<div id="userModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-900 p-8 rounded-lg shadow-lg w-full max-w-2xl">
        <h2 id="modalTitle" class="text-xl font-bold mb-4 text-black dark:text-white">Crear Usuario</h2>
        <form id="userForm" action="{{ route('usuarios.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" value="POST" id="formMethod">
            <div class="grid grid-cols-1 gap-4">
                <input class="w-full p-3 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600" 
                       type="text" name="nombre" id="nombre" placeholder="Nombre" required>
                       
                <input class="w-full p-3 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600" 
                       type="text" name="apellido" id="apellido" placeholder="Apellido" required>
                       
                <input class="w-full p-3 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600" 
                       type="text" name="telefono" id="telefono" placeholder="Teléfono" required>
                       
                <input class="w-full p-3 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600" 
                       type="text" name="direccion" id="direccion" placeholder="Dirección" required>
                       
                <input class="w-full p-3 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600" 
                       type="text" name="dni" id="dni" placeholder="DNI" required>
            </div>
            <div class="mt-4 flex justify-end space-x-3">
                <button type="submit" class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-700 transition-colors">
                    Guardar
                </button>
                <button type="button" onclick="closeModal()" 
                        class="px-4 py-2 bg-gray-200 text-black rounded-lg hover:bg-red-500 hover:text-white transition-colors">
                    Cerrar
                </button>
            </div>
        </form>
    </div>
</div>