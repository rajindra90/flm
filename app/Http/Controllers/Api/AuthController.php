<?php

namespace App\Http\Controllers\Api;

use App\Repositories\AuthRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    /**
     * @var AuthRepository
     */
    private $authRepo;

    public function __construct(AuthRepository $authRepo)
    {
        $this->authRepo = $authRepo;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|confirmed|min:6'
        ]);

        $data = $this->authRepo->create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'email_verified_token' => Str::random(40) . time(),
            'password' => Hash::make($request['password']),
        ]);

        if ($data) {
            return response()->json([
                'message' => 'User has been created Successfully.Please confirm your email.'
            ], 200);
        } else {
            return response()->json([
                'message' => $data
            ], 500);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Email or password is incorrect'
            ], 401);
        }
        $user = $request->user();

        if ($user->email_confirm == 0) {
            return response()->json([
                'message' => 'This email address is not yet confirm'
            ], 401);
        }

        if ($user->status != 1) {
            return response()->json([
                'message' => 'This user is no longer available'
            ], 401);
        }

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'name' => $user->first_name,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Logout User
     */
    public function logOut()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmEmail()
    {
        $token = Input::get('token', false);

        if ($this->authRepo->confirmEmail($token)) {
            return Redirect::to('/#/confirm');
        } else {
            return Redirect::to('/#/notconfirm');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email'
        ]);

        $data = $this->authRepo->resendEmail($request->email, [
            'email_verified_token' => Str::random(40) . time()
        ]);

        if ($data) {
            return response()->json([
                'message' => 'Confirmation email has been sent again.Please confirm your email',
                'email' => $request['email']
            ], 200);
        } else {
            return response()->json([
                'message' => $data
            ], 500);
        }

    }
}
