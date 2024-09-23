<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Database\QueryException;
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


    public function test_it_hashes_the_password_when_creating_a_user()
    {
        $this->assertNotEquals('password123', $this->user->password);
        $this->assertTrue(password_verify('password123', $this->user->password));
    }


    public function test_user_email_must_be_unique()
    {
        $this->expectException(QueryException::class);
        $this->createUser('Another Jhon', 'jhonwick@example.com', 'anotherpassword');
    }
}
