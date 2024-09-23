<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Expense;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExpenseRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        if ($this->isMethod('post')) {

            return $this->user()->can('store', Expense::class);

        } elseif ($this->isMethod('put')) {

            $expense_id = $this->route('despesa');
            $expense = Expense::findOrFail($expense_id);
            return $this->user()->can('update', $expense);

        }

        throw new HttpException(405, 'Method Not Allowed');
    }

    public function prepareForValidation()
    {
        if (!$this->input('usuario')) {
            $this->merge(['usuario' => $this->user()->id]);
        }
    }

    public function rules(): array
    {
        return [
            'descricao' => 'required|string|min:3|max:191',
            'data' => 'required|date|before_or_equal:today',
            'usuario' => 'required|integer|gt:0',
            'valor' => 'required|decimal:0,2|gt:0'
        ];
    }

    public function withValidator(Validator $validator): void
    {
        if ($validator->errors()->count() > 0) {
            return;
        }

        $validator->after(function ($validator) {
            $user_id = $this->input('usuario');

            if (!$this->userExists($user_id)) {
                $validator->errors()->add(
                    'usuario',
                    'O usuário informado não está cadastrado.'
                );
            } elseif($user_id != $this->user()->id){
                $validator->errors()->add(
                    'usuario',
                    'Não é possível atualizar uma despesa trocando o usuário ou criar uma despesa para outro usuário.'
                );
            }


        });
    }

    public function userExists(int $user_id): bool
    {
        return User::where('id', $user_id)->exists();
    }

    public function messages(): array
    {
        return [
            'Data.before_or_equal' => 'A data deve ser anterior ou igual ao dia de hoje (' . now()->format('d-m-Y') . ')'
        ];
    }


}
