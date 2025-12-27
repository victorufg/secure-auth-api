<?php

use App\Models\User;
use Laravel\Passport\Passport;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

test('authenticated user can logout', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);

    $response = postJson('/api/logout');

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Logout realizado com sucesso.',
        ]);
});

test('unauthenticated user cannot logout', function () {
    $response = postJson('/api/logout');

    $response->assertStatus(401);
});

test('authenticated user can access protected routes', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);

    $response = getJson('/api/me');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
            ],
        ]);
});

test('unauthenticated user cannot access protected routes', function () {
    $response = getJson('/api/me');

    $response->assertStatus(401);
});
