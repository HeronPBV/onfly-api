<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExpensesTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser('Test User', 'testuser@example.com', 'password123');
    }

    protected function createUser(string $name, string $email, string $password): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);
    }

    protected function authenticatedRequest($method, $uri, $data = [])
    {
        $token = $this->user->createToken('userLogin')->plainTextToken;

        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->$method($uri, $data);
    }

    protected function assertUnauthorized($method, $uri, $data = [])
    {
        $response = $this->$method($uri, $data);
        $response->assertStatus(401); // Unauthorized
    }





    public function test_expenses_index_should_return_ok_only_if_user_is_authenticated()
    {
        // Teste sem autenticação
        $this->assertUnauthorized('get', '/api/despesas');

        // Teste com autenticação
        $response = $this->authenticatedRequest('get', '/api/despesas');
        $response->assertStatus(200); // OK
    }


    public function test_expense_show_should_return_ok_only_if_user_is_authenticated()
    {
        $expense = $this->user->expenses()->create([
            'description' => 'Expense test',
            'date' => now()->toDateString(),
            'user_id' => $this->user->id,
            'value' => 100.00,
        ]);
    
        // Teste sem autenticação
        $this->assertUnauthorized('get', "/api/despesas/{$expense->id}");

        // Teste com autenticação
        $response = $this->authenticatedRequest('get', "/api/despesas/{$expense->id}");
        $response->assertStatus(200); // OK
    }


    public function test_expense_store_should_only_permit_if_user_is_authenticated()
    {
        // Teste sem autenticação
        $this->assertUnauthorized('post', '/api/despesas', [
            'descricao' => 'Expense test',
            'data' => now()->toDateString(),
            'usuario' => 1,
            'valor' => 100.00,
        ]);

        // Teste com autenticação
        $response = $this->authenticatedRequest('post', '/api/despesas', [
            'descricao' => 'Expense test',
            'data' => now()->toDateString(),
            'usuario' => $this->user->id,
            'valor' => 100.00,
        ]);

        $response->assertStatus(201); // Created
    }

    public function test_expense_update_should_only_permit_if_user_is_authenticated()
    {
        $expense = $this->user->expenses()->create([
            'description' => 'Expense test',
            'date' => now()->toDateString(),
            'user_id' => $this->user->id,
            'value' => 100.00,
        ]);

        // Teste sem autenticação
        $this->assertUnauthorized('put', "/api/despesas/{$expense->id}", [
            'descricao' => 'Updated Expense',
            'data' => now()->toDateString(),
            'usuario' => 1,
            'valor' => 150.00,
        ]);

        // Teste com autenticação
        $response = $this->authenticatedRequest('put', "/api/despesas/{$expense->id}", [
            'descricao' => 'Updated Expense',
            'data' => now()->toDateString(),
            'usuario' => $this->user->id,
            'valor' => 150.00,
        ]);
        $response->assertStatus(200); // OK
    }

    public function test_user_can_destroy_expense_only_if_is_authenticated()
    {
        $expense = $this->user->expenses()->create([
            'description' => 'Expense test',
            'date' => now()->toDateString(),
            'user_id' => $this->user->id,
            'value' => 100.00,
        ]);

        // Teste sem autenticação
        $this->assertUnauthorized('delete', "/api/despesas/{$expense->id}");

        // Teste com autenticação
        $response = $this->authenticatedRequest('delete', "/api/despesas/{$expense->id}");
        $response->assertStatus(200); // OK

        $this->assertSoftDeleted('expenses', [
            'id' => $expense->id,
        ]);
    }

}
