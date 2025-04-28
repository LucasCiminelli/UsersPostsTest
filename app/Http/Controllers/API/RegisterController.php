<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;

class RegisterController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Registra un nuevo usuario",
     *     tags={"auth"},
     *      @OA\RequestBody(
     *         description="Datos para registrar un usuario",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             type="object",
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string")
     *             
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Datos del usuario creado",
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Lucas Ciminelli"),
     *                 @OA\Property(property="email", type="string", format="email", example="testemail@test.com")
     *             ),
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación de datos"
     *     )
     * )
     */
    public function __invoke(RegisterRequest $request)
    {


        $validatedRequest = $request->validated();

        $user = User::create([
            'name' => $validatedRequest['name'],
            'email' => $validatedRequest['email'],
            'password' => bcrypt($validatedRequest['password'])
        ]);

        $token = auth(guard: 'api')->attempt([
            'email' => $validatedRequest['email'],
            'password' => $validatedRequest['password']
        ]);

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token
        ], 201);
    }
}
