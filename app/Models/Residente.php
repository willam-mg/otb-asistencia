<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Residente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombres',
        'apellidos',
        'direccion',
        'telefono',
        'celular',
        'numero_domicilio',
        'calle',
        'foto',
        'user_id',
        'tipo',
    ];
}
