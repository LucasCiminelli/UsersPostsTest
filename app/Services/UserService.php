<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Http\JsonResponse;



class UserService
{


    public function validateUser($user): ?JsonResponse
    {

        if ($user === null) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        if ($user->id != auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado'
            ], 401);
        }

        return null;
    }
}
