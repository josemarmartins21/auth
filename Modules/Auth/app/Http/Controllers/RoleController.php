<?php

namespace Modules\Auth\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Auth\app\Models\Permission;

class RoleController extends Controller
{

    public function index()
    {
        return response()->json([
            'permissions' => Permission::all(),
            'status' => true,
        ]);
    }

    public function givePermission($id, Request $request)
    {
        try {

            $this->validate($request);

            $user = User::findOrFail($id);

            $user->givePermissionTo($request->permission);
    
            return response()->json([
                'message' => 'Permissão atribuida com sucesso.',
                'permissions' => $user->getDirectPermissions(),
                'status' => true,
            ]);

        } catch (\Throwable $th) {
            Log::error("Erro ao atribuir permissão.", [
                'error' => $th->getMessage(),
            ]);

            return response()->json([
                'message' => 'Erro ao atribuir permissão. Tente novamente', 
                'status' => false
            ], 500);
        }
    }
    
    public function removePermission($id, Request $request)
    {
        try {
            $this->validate($request);

            $user = User::findOrFail($id);

            $user->revokePermissionTo($request->permission);
    
            return response()->json([
                'message' => 'Permissão removida com sucesso.',
                'status' => true,
            ]);

        } catch (\Throwable $th) {
            Log::error("Erro ao remover permissão.", [
                'error' => $th->getMessage(),
            ]);

            return response()->json([
                'message' => 'Erro ao remover permissão. Tente novamente', 
                'status' => false
            ], 500);
        }
    }

    private function validate(Request $request): void
    {
        $request->validate([
            'permission' => 'required|string|max:50',
        ], [
            'permission' => 'permissão'
        ]);
    }



}
