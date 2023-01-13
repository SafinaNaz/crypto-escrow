<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use Form;
use Illuminate\Http\Request;
use DataTables;
use Alert;

class ReviewsController extends Controller
{

    public function __construct()
    {
        //setTimeZone();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {

        if (!auth()->user()->can('View Reviews')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
                return redirect()->back();
        }
        $data = array();
        if ($request->ajax()) {
            $db_record = Review::with('product', 'reviewer');
            if(isset($request->user_id))
            {
                $db_record = $db_record->where('review_to', $request->user_id);
            }
            if(isset($request->filter_type)){
                switch($request->filter_type){
                    case '0':   // Seller to Buyer
                        // Review to Buyer
                        $db_record = $db_record->whereHas('review_to_user', function($query) use($request){
                            $query->where('user_type', 2);

                            // If the buyer is seleted
                            if(isset($request->buyer)){
                                if($request->buyer != 0 && $request->buyer != ''){
                                    $query->where('id', $request->buyer);
                                }
                            }
                        });

                        // Review from given Seller
                        if(isset($request->seller)){
                            if($request->seller != 0 && $request->seller != ''){
                                $db_record = $db_record->whereHas('reviewer', function($query) use($request){
                                    $query->where('id', $request->seller);
                                });
                            }
                        }
                        break;
                    case '1':   // Buyer to Seller
                        // Review to Seller
                        $db_record = $db_record->whereHas('review_to_user', function($query) use($request){
                            $query->where('user_type', 1);

                            // If the buyer is seleted
                            if(isset($request->seller)){
                                if($request->seller != 0 && $request->seller != ''){
                                    $query->where('id', $request->seller);
                                }
                            }
                        });

                        // Review from given Buyer
                        if(isset($request->buyer)){
                            if($request->buyer != 0 && $request->buyer != ''){
                                $db_record = $db_record->whereHas('reviewer', function($query) use($request){
                                    $query->where('id', $request->buyer);
                                });
                            }
                        }

                        break;
                    case '2':   // Buyer to Sub Admin
                        $db_record = $db_record->where('admin_review',1);
                        // Review from given Buyer
                        $db_record = $db_record->whereHas('reviewer', function($query) use($request){
                            if(isset($request->buyer)){
                                if($request->buyer != 0 && $request->buyer != ''){
                                    $query->where('id', $request->buyer);
                                }
                            }
                            $query->where('user_type', 2);
                        });
                        break;
                    case '3':   // Seller to Sub Admin
                        $db_record = $db_record->where('admin_review',1);
                        // Review from given Seller
                        $db_record = $db_record->whereHas('reviewer', function($query) use($request){
                            if(isset($request->seller)){
                                if($request->seller != 0 && $request->seller != ''){
                                    $query->where('id', $request->seller);
                                }
                            }
                            $query->where('user_type', 1);
                        });

                        break;
                }
            }
            $db_record = $db_record->orderByDesc('id');

            $datatable = DataTables::of($db_record);
            $datatable = $datatable->editColumn('photo', function ($row) {
                return '<img loading="lazy" style="height: 40px;width: 40px;" src="' . $row->reviewer->photo() . '" >';
            });
            $datatable = $datatable->addColumn('full_name', function ($row) {
                return $row->reviewer->full_name();
            });
            $datatable = $datatable->editColumn('review_to', function ($row) {
                if ($row->admin_review == 1)
                    return '<label class="badge badge-success">Admin</label>';
            
                elseif ($row->review_to_user->user_type == 1)
                    return $row->review_to_user->username.'<br><label class="badge badge-primary">Seller</label>';
 
                elseif ($row->review_to_user->user_type == 2)
                    return $row->review_to_user->username.'<br><label class="badge badge-warning">Buyer</label>';
            });
            $datatable = $datatable->addColumn('transaction_id', function ($row) {
                return $row->product->transaction_id;
            });
            $datatable = $datatable->editColumn('rating', function ($row) {
                return $row->rating . ' / 5';
            });
            $datatable = $datatable->addColumn('feedback', function ($row) {
                if(auth()->user()->can('View Feedback Review'))
                {
                    return $row->review;
                }else{
                    return '';
                }

            });
            $datatable = $datatable->addColumn('date', function ($row) {
                return date('M d, Y G:i A', strtotime($row->created_at));
            });
            $datatable->addColumn('action', function ($row) {
                $actions = '';

                $actions .= Form::open([
                    'method' => 'POST',
                    'url' => ['admin/reviews/update-status'],
                    'style' => 'display:table;margin-right:10px;',
                    'class' => 'float-sm-right',
                    'id' => 'statusForm' . $row->id
                ]);
                $actions .= Form::hidden('id', encode($row->id));
                $actions .= Form::select('is_active', [
                    '0' => 'Inactive',
                    '1' => 'Active',
                ], $row->is_active, ['class' => 'form-control', 'onchange' => '$(form).submit();','style' => 'width:120px']);

                $actions .= Form::close();

                return $actions;
            });
            $datatable = $datatable->rawColumns(['photo', 'full_name', 'transaction_id', 'rating', 'feedback', 'date', 'review_to', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        $data['user_id'] = isset($request->user_id) ? $request->user_id : 0;
        $data['ajax_url'] = isset($request->user_id) ? route('admin.reviews.index',['user_id'=>$request->user_id]) : route('admin.reviews.index');
        $data['buyers'] = User::where('user_type',2)->get();
        $data['sellers'] = User::where('user_type',1)->get();
        return view('admin.reviews.index')->with($data);
    }

    // Update Status
    public function update_status(Request $request)
    {
        if (!auth()->user()->can('Update Reviews Status')) {
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

            Review::whereId($id)->update($data);
            Alert::success('Success', 'status updated successfully!')->persistent('Close')->autoclose(5000);
        } else {
            Alert::error('Error', 'Error occured. Status not updated!')->persistent('Close')->autoclose(5000);
        }
        return redirect()->back();
    }
}
