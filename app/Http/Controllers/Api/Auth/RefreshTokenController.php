<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RefreshTokenController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/refresh",
     *     summary="Renovar token de acesso",
     *     description="Gera um novo token de acesso usando o refresh token",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"refresh_token"},
     *             @OA\Property(property="refresh_token", type="string", example="def50200...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token renovado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1Qi..."),
     *             @OA\Property(property="refresh_token", type="string", example="def50200..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=900)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Refresh token inválido ou expirado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Refresh token inválido ou expirado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
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
    public function refresh(Request $request): JsonResponse
    {
        $request->validate([
            'refresh_token' => ['required', 'string'],
        ]);

        try {
            // Fazer requisição para o endpoint OAuth do Passport
            $response = Http::asForm()->post(url('/oauth/token'), [
                'grant_type' => 'refresh_token',
                'refresh_token' => $request->refresh_token,
                'client_id' => config('passport.personal_access_client.id'),
                'client_secret' => config('passport.personal_access_client.secret'),
                'scope' => '',
            ]);

            if ($response->failed()) {
                return response()->json([
                    'message' => 'Refresh token inválido ou expirado.',
                ], 401);
            }

            $data = $response->json();

            return response()->json([
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'],
                'token_type' => 'Bearer',
                'expires_in' => $data['expires_in'],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao renovar token.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
