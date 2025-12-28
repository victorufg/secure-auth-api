<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Fazer login",
     *     description="Autentica um usuário e retorna tokens de acesso e refresh",
     *     tags={"Autenticação"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"email","password"},
     *
     *             @OA\Property(property="email", type="string", format="email", example="joao@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="SenhaForte123!")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Login realizado com sucesso",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1Qi..."),
     *             @OA\Property(property="refresh_token", type="string", example="def50200..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=900),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="João Silva"),
     *                 @OA\Property(property="email", type="string", example="joao@example.com")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Credenciais inválidas",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="As credenciais fornecidas estão incorretas."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=429,
     *         description="Muitas tentativas",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Muitas tentativas. Por favor, tente novamente em alguns instantes."),
     *             @OA\Property(property="retry_after", type="integer", example=60)
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            // Log de falha de login
            AuditLog::logFailure(
                'login_failed',
                'Credenciais inválidas',
                null,
                ['email' => $request->email]
            );

            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        $tokenResult = $user->createToken('auth-token');

        // Log de sucesso de login
        AuditLog::logSuccess('login', $user->id, [
            'email' => $user->email,
        ]);

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'refresh_token' => null,
            'token_type' => 'Bearer',
            'expires_in' => config('passport.token_expiration.access_token', 15) * 60, // em segundos
            'user' => $user,
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Fazer logout",
     *     description="Revoga o token de acesso atual do usuário",
     *     tags={"Autenticação"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Logout realizado com sucesso",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Logout realizado com sucesso.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Não autenticado",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        /** @var \Laravel\Passport\Token $token */
        $token = $user->token();
        $token->revoke();

        // Log de logout
        AuditLog::logSuccess('logout', $user->id);

        return response()->json([
            'message' => 'Logout realizado com sucesso.',
        ], 200);
    }
}
