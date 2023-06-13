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
        'src_foto',
        'user_id',
    ];

    protected $appends = [
        'foto',
        'foto_thumbnail',
        'foto_thumbnail_sm',
    ];

    /**
     * Get foto attribute.
     */
    public function getFotoAttribute()
    {
        return $this->src_foto ? url('/') . '/storage/uploads/' . $this->src_foto : null;
    }

    /**
     * Get foto thumbnail attribute.
     */
    public function getFotoThumbnailAttribute()
    {
        return $this->src_foto ? url('/') . '/storage/uploads/thumbnail/' . $this->src_foto : null;
    }

    /**
     * Get foto small thumbnail attribute.
     */
    public function getFotoThumbnailSmAttribute()
    {
        return $this->src_foto ? url('/') . '/storage/uploads/thumbnail-small/' . $this->src_foto : null;
    }
}
