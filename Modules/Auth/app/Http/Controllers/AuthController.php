<?php

namespace Modules\Auth\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {

            $validated = $request->validate([
                'email' => 'required|email|string',
                'password' => 'required|string',
                'remenber' => 'nullable|boolean',
            ]);  

            if (Auth::attempt($validated, $validated['remenber'] ?? false)) {
                $token = $request->user()->createToken('auth_token');

                return response()->json([
                    'status' => true,
                    'token' => $token->plainTextToken,
                ]); 
            }

            return response()->json([
                'message' => 'Email ou senha inválida', 
                'status' => false
            ], 401);



        } catch (\Throwable $th) {
            Log::info("Erro ao realizar o login.", [
                'error' => $th->getMessage(),
                'error_code' => $th->getCode(),
                'file_name' => $th->getFile(),
            ]);

            return response()->json([
                'message' => 'Erro ao realizar o login. Tente novamente', 
                'status' => false
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {

            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Usuário deslogado com sucesso!',
            ]); 

        } catch (\Throwable $th) {
            Log::info("Erro ao realizar o logout.", [
                'error' => $th->getMessage(),
            ]);

            return response()->json([
                'message' => 'Erro ao realizar o logout. Tente novamente', 
                'status' => false
            ], 500);
        }
    }
}
