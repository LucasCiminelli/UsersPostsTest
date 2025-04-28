<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;


class UsersController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Listar usuarios",
     *     security={{"bearerAuth":{}}},
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuarios paginados",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success", 
     *                 type="boolean", 
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="users",
     *                 type="object",
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Lucas Ciminelli"),
     *                         @OA\Property(property="email", type="string", format="email", example="example@test.com")
     *                     )
     *                 ),
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=100)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No users found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                  property="success", 
     *                  type="boolean", 
     *                  example=false
     *             ),
     *             @OA\Property(
     *                  property="message",
     *                  type="string", 
     *                  example="No users found"
     *              )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $users = User::paginate(10);

        if ($users->total() === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No users found'
            ]);
        }

        return response()->json([
            'success' => true,
            'users' => $users
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Mostrar un usuario específico",
     *     security={{"bearerAuth":{}}},
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del usuario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos del usuario",
     *         @OA\JsonContent(
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
     *                 @OA\Property(property="email", type="string", format="email", example="example@test.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
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
     *                 example="Usuario no encontrado"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
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
     *                 example="No autorizado"
     *             )
     *         )
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $user = User::find($id);

        $validateUser = $this->userService->validateUser($user);

        if ($validateUser) {
            return $validateUser;
        }

        return response()->json([
            'success' => true,
            'user' => $user
        ], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Actualizar un usuario específico",
     *     security={{"bearerAuth":{}}},
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del usuario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="Datos para actualizar el usuario",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="password", type="string")
     *             
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos del usuario modificados",
     *         @OA\JsonContent(
     *             required={"name", "password"},
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
     *                 @OA\Property(property="name", type="string", example="Lucas Ciminelli Modificado"),
     *                 @OA\Property(property="password", type="string", example="NuevaContraseña1234")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
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
     *                 example="Usuario no encontrado"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
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
     *                 example="No autorizado"
     *             )
     *         )
     *     )
     * )
     */
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        $user = User::find($id);

        $validateUser = $this->userService->validateUser($user);

        if ($validateUser) {
            return $validateUser;
        }

        $user->update($request->all());

        return response()->json([
            'success' => true,
            'user' => $user
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Eliminar un usuario específico",
     *     security={{"bearerAuth":{}}},
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del usuario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Usuario eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
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
     *                 example="Usuario no encontrado"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
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
     *                 example="No autorizado"
     *             )
     *         )
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $user = User::find($id);

        $validateUser = $this->userService->validateUser($user);

        if ($validateUser) {
            return $validateUser;
        }

        $user->delete();

        return response()->json(null, 204);
    }
}
