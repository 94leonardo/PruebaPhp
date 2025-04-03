<?php


//controlador
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Validator;
use Dotenv\Repository\RepositoryInterface;
use Symfony\Contracts\Service\Attribute\Required;

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

        $validator = Validator::make($request->all(), [
            'documento' => 'required|unique:producto',
            'nombreproducto' => 'required|max:255',
            'Referencia' => 'required|max:255',
            'precio' => 'required|max:255',
            'stock' => 'required|max:255',
            'fechacreacion' => 'required|date_format:Y-m-d' // Valida el formato correcto
            // Formatear la fecha

        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error validacion producto stock',
                'error' => $validator->errors(),
                'status' => 400,
            ];
            return response()->json($data, 404);
        }
        // Formatear la fecha manualmente antes de guardarla
        $fechaFormateada = date('Y-m-d', strtotime($request->fechacreacion));

        //creacion
        $producto = Producto::create([
            'documento' => $request->documento,
            'nombreproducto' => $request->nombreproducto,
            'Referencia' => $request->Referencia,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'fechacreacion' => $fechaFormateada
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

    //consultar Productos

    public function show($id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            $data = [
                'message' => 'Producto no encontrado',
                'status' => '404'
            ];
            return response()->json($data, 404);
        }
        $data = [
            'producto' => $producto,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    //eliminar producto

    public function destroy($id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            $data = [
                'message' => 'Producto no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $producto->delete();
        $data = [
            'message' => 'Producto eliminado',
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    //actualizar producto


}
