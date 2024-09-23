<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $token;


    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');

        $this->user = $this->createUser('Jhon Wick', 'jhonwick@example.com', 'password123');
        $this->token = $this->user->createToken('userLogin')->plainTextToken;
    }

    protected function createUser(string $name, string $email, string $password): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);
    }

    protected function assertUnauthorized($method, $uri, $data = [])
    {
        $response = $this->$method($uri, $data);
        $response->assertStatus(401); // Unauthorized
    }




    public function test_user_can_register()
    {
        $response = $this->post('/api/registrar', [
            'nome' => 'Jane Doe',
            'email' => 'janedoe@example.com',
            'senha' => 'password123',
        ]);

        $response->assertStatus(201); // Created
        $this->assertArrayHasKey('Token', $response->json('data'));
    }


    public function test_user_can_login()
    {
        $response = $this->post('/api/login', [
            'email' => 'jhonwick@example.com',
            'senha' => 'password123',
        ]);

        $response->assertStatus(200); // OK
        $this->assertArrayHasKey('Token', $response->json('data'));
    }


    public function test_user_can_logout()
    {
        $responsePost = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->post('/api/logout');

        $responsePost->assertStatus(200); // OK
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $this->user->id,
            'tokenable_type' => 'App\Models\User',
            'token' => $this->token,
        ]);


        $responseGet = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get('/api/logout');

        $responseGet->assertStatus(200); // OK
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $this->user->id,
            'tokenable_type' => 'App\Models\User',
            'token' => $this->token,
        ]);
    }


    public function test_user_cannot_logout_without_authentication()
    {
        $responsePost = $this->post('/api/logout');
        $responsePost->assertStatus(401); // Unauthorized

        $responseGet = $this->get('/api/logout');
        $responseGet->assertStatus(401); // Unauthorized
    }

}
