<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Countries;
use Illuminate\Http\Request;
use Alert;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Form;
use Illuminate\Support\Facades\Validator;

class BuyersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [];
        if (!auth()->user()->can('View Buyers')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        if ($request->ajax()) {
            $data = User::where('user_type', 2)->get();
            $datatable = Datatables::of($data);
            $datatable->editColumn('name', function ($row) {
                $admin_name = $row->full_name();
                return $admin_name;
            });

            $datatable->addColumn('avg_rating', function ($row){

                return $row->avg_rating;
            });


            $datatable = $datatable->editColumn('status', function ($row) {
                if ($row->is_active == 1)
                    return '<label class="badge badge-success">Active</label>';
                elseif ($row->is_active == 0)
                    return '<label class="badge badge-warning">Inactive</label>';
                else
                    return '<label class="badge badge-danger">Deleted</label>';
            });
             $datatable->addColumn('created_at', function ($row){
                return date('M d, Y G:i A', strtotime($row->created_at));
            });

            $datatable->addColumn('last_login_on', function ($row){
                if(!is_null($row->last_login_on)) {
                    return date('M d, Y G:i A', strtotime($row->last_login_on));
                } else {
                    return '';
                }
            });


            $datatable->addColumn('action', function ($row) {
                $actions = '';

                $actions .= Form::open([
                    'method' => 'POST',
                    'url' => ['admin/buyers/update-status'],
                    'style' => 'display:table;margin-right:10px;',
                    'class' => 'float-sm-right',
                    'id' => 'statusForm' . $row->id
                ]);
                $actions .= Form::hidden('id', encode($row->id));
                $actions .= Form::select('is_active', [
                    '0' => 'Inactive',
                    '1' => 'Active',
                ], $row->is_active, ['class' => 'form-control', 'onchange' => '$(form).submit();']);
                $actions .= Form::close();

                $actions .= '&nbsp;<a class="btn btn-primary btn-icon" href="' . url("admin/buyers/" . encode($row->id) . '/edit') . '" title="Edit"><i class="fa fa-pencil-alt"></i></a>';
                $actions .= '&nbsp;<a class="btn btn-success btn-icon" target="_blank" href="'.route('admin.reviews.index', ['user_id'=>$row->id]).'" title="View All Feedback"><i class="fa fa-star"></i></a>';

                $actions .= '&nbsp;' . Form::open([
                    'method' => 'DELETE',
                    'url' => ['admin/buyers', encode($row->id)],
                    'style' => 'display:inline'
                ]);

                $actions .= Form::button('<i class="fa fa-trash fa-fw" title="Delete Buyer"></i>', ['class' => 'delete-form-btn btn btn-default btn-icon']);
                $actions .= Form::submit('Delete', ['class' => 'hidden deleteSubmit']);

                $actions .= Form::close();
                return $actions;
            });
              $datatable->addColumn('approve_etl', function ($row) {
                $actions = '';

                $actions .= Form::open([
                    'method' => 'POST',
                    'url' => ['admin/sellers/approve-etl'],
                    'style' => 'display:table;margin-right:10px;',
                    'class' => 'float-sm-right',
                    'id' => 'approveForm' . $row->id
                ]);
                $actions .= Form::hidden('id', encode($row->id));
                $actions .= Form::select('approved_status', [
                    '0' => 'Unverified',
                    '1' => 'Verified',
                ], $row->approved_status, ['class' => 'form-control', 'onchange' => '$(form).submit();']);
                $actions .= Form::close();
                return $actions;
            });



            $datatable = $datatable->rawColumns(['avg_rating','role', 'name', 'status', 'approved_status', 'action', 'approve_etl']);
            return $datatable->make(true);
        }

        return view('admin.buyers.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('Add Buyers')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        $data = [];
        $data['action'] = "Add";

        if (old()) {
            $data['seller'] = old();
        }

        return view('admin.buyers.form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();

        if ($input['action'] == 'Edit') {
            $user = User::findOrFail($input['id']);

            Validator::extend('valid_btc', function ($attribute, $value, $parameters, $validator) {
                return btc_address_validate($value);
            });
            Validator::extend('valid_xmr', function ($attribute, $value, $parameters, $validator) {
                return xmr_address_validate($value);
            });

            $this->validate($request, [

                'btc_address' => 'required|valid_btc|string|between:26,35',
                'monero_address' => 'required|valid_xmr|string:95'
            ], [
                'btc_address.valid_btc' => 'Please enter valid BTC address.',
                'monero_address.valid_xmr' => 'Please enter valid XMR address.',
            ]);

            if ($input['password'] <> "") {
                $input['password'] = Hash::make($input['password']);
            }

            $User = User::findOrFail($input['id']);

            $User->update($input);
            //EVENT LOG START
            \App\Events\UserEvents::dispatch('admin', 11, '', ['item_id' => $User->id]);
            Alert::success('Success', 'Buyer updated successfully.')->persistent('Close')->autoclose(5000);
        } else {
            Validator::extend('valid_btc', function ($attribute, $value, $parameters, $validator) {
                return btc_address_validate($value);
            });
            Validator::extend('valid_xmr', function ($attribute, $value, $parameters, $validator) {
                return xmr_address_validate($value);
            });
            $this->validate($request, [
                'username' => 'required|string|max:100|unique:users,username',
                'btc_address' => 'required|valid_btc|string|between:26,35',
                'monero_address' => 'required|valid_xmr|string:95'
            ], [
                'btc_address.valid_btc' => 'Please enter valid BTC address.',
                'monero_address.valid_xmr' => 'Please enter valid XMR address.',
            ]);

            $input['password'] = Hash::make($input['password']);

            $User = User::create($input);
            //EVENT LOG START
            \App\Events\UserEvents::dispatch('admin', 10, '', ['item_id' => $User->id]);

            Alert::success('Success', 'Buyer added successfully.')->persistent('Close')->autoclose(5000);
        }

        return redirect('admin/buyers');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('Update Buyers')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        $id = decode($id);
        $data['seller'] = User::findOrFail($id);
        $data['action'] = "Edit";
        return view('admin.buyers.form')->with($data);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('Delete Buyers')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        $id = decode($id);

        $data = array(
            'is_active' => 2,
        );

        User::whereId($id)->update($data);

        //EVENT LOG START
        \App\Events\UserEvents::dispatch('admin', 12, '', ['item_id' => $id]);
        Alert::success('Success', 'Buyer deleted successfully!')->persistent('Close')->autoclose(5000);
        return redirect()->back();
    }

    // Update Status
    public function update_status(Request $request)
    {
        if (!auth()->user()->can('Update Buyers')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        $input = $request->all();

        $is_active = $input['is_active'];
        $id = decode($input['id']);

        if ($is_active <> '' && $id <> '') {
            $data = array(
                'is_active' => $is_active,
            );

            User::whereId($id)->update($data);
            //EVENT LOG START
            \App\Events\UserEvents::dispatch('admin', 13, '', ['item_id' => $id]);
            Alert::success('Success', 'status updated successfully!')->persistent('Close')->autoclose(5000);
        } else {
            Alert::error('Error', 'Error occured. Status not updated!')->persistent('Close')->autoclose(5000);
        }
        return redirect()->back();
    }
     public function approve_etl(Request $request)
    {
        if (!auth()->user()->can('Approve ETL Sellers')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        $input = $request->all();

        $approved_status = $input['approved_status'];
        $id = decode($input['id']);

        if ($approved_status <> '' && $id <> '') {
            $data = array(
                'approved_status' => $approved_status,
            );

            User::whereId($id)->update($data);
            //EVENT LOG START
            \App\Events\UserEvents::dispatch('admin', 18, '', ['item_id' => $id]);
            Alert::success('Success', 'Verified updated successfully!')->persistent('Close')->autoclose(5000);
        } else {
            Alert::error('Error', 'Error occured. Verification status not updated!')->persistent('Close')->autoclose(5000);
        }
        return redirect()->back();
    }
}
