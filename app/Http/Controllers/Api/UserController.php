<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Obter dados do usuário autenticado",
     *     description="Retorna os dados do usuário atualmente autenticado",
     *     tags={"Usuário"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Dados do usuário",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="João Silva"),
     *             @OA\Property(property="email", type="string", example="joao@example.com"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-12-27T20:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-12-27T20:00:00.000000Z")
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
    public function me(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'message' => 'Usuário não autenticado.',
            ], 401);
        }

        return new UserResource($user);
    }
}
