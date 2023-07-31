<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\MultaResource;
use App\Models\Inquilino;
use App\Models\Multa;
use App\Models\Residente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MultaController extends Controller
{
    /**
     * Listado de Multa
     * 
     * @group Multa
     * @authenticated
     * @response scenario=success [
     *      {
     *          "id": 1,
     *          "residente_id": 1,
     *          "inquilino_id": 1,
     *          "descripcion": "anyone",
     *          "monto": 199,
     *          "fecha_emision": "2023-09-29",
     *          "fecha_cancelacion": "2023-01-30",
     *          "cancelado": false
     *      }
     * ]
     */
    public function index(Request $request)
    {
        $data = Multa::when($request->residente_id, function ($query) use ($request) {
            $query->where("residente_id", 'like', '%' . $request->residente_id . '%');
        })
            ->when($request->inquilino_id, function ($query) use ($request) {
                $query->where("inquilino_id", 'like', '%' . $request->inquilino_id . '%');
            })
            ->when($request->fecha_emision, function ($query) use ($request) {
                $query->where("fecha_emision", 'like', '%' . $request->fecha_emision . '%');
            })
            ->orderBy('id', 'DESC')
            ->paginate(5);
        return response()->json(MultaResource::collection($data)->response()->getData(true), 200);
    }

    /**
     * Create Multa
     * 
     * @group Multa
     * @authenticated
     * @bodyParam. Example: {
     *      "residente_id": 1,
     *      "inquilino_id": 1,
     *      "descripcion": "anyone",
     *      "monto": 199,
     *      "fecha_emision": "2023-09-29",
     *      "fecha_cancelacion": "2023-01-30",
     *      "cancelado": false
     * }
     * @response scenario=success [
     *      {
     *          "id": 1,
     *          "residente_id": 1,
     *          "inquilino_id": 1,
     *          "descripcion": "anyone",
     *          "monto": 199,
     *          "fecha_emision": "2023-09-29",
     *          "fecha_cancelacion": "2023-01-30",
     *          "cancelado": false
     *      }
     * ]
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $rules = [
                'residente_id' => ['required', 'exists:'.Residente::class.',id'],
                'inquilino_id' => ['required', 'exists:'.Inquilino::class.',id'],
                'descripcion' => ['required'],
                'monto' => ['required'],
                'fecha_emision' => ['nullable'],
                'fecha_cancelacion' => ['nullable'],
                'cancelado' => ['nullable'],
            ];
            $this->validate($request, $rules);
            $data = $request->all();

            $model = Multa::create($data);

            DB::commit();
            return response()->json(new MultaResource($model), 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Show Multa
     * 
     * @group Multa
     * @authenticated
     * @bodyParam id int 
     * @response scenario=success [
     *      {
     *          "id": 1,
     *          "residente_id": 1,
     *          "inquilino_id": 1,
     *          "descripcion": "anyone",
     *          "monto": 199,
     *          "fecha_emision": "2023-09-29",
     *          "fecha_cancelacion": "2023-01-30",
     *          "cancelado": false
     *      }
     * ]
     */
    public function show($id)
    {
        return new MultaResource(Multa::findOrFail($id));
    }

    /**
     * Update Multa
     * 
     * @group Multa
     * @authenticated
     * @bodyParam id int 
     * @bodyParam. Example: {
     *      "residente_id": 1,
     *      "inquilino_id": 1,
     *      "descripcion": "anyone",
     *      "monto": 199,
     *      "fecha_emision": "2023-09-29",
     *      "fecha_cancelacion": "2023-01-30",
     *      "cancelado": false
     * }
     * @response scenario=success [
     *      {
     *          "id": 1,
     *          "residente_id": 1,
     *          "inquilino_id": 1,
     *          "descripcion": "anyone",
     *          "monto": 199,
     *          "fecha_emision": "2023-09-29",
     *          "fecha_cancelacion": "2023-01-30",
     *          "cancelado": false
     *      }
     * ]
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $rules = [
                'residente_id' => ['required', 'exists:' . Residente::class . ',id'],
                'inquilino_id' => ['required', 'exists:' . Inquilino::class . ',id'],
                'descripcion' => ['required'],
                'monto' => ['required'],
                'fecha_emision' => ['nullable'],
                'fecha_cancelacion' => ['nullable'],
                'cancelado' => ['nullable'],
            ];
            $this->validate($request, $rules);
            $data = $request->all();

            $model = Multa::findOrFail($id);
            $model->residente_id = $request->residente_id;
            $model->inquilino_id = $request->inquilino_id;
            $model->descripcion = $request->descripcion;
            $model->monto = $request->monto;
            $model->fecha_emision = $request->fecha_emision;
            $model->fecha_cancelacion = $request->fecha_cancelacion;
            $model->cancelado = $request->cancelado;
            $model->save();

            DB::commit();
            return response()->json(new MultaResource($model), 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Delete Multa
     * 
     * @group Multa
     * @authenticated
     * @bodyParam id int 
     * @response scenario=success {
     *     "data": "Eliminado",
     * }
     */
    public function destroy($id)
    {
        $model = Multa::findOrFail($id);
        $model->delete();
        return response()->json([
            'data' => 'Eliminado'
        ], 200);
    }
}
