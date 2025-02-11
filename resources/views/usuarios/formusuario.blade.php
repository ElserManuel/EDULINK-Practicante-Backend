<!-- Modal Form -->
<form id="userForm" method="POST" class="space-y-4">
    @csrf
    <input type="hidden" name="_method" value="POST">
    <input type="hidden" name="id" id="userId">
    
    <div>
        <label class="block text-gray-700 dark:text-gray-200">Nombre:</label>
        <input type="text" name="nombre" required class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
    </div>
    
    <div>
        <label class="block text-gray-700 dark:text-gray-200">Apellido:</label>
        <input type="text" name="apellido" required class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
    </div>
    
    <div>
        <label class="block text-gray-700 dark:text-gray-200">Teléfono:</label>
        <input type="text" name="telefono" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
    </div>
    
    <div>
        <label class="block text-gray-700 dark:text-gray-200">Dirección:</label>
        <input type="text" name="direccion" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
    </div>
    
    <div>
        <label class="block text-gray-700 dark:text-gray-200">DNI:</label>
        <input type="text" name="dni" required class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
    </div>
    
    <div class="flex justify-end space-x-3">
        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-black rounded-lg hover:bg-gray-300">
            Cancelar
        </button>
        <button type="submit" class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-700">
            Guardar Usuario
        </button>
    </div>
</form>

<script>
function openModal(mode, usuario = null) {
    const modal = document.getElementById('userModal');
    const form = document.getElementById('userForm');
    const title = document.getElementById('modalTitle');
    
    if (mode === 'edit' && usuario) {
        title.textContent = 'Editar Usuario';
        form._method.value = 'PUT';
        form.id.value = usuario.id;
        
        // Rellenar el formulario
        form.nombre.value = usuario.nombre;
        form.apellido.value = usuario.apellido;
        form.telefono.value = usuario.telefono;
        form.direccion.value = usuario.direccion;
        form.dni.value = usuario.dni;
    } else {
        title.textContent = 'Crear Usuario';
        form._method.value = 'POST';
        form.id.value = '';
        form.reset();
    }
    
    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('userModal').classList.add('hidden');
}

document.getElementById('userForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const isEdit = formData.get('_method') === 'PUT';
    const userId = formData.get('id');
    const url = isEdit ? `/usuarios/${userId}` : '/usuarios';
    
    try {
        const response = await fetch(url, {
            method: isEdit ? 'PUT' : 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        });
        
        if (response.ok) {
            closeModal();
            window.location.href = '/usuarios';
        } else {
            // Si hay error, igualmente redirigimos
            window.location.href = '/usuarios';
        }
    } catch (error) {
        window.location.href = '/usuarios';
    }
});

function deleteUser(userId) {
    if (confirm('¿Está seguro de que desea eliminar este usuario?')) {
        const token = document.querySelector('meta[name="csrf-token"]').content;
        
        fetch(`/usuarios/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token
            }
        }).then(() => {
            window.location.href = '/usuarios';
        });
    }
}
</script>