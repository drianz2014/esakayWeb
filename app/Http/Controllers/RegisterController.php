<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    /**
     * Register API.
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|min:5',
            'password' => 'required|min:5',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError(
                config('messages.validation'),
                $validator->errors(),
                422
            );
        }

        $input = $request->only([
            'name',
            'email',
            'phone',
            'password',
        ]);

        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        $success = [
            'token' => $user->createToken('api')->plainTextToken,
            'name' => $user->name,
        ];

        return $this->sendResponse(
            $success,
            config('messages.success')
        );
    }

    /**
     * Login API.
     */
    public function login(Request $request): JsonResponse
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;


            return $this->sendResponse(
                $success,
                config('messages.loginSuccess')
            );
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }
}
