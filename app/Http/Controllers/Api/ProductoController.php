<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = Producto::paginate(10);
        return response()->json($productos, 200);
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
            "nombre" => "required|min:3|max:200|unique:productos",
            "precio" => "required",
            "categoria_id" => "required"
        ]);

        $nom_img = "";
        if($file = $request->file("imagen")){
            $nom_img = time(). "-". $file->getClientOriginalName();
            $file->move("imagenes");
        }

        $prod = new Producto();
        $prod->nombre = $request->nombre;
        $prod->precio = $request->precio;
        $prod->stock = $request->stock;
        $prod->descripcion = $request->descripcion;
        $prod->estado = $request->estado;
        $prod->categoria_id = $request->categoria_id;
        $prod->imagen = '/imagenes' . $nom_img;  
        $prod->save();   
        
        return response()->json(["mensaje" => "Producto registrado"], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $producto = Producto::find($id);
        return response()->json($producto, 200);
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
        $request->validate([
            "nombre" => "required|min:3|max:200|unique:productos,nombre,$id",
            "precio" => "required",
            "categoria_id" => "required"
        ]);

        $prod = Producto::find($id);
        $prod->nombre = $request->nombre;
        $prod->precio = $request->precio;
        $prod->stock = $request->stock;
        $prod->descripcion = $request->descripcion;
        $prod->estado = $request->estado;
        $prod->categoria_id = $request->categoria_id;
        $nom_img = "";
        if($file = $request->file("imagen")){
            $nom_img = time(). "-". $file->getClientOriginalName();
            $file->move("imagenes");
            $prod->imagen = '/imagenes' . $nom_img;     
        }
        $prod->save();
        
        return response()->json(["mensaje" => "Producto Modificado"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $prod = Producto::find($id);
        $prod->delete();

        return response()->json(["mensaje" => "Producto Eliminado"], 200);
    }
}
