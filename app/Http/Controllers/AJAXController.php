<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AJAXController extends Controller
{
    public function showBy(Request $request)
    {
        $table = $this->validateModel($request->get('model'));
        $key = $request->get('key');

        $request->validate([
            'data' => 'required|exists:osano.'.$table.','.$key,
        ]);

        $model = "\App\Models\\".$request->get('model');

        $data = $request->get('is_collection') 
            ? $model::where($key, $request->get('data'))->get()
            : $model::where($key, $request->get('data'))->first();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function showRelation(Request $request)
    {
        $table = $this->validateModel($request->get('model'));
        $key = $request->get('key');
        $relation = $request->get('relation');
        $request->validate([
            'relation' => 'nullable|string',
            'data' => 'required|exists:osano.'.$table.','.$key,
        ]);

        $model = "\App\Models\\".$request->get('model');

        $data = $request->get('is_collection') 
            ? $model::where($key, $request->get('data'))->with((string) $relation)->get()
            : $model::where($key, $request->get('data'))->with((string) $relation)->first();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    private function validateModel($modelName)
    {
        if (empty($modelName) || !preg_match('/^[a-zA-Z0-9_]+$/', $modelName)) {
            abort(400, 'Model tidak valid');
        }

        $modelClass = "\App\Models\\" . $modelName;
        if (!class_exists($modelClass)) {
            abort(404, 'Model tidak ditemukan');
        }

        $model = new $modelClass();
        if (!Schema::connection('osano')->hasTable($model->getTable())) {
            abort(404, 'Tabel tidak ditemukan');
        }

        return $model->getTable();
    }
}
