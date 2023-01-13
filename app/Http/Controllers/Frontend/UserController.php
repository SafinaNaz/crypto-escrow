<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Countries;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['verified']);
    }

    public function index()
    {
        $data = [];
        $data['profile'] = auth()->user();

        return view('frontend.dashboard.profile')->with($data);
    }

    public function profile_update(Request $request)
    {
        $User =   auth()->user();

        Validator::extend('valid_btc', function ($attribute, $value, $parameters, $validator) {
            return btc_address_validate($value);
        });
        Validator::extend('valid_xmr', function ($attribute, $value, $parameters, $validator) {
            return xmr_address_validate($value);
        });

        $validation = $request->validate([
          
        ], [
            'btc_address.valid_btc' => 'Please enter valid BTC address.',
            'monero_address.valid_xmr' => 'Please enter valid XMR address.',
        ]);

        DB::beginTransaction();
        try {
            $input = $request->all();

            if ($request->password && $request->password_confirmation && $request->password == $request->password_confirmation) {
                $input['password'] = Hash::make($request->password);
                $input['remember_token'] = Str::random(60);
            }
            $User->update($input);

            DB::commit();

            $request->session()->flash('success', 'Profile updated successfully.');

            return redirect()->to('profile');
        } catch (\Exception $e) {
            DB::rollback();

            $request->session()->flash('error', $e->getMessage());

            return redirect()->back()->withErrors($validation)->withInput();
        }
    }

    public function change_password(Request $request)
    {
        $data = [];
        $User =   auth()->user();
        if ($request->all()) {

            $validation = $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);

            DB::beginTransaction();
            try {

                $User->update(['password' => Hash::make($request->password), 'remember_token' => Str::random(60)]);

                DB::commit();

                $request->session()->flash('success', 'Password updated successfully.');

                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();
                $request->session()->flash('error', $e->getMessage());
                $request->session()->flash('error', $e->getMessage());
                return redirect()->back()->withErrors($validation)->withInput();
            }
        }
        return view('frontend.dashboard.password')->with($data);
    }

    /**
     * authenticate_2fa
     *
     * @param  mixed $request
     * @return void
     */
    public function authenticate_2fa(Request $request)
    {
        $user = Auth::user();
        $input = $request->all();

        if ($request->has('one_time_password') && $input['one_time_password'] <> '' && $input['google2fa'] <> '') {

            try {
                $google2fa = app('pragmarx.google2fa');

                $valid = $google2fa->verifyGoogle2FA($input['google2fa'], $input['one_time_password']);
                if ($valid) {

                    $user->google2fa_secret = $request['google2fa'];
                    $user->save();
                    $request->session()->flash('success', '2FA setup successfully.');

                    return redirect()->to('profile');
                } else {
                    $request->session()->flash('error', 'Please scan QR again and enter one time password.');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                $request->session()->flash('error', $e->getMessage());

                return redirect()->back();
            }
        } else {
            $google2fa = app('pragmarx.google2fa');

            $google2fa_secret = $google2fa->generateSecretKey();

            // generate the QR image
            $QR_Image = $google2fa->getQRCodeInline(
                config('app.name'),
                $user->username,
                $google2fa_secret
            );

            // Pass the QR barcode image to our view.
            return view('frontend.dashboard.2fa_authenticate')->with([
                'QR_Image' => $QR_Image,
                'secret' => $google2fa_secret,
                'reauthenticating' => true
            ]);
        }
    }
}
