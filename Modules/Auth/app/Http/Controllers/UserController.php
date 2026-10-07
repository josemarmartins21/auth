<?php

namespace Modules\Auth\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'data' => User::withoutRole('admin')->get(),
            'status' => true,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        try {
            
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|string|max:100|unique:' . User::class,
                'password' => 'required|min:6',
            ]);

            $user = User::create($validated);

            $user->assignRole('user');

            return response()->json([
                'status' => true,
                'user' => $user,
                'role' => $user->roles,
            ], 201);

        } catch (\Throwable $th) {
            Log::info('Tentativa falhada de registar usuário', [
                'error' => $th->getMessage(),
                'file' => $th->getFile(), 
            ]);

            return response()->json([
                'status' => false,
                'error' => 'Erro ao registar o usuário, tente novamente.',
            ], 500);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try {

            $user = User::findOrFail($id);

            return response()->json([
                'status' => true,
                'user' => $user,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getDirectPermissions(),
            ]);

        } catch (ModelNotFoundException) {
             return response()->json([
                'status' => false,
                'error' => 'Usuário não encontrado.',
            ], 404);

        } catch ( \Exception $th) {
            Log::info('Tentativa falhada de buscar usuário', [
                'error' => $th->getMessage(),
                'file' => $th->getFile(), 
            ]);

            return response()->json([
                'status' => false,
                'error' => 'Erro ao buscar o usuário, tente novamente.',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
        try {
            
            Gate::allowIf(
                fn (User $user) => $user->hasRole('admin') || $user->hasPermissionTo('editar usuário')
            );

            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|string|max:100',
                'password' => 'required|min:6',
            ]);

            $user = User::findOrFail($id);
            
            $user->updateOrFail($validated);

            return response()->json([
                'status' => true,
                'user' => $user,
            ]);

        } catch (\Throwable $th) {
            Log::info('Tentativa falhada de actualizar usuário', [
                'error' => $th->getMessage(),
                'file' => $th->getFile(), 
            ]);

            return response()->json([
                'status' => false,
                'error' => 'Erro ao actualizar o usuário, tente novamente.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        try {
            $user = User::findOrFail($id);
            
            $user->delete();

            return response()->json([
                'status' => true,
                'user' => "Usuário excluido com successo!",
            ]);


        } catch (\Throwable $th) {
            Log::info('Tentativa falhada de exluir usuário', [
                'error' => $th->getMessage(),
                'file' => $th->getFile(), 
            ]);

            return response()->json([
                'status' => false,
                'error' => 'Erro ao exluir o usuário, tente novamente.',
            ], 500);
        }
    }
}
