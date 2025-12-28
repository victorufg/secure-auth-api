<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registrar novo usuário",
     *     description="Cria um novo usuário e retorna tokens de acesso e refresh",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="João Silva"),
     *             @OA\Property(property="email", type="string", format="email", example="joao@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="SenhaForte123!", description="Mínimo 8 caracteres em dev, 12 em produção com maiúsculas, minúsculas, números e símbolos"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="SenhaForte123!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1Qi..."),
     *             @OA\Property(property="refresh_token", type="string", example="def50200..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=900, description="Tempo de expiração em segundos"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="João Silva"),
     *                 @OA\Property(property="email", type="string", example="joao@example.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="O e-mail já está cadastrado."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Muitas tentativas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Muitas tentativas. Por favor, tente novamente em alguns instantes."),
     *             @OA\Property(property="retry_after", type="integer", example=60)
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // Criar usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Log de registro de usuário
        AuditLog::logSuccess('register', $user->id, [
            'email' => $user->email,
            'name' => $user->name,
        ]);

        // Gerar token
        $token = $user->createToken('auth-token');
        $refreshToken = $token->token->refreshToken;

        // Retornar resposta
        return response()->json([
            'access_token' => $token->accessToken,
            'refresh_token' => $refreshToken ? $refreshToken->id : null,
            'token_type' => 'Bearer',
            'expires_in' => config('passport.token_expiration.access_token', 15) * 60, // em segundos
            'user' => new UserResource($user),
        ], 201);
    }
}
