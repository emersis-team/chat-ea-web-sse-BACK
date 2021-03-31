<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Novedad;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class NovedadesBackController extends Controller
{
    public function getNovedades()
    {
      $novedades = Novedad::orderBy('updated_at', 'desc')
                            -> with('files')
                            -> paginate(10);

      return response()->json([
        'novedades' => $novedades,
      ]);
    }

    public function createNovedad()
    {
      try {

        //Chequea los campos de entrada
        $campos = request()->validate([
                            'titulo' => 'string',
                            'descripcion' => 'string',
                            'activo' => 'boolean',
                            ]);

        $novedad = new Novedad;
        $novedad->titulo = $campos['titulo'];
        $novedad->descripcion = $campos['descripcion'];
        $novedad->activo = $campos['activo'];

        if ($novedad->save()) {
          //var_dump($novedad->id);
          return response()->json([
            'status' => 200,
            'message' => 'Creación dela novedad realizada con éxito',
            'novedad_id' => $novedad->id,
          ]);
        } else {
          throw new \Error('No se pudo crear el presupuesto.');
        }
      } catch (\Throwable $e) {

        return response()->json([
          'status' => $e->getCode() ? $e->getCode() : 500,
          'message' => $e->getMessage()
        ]);
      }
    }

    public function updateNovedad($novedadId)
    {
        try {

            if (!is_numeric($novedadId)) {
                throw new \Error('No ingresó un número válido de Novedad.');
            }

            $novedades = Novedad::where('id', $novedadId)
                                ->first();

            if (!$novedades)
            {
                throw new \Error('No existe la novedad.');
            }

            //Chequea los campos de entrada
            $campos = request()->validate([
                'titulo' => 'string',
                'descripcion' => 'string',
                'activo' => 'boolean',
            ]);

            //UPDATE en la DB
            $updateNovedad = Novedad::where('id',$novedadId)
                            -> update(['titulo' => $campos['titulo'],
                                   'descripcion' => $campos['descripcion'],
                                   'activo' => $campos['activo'],]);

            if (!$updateNovedad)
            {
                throw new \Error('No se pudo actualizar la novedad.');
            }

            return response()->json([
            'status' => 200,
            'message' => 'La actualización de la novedad fue realizada con éxito'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => $e->getCode() ? $e->getCode() : 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteNovedad($novedadId)
    {
        try {

            if (!is_numeric($novedadId)) {
                throw new \Error('No ingresó un número válido de Novedad.');
            }

            $novedades = Novedad::where('id', $novedadId)
                                ->first();

            if (!$novedades)
            {
                throw new \Error('No existe la novedad.');
            }

            //DELETE Novedad en la DB
            if (!$novedades->delete())
            {
                throw new \Error('No se pudo borrar la novedad.');
            }

            return response()->json([
            'status' => 200,
            'message' => 'La novedad fur borrada con éxito'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => $e->getCode() ? $e->getCode() : 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getFilesFromNovedad(Novedad $novedad)
    {
        try {

            return response()->json($novedad->load('files')->toArray());

        } catch (\Throwable $e) {

            return response()->json([
                'status' => $e->getCode() ? $e->getCode() : 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function createFiles(Request $request, Novedad $novedad)
    {
        try {

            //Chequea Description y File
            $campos = request()->validate([
                'file' => 'required',
                'file.*' => ['required', 'mimes:doc,pdf,docx,txt,zip,jpeg,bmp,png,xls,xlsx|max:51200'],
                'description' => ['sometimes', 'array'],
                'description.*' => ['nullable', 'string']
            ]);

            $insert = array();

            if ($request->hasfile('file')) {

                foreach ($request->file('file') as $index => $file) {
                    //Guardar los archivos en storage/app/public -
                    //Link from "public/storage" to "storage/app/public" con:
                    //php artisan storage:link

                    //var_dump($index);

                    $original_filename = $file->getClientOriginalName();
                    $filename = $file->store('files', 'public');

                    $insert[$index]['file'] = "$filename";
                    $insert[$index]['original_file'] = "$original_filename";
                    $insert[$index]['description'] = isset($campos['description'][$index])?$campos['description'][$index] : NULL;
                    $insert[$index]['novedad_id'] = $novedad->id;
                    $insert[$index]['created_at'] = Carbon::now();
                    $insert[$index]['updated_at'] = Carbon::now();
                }
            }

           // $id = 1000;

            if (!File::insert($insert)) {
                //Como No se pudo insertar en la BD, borrar los archivos bajados al servidor
                foreach ($insert as $index => $file) {
                    $filename = $insert[$index]['file'];
                    Storage::disk('public')->delete($filename);
                }

                throw new \Error('No se pudieron crear los archivos.');
            }
            $id = DB::getPdo()->lastInsertId();

            return response()->json([
                'status' => 200,
                'message' => 'Creación de los archivos realizada con éxito',
                'file_id' => $id,
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'status' => $e->getCode() ? $e->getCode() : 500,
                'message' => $e->getMessage()
            ]);
        }
    }

}
