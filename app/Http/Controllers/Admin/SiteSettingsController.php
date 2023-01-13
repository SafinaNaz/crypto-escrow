<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSettings;
use Illuminate\Http\Request;
use Alert;
use Storage;
use Image;
use DB;
use Illuminate\Support\Facades\Validator;

class SiteSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $data['settings'] = SiteSettings::first();
        return view('admin.settings.settings', $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!auth()->user()->can('Update Site Settings')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        $input = $request->all();

        unset($input['_token']);

        if (!empty($request->files) && $request->hasFile('site_logo')) {

            $file      = $request->file('site_logo');
            $file_name = $file->getClientOriginalName();
            $type      = strtolower($file->getClientOriginalExtension());
            $real_path = $file->getRealPath();
            $size      = $file->getSize();
            $size_mbs  = ($size / 1024) / 1024;
            $mime_type = $file->getMimeType();

            if (in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'jfif', 'svg'])) {

                $file_temp_name = 'profile-' . time() . '.' . $type;

                $old = settingValue('site_logo');
                $old_file = public_path() . '/storage/uploads/images/'  . $old;

                if (file_exists($old_file) && !empty($old) && $old <> null) {
                    //delete previous file
                    unlink($old_file);
                }

                $path = public_path('storage/uploads/images');
                $file->move($path, $file_temp_name);

                $input['site_logo'] = $file_temp_name;
            }
        }

        if ($input['id'] <> '') {

            $Sites = SiteSettings::findOrFail($input['id']);
            $Sites->update($input);
            //EVENT LOG START
            \App\Events\UserEvents::dispatch('admin', 4, '', []);
            Alert::success('Success Message', 'Site settings updated successfully!')->persistent('Close')->autoclose(5000);
        } else {

            SiteSettings::create($input);
            //EVENT LOG START
            \App\Events\UserEvents::dispatch('admin', 4, '', []);
            Alert::success('Success Message', 'Site settings added successfully!')->persistent('Close')->autoclose(5000);
        }
        return redirect('admin/site-settings');
    }


    public function escrow_index()
    {
        $data = [];
        $data['settings'] = SiteSettings::select('id', 'escrow_fee_btc', 'escrow_fee_monero', 'btc_address', 'monero_address')->first();
        return view('admin.settings.escrow_settings', $data);
    }


    public function escrow_update(Request $request)
    {
        if (!auth()->user()->can('Update Escrow Settings')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        Validator::extend('valid_btc', function ($attribute, $value, $parameters, $validator) {
            return btc_address_validate($value);
        });
        Validator::extend('valid_xmr', function ($attribute, $value, $parameters, $validator) {
            return xmr_address_validate($value);
        });
        $request->validate([
            'escrow_fee_btc' => ['required', 'min:0', 'max:100', 'numeric'],
            'escrow_fee_monero' => ['required', 'min:0', 'max:100', 'numeric'],

            'btc_address' => 'required|valid_btc|string|between:26,35',
            'monero_address' => 'required|valid_xmr|string:95'
        ], [
            'btc_address.valid_btc' => 'Please enter valid BTC address.',
            'monero_address.valid_xmr' => 'Please enter valid XMR address.',
        ]);

        DB::beginTransaction();
        try {

            $model = SiteSettings::select('id', 'escrow_fee_btc', 'escrow_fee_monero', 'btc_address', 'monero_address')->find($request->get('id'));
            $model->fill($request->all());
            $model->save();
            DB::commit();
            //EVENT LOG START
            \App\Events\UserEvents::dispatch('admin', 5, '', []);
            Alert::success('Success Message', 'Escrow settings saved successfully!')->persistent('Close')->autoclose(5000);
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', $e->getMessage())->persistent('Close')->autoclose(5000);
            return redirect()->back()->withInput();
        }

        return redirect('admin/escrow-settings');
    }

    public function announcement_index()
    {
        $data = [];
        $data['settings'] = SiteSettings::select('id', 'site_announcement', 'show_site_announcement', 'seller_announcement', 'show_seller_announcement', 'buyer_announcement', 'show_buyer_announcement')->first();
        return view('admin.settings.announcement_settings', $data);
    }


    public function announcement_update(Request $request)
    {
        if (!auth()->user()->can('Update Announcement Settings')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }

        $request->validate([
            'site_announcement' => ['required'],
            'seller_announcement' => ['required'],
            'buyer_announcement' => ['required'],
        ]);

        DB::beginTransaction();
        try {

            $model = SiteSettings::select('id', 'site_announcement', 'show_site_announcement', 'seller_announcement', 'show_seller_announcement', 'buyer_announcement', 'show_buyer_announcement')->find($request->get('id'));
            $model->fill($request->all());
            $model->save();
            DB::commit();
            //EVENT LOG START
            \App\Events\UserEvents::dispatch('admin', 5, '', []);
            Alert::success('Success Message', 'Announcement settings saved successfully!')->persistent('Close')->autoclose(5000);
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', $e->getMessage())->persistent('Close')->autoclose(5000);
            return redirect()->back()->withInput();
        }

        return redirect('admin/announcement-settings');
    }
}
