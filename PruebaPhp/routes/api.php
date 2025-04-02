<?php

use App\Http\Controllers\Api\InventarioControllers;
use App\Models\Producto;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//mostrar productos
Route::get('/producto', [InventarioControllers::class, 'index']);

//mostrar productos id
Route::get('/producto/{id}');

//crear un producto
Route::post('/producto', [InventarioControllers::class, 'store']);

//actualizar producto
Route::put('/producto');

//eliminar producto
Route::delete('/producto');
