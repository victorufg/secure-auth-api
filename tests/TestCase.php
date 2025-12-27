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
        if (!\Laravel\Passport\Client::where('personal_access_client', true)->exists()) {
            $client = \Laravel\Passport\Client::create([
                'name' => 'Test Personal Access Client',
                'secret' => null,
                'provider' => 'users',
                'redirect' => 'http://localhost',
                'personal_access_client' => true,
                'password_client' => false,
                'revoked' => false,
            ]);
            
            \Laravel\Passport\PersonalAccessClient::create([
                'client_id' => $client->id,
            ]);
        }
    }
}
