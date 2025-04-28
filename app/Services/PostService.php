<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Http\JsonResponse;



class PostService
{


    public function validatePostOwnership($post): ?JsonResponse
    {

        if ($post === null) {
            return response()->json([
                'success' => false,
                'message' => 'Post no encontrado'
            ], 404);
        }

        if ($post->user_id != auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado'
            ], 401);
        }

        return null;
    }
}
