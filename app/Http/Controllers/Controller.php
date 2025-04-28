<?php

namespace App\Http\Controllers;



/**
 * @OA\Info(
 *     title="Mi API",
 *     description="Documentación de la API para el sistema UsersPosts",
 *     version="1.0.0",
 *     @OA\Contact(
 *         email="contacto@miapp.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Autenticación usando un token JWT"
 * )
 */



abstract class Controller
{
    //
}
