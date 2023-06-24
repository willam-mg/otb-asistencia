<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Residente;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResidenteController extends Controller
{
    use ImageTrait;
    
    /**
     * Listado de Residentes
     * 
     * @group Residentes
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
        $data = Residente::when($request->nombres, function ($query) use ($request) {
                $query->where("nombres", 'like', '%' . $request->nombres . '%');
            })
            ->when($request->apellidos, function ($query) use ($request) {
                $query->where("apellidos", 'like', '%' . $request->apellidos . '%');
            })
            ->when($request->telefono, function ($query) use ($request) {
                $query->where("telefono", 'like', '%' . $request->telefono . '%');
            })
            ->when($request->celular, function ($query) use ($request) {
                $query->where("celular", 'like', '%' . $request->celular . '%');
            })
            ->orderBy('id', 'DESC')
            ->paginate(5);
        return response()->json($data, 200);
    }

    /**
     * Create residente
     * 
     * @group Residentes
     * @authenticated
     * @bodyParam. Example: {
     *         "nombres": "juan",
     *         "apellidos": "peres",
     *         "direccion": "Av villazon",
     *         "telefono": "7898789",
     *         "celular": "78967887",
     *         "numero_domicilio": "sin numero",
     *         "calle": "calle 17 de octubre",
     *         "src_foto": "",
     *         "user_id": 1,
     * }
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
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $rules = [
                'nombres' => ['required'],
                'apellidos' => ['required'],
                'direccion' => ['nullable'],
                'telefono' => ['nullable'],
                'celular' => ['nullable'],
                'numero_domicilio' => ['nullable'],
                'calle' => ['nullable'],
                'src_foto' => ['nullable'],
            ];
            $this->validate($request, $rules);
            $data = $request->except(
                'src_foto',
            );
            $model = Residente::create($data);

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
     * Show residente
     * 
     * @group Residentes
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
        return Residente::findOrFail($id);
    }

    /**
     * Update residente
     * 
     * @group Residentes
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
                'nombres' => ['required'],
                'apellidos' => ['required'],
                'direccion' => ['nullable'],
                'telefono' => ['nullable'],
                'celular' => ['nullable'],
                'numero_domicilio' => ['nullable'],
                'calle' => ['nullable'],
                'src_foto' => ['nullable'],
            ];
            $this->validate($request, $rules);
            
            $model = Residente::findOrFail($id);
            $model->nombres = $request->nombres;
            $model->apellidos = $request->apellidos;
            $model->direccion = $request->direccion;
            $model->telefono = $request->telefono;
            $model->celular = $request->celular;
            $model->numero_domicilio = $request->numero_domicilio;
            $model->calle = $request->calle;
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
     * Update residente
     * 
     * @group Residente
     * @authenticated
     * @bodyParam id int 
     * @response scenario=success {
     *     "data": "Eliminado",
     * }
     */
    public function destroy($id)
    {
        $cliente = Residente::findOrFail($id);
        $cliente->delete();
        return response()->json([
            'data' => 'Eliminado'
        ], 200);
    }
}
