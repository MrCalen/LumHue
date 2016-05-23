<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use View;
use Redirect;
use Hash;
use JWTAuth;
use App\User;
use Exception;
use URL;
use Input;
use Validator;
use HueMail;
use MongoHue;

class SignupController extends Controller
{
    public function signUpPage()
    {
        if (Auth::user()) {
            return Redirect::to('app');
        }

        return View::make('signup/signup', []);
    }

    public function signup(Request $request)
    {
        $rules = array(
            'name'                => 'required',
            'email'               => 'required|email|unique:users',
            'password'            => 'required|min:6|regex:/^.*(?=.{6,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/',
            'passwordVerificaton' => 'required|same:password'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();

            return Redirect::to('signup')
                ->withErrors($validator)
                ->withInput(Input::except('password', 'passwordVerificaton'));

        } else {
            $user = User::create($request->all());
            $user->password = Hash::make($request->input('password'));
            $validation_token = Hash::make(substr(str_shuffle(MD5(microtime())), 0, 16));
            $user->validation_token = $validation_token;
            $user->save();
            $validation_link = URL::to('signup/confirm') . '?token=' . $validation_token;
            HueMail::sendConfirmation($user->name, $user->email, $validation_link);

            return Redirect::to('login');
        }
    }

    public function confirm(Request $request)
    {
        $confirmation = $request->input('token');
        if (!$confirmation) {
            return Redirect::to('login')->with([
                'info' => 'Token is required',
            ]);
        }
        $user = User::where('validation_token', '=', $confirmation)->first();
        if (!$user) {
            return Redirect::to('login')->with([
                'info' => 'Token is invalid',
            ]);
        }
        $user->validation_token = null;
        $user->save();

        $prefToken = Hash::make(substr(str_shuffle(MD5(microtime())), 0, 16));

        MongoHue::insert('user_settings', [
            'user_id' => $user->id,
            'token' => $prefToken,
        ]);

        return Redirect::to('login')->with([
            'info' => 'Email Confirmed, please login.',
        ]);
    }

    public function reset(Request $request)
    {
        $credentials = $request->only(['email']);

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return Redirect::to('login')->with([
                'info' => 'Email does not exists.',
            ]);
        }

        $email = $credentials['email'];
        $resetToken = Hash::make(substr(str_shuffle(MD5(microtime())), 0, 16));
        $user = User::where('email', '=', $email)->first();
        if (!$user) {
            return Redirect::to('login')->with([
                'info' => 'Token invalid.',
            ]);
        }
        if ($user->reset_token) {
            return Redirect::to('login')->with([
                'info' => 'Password is already reset.',
            ]);
        }

        if ($user->validation_token) {
            return Redirect::to('login')->with([
                'info' => 'User is not confirmed.',
            ]);
        }

        $user->reset_token = $resetToken;
        $user->save();
        $resetLink = URL::to('/signup/reset/index') . '?token=' . $resetToken;
        HueMail::sendResetPassword($user->name, $user->email, $resetLink);

        return Redirect::to('login')->with([
            'info' => 'Email send.',
        ]);
    }

    public function resetPwd(Request $request)
    {
        $token = $request->input('token');
        if (!$token) {
            return Redirect::to('login')->with([
                'info' => 'Token is required',
            ]);
        }

        $user = User::where('reset_token', '=', $token)->first();
        if (!$user) {
            return Redirect::to('login')->with([
                'info' => 'Token is invalid',
            ]);
        }
        $user->save();

        return View::make('signup/reset', [
            'token' => $token,
        ]);
    }

    public function resetPasswordPost(Request $request)
    {
        $rules = array(
            'email'               => 'required|email',
            'password'            => 'required|min:6|regex:/^.*(?=.{6,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/',
            'passwordVerificaton' => 'required|same:password'
        );
        $token = $request->get('token');

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();

            return Redirect::back()
                ->withErrors($validator)
                ->withInput(Input::except('password', 'passwordVerificaton'));

        } else {
            $user = User::where('email', '=', $request->get('email'))
                        ->where('reset_token', '=', $token)
                        ->first();
            if (!$user) {
                return Redirect::to('login')->with([
                    'info' => 'Email or token does not exists',
                ]);
            }
            if ($user->validation_token != null) {
                return Redirect::to('login')->with([
                    'info' => 'User is not confirmed.',
                ]);
            }
            $user->password = Hash::make($request->input('password'));
            $user->reset_token = null;
            $user->validation_token = null;
            $user->save();
            return Redirect::to('login')->with([
                'info' => 'Password has been reset'
            ]);
        }
    }
}
