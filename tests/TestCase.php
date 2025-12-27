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
            $this->artisan('passport:client', [
                '--personal' => true,
                '--name' => 'Test Personal Access Client',
            ]);
        }
    }
}
