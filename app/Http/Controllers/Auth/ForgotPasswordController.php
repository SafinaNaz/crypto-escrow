<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Passwords\PasswordBroker;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    // use SendsPasswordResetEmails;

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm(Request $request)
    {
        // dd(session());
        return view('auth.passwords.username');
    }

    public function check_username(Request $request)
    {
        $input = $request->all();
        // print_r($input);
        if ($request->has('one_time_password') && $input['one_time_password'] <> '' && $input['username'] <> '') {

            $user = User::where('username', $input['username'])->first();

            if ($user) {
                try {
                    $google2fa = app('pragmarx.google2fa');

                    $valid = $google2fa->verifyGoogle2FA($user->google2fa_secret, $input['one_time_password']);
                    if ($valid) {
                        //Create Password Reset Token
                        $token = app(PasswordBroker::class)->createToken($user);
                        \DB::table('password_resets')->insert([
                            'email' => $input['username'],
                            'token' => $token,
                            'created_at' => Carbon::now()
                        ]);

                        $link = url('password/reset/' . $token . '?username=' . urlencode($user->username));

                        return redirect()->to($link);
                    } else {
                        $request->session()->flash('error', 'Please enter correct one time password.');
                        return redirect()->back();
                    }
                } catch (\Exception $e) {
                    $request->session()->flash('error', $e->getMessage());

                    return redirect()->back();
                }
            } else {
                $request->session()->flash('error', 'Username does not exists.');
                return redirect()->back();
            }
        } else {
            $request->session()->flash('error', 'Username does not exists.');
            return redirect()->back();
        }
    }
}
