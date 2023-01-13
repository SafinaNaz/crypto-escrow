<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EscrowProducts;
use DataTables;
use DB;
use Alert;
use App\Jobs\DisputeProcessCheck;
use App\Models\Transaction;
use App\Models\MessageThreads;
use Carbon\Carbon;

class EscrowsController extends Controller
{
    use \App\Traits\SendMessageTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * index
     *
     * @param  mixed $request
     * @return void
     */
    public function index(Request $request)
    {

        if (!auth()->user()->can('View Escrow Products')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
            return redirect()->back();
        }
        $data = [];
        $lev2 = auth()->user()->can('Dispute Level 2') ? 1 : 0;

        $lev3 = auth()->user()->can('Dispute Level 3') ? 1 : 0;
        if ($request->ajax()) {
            $value = '';
            if ($lev2 == 1) {
                $value = 3;
            }
            if ($lev3 == 1) {
                 $value = 2;
            }
            if($lev2 == 1 && $lev3 == 1) {
                $value = '';
            }
            // \DB::enableQueryLog();
            if(!empty($value)) {
            $data =  EscrowProducts::selectRaw('transactions.*, escrow_products.*')->leftJoin('transactions', function($join) {
                $join->on('escrow_products.id', '=', 'transactions.product_id');
            })->whereNotIn('transactions.product_id', function($query) use($value) {
                $query->select('product_id')->from('dispute_transaction')

                ->where('level','=', $value);
            })
            ->orderBy('transactions.id', 'desc')
            ->get();
            } else {
                $data =EscrowProducts::with(['seller', 'buyer', 'productCurrency', 'productTransaction','disputeTransaction'])
                //->whereRaw("UNIX_TIMESTAMP(completion_time) >= UNIX_TIMESTAMP()")

                ->orderByDesc('id')
                ->get();
            }
            // dd(\DB::getQueryLog());



            $datatable = Datatables::of($data);

            $datatable->editColumn('id', function ($row) {
                return encode($row->id);
            });

            $datatable->editColumn('transaction_id', function ($row) {
                return $row->transaction_id;
            });

            $datatable->editColumn('currency', function ($row) {
                $currency = '<label class="badge badge-success">' . $row->productCurrency->currency . '</label>';
                return $currency;
            });

            $datatable->editColumn('transaction_status', function ($row) {
                $status = '';
                if (isset($row->productTransaction->transactionStatus->status)) {
                    $status = $row->productTransaction->transactionStatus->status;
                } else {
                     if (isset($row->productTransaction->status_id) && $row->productTransaction->status_id == 1) {

                        $status = 'Pending';
                     }
                     if (isset($row->productTransaction->status_id) && $row->productTransaction->status_id == 7) {

                        $status = 'Completed';
                     }


                }
                $level = '';
                // if ($row->disputeTransaction->level) {
                //     $level = ' <label class="badge badge-info">Level : ' . $row->disputeTransaction->level . '</label>';
                // }
                return '<label class="badge badge-danger">' . $status . '</label>' . $level;
            });

            $datatable->editColumn('status', function ($row) {
                if (isset($row->productTransaction->status_id) && $row->productTransaction->status_id == 9) {

                    $status = '<label class="badge badge-info">Dispute Finished</label>';
                } else {
                    if ($row->status == 0) {
                        $status = '<label class="badge badge-warning">Pending for Approval</label>';
                    } elseif ($row->status == 2) {
                        $status = '<label class="badge badge-danger">In-Dispute</label>';
                    } elseif ($row->status == 1) {
                        $status = '<label class="badge badge-success">Buyer Approved</label>';
                    }
                }
                if ($row->pocRequest <> null) {
                    if ($row->pocRequest->status == 0) {
                        $status .= '<label class="badge badge-info">POC Requested to Seller</label>';
                    } else {
                        $status .= '<label class="badge badge-primary">POC Responded By Seller</label>';
                    }
                }
                return $status;
            });

            $datatable->editColumn('amount', function ($row) {

                $currency = $row->productCurrency->currency;

                $price = $row->price;
                $comm = $row->commission;
                if ($row->escrow_fee_payer == 1) {
                    $total = number_format($price + (($price * $comm) / 100), 2, '.', '');
                } elseif ($row->escrow_fee_payer == 2) {
                    $total = number_format($price + (($price * $comm) / 100), 2, '.', '');
                } elseif ($row->escrow_fee_payer == 3) {
                    $total = number_format($price + (($price * $comm) / 100), 2, '.', '');
                }
                return  $total . ' ' . $currency;
            });

            $datatable->editColumn('customer', function ($row) {
                if ($row->buyer) {
                    return $row->buyer->full_name();
                } else {
                    return '';
                }
            });
            $datatable->editColumn('completion_days', function ($row) {
                return $row->completion_days();
            });
            $datatable->editColumn('seller', function ($row) {
                if ($row->seller) {
                    return $row->seller->full_name();
                } else {
                    return '';
                }
            });

            $datatable->editColumn('escrow_fee_payer', function ($row) {
                if ($row->escrow_fee_payer == 1) {
                    $p = 'Buyer';
                } elseif ($row->escrow_fee_payer == 2) {
                    $p = 'Seller';
                } elseif ($row->escrow_fee_payer == 3) {
                    $p = '50% Buyer & 50% Seller';
                }
                return $p;
            });

            $datatable->addColumn('action', function ($row) {
                $actions = '';
                $actions .= '&nbsp;<a class="btn btn-primary btn-icon" href="' . url("admin/messages/" . encode($row->id) . '/view') . '" title="View Messages"><i class="fa fa-envelope"></i></a>';

                $actions .= '&nbsp;<a class="btn btn-success btn-icon" href="' . url("admin/transaction-status/" . encode($row->id) . '/detail') . '" title="View Detail"><i class="fa fa-eye"></i></a>';


                return $actions;
            });

            $datatable = $datatable->rawColumns(['id', 'transaction_id', 'currency', 'status', 'amount', 'seller', 'customer', 'escrow_fee_payer', 'action', 'transaction_status', 'completion_days']);

            return $datatable->make(true);
        }



        return view('admin.escrows.index');
    }

    /**
     * transaction_status
     *
     * @param  mixed $id
     * @param  mixed $type
     * @return void
     */
    public function transaction_status($id, $type)
    {
        if (!auth()->user()->can('Update Transaction Status')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
            return redirect()->back();
        }
        $data = [];

        $id = decode($id);

        if ($id) {
            $data['product'] = $product = EscrowProducts::with(['seller', 'buyer', 'productCurrency', 'productTransaction', 'threads'])->where('id', $id)->first();

            if (!$data['product']) {
                Alert::error('Error', 'You are not allowed to view this Transaction or transaction does not exist.')->persistent('Close')->autoclose(5000);
                return redirect()->back();
            }
        }

        if ($type == 'reject') {
            $data['status'] = 'reject';
        } else if ($type == 'cancel') {
            $data['status'] = 'cancel';
        } else if ($type == 'transfer') {
            $data['status'] = 'transfer';
        } else if ($type == 'complete') {
            $data['status'] = 'complete';
        } else {
            $data['status'] = 'detail';
        }
        $data['dispute_transaction'] = \DB::table('dispute_transaction')->where('product_id', $id)->first();
        $data['current_status'] = $product->productTransaction->status_id;

        return view('admin.escrows.transaction_status', $data);
    }

    /**
     * update_transaction_status
     *
     * @param  mixed $id
     * @param  mixed $type
     * @param  mixed $request
     * @return void
     */
    public function update_transaction_status($id, $type, Request $request)
    {
        if (!auth()->user()->can('Update Transaction Status')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
            return redirect()->back();
        }
        $data = [];
        $input = $request->all();
        $user = auth()->user();
        $id = decode($id);
        if ($id) {
            $data['product'] = $product = EscrowProducts::with(['seller', 'buyer', 'productCurrency', 'productTransaction', 'pocRequest'])->where('id', $id)->first();

            if (!$data['product']) {
                Alert::error('Error', 'You are not allowed to view this Transaction or transaction does not exist.')->persistent('Close')->autoclose(5000);
                return redirect()->back();
            }
        }

        $link = url('/transaction-detail/' . encode($product->id) . '/detail');
        if ($type == 'reject') {
            $message = 'Transaction Rejected successfully.';
            $status = 4;
        } else if ($type == 'cancel') {
            $message = 'Transaction Cancel status updated successfully.';
            $status = 5;
        } else if ($type == 'transfer') {

            if(isset($input['admin_sender_wallet_address']) && 
            !empty($input['admin_sender_wallet_address']) && 
            isset($input['admin_receiver_wallet_address']) && 
            !empty($input['admin_receiver_wallet_address'])) {
                $validation = $request->validate([
                    'admin_sender_wallet_address' => ['required'],
                    'admin_receiver_wallet_address' => ['required'],
                    'message' => ['required', 'string']
                ]);
            } else {
                $validation = $request->validate([
               'admin_reference_no' => ['required', 'string', 'unique:transactions'],
                'message' => ['required', 'string']
                ]);
            }

            

            DB::beginTransaction();
            try {
                $update_status_value = '';
                if(isset($input['admin_sender_wallet_address']) && 
                    !empty($input['admin_sender_wallet_address']) && 
                    isset($input['admin_receiver_wallet_address']) && 
                    !empty($input['admin_receiver_wallet_address']) && 
                    empty($input['admin_reference_no'])) {
                    $trans_msg = \DB::table('transaction_messages');
                    $trans_msg->where('product_id', $id);
                    $trans_msg->where('admin_id', auth()->user()->id);
                    $trans_result = $trans_msg->first();
                    if(!empty($trans_result)) {
                        $affected_msg = DB::table('transaction_messages');
                        $affected_msg->where('product_id', $id);
                        $affected_msg->where('transaction_type', 0);
                        $affected_msg->where('admin_id', auth()->user()->id);
                        $affected_msg->update(['message' => $input['message']]);

                    } else {
                        $userv = [];
                        \DB::table('transaction_messages')->insert([
                            'product_id' => $id,
                            'admin_id' =>  auth()->user()->id,
                            'message' => $input['message'],
                        ]);
                        
                    }
                    
                     Alert::success('Success', 'Payment Status updated successfully.')->persistent('Close')->autoclose(5000);
                    
                } else {
                    $update_status_value = [
                        'admin_reference_no' => $input['admin_reference_no'], 
                        'status_id' => 6
                    ];
                     Alert::success('Success', 'Escrowed Amount Transfered to seller successfully.')->persistent('Close')->autoclose(5000);
                     Transaction::whereId($input['id'])->update($update_status_value);
                }


                
                //EVENT LOG START
                $meta = ['product_id' => $product->id];
                \App\Events\UserEvents::dispatch('admin', 32, '', $meta, $product->seller_id);

                $transaction = Transaction::whereId($input['id'])->first();

                DB::commit();
               
                return redirect()->to('admin/escrows');
            } catch (\Exception $e) {
                DB::rollback();
                Alert::error('Error', $e->getMessage())->persistent('Close')->autoclose(5000);

                return redirect()->back()->withErrors($validation)->withInput();
            }

            Alert::success('Success', 'Transaction Transfer status updated successfully.')->persistent('Close')->autoclose(5000);
            return redirect()->back();
        }

        if ($type == 'reject' || $type == 'cancel') {

            $validation = $request->validate([
                'message' => ['required', 'string']
            ]);

            DB::beginTransaction();
            try {

                Transaction::whereId($input['id'])->update(['status_id' => $status, 'cancelled_by' => 1]);



                /**
                 * SEND MESSAGE
                 */
                $thread_id = $product->threads->id;
                $msg = [
                    'thread_id' => $thread_id,
                    'sender_id' => $user->id,
                    'receiver_id' => $product->seller_id,
                    'message' =>   $input['message'],
                    'is_private' => 0,
                    'is_admin' => 1,
                    'is_dispute' => 0
                ];
                $response = $this->sendMessageFromTrait($msg);

                if ($type == 'reject') {
                    //EVENT LOG START
                    $meta = ['item_id' => $product->id, 'thread_id' => $product->threads->id, 'message_id' => $response['id']];
                    \App\Events\UserEvents::dispatch('admin', 30, $response['message'], $meta, $product->buyer_id);
                } elseif ($type == 'cancel') {
                    //EVENT LOG START
                    $meta = ['item_id' => $product->id, 'thread_id' => $product->threads->id, 'message_id' => $response['id']];
                    \App\Events\UserEvents::dispatch('admin', 31, $response['message'], $meta, $product->buyer_id);
                }

                DB::commit();
                Alert::success('Success', $message)->persistent('Close')->autoclose(5000);
                return redirect()->to('escrows');
            } catch (\Exception $e) {
                DB::rollback();
                Alert::error('Error', $e->getMessage())->persistent('Close')->autoclose(5000);

                return redirect()->back()->withErrors($validation)->withInput();
            }
        }
    }

    public function dispute_history(Request $request, $id, $type = '')
    {
        if (!auth()->user()->can('View Dispute History')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
            return redirect()->back();
        }


        $data = [];
        $id = decode($id);
        if ($id) {
            $data['product'] = $product = EscrowProducts::with(['seller', 'buyer', 'productCurrency', 'productTransaction'])
                ->where('id', $id)->first();
            if (!$data['product']) {
                $request->session()->flash('error', 'You are not allowed to view this Transaction or transaction does not exist.');
                return redirect()->back();
            }
        }
        $data['current_status'] = $product->productTransaction->status_id;
        $data['current_thread'] = $current_thread  = MessageThreads::with(['messages' => function ($q) {

        }])
            ->where('product_id', $id)
            ->orderByDesc('id')
            ->first();
        $data['thread_id'] = $current_thread->id;


        $data['lastSender'] = $current_thread->messages->first()->sender->full_name();
        $data['lastSenderImg'] = $current_thread->messages->first()->sender->photo();
        $data['lastMsg'] = $current_thread->messages->first()->message;
        $data['type'] = $type;
        $data['dispute_transaction'] = $dispute_transaction = \DB::table('dispute_transaction')->where('product_id', $id)->get();

        /**
         * SUPER ADMIN level 2 check
         */
        if (@$dispute_transaction[0]->level == 2 && !auth()->user()->can('Dispute Level 2')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
            return redirect()->back();
        }

        return view('admin.escrows.dispute_history', $data);
    }

    public function send_dispute_message(Request $request)
    {
        if (!auth()->user()->can('Send Dispute Message')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
            return redirect()->back();
        }
        $input = $request->all();
        // dd($input);
        if ($input && $input['type'] == '') {

            $user = auth()->user();

            $input['is_admin'] = 1;
            $input['sender_id'] = $user->id;
            $input['is_private'] = 0;
            $input['is_dispute'] = 1;
            $response = $this->sendMessageFromTrait($input);

            $product_id = decode($input['id']);
            $data['product'] = $product = EscrowProducts::with(['seller', 'buyer', 'productCurrency', 'productTransaction', 'pocRequest'])->where('id', $product_id)->first();

            if (isset($input['winner']) && $input['winner'] <> '') {
                $userType = ($input['winner'] == $input['buyer_id']) ? 'Buyer' : 'Seller';
                \DB::table('dispute_transaction')->where('product_id', $product_id)->update(['winner_id' => $input['winner'], 'updated_at' => date('Y-m-d H:i:s'), 'level' => 2, 'offer_expire_time' => Carbon::now()->addHours(settingValue('level3_time'))->format('Y-m-d H:i:s')]);
                $jobData = ['product_id' => $product_id, 'level' => 3];
                $seconds = (settingValue('level3_time') * 3600);
                DisputeProcessCheck::dispatch($jobData)->delay(Carbon::now()->addSeconds($seconds));

                $meta = ['item_id' => $product_id];
                $message = auth()->user()->firstname . ' ' . auth()->user()->lastname . ' marked ' . $userType . ' as winner';
                \App\Events\UserEvents::dispatch('admin', 33, $message, $meta);

                /**
                 * REVIEW
                 */
                $link = url('/admin-review/' . encode($product_id));
                $msg = [
                    'thread_id' => $input['thread_id'],
                    'sender_id' => $user->id,
                    'receiver_id' => $input['receiver_id'],
                    'message' =>   'Please Review Admin against its Escalate decision. link is shown below' . "\n\n" . '<a href="' . $link . '">' . $link . '</a>',
                    'is_private' => 0,
                    'is_admin' => 1,
                    'is_dispute' => 1
                ];
                $this->sendMessageFromTrait($msg);
                $trans_msg = \DB::table('transaction_messages');
                $trans_msg->where('product_id', $product_id);
                $trans_msg->where('transaction_type', 1);
                $trans_msg->where('admin_id',  auth()->user()->id);
                $trans_result = $trans_msg->first();
                if(!empty($trans_result)) {
                    $affected_msg = DB::table('transaction_messages');
                    $affected_msg->where('product_id', $product_id);
                    $affected_msg->where('transaction_type', 1);
                    $affected_msg->update(['message' => $input['message']]);
                } else {
                    $userv = [];
                    \DB::table('transaction_messages')->insert([
                        'product_id' => $product_id,
                        'transaction_type' => 1,
                        'admin_id' =>  auth()->user()->id,
                        'message' => $input['message'],
                    ]);   
                }

            }


            //EVENT LOG START
            $meta = ['item_id' => $product_id, 'thread_id' => $input['thread_id'], 'message_id' => $response['id']];
            \App\Events\UserEvents::dispatch('admin', 33, $response['message'], $meta);

            if ($response['status'] == 0) {
                Alert::error('Error', $response['message'])->persistent('Close')->autoclose(5000);
            } else {
                Alert::success('Success', $response['message'])->persistent('Close')->autoclose(5000);
            }
        }


        if ($input && $input['type'] == 'finish') {
            $user = auth()->user();
            $product_id = decode($input['id']);

                if(isset($input['admin_sender_wallet_address']) && 
                !empty($input['admin_sender_wallet_address']) && 
                isset($input['admin_receiver_wallet_address']) && 
                !empty($input['admin_receiver_wallet_address'])) {
                    $validation = $request->validate([
                    'admin_sender_wallet_address' => ['required'],
                    'admin_receiver_wallet_address' => ['required'],
                    'message' => ['required', 'string']
                    ]);
                } else {
                    $validation = $request->validate([
                    'admin_reference_no' => ['required', 'string', 'unique:transactions'],
                    'message' => ['required', 'string']
                    ]);
                }
         

            DB::beginTransaction();
            try {

                  $update_status_value = '';
                  if(isset($input['admin_sender_wallet_address']) && 
                    !empty($input['admin_sender_wallet_address']) && 
                    isset($input['admin_receiver_wallet_address']) && 
                    !empty($input['admin_receiver_wallet_address'])&&
                    empty($input['admin_reference_no'])) {

                    $trans_msg = \DB::table('transaction_messages');
                    $trans_msg->where('product_id', $product_id);
                    $trans_msg->where('transaction_type', 1);
                    $trans_msg->where('admin_id',  auth()->user()->id);
                    $trans_result = $trans_msg->first();
                    if(!empty($trans_result)) {
                        $affected_msg = DB::table('transaction_messages');
                        $affected_msg->where('product_id', $product_id);
                        $affected_msg->where('transaction_type', 1);
                        $affected_msg->update(['message' => $input['message']]);
                    } else {
                        $userv = [];
                        \DB::table('transaction_messages')->insert([
                            'product_id' => $product_id,
                            'transaction_type' => 1,
                            'admin_id' =>  auth()->user()->id,
                            'message' => $input['message'],
                        ]);   
                    }
                      Alert::success('Success', 'Payment Status updated successfully.')->persistent('Close')->autoclose(5000);
                    
                } else {
                    $res = [
                        'status_id' => 9,
                        'admin_reference_no' => $input['admin_reference_no'],
                        'refund_amount' => $input['refund_amount'],
                        'refund_message' => $input['message']
                    ];
                    
                    Transaction::where('product_id', $product_id)->update($res);


                            $meta = ['item_id' => $product_id];
                            \App\Events\UserEvents::dispatch('admin', 34, '', $meta);

                            if (isset($input['winner_user_by_admin']) && $input['winner_user_by_admin'] != null) {
                                $userType = ($input['winner_user_by_admin'] == $input['buyer_id']) ? 'Buyer' : 'Seller';
                                \DB::table('dispute_transaction')->where('product_id', $product_id)->update(['winner_user_by_admin' => $input['winner_user_by_admin'], 'updated_at' => date('Y-m-d H:i:s')]);

                                $meta = ['item_id' => $product_id];
                                $message = auth()->user()->firstname . ' ' . auth()->user()->lastname . ' marked ' . $userType . ' as winner';
                                \App\Events\UserEvents::dispatch('admin', 33, $message, $meta);
                            }

                            $input['is_admin'] = 1;
                            $input['sender_id'] = $user->id;
                            $input['is_private'] = 0;
                            $input['is_dispute'] = 1;
                            $response = $this->sendMessageFromTrait($input);

                            
                            Alert::success('Success', 'Dispute finished successfully.')->persistent('Close')->autoclose(5000);
                }
                DB::commit();



         
                return redirect()->to('admin/dispute-history/' . $input['id']);
            } catch (\Exception $e) {
                DB::rollback();
                Alert::error('Error', $e->getMessage())->persistent('Close')->autoclose(5000);

                return redirect()->back()->withErrors($validation)->withInput();
            }
        }



        return redirect('admin/dispute-history/' . $input['id']);
    }


    /**
     * transactions
     *
     * @param  mixed $request
     * @return void
     */
    public function transactions(Request $request)
    {
        if (!auth()->user()->can('View Transactions')) {
            $request->session()->flash('error', 'You Dont have permission to access this page.');
            return redirect()->back();
        }
        $data = [];

        if ($request->ajax()) {
            $data = EscrowProducts::with(['seller', 'buyer', 'productCurrency', 'productTransaction'])->get();
            $datatable = Datatables::of($data);

            $datatable->editColumn('id', function ($row) {
                return encode($row->id);
            });

            $datatable->editColumn('transaction_id', function ($row) {
                return $row->transaction_id;
            });
            $datatable->editColumn('completion_days', function ($row) {
                return $row->completion_days();
            });

            $datatable->editColumn('currency', function ($row) {
                $currency = '<label class="badge badge-success">' . $row->productCurrency->currency . '</label>';
                return $currency;
            });

            $datatable->editColumn('transaction_status', function ($row) {
                $status = '';
                if ($row->productTransaction->transactionStatus->status) {
                    $status = $row->productTransaction->transactionStatus->status;
                }
                return '<label class="badge badge-danger">' . $status . '</label>';
            });

            $datatable->editColumn('status', function ($row) {
                if ($row->status == 0) {
                    $status = '<label class="badge badge-warning">Pending for Approval</label>';
                } elseif ($row->status == 2) {
                    $status = '<label class="badge badge-danger">In-Dispute</label>';
                } elseif ($row->status == 1) {
                    $status = '<label class="badge badge-success">Buyer Approved</label>';
                }
                if ($row->pocRequest <> null) {
                    if ($row->pocRequest->status == 0) {
                        $status .= '<label class="badge badge-info">POC Requested to Seller</label>';
                    } else {
                        $status .= '<label class="badge badge-primary">POC Responded By Seller</label>';
                    }
                }
                return $status;
            });

            $datatable->editColumn('amount', function ($row) {
                return  $row->productTransaction->total_amount . ' ' . $row->productCurrency->currency;
            });
            $datatable->editColumn('commission', function ($row) {
                return  $row->productTransaction->commission . ' ' . $row->productCurrency->currency;
            });

            $datatable->editColumn('customer', function ($row) {
                if ($row->buyer) {
                    return $row->buyer->full_name();
                } else {
                    return '';
                }
            });
            $datatable->editColumn('seller', function ($row) {
                if ($row->seller) {
                    return $row->seller->full_name();
                } else {
                    return '';
                }
            });

            $datatable->editColumn('escrow_fee_payer', function ($row) {
                if ($row->escrow_fee_payer == 1) {
                    $p = 'Buyer';
                } elseif ($row->escrow_fee_payer == 2) {
                    $p = 'Seller';
                } elseif ($row->escrow_fee_payer == 3) {
                    $p = '50% Buyer & 50% Seller';
                }
                return $p;
            });


            $datatable = $datatable->rawColumns(['id', 'transaction_id', 'currency', 'status', 'amount', 'seller', 'customer', 'escrow_fee_payer', 'action', 'transaction_status', 'commission', 'completion_days']);

            return $datatable->make(true);
        }
        return view('admin.escrows.transactions');
    }
}
