<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Multa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'residente_id',
        'inquilino_id',
        'descripcion',
        'monto',
        'fecha_emision',
        'fecha_cancelacion',
        'cancelado',
    ];
}
