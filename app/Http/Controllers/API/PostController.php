<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;



class PostController extends Controller
{

    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Lista de los posts creados por el usuario autenticado",
     *     security={{"bearerAuth":{}}},
     *     tags={"posts"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de posts paginados",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success", 
     *                 type="boolean", 
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="posts",
     *                 type="object",
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Titulo de prueba"),
     *                         @OA\Property(property="description", type="string", example="Descripcion de prueba"),
     *                         @OA\Property(property="content", type="string", example="contenido de prueba de minimo 50 caracteres ...")
     *                     )
     *                 ),
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=100)
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="El usuario autenticado no tiene posts creados",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success", 
     *                 type="boolean", 
     *                 example=false
     *             ),
     *             @OA\Property(
     *                  property="message",
     *                  type="string", 
     *                  example="Posts not founded for this user"
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $posts = Post::where('user_id', auth()->id())->paginate(10);

        if ($posts->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Posts not founded for this user'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'posts' => $posts
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Crea un nuevo post y lo vincula con el usuario autenticado como su creador.",
     *     security={{"bearerAuth":{}}},
     *     tags={"posts"},
     *      @OA\RequestBody(
     *         description="Datos para crear un post",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="content", type="string")
     *             
     *         )
     *     ),
     *     @OA\Response(       
     *         response=201,
     *         description="Post creado exitosamente. Retorna los datos del post",
     *         @OA\JsonContent(
     *             required={"title", "description", "content"},
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="post",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Titulo Ejemplo"),
     *                 @OA\Property(property="description", type="string", example="Descripción de ejemplo"),
     *                 @OA\Property(property="content", type="string", example="Contenido de ejemplo minimo 50 caracteres ...")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación de datos"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function store(PostRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();

        $post = Post::create([
            'title' => $validatedRequest['title'],
            'description' => $validatedRequest['description'],
            'content' => $validatedRequest['content'],
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'post' => $post
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     summary="Mostrar un post específico",
     *     security={{"bearerAuth":{}}},
     *     tags={"posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del post",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos del post",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="post",
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="Titulo Ejemplo"),
     *                 @OA\Property(property="description", type="string", example="Descripción de ejemplo"),
     *                 @OA\Property(property="content", type="string", example="Contenido de ejemplo minimo 50 caracteres ...")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post no encontrado",
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
     *                 example="post no encontrado"
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
    public function show($id): JsonResponse
    {
        $post = Post::find($id);


        $validationResponse = $this->postService->validatePostOwnership($post);

        if ($validationResponse) {
            return $validationResponse;
        }

        return response()->json([
            'success' => true,
            'post' => $post
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     summary="Actualizar un post específico",
     *     security={{"bearerAuth":{}}},
     *     tags={"posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del Post",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="Datos para actualizar el post",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="content", type="string")
     * 
     *             
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post actualizado correctamente",
     *         @OA\JsonContent(
     *             required={"title", "description", "content"},
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="post",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Titulo Modificado"),
     *                 @OA\Property(property="description", type="string", example="Descripcion modificada"),
     *                 @OA\Property(property="content", type="string", example="Contenido modificado minimo 50 caracteres") 
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post no encontrado",
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
     *                 example="Post no encontrado"
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
    public function update(PostRequest $request, $id): JsonResponse
    {
        $post = Post::find($id);

        $validationResponse = $this->postService->validatePostOwnership($post);

        if ($validationResponse) {
            return $validationResponse;
        }

        $post->update($request->all());

        return response()->json([
            'success' => true,
            'post' => $post
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     summary="Eliminar un post específico",
     *     security={{"bearerAuth":{}}},
     *     tags={"posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del post",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Post eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post no encontrado",
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
     *                 example="Post no encontrado"
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
        $post = Post::find($id);

        $validationResponse = $this->postService->validatePostOwnership($post);

        if ($validationResponse) {
            return $validationResponse;
        }

        $post->delete();

        return response()->json(null, 204);
    }
}
