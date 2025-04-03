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
        // Validar que se está recibiendo un array de productos
        $validator = Validator::make($request->all(), [
            'productos' => 'required|array|min:1',
            'productos.*.documento' => 'required|integer|unique:producto,documento',
            'productos.*.nombreproducto' => 'required|string|max:255',
            'productos.*.Referencia' => 'required|string|max:255',
            'productos.*.precio' => 'required|numeric',
            'productos.*.stock' => 'required|integer',
            'productos.*.fechacreacion' => 'required|date_format:Y-m-d', // Validar formato correcto de fecha
        ]);

        // Si la validación falla
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        $productosGuardados = [];

        // Recorrer y guardar cada producto
        foreach ($request->productos as $productoData) {
            $productoData['fechacreacion'] = date('Y-m-d', strtotime($productoData['fechacreacion'])); // Formatear fecha

            $producto = Producto::create($productoData);

            if (!$producto) {
                return response()->json([
                    'message' => 'Error al crear un producto',
                    'status' => 500
                ], 500);
            }

            $productosGuardados[] = $producto;
        }

        return response()->json([
            'message' => 'Productos creados con éxito',
            'productos' => $productosGuardados,
            'status' => 201
        ], 201);
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

    public function update(Request $request, $id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            $data = [
                'message' => 'Este producto no se encuentra',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'documento' => 'required|unique:producto,documento,' . $id . ',id',
            'nombreproducto' => 'required|max:255',
            'Referencia' => 'required|max:255',
            'precio' => 'required|max:255',
            'stock' => 'required|max:255',
            'fechacreacion' => 'required|date_format:Y-m-d' // Valida el formato correcto
            // Formatear la fecha

        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Producto no encontrado',
                'error' => $validator->errors(),
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        // Formatear fecha solo si es necesario
        $fechaFormateada = $request->fechacreacion;
        if (strtotime($fechaFormateada) === false) {
            $fechaFormateada = date('Y-m-d', strtotime($request->fechacreacion));
        }
        $producto->update([
            $producto->documento = $request->documento,
            $producto->nombreproducto = $request->nombreproducto,
            $producto->Referencia = $request->Referencia,
            $producto->precio = $request->precio,
            $producto->stock = $request->stock,
            $producto->fechacreacion = $fechaFormateada

        ]);
        $producto->save();
        $data = [
            'message' => 'Producto actualizado',
            'producto' => $producto,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    //actualizar usn campo
    public function updatePath(Request $request, $id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            $data = [
                'message' => 'Producto no se encontro',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'documento' => 'required|unique:producto,documento,' . $id . ',id',
            'nombreproducto' => 'required|max:255',
            'Referencia' => 'required|max:255',
            'precio' => 'required|max:255',
            'stock' => 'required|max:255',
            'fechacreacion' => 'required|date_format:Y-m-d' // Valida el formato correcto
            // Formatear la fecha

        ]);
        if ($validator->fails()) {
            $data = [
                'message' => 'Error al validar los datos',
                'errors' => $validator->errors(),
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        if ($request->has('documento')) {
            $producto->documento = $request->documento;
        }

        if ($request->has('nombreproducto')) {
            $producto->nombreproducto = $request->nombreproducto;
        }
        if ($request->has('Referencia')) {
            $producto->Referencia = $request->Referencia;
        }
        if ($request->has('precio')) {
            $producto->precio = $request->precio;
        }
        if ($request->has('stock')) {
            $producto->stock = $request->stock;
        }
        if ($request->has('fechacreacion')) {
            $producto->fechacreacion = $request->fechacreacion;
        }
        $producto->save();
        $data = [
            'message' => 'producto actualizado',
            'producto' => $producto,
            'status' => 200
        ];
        return response()->json($data, 200);
    }
}
