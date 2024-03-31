<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }




    public function test_index_returns_all_users()
    {
        $admin = $this->authenticateUser('admin');

        $response = $this->actingAs($admin)->get('/api/users');

        $response->assertStatus(200);
    }



    public function test_store_creates_user()
    {
        $admin = $this->authenticateUser('admin');

        $userData = [
            'name' => 'user test',
            'email' => 'usertest@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->actingAs($admin)->post('/api/users', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }
}
