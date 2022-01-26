<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\PedidoController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\UsuarioController;

/*
 GET   /api/categoria      (index - listar)
 POST  /api/categoria      (store - guardar)
 GET   /api/categoria/{id} (show  - mostrar)
 PUT   /api/categoria/{id} (update - modificar)
DELETE /api/categoria/{id} (destroy - eliminar)
*/
/*
Route::get("categoria", [CategoriaController::class, "index"])->name("lista_categoria");
Route::post("categoria", [CategoriaController::class, "store"])->name("guardar_categoria");
Route::get("categoria/{id}", [CategoriaController::class, "show"])->name("mostrar_categoria");
Route::put("categoria/{id}", [CategoriaController::class, "update"])->name("modificar_categoria");
Route::delete("categoria/{id}", [CategoriaController::class, "destroy"])->name("eliminar_categoria");
*/

Route::apiResource("categoria", CategoriaController::class); // 
Route::apiResource("producto", ProductoController::class);
Route::apiResource("cliente", ClienteController::class);
Route::apiResource("pedido", PedidoController::class);
Route::apiResource("usuario", UsuarioController::class);
