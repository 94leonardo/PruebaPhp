<?php


//controlador
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Validator;
use Dotenv\Repository\RepositoryInterface;

class InventarioControllers extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::all();
        if ($productos->isEmpty()) {
            $data = [
                'message' => 'no se encontro producto stock',
                'status' => 200,
            ];
            return response()->json($data, 404);
        }
        return response()->json($productos, 200);
    }
    //validacion de datos
    public function store(Request $request)
    {
        dd($request->all());
        $validator = Validator::make($request->all(), [
            'documento' => 'required|unique:producto',
            'nombreproducto' => 'required|max255',
            'Referencia' => 'required|max255',
            'precio' => 'required|max255',
            'stock' => 'required|max255',
            'fechacreacion' => ''
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error validacion producto stock',
                'error' => $validator->errors(),
                'status' => 400,
            ];
            return response()->json($data, 404);
        }

        //creacion
        $producto = Producto::create([
            'documento' => $request->documento,
            'nombreproducto' => $request->nombreproducto,
            'Referencia' => $request->Referencia,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'fechacreacion' => $request->fechacreacion
        ]);
        if (!$producto) {
            $data = [
                'message' => 'error al crear producto',
                'status' => 500
            ];
            return response()->json($data, 500);
        }
        $data = [
            'producto' => $producto,
            'status' => '201',
        ];
        return response()->json($data, 201);
    }

    //consultar





}
