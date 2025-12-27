<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

abstract class TestCase extends BaseTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->artisan('migrate');
        
        // Create Personal Access Client if it doesn't exist
        $existingClient = \DB::table('oauth_personal_access_clients')->first();
        
        if (!$existingClient) {
            $client = \Laravel\Passport\Client::create([
                'name' => 'Test Personal Access Client',
                'secret' => null,
                'provider' => 'users',
                'redirect_uris' => [],
                'grant_types' => ['personal_access'],
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
