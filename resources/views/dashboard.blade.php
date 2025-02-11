<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('usuarios.index') }}" 
                   class="inline-block px-6 py-3 bg-blue-600 text-black dark:text-white font-semibold text-lg rounded-lg shadow-md 
                          hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 
                          transition duration-300">
                    Usuarios
                </a>
            </div>
        </div>
    </div>
</x-app-layout>