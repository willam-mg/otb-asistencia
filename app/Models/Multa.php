<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function residente():BelongsTo {
        return $this->belongsTo(Residente::class, 'residente_id');
    }

    public function inquilino():BelongsTo {
        return $this->belongsTo(Inquilino::class, 'inquilino_id');
    }
}
