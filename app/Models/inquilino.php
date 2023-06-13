<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquilino extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombres',
        'apellidos',
        'ci',
        'fecha_inicio_contrato',
        'fecha_fin_contrato',
        'permiso',
        'estado',
        'user_id',
        'residente_id',
    ];
}
