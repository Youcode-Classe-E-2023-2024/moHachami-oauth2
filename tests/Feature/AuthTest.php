<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_auth(): void
    {

        $user = User::factory(UserFactory::class)->make();
        $name = $user->name;
        $email = $user->email;
        $password = $user->password;
        $credentials = [
            "name" => $name,
            "email" => $email,
            "password" => $password,
        ];

        // register
        $response = $this->post('/api/auth/register', $credentials);
        $data = $response->json();
        $token = $data['data']['token'];

        // login
        $response = $this->post('/api/auth/login', $credentials)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ]);

        // logout
        $response = $this->post('/api/auth/logout')->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ]);

    }
}
