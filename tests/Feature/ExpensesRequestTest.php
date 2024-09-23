<?php

namespace Tests\Feature;

use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExpensesRequestTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_authorization_for_authenticated_user()
    {
        $request = new ExpenseRequest();
        $request->setMethod('POST');
        $request->setUserResolver(fn() => $this->user);

        $this->assertTrue($request->authorize());
    }

    public function test_valid_data_passes_validation()
    {
        $response = $this->post('/api/despesas', [
            'descricao' => 'Teste Despesa',
            'data' => now()->toDateString(),
            'usuario' => $this->user->id,
            'valor' => 100.00,
        ]);

        $response->assertStatus(201); // Created
    }


    public function test_validation_fails_with_missing_fields()
    {
        $data = [
            'descricao' => '',
            'data' => '',
            'usuario' => '',
            'valor' => '',
        ];

        $response = $this->post('/api/despesas', $data);

        $response->assertStatus(422); //Unprocessable Content
    }

    public function test_validation_fails_with_invalid_usuario()
    {
        $maxUserId = User::max('id');

        $data = [
            'descricao' => 'Teste Despesa',
            'data' => now()->toDateString(),
            'usuario' => ($maxUserId + 1), // Um ID de usuário que não existe
            'valor' => 100.00,
        ];

        $response = $this->post('/api/despesas', $data);

        $response->assertStatus(422); //Unprocessable Content
    }

    public function test_validation_fails_with_future_date()
    {
        $data = [
            'descricao' => 'Teste',
            'data' => now()->addDay()->toDateString(), // Data inválida
            'usuario' => $this->user->id,
            'valor' => 100.00,
        ];

        $response = $this->post('/api/despesas', $data);

        $response->assertStatus(422); //Unprocessable Content
    }

    public function test_user_cannot_change_user_of_expense_on_update()
    {

        $expense = Expense::create([
            'description' => 'Despesa Teste',
            'date' => now()->toDateString(),
            'user_id' => $this->user->id,
            'value' => 100.00,
        ]);

        $newUser = User::factory()->create();

        $data = [
            'descricao' => 'Despesa Atualizada',
            'data' => now()->toDateString(),
            'usuario' => $newUser->id,
            'valor' => 150.00,
        ];

        $response = $this->put("/api/despesas/{$expense->id}", $data);

        $response->assertStatus(422); // Unprocessable Entity
    }


}
