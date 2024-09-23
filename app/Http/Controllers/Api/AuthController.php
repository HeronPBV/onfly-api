<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('senha')])) {
            $token = $request->user()->createToken('userLogin')->plainTextToken;
            return $this->successResponse('Usuário logado com sucesso', ['Token' => $token]);
        }

        return $this->errorResponse('Dados de login incorretos', 403); //Forbidden
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse('Usuário deslogado com sucesso');
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'nome' => 'required|string|max:25',
            'email' => 'required|email|unique:users,email|max:40',
            'senha' => 'required|string|min:8',
        ]);

        $validated = $request->only(['nome', 'email', 'senha']);

        $user = User::create([
            'name' => $validated['nome'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['senha']),
        ]);

        Auth::login($user);

        return $this->successResponse('Usuário registrado e logado com sucesso', [
            'Token' => $user->createToken('userLogin')->plainTextToken
        ], 201); // Created
    }

}
