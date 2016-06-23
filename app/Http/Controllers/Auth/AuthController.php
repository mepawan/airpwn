<?php

namespace App\Http\Controllers\Auth;


use Auth;
use App\User;
use Validator;
use Illuminate\Http\Request;
use Hash;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use GuzzleHttp;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Firebase\JWT\JWT;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }


    protected function createToken($user)
    {
        $payload = [
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + (2 * 7 * 24 * 60 * 60)
        ];
        return JWT::encode($payload, \Config::get('app.token_secret'));
    }

    /**
      * Log in with Email and Password.
     */
     public function login(Request $request)
     {
        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::where('email', '=', $email)->first();
        if (!$user)
        {
            return response()->json(['message' => 'Wrong email and/or password'], 401);
        }
        if (Hash::check($password, $user->password))
        {
            unset($user->password);
            Auth::loginUsingId($user->id);
            return response()->json(['token' => $this->createToken($user)]);
        }
        else
        {
            return response()->json(['message' => 'Wrong password'], 401);
        }
    }
    /**
     * Create Email and Password Account.
     */
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 400);
        }
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json(['token' => $this->createToken($user)]);
    }

    //  log out
    public function getLogout()
    {
        Auth::logout();
    }

    public function facebook(Request $request)
    {
        $accessTokenUrl = 'https://graph.facebook.com/v2.3/oauth/access_token';
        $graphApiUrl = 'https://graph.facebook.com/v2.3/me';
        $params = [
            'code' => $request->input('code'),
            'client_id' => $request->input('clientId'),
            'redirect_uri' => $request->input('redirectUri'),
            'client_secret' => \Config::get('app.facebook_secret')
        ];
        $client = new GuzzleHttp\Client();
        // Step 1. Exchange authorization code for access token.
        $accessToken = $client->get($accessTokenUrl, ['query' => $params])->json();
        // Step 2. Retrieve profile information about the current user.
        $profile = $client->get($graphApiUrl, ['query' => $accessToken])->json();
        // Step 3a. If user is already signed in then link accounts.
        if ($request->header('Authorization'))
        {
            $user = User::where('facebook', '=', $profile['id'])->first();
            if ($user)
            {
                return response()->json(['message' => 'There is already a Facebook account that belongs to you'], 409);
            } else {
                $token = explode(' ', $request->header('Authorization'))[1];
                $payload = (array) JWT::decode($token, \Config::get('app.token_secret'), array('HS256'));
                $user = User::find($payload['sub']);
                $user->facebook = $profile['id'];
                $user->name = $user->name || $profile['name'];
                $user->save();
                return response()->json(['token' => $this->createToken($user)]);
            }
        }
        // Step 3b. Create a new user account or return an existing one.
        else
        {
            $user = User::where('facebook', '=', $profile['id'])->first();
            if ($user)
            {
                Auth::loginUsingId($user->id);
                return response()->json(['token' => $this->createToken($user->first())]);
            } else {
                $user = new User;
                $user->facebook = $profile['id'];
                $user->name = $profile['name'];
                $user->avatar = 'http://graph.facebook.com/' . $profile['id'] . '/picture?type=large';
                $user->save();
                Auth::loginUsingId($user->id);
                return response()->json(['token' => $this->createToken($user)]);
            }
        }
    }
}
