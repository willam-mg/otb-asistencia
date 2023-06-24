<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Inquilino;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InquilinoController extends Controller
{
    use ImageTrait;

    /**
     * Listado de alquilesres
     * 
     * @group Inquilinos
     * @authenticated
     * @response scenario=success [
     *     {
     *         "nombres": "juan",
     *         "apellidos": "peres",
     *         "ci": "8979879",
     *         "fecha_inicio_contrato": "2020-04-01",
     *         "fecha_fin_contrato": "2023-04-01",
     *         "permiso": true,
     *         "estado": 1,
     *         "user_id": 1,
     *         "residente_id": 1,
     *     }
     * ]
     */
    public function index(Request $request)
    {
        $data = Inquilino::when($request->nombres, function ($query) use ($request) {
                $query->where("nombres", 'like', '%' . $request->nombres . '%');
            })
            ->when($request->apellidos, function ($query) use ($request) {
                $query->where("apellidos", 'like', '%' . $request->apellidos . '%');
            })
            ->when($request->ci, function ($query) use ($request) {
                $query->where("ci", 'like', '%' . $request->ci . '%');
            })
            ->orderBy('id', 'DESC')
            ->paginate(5);
        return response()->json($data, 200);
    }

    /**
     * create inquilino
     * 
     * @group Inquilinos
     * @authenticated
     * @bodyParam. Example: {
     *     "nombres": "juan",
     *     "apellidos": "peres",
     *     "ci": "8979879",
     *     "fecha_inicio_contrato": "2020-04-01",
     *     "fecha_fin_contrato": "2023-04-01",
     *     "permiso": true,
     *     "estado": 1,
     *     "user_id": 1,
     *     "residente_id": 1,
     * }
     * @response scenario=success {
     *     "nombres": "juan",
     *     "apellidos": "peres",
     *     "ci": "8979879",
     *     "fecha_inicio_contrato": "2020-04-01",
     *     "fecha_fin_contrato": "2023-04-01",
     *     "permiso": true,
     *     "estado": 1,
     *     "user_id": 1,
     *     "residente_id": 1,
     * }
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $rules = [
                'nombres' => ['required'],
                'apellidos' => ['required'],
                'ci' => ['required'],
                'fecha_inicio_contrato' => ['required'],
                'fecha_fin_contrato' => ['nullable'],
                'permiso' => ['required'],
                'residente_id' => ['required'],
            ];
            $this->validate($request, $rules);
            $data = $request->except(
                'src_foto',
            );
            $data['estado'] = Inquilino::ESTADO_ACTIVO;
            $data['user_id'] = Auth::id();

            $model = Inquilino::create($data);

            $image = $request->has('src_foto') ? $request->src_foto : null;
            if ($image) {
                $model->firma_expresa = $this->saveImage($image, $model->id, 'residente');
                $model->save();
            }

            DB::commit();
            return response()->json($model, 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Show inquilino
     * 
     * @group Inquilinos
     * @authenticated
     * @bodyParam id int 
     * @response scenario=success {
     *     "nombres": "juan",
     *     "apellidos": "peres",
     *     "ci": "8979879",
     *     "fecha_inicio_contrato": "2020-04-01",
     *     "fecha_fin_contrato": "2023-04-01",
     *     "permiso": true,
     *     "estado": 1,
     *     "user_id": 1,
     *     "residente_id": 1,
     * }
     */
    public function show($id)
    {
        return Inquilino::findOrFail($id);
    }

    /**
     * Update inquilino
     * 
     * @group Inquilinos
     * @authenticated
     * @bodyParam id int 
     * @bodyParam. Example: {
     *     "nombres": "juan",
     *     "apellidos": "peres",
     *     "ci": "8979879",
     *     "fecha_inicio_contrato": "2020-04-01",
     *     "fecha_fin_contrato": "2023-04-01",
     *     "permiso": true,
     *     "estado": 1,
     *     "user_id": 1,
     *     "residente_id": 1,
     * }
     * @response scenario=success {
     *     "nombres": "juan",
     *     "apellidos": "peres",
     *     "ci": "8979879",
     *     "fecha_inicio_contrato": "2020-04-01",
     *     "fecha_fin_contrato": "2023-04-01",
     *     "permiso": true,
     *     "estado": 1,
     *     "user_id": 1,
     *     "residente_id": 1,
     * }
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $rules = [
                'nombres' => ['required'],
                'apellidos' => ['required'],
                'ci' => ['required'],
                'fecha_inicio_contrato' => ['required'],
                'fecha_fin_contrato' => ['nullable'],
                'permiso' => ['required'],
                'residente_id' => ['required'],
            ];
            $this->validate($request, $rules);

            $model = Inquilino::findOrFail($id);
            $model->nombres = $request->nombres;
            $model->apellidos = $request->apellidos;
            $model->ci = $request->ci;
            $model->fecha_inicio_contrato = $request->fecha_inicio_contrato;
            $model->fecha_fin_contrato = $request->fecha_fin_contrato;
            $model->permiso = $request->permiso;
            $model->estado = $request->estado;
            $model->residente_id = $request->residente_id;
            $model->save();

            $image = $request->has('src_foto') ? $request->src_foto : null;
            if ($image) {
                $model->firma_expresa = $this->saveImage($image, $model->id, 'residente', $model->src_foto);
                $model->save();
            }

            DB::commit();
            return response()->json($model, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Update inquilino
     * 
     * @group Inquilinos
     * @authenticated
     * @bodyParam id int 
     * @response scenario=success {
     *     "data": "Eliminado",
     * }
     */
    public function destroy($id)
    {
        $cliente = Inquilino::findOrFail($id);
        $cliente->delete();
        return response()->json([
            'data' => 'Eliminado'
        ], 200);
    }
}
