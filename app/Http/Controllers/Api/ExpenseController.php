<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Expense;
use App\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseResource;
use App\Http\Requests\ExpenseRequest;

class ExpenseController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $this->authorize('index', Expense::class);

        $expense = Expense::with('user')->get();
        return $this->successResponse('Lista de despesas', ExpenseResource::collection($expense));
    }

    public function store(ExpenseRequest $request)
    {
        $validData = $request->validated();

        $expense = Expense::create([
            'description' => $validData['descricao'],
            'date' => Carbon::parse($validData['data'])->format('Y-m-d'),
            'user_id' => $validData['usuario'],
            'value' => $validData['valor']
        ]);

        //$this->notifyUser($expense);

        return $this->successResponse('Despesa criada com sucesso', new ExpenseResource($expense), 201); //Created
    }

    public function show(int $id)
    {
        $expense = Expense::findOrFail($id);
        $this->authorize('show', $expense);
        return $this->successResponse('Detalhes da despesa', new ExpenseResource($expense));
    }

    public function update(ExpenseRequest $request, int $id)
    {
        $expense = Expense::findOrFail($id);
        $validData = $request->validated();

        $updated = $expense->update([
            'description' => $validData['descricao'],
            'date' => Carbon::parse($validData['data'])->format('Y-m-d'),
            'user_id' => $validData['usuario'],
            'value' => $validData['valor'],
        ]);

        if ($updated) {
            return $this->successResponse('Despesa atualizada com sucesso', new ExpenseResource($expense));
        }

        return $this->errorResponse('A despesa não foi atualizada', 500); //Internal Server Error
    }

    public function destroy(int $id)
    {
        $expense = Expense::findOrFail($id);
        $this->authorize('destroy', $expense);

        $deleted = $expense->delete();

        if ($deleted) {
            return $this->successResponse('Despesa deletada com sucesso!');
        }

        return $this->errorResponse('A despesa não foi deletada', 500); //Internal Server Error
    }

    // private function notifyUser(Expense $despesa)
    // {
    //     $user = User::find($despesa->user_id);
    //     $user->notify(new NewExpenseNotification($user, $despesa));
    // }

}
