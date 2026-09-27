<?php

namespace Modules\Auth\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'data' => User::all(),
            'status' => true,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth::create');
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
            Log::info('Tentativa de criar usuário', [
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
        return view('auth::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('auth::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
