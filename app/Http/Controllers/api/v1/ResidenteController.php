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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Residente::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
