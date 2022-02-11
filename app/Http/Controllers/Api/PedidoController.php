<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use App\Models\Cliente;
use App\Models\Producto;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pedidos = Pedido::paginate(10);
        return response()->json($pedidos, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validación
        $request->validate([
            "cod_factura" => "required|unique:pedidos",
            "cliente_id" => "required",
            "productos" => "required"
        ]);
        try {
            $user = Auth::user();
            $cliente = Cliente::FindOrFail($request->cliente_id);
            
            // generamos pedido
            $pedido = new Pedido;
            $pedido->fecha_pedido = date("Y-m-d H:i:s");
            $pedido->cod_factura = $request->cod_factura;
            $pedido->user_id = $user->id;
            $pedido->cliente_id = $cliente->id;
            $pedido->estado = 0;
            $pedido->save();            

            if(count($request->productos)> 0){
                foreach ($request->productos as $ped) {
                    $prod_id = $ped["producto_id"];
                    $prod_cantidad = $ped["cantidad"];

                    $producto = Producto::FindOrFail($prod_id);
                    if($producto->stock > $prod_cantidad){
                        // continuar
                        // relacion de muchos a muchos
                        $pedido->productos()->attach($producto->id, ["cantidad" => $prod_cantidad]);

                    }else{
                        return response()->json(['mensaje' => "Cantidad inexistente del producto"], 400);
                    }
                }
                
                // pedido completado
                $pedido->estado = 2;
                $pedido->save();
                
                return response()->json(['mensaje' => "Pedido completado"], 200);                
            }else{
                return response()->json([
                    'mensaje'=>'los productos son obligatorios.',
                    'error' => true,
                    'status' => 400], 400);                
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensaje'=>'el cliente o productos son obligatorio!',
                'error' => true,
                'status' => 422], 422);
        }

        // cliente_id, user_id
        // [producto_id]
        // pedido
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{

            $pedido = Pedido::with('cliente')
                                ->with('user')
                                ->with('productos')
                                ->FindOrFail($id);
            return response()->json($pedido);

        } catch (\Exception $e) {
            return response()->json([
                'mensaje'=>'El pedido no existe!',
                'error' => true,
                'status' => 404], 404);
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
