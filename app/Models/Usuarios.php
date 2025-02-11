<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Usuarios extends Model
{
    use HasFactory;

    protected $table = 'usuarios';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['nombre', 'apellido', 'telefono', 'direccion', 'dni', 'state'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            $model->state = 'A'; // Estado por defecto
        });
    }
}
