<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;



class LogoutController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Desloguea un usuario",
     *     security={{"bearerAuth":{}}},
     *     tags={"auth"},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\Property(
     *             property="success",
     *             type="boolean",
     *             example=true
     *         ),
     *         @OA\Property(
     *             property="message",
     *             type="string",
     *             example="Logout realizado correctamente"
     *          )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token not found",
     *         @OA\Property(
     *             property="success",
     *             type="boolean",
     *             example=false
     *         ),
     *         @OA\Property(
     *             property="message",
     *             type="string",
     *             example="Token not found"
     *          )
     *     )
     * )
     */
    public function __invoke(Request $request)
    {
        try {


            auth()->logout();

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out'
            ], 200);
            //code...
        } catch (JWTException $exception) {

            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], status: 401);
        }
    }
}
