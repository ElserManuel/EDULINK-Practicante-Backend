<?php

namespace App\Exports;

use App\Models\Usuarios;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UsuariosExport 
{
    protected $writer;
    protected $filePath;

    public function export()
    {
        try {
            $this->initializeWriter();
            $this->writeHeaders();
            $this->writeData();
            $this->writer->close();

            return $this->filePath;
        } catch (\Exception $e) {
            Log::error('Error en exportación: ' . $e->getMessage());
            if ($this->writer) {
                $this->writer->close();
            }
            throw $e;
        }
    }

    protected function initializeWriter()
    {
        // Crear directorio si no existe
        Storage::disk('public')->makeDirectory('exports', true);

        $fileName = 'exports/usuarios_' . date('Y-m-d_His') . '.xlsx';
        $this->filePath = Storage::disk('public')->path($fileName);

        // Instanciar Writer sin opciones
        $this->writer = new Writer();
        $this->writer->openToFile($this->filePath);
    }
}