<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Jobs\DisputeProcessCheck;
use App\Models\EscrowProducts;
use App\Models\MessageThreads;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EscalateDecisionController extends Controller
{
    use \App\Traits\SendMessageTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['verified']);
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $data = [];

        if (auth()->user()->user_type == 2) {
            $data['all_products'] = EscrowProducts::with(['seller', 'buyer', 'productCurrency', 'productTransaction', 'pocRequest'])
                ->whereHas('productTransaction', function ($q) {
                    return $q->whereNotIn('status_id', [7]);
                })
                ->where(['buyer_id' => auth()->user()->id, 'status' => 2])
                ->orderBy('id', 'DESC')
                ->paginate(10);
        } else {
            $data['all_products'] = EscrowProducts::with(['seller', 'buyer', 'productCurrency', 'productTransaction', 'pocRequest'])
                ->whereHas('productTransaction', function ($q) {
                    return $q->whereNotIn('status_id', [7]);
                })
                ->where(['seller_id' => auth()->user()->id, 'status' => 2])
                ->orderBy('id', 'DESC')
                ->paginate(10);
        }

        return view('frontend.escalatedecision.dispute', $data);
    }

    public function dispute_messages($id, Request $request)
    {
        $data = [];
        $id = decode($id);
        if ($id) {
            $data['product'] = $product = EscrowProducts::with(['seller', 'buyer', 'productCurrency', 'productTransaction'])
                ->whereHas('productTransaction', function ($q) {
                    return $q->whereNotIn('status_id', [7]);
                })
                ->where('id', $id)->first();
            if (!$data['product']) {
                $request->session()->flash('error', 'You are not allowed to view this Transaction or transaction does not exist.');
                return redirect()->back();
            }
        }
        $data['current_status'] = $product->productTransaction->status_id;

        $data['current_thread'] = $current_thread  = MessageThreads::with(['messages' => function ($q) {
            return $q->where('is_dispute', 1);
        }])
            ->where('product_id', $id)
            ->orderByDesc('id')
            ->first();

        $data['thread_id'] = $current_thread->id;

        $data['lastSender'] = $current_thread->messages->first()->sender->full_name();
        $data['lastSenderImg'] = $current_thread->messages->first()->sender->photo();
        $data['lastMsg'] = $current_thread->messages->first()->message;

        $data['dispute_transaction'] = \DB::table('dispute_transaction')->where('product_id', $id)->get();
        return view('frontend.escalatedecision.dispute_history', $data);
    }

    /**
     * Persist message to database
     *
     * @param  Request $request
     * @return Response
     */
    public function send_dispute_message(Request $request)
    {
        $user = auth()->user();
        $input = $request->all();
        $input['sender_id'] = $user->id;
        $input['is_admin'] = 0;
        $input['is_private'] = 0;
        $input['is_dispute'] = 1;
        $response =  $this->sendMessageFromTrait($input);

        $request->session()->flash('success', $response['message']);
        return redirect('dispute-messages/' . $input['id']);
    }

    public function update_dispute_status(Request $request)
    {

        $user = auth()->user();
        $input = $request->all();

        $product_id = decode($input['id']);
        $input['sender_id'] = $user->id;
        $input['is_admin'] = 0;
        $input['is_private'] = 0;
        $input['is_dispute'] = 1;
      

        if ($user->id == $input['buyer_id'] && $request->has('discount_offer')  && $input['discount_offer'] > 0) {
         
            $input['message'] = 'Buyer added a discount offer of ' . $input['discount_offer'];
            $response =  $this->sendMessageFromTrait($input);
            $msg = [
                'user_id' => $user->id,
                'message_id' => $response['id'],
                'discount_offer' =>  $input['discount_offer'],
                'product_id' =>  decode($input['id']),
                'offer_expire_time' =>   Carbon::now()->addHours(settingValue('level1_time'))->format('Y-m-d H:i:s'),
                'level' => $input['level'] ?? 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            \DB::table('dispute_transaction')->insertGetId($msg);

            $input['message'] = 'Buyer added a discount offer of ' . $input['discount_offer'];
            $this->sendMessageFromTrait($input);
            /**
             * dispatch JOB
             */
            $jobData = $msg;
            $jobData['level'] = 1;
            $seconds = (settingValue('level1_time') * 3600);
            DisputeProcessCheck::dispatch($jobData)->delay(Carbon::now()->addSeconds($seconds));
        }
        if ($user->id == $input['buyer_id']) {

            if ($request->has('btn_status') && $input['btn_status'] == 'accept') {
                // approved
                \DB::table('dispute_transaction')->where('product_id', $product_id)->update(['status' => 1, 'updated_at' => date('Y-m-d H:i:s')]);
                $input['message'] = 'Buyer accepted seller offer.';
                $this->sendMessageFromTrait($input);
                $request->session()->flash('success', 'Buyer accepted seller offer successfully.');
                return redirect('dispute-messages/' . $input['id']);
            } elseif ($request->has('btn_status')  && $input['btn_status'] == 'reject') {
                // rejected
                \DB::table('dispute_transaction')->where('product_id', $product_id)->update(['status' => 2, 'level' => 2, 'updated_at' => date('Y-m-d H:i:s')]);
                $input['message'] = 'Buyer rejected seller offer.';
                $this->sendMessageFromTrait($input);

                $request->session()->flash('success', 'Buyer rejected buyer offer successfully.');
                return redirect('dispute-messages/' . $input['id']);
            }

            if ($request->has('level')  && $input['level'] == 3) {
                return redirect('transaction-detail/' . $input['id']. '/deposit');
             
            }
        }

        // after Seller Offer it will move to level 2
        if ($user->id == $input['seller_id'] && $request->has('discount_offer')  && $input['discount_offer'] > 0) {
            $input['message'] = 'seller added a discount offer of ' . $input['discount_offer'];
            $response = $this->sendMessageFromTrait($input);
            $msg = [
                'user_id' => $user->id,
                'message_id' => $response['id'],
                'discount_offer' =>  $input['discount_offer'],
                'product_id' =>  decode($input['id']),
                'offer_expire_time' =>   Carbon::now()->addHours(settingValue('level1_time'))->format('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            \DB::table('dispute_transaction')->insertGetId($msg);
            \DB::table('dispute_transaction')->where('product_id', $product_id)->update(['level' => 1, 'updated_at' => date('Y-m-d H:i:s')]);
            $request->session()->flash('success', 'seller added a discount offer successfully.');
            return redirect('dispute-messages/' . $input['id']);
        }
        if ($user->id == $input['seller_id']) {

            if ($request->has('discount_offer') && ($input['discount_offer'] == null || $input['discount_offer'] == '')) {
                if ($request->has('btn_status') && $input['btn_status'] == 'accept') {
                    // approved
                    \DB::table('dispute_transaction')->where('product_id', $product_id)->update(['status' => 1, 'updated_at' => date('Y-m-d H:i:s')]);
                    $input['message'] = 'Seller accepted buyer offer.';
                    $this->sendMessageFromTrait($input);
                    $request->session()->flash('success', 'seller accepted buyer offer successfully.');
                    return redirect('dispute-messages/' . $input['id']);
                } elseif ($request->has('btn_status')  && $input['btn_status'] == 'reject') {
                    // rejected
                    \DB::table('dispute_transaction')->where('product_id', $product_id)->update(['status' => 2, 'level' => 2, 'updated_at' => date('Y-m-d H:i:s')]);
                    $input['message'] = 'Seller rejected buyer offer.';
                    $this->sendMessageFromTrait($input);

                    $request->session()->flash('success', 'seller rejected buyer offer successfully.');
                    return redirect('dispute-messages/' . $input['id']);
                } else {

                    $request->session()->flash('error', 'Please enter offer.');
                    return redirect('dispute-messages/' . $input['id']);
                }
            }
            if ($request->has('level')  && $input['level'] == 3) {

                  return redirect('transaction-detail/' . $input['id']. '/deposit');
                
            }
        }

        return redirect('dispute-messages/' . $input['id']);
    }
    
}
