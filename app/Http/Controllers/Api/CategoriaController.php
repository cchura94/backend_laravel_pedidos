<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lista_categorias = Categoria::all();
        return response()->json($lista_categorias, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validar
        $request->validate([
            "nombre" => "required|unique:categorias|max:50|min:3",
        ]);
        // guardar
        $cat = new Categoria();
        $cat->nombre = $request->nombre;
        $cat->detalle = $request->detalle;
        $cat->save();

        return response()->json(["mensaje" => "Categoria Registrada", "data" => $cat], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categoria = Categoria::find($id);
        if($categoria){
            return response()->json($categoria, 200);
        }
        return response()->json(["mensaje" => "No se encontró la categoria"], 404);
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
        $cat = Categoria::where("id", $id)->first();
        if($cat){
            // validar
            $request->validate([
                "nombre" => "required|max:50|min:3|unique:categorias,nombre,$id",
            ]);

            $cat->nombre = $request->nombre;
            $cat->detalle = $request->detalle;
            $cat->save();

            return response()->json(["mensaje" => "Categoria Modificada", "data" => $cat], 200);
        }
        return response()->json(["mensaje" => "No se encontró la categoria"], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat = Categoria::where("id", $id)->first();
        if($cat){
            $cat->delete();            

            return response()->json(["mensaje" => "Categoria Eliminada"], 200);
        }
        return response()->json(["mensaje" => "No se encontró la categoria"], 404);
    }
}
