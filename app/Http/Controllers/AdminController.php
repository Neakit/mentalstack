<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{

    /**
     * @param Request $request
     * @return false|string
     */
    public function login(Request $request)
    {
        $messages = [
            'required' => 'Поле обязательно к заполнению',
            'email' => 'Поле Email должно быть валидным'
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|max:255|email',
            'password' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->messages(),
                ], 422);
        }

        $adminUser = [
            'email' => $request->email,
            'password' => $request->password,
            'role' => 100
        ];

        if (Auth::attempt($adminUser)) {
            return response()->json([
                'success' => true,
                'url' => '/admin/dashboard'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'errors' => ['field' => ['Пользователь не найден']]
            ], 401);
        }
    }
}
