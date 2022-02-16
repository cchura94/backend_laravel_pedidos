<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;


class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->search != ''){
            $lista_clientes = Cliente::where('ci_nit', 'like', '%'.$request->search.'%')->get();
        }else{
            $lista_clientes = Cliente::all();
        }
        return response()->json($lista_clientes, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "nombre_completo" => "required",            
        ]);
        
        $cliente = new Cliente();
        $cliente->nombre_completo= $request->nombre_completo;
        $cliente->ci_nit =  $request->ci_nit;
        $cliente->telefono =  $request->telefono;
        $cliente->correo =  $request->correo;
        $cliente->save();
        return response()->json(["mensaje" => "cliente registrado", "cliente" => $cliente]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
