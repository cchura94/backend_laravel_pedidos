<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SanctumAuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        // verificar el correo
        $user = User::where("email", "=", $request->email)->first();
        // verificar la contraseña
            if ($user && Hash::check($request->password, $user->password)){
                // generar el token
                $token = $user->createToken("auth_token")->plainTextToken;
                return response()->json([
                    "mensaje" => "Usuario logueado",
                    "user" => $user,
                    "access_token" => $token,
                    "error" => false
                ]);
            }else{ // contraseña incorrecta
                return response()->json(["mensaje" => "Credenciales invalidas", "error"=>true], 200);
            }
    }

    public function registro(Request $request){
        // validar
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed"
        ]);
        // guardar el nuevo usuario
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        
        return response()->json(["mensaje" => "Usuario registrado"], 201);
    }

    public function perfil()
    {
        return response()->json([
            "mensaje" => "Perfil",
            "data" => Auth::user()
        ]);
    }

    public function logout()
    {
        return Auth::user()->tokens()->delete();
    }

    public function refresh(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'access_token' => $request->user()->createToken('api')->plainTextToken,
        ]);
    }
}
