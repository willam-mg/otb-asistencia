<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventoController extends Controller
{
    use ImageTrait;
    
    /**
     * Listado de Eventos
     * 
     * @group Eventos
     * @authenticated
     * @response scenario=success [
     *     {
     *         "nombres": "juan",
     *         "apellidos": "peres",
     *         "direccion": "Av villazon",
     *         "telefono": "7898789",
     *         "celular": "78967887",
     *         "numero_domicilio": "sin numero",
     *         "calle": "calle 17 de octubre",
     *         "src_foto": "",
     *         "user_id": 1,
     *     }
     * ]
     */
    public function index(Request $request)
    {
        $data = Evento::when($request->nombres, function ($query) use ($request) {
                $query->where("nombres", 'like', '%' . $request->nombres . '%');
            })
            ->when($request->apellidos, function ($query) use ($request) {
                $query->where("fecha", 'like', '%' . $request->apellidos . '%');
            })
            ->orderBy('id', 'DESC')
            ->paginate(5);
        return response()->json($data, 200);
    }

    /**
     * Create evento
     * 
     * @group Eventos
     * @authenticated
     * @bodyParam. Example: {
     *         "nombre": "evento de caridad",
     *         "fecha": "2023-01-03",
     *         "hora_inicio": "08:00",
     *         "hora_fin": "08:00",
     *         "descripcion": "eventoa benficencia de ...",
     *         "lugar": "en la canchita del barrio",
     *         "monto_recaudado": 0,
     * }
     * @response scenario=success {
     *         "id": 1,
     *         "nombre": "evento de caridad",
     *         "fecha": "2023-01-03",
     *         "hora_inicio": "08:00",
     *         "hora_fin": "08:00",
     *         "descripcion": "eventoa benficencia de ...",
     *         "lugar": "en la canchita del barrio",
     *         "monto_recaudado": 0,
     * }
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $rules = [
                'nombre' => ['required'],
                'fecha' => ['required'],
                'hora_inicio' => ['nullable'],
                'hora_fin' => ['nullable'],
                'descripcion' => ['nullable'],
                'lugar' => ['nullable'],
                'monto_recaudado' => ['nullable'],
            ];
            $this->validate($request, $rules);
            $data = $request;
            $data['user_id'] = auth()->user()->id;
            $model = Evento::create($request);

            DB::commit();
            return response()->json($model, 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Show residente
     * 
     * @group Eventos
     * @authenticated
     * @bodyParam id int 
     * @response scenario=success [
     *     {
     *         "nombres": "juan",
     *         "apellidos": "peres",
     *         "direccion": "Av villazon",
     *         "telefono": "7898789",
     *         "celular": "78967887",
     *         "numero_domicilio": "sin numero",
     *         "calle": "calle 17 de octubre",
     *         "src_foto": "",
     *         "user_id": 1,
     *     }
     * ]
     */
    public function show($id)
    {
        return Evento::findOrFail($id);
    }

    /**
     * Update residente
     * 
     * @group Eventos
     * @authenticated
     * @bodyParam id int 
     * @bodyParam. Example: {
     *     "nombres": "juan",
     *     "apellidos": "peres",
     *     "direccion": "Av villazon",
     *     "telefono": "7898789",
     *     "celular": "78967887",
     *     "numero_domicilio": "sin numero",
     *     "calle": "calle 17 de octubre",
     *     "src_foto": "",
     *     "user_id": 1,
     * }
     * @response scenario=success {
     *     "nombres": "juan",
     *     "apellidos": "peres",
     *     "direccion": "Av villazon",
     *     "telefono": "7898789",
     *     "celular": "78967887",
     *     "numero_domicilio": "sin numero",
     *     "calle": "calle 17 de octubre",
     *     "src_foto": "",
     *     "user_id": 1,
     * }
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $rules = [
                'nombre' => ['required'],
                'fecha' => ['required'],
                'hora_inicio' => ['nullable'],
                'hora_fin' => ['nullable'],
                'descripcion' => ['nullable'],
                'lugar' => ['nullable'],
                'monto_recaudado' => ['nullable'],
                'residente_id' => ['nullable'],
                'inquilino_id' => ['nullable'],
            ];
            $this->validate($request, $rules);
            
            $model = Evento::findOrFail($id);
            $model->nombre = $request->nombre;
            $model->fecha = $request->fecha;
            $model->hora_inicio = $request->hora_inicio;
            $model->hora_fin = $request->hora_fin;
            $model->descripcion = $request->descripcion;
            $model->lugar = $request->lugar;
            $model->monto_recaudado = $request->monto_recaudado;
            $model->residente_id = $request->residente_id;
            $model->inquilino_id = $request->inquilino_id;
            $model->save();

            DB::commit();
            return response()->json($model, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Update residente
     * 
     * @group Evento
     * @authenticated
     * @bodyParam id int 
     * @response scenario=success {
     *     "data": "Eliminado",
     * }
     */
    public function destroy($id)
    {
        $cliente = Evento::findOrFail($id);
        $cliente->delete();
        return response()->json([
            'data' => 'Eliminado'
        ], 200);
    }
}
