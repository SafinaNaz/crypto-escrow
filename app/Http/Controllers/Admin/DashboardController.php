<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    /**
     * Show Admin Dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];

        $data['admin_users'] = \App\Models\Admin::count();
        $data['cmspages'] = \App\Models\CmsPages::count();
        $data['sellers'] = \App\Models\User::count();
        $sellers =\App\Models\User::where('user_type', '=', 1)->get();
        $data['sellers'] = $sellers->count();
        $buyers =\App\Models\User::where('user_type', '=', 2)->get();
        $data['buyers'] = $buyers->count();
        $data['products'] = \App\Models\EscrowProducts::count();

        $data['latest_products'] = \App\Models\EscrowProducts::with(['seller', 'buyer', 'productCurrency', 'productTransaction'])
            //->whereRaw("UNIX_TIMESTAMP(completion_time) >= UNIX_TIMESTAMP()")
            ->limit(10)
            ->orderByDesc('id')
            ->get();
        return view('admin.dashboard.dashboard')->with($data);
    }
}
