<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\UserRepository;
use Auth;
use JWTAuth;
use Validator;

class AuthController extends Controller
{
    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
        // $this->middleware('jwt.auth', ['except' => ['register', 'auth']]);
        $this->middleware('jwt.auth', ['only' => 'reset']);
    }

    public function register(Request $request)
    {
        $data = $request->only('name', 'email', 'password', 'password_confirmation', 'from');

        if ($data['from'])
            $data['email'] = $data['email'].'@'.$data['from'];

        $validator = $this->validator($data);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);

        $user = $this->repo->create($data);

        return response()->json(['status' => 'success', 'user' => $user]);
    }

    public function auth(Request $request)
    {
        $credentials = $request->only('email', 'password', 'from');

        if ($credentials['from'])
            $credentials['email'] = $credentials['email'].'@'.$credentials['from'];

        if (! $token = JWTAuth::attempt($credentials))
            return response()->json(['status' => 'error', 'message' => 'Invalid Credentials'], 401);

        return response()->json(['status' => 'success', 'token' => $token]);
    }

    public function reset(Request $request)
    {

        $credentials = ['email' => Auth::user()->email, 'password' => $request->password];

        if (!Auth::attempt($credentials))
            return response()->json(['error' => 'Invalid Credentials'], 401);

        $data = $request->only('new_password', 'new_password_confirmation');

        $validator = $this->resetValidator($data);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);

        Auth::user()->forceFill([
            'password' => bcrypt($request->new_password),
        ])->save();

        $credentials['password'] = $request->new_password;
        // $token = JWTAuth::attempt($credentials);

        // return response()->json(['status' => 'success', 'user' => Auth::user(), 'token' => $token]);
        // return response()->json(['status' => 'success', 'user' => Auth::user()]);
        return response()->json(['status' => 'success', 'message' => 'Password Reset']);
    }

    private function validator(array $data)
    {
        $email_rule = $data['from'] ? '' : '|email';
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|max:255|unique:users'.$email_rule,
            'password' => 'required|min:6|confirmed',
        ]);
    }

    private function resetValidator(array $data)
    {
        return Validator::make($data, [
            'new_password' => 'required|min:6|confirmed',
        ]);
    }
}
