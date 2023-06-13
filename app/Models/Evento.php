<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evento extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'descripcion',
        'lugar',
        'monto_recaudado',
        'user_id',
        'residente_id',
        'inquilino_id',
    ];
}
