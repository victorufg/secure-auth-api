<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create Personal Access Client if it doesn't exist
        $existingClient = \DB::table('oauth_personal_access_clients')->first();
        
        if (!$existingClient) {
            $client = \Laravel\Passport\Client::create([
                'name' => 'Test Personal Access Client',
                'secret' => null,
                'provider' => 'users',
                'redirect_uris' => json_encode([]),
                'grant_types' => json_encode(['personal_access']),
                'revoked' => false,
            ]);
            
            \DB::table('oauth_personal_access_clients')->insert([
                'client_id' => $client->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
