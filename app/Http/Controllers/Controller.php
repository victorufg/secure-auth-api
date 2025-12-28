<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Secure Auth API",
 *     version="1.0.0",
 *     description="API de autenticação segura com OAuth2, rate limiting, validação de senha forte e audit logging",
 *
 *     @OA\Contact(
 *         email="contato@secureauthapi.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost",
 *     description="Servidor de Desenvolvimento"
 * )
 * @OA\Server(
 *     url="https://api.secureauthapi.com",
 *     description="Servidor de Produção"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Insira o token de acesso obtido no login"
 * )
 *
 * @OA\Tag(
 *     name="Autenticação",
 *     description="Endpoints de autenticação e gerenciamento de tokens"
 * )
 */
abstract class Controller
{
    //
}
