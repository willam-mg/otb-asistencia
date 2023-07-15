<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsistenciaController extends Controller
{
    use ImageTrait;
    
    /**
     * Listado de Asistencias
     * 
     * @group Asistencias
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
        $data = Asistencia::when($request->apellidos, function ($query) use ($request) {
                $query->where("fecha", 'like', '%' . $request->apellidos . '%');
            })
            ->orderBy('id', 'DESC')
            ->paginate(5);
        return response()->json($data, 200);
    }

    /**
     * Create asistencia
     * 
     * @group Asistencias
     * @authenticated
     * @bodyParam. Example: {
     *         "nombre": "asistencia de caridad",
     *         "fecha": "2023-01-03",
     *         "hora_inicio": "08:00",
     *         "hora_fin": "08:00",
     *         "descripcion": "eventoa benficencia de ...",
     *         "lugar": "en la canchita del barrio",
     *         "monto_recaudado": 0,
     * }
     * @response scenario=success {
     *         "id": 1,
     *         "nombre": "asistencia de caridad",
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
                'residente_id' => ['nullable'],
                'inquilino_id' => ['nullable'],
            ];
            $this->validate($request, $rules);
            $data = $request;
            $data['fecha'] = date('Y-m-d');
            $data['hora'] = date('H:i:s');
            $model = Asistencia::create($request);

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
     * @group Asistencias
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
        return Asistencia::findOrFail($id);
    }

    /**
     * Update residente
     * 
     * @group Asistencias
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
                'residente_id' => ['nullable'],
                'inquilino_id' => ['nullable'],
            ];
            $this->validate($request, $rules);
            
            $model = Asistencia::findOrFail($id);
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
     * @group Asistencia
     * @authenticated
     * @bodyParam id int 
     * @response scenario=success {
     *     "data": "Eliminado",
     * }
     */
    public function destroy($id)
    {
        $cliente = Asistencia::findOrFail($id);
        $cliente->delete();
        return response()->json([
            'data' => 'Eliminado'
        ], 200);
    }
}
