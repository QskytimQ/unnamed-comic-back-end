<?php

namespace App\Http\Controllers;

use Validator;
use JWTAuth;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;

class AuthController extends Controller
{
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function register(Request $request)
    {
        $data = $request->only('name', 'email', 'password');
        $validator = $this->registerValidator($data);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
        }

        $this->userRepo->create($data);

        return response()->json(['status' => 'success', 'message' => 'Register successfully.']);
    }

    public function auth(Request $request)
    {
        $data = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($data)) {
                return response()->json(['status' => 'error', 'message' => 'Invalid credentials.'], 401);
            }
        } catch (JWTException $e){
            return response()->json(['status' => 'error', 'message' => 'Count not create token.'], 500);
        }

        return response()->json(['status' => 'success', 'token' => $token]);
    }

    private function registerValidator(array $data)
    {
        return
            Validator::make($data, [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
            ]);
    }
}
