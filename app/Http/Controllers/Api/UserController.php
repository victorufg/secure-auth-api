<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function me(Request $request)
    {
        $user = $request->user();
        
        if(!$user) {
            return response()->json([
                'message' => 'Usuário não autenticado.'
            ], 401);
        }

        return new UserResource($user);
    }
}
