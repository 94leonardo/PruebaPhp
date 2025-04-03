<?php

use App\Http\Controllers\Api\InventarioControllers;
use App\Models\Producto;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//mostrar productos
Route::get('/producto', [InventarioControllers::class, 'index']);

//mostrar productos id
Route::get('/producto/{id}', [InventarioControllers::class, 'show']);

//crear un producto
Route::post('/producto', [InventarioControllers::class, 'store']);

//actualizar producto
Route::put('/producto/{id}', [InventarioControllers::class, 'update']);
//actualizar producto
Route::patch('/producto/{id}', [InventarioControllers::class, 'updatePath']);

//eliminar producto
Route::delete('/producto/{id}', [InventarioControllers::class, 'destroy']);
