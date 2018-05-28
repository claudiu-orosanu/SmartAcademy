<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Notifications\UserRegistered;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Hash;
use Mail;
use Password;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'resetPassword', 'verifyUser']]);
    }

    /**
     * Registers a new user.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request)
    {
        // validate request
        $this->validate($request, [
            'firstName' => 'required|max:128',
            'lastName' => 'required|max:128',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:5|max:255',
        ]);

        $firstName = $request->input('firstName');
        $lastName = $request->input('lastName');
        $email = $request->input('email');
        $password = $request->input('password');
        $verification_code = str_random(30);

        // create user
        $user = User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => Hash::make($password),
            'verify_token' => $verification_code,
        ]);

        // assign 'student' role to new user
        $user->attachRole(Role::whereName('student')->first());

        // send verification mail
        $user->notify(new UserRegistered($user));

        return response([
            'message' => 'Thanks for signing up! Please check your email to complete your registration.'
        ]);
    }

    /**
     * Log the user in based on his credentials.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request)
    {

        // validate request
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required|min:5|max:255',
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['is_verified'] = 1;

        try {
            if (!$token = auth()->attempt($credentials)) {
                return response(['error' => 'Unauthorized'], 401);
            }

        } catch (JWTException $e) {
            return response([
                'error' => 'Could not create token!'
            ], 500);
        } catch (\Exception $e) {
            return response($e, 500);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => new UserResource(auth()->user()),
        ]);
    }

    /**
     * Recover Password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();
        if (!$user) {
            return response([
                'message' => 'A reset email has been sent! Please check your email.'
            ]);
        }
        try {
            Password::sendResetLink($request->only('email'), function (Message $message) {
                $message->subject('Your Password Reset Link');
            });
        } catch (\Exception $e) {
            //Return with error
            return response()->json(['success' => false, 'error' => $e->getMessage()], 401);
        }
        return response()->json([
            'success' => true, 'data' => ['message' => 'A reset email has been sent! Please check your email.']
        ]);
    }

    /**
     * Verifies user.
     *
     * @param $verification_code
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyUser($verification_code)
    {
        $user = User::where('verify_token', $verification_code)->first();

        if (!$user) {
            return response([
                'error' => 'Verification code is invalid.'
            ]);
        }

        if ($user->is_verified == 1) {
            return response([
                'message' => 'Account already verified.'
            ]);
        }

        $user->update([
            'is_verified' => 1,
            'verify_token' => null,
        ]);

        return redirect(getenv('APP_URL') . '/login');
    }
}
