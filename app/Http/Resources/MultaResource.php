<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MultaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'residente_id' => $this->residente_id,
            'residente' => $this->residente,
            'inquilino_id' => $this->inquilino_id,
            'inquilino' => $this->inquilino,
            'descripcion' => $this->descripcion,
            'monto' => $this->monto,
            'fecha_emision' => $this->fecha_emision,
            'fecha_cancelacion' => $this->fecha_cancelacion,
            'cancelado' => $this->cancelado,
        ];
    }
}
