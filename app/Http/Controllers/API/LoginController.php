<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;


class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Loguea un usuario existente",
     *     tags={"auth"},
     *      @OA\RequestBody(
     *         description="Datos para loguear un usuario",
     *         required=true,
     *         @OA\JsonContent(
     *             required = {"email", "password"},
     *             type="object",
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string")
     *             
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario logueado exitosamente. Retorna el token de acceso.",
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales invalidas",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Credenciales invalidas"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validacion de datos"
     *     )
     * )
     */

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);


        if (!$token = auth(guard: 'api')->attempt($credentials)) {

            return response()->json([
                'success' => false,
                'message' => 'Credenciales invalidas'
            ], status: 401);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
        ], 200);
    }
}
