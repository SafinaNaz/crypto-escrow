<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Jobs\DisputeProcessCheck;
use Illuminate\Http\Request;
use App\Models\EscrowProducts;
use App\Models\Transaction;
use DB;
use App\Models\RequestedPoc;
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
		$this->middleware(['verified']);
	}

	/**
	 * index
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function index(Request $request)
	{
		$data = [];

		if (auth()->user()->user_type == 2) {
			$data['all_products'] = EscrowProducts::with(['seller', 'buyer', 'productCurrency', 'productTransaction', 'pocRequest'])
				->whereHas('productTransaction', function ($q) {
					return $q->whereNotIn('status_id', [7, 9]);
				})
				->where('buyer_id', auth()->user()->id)
				->orderBy('id', 'desc')
				->paginate(10);
		} else {
			$data['all_products'] = EscrowProducts::with(['seller', 'buyer', 'productCurrency', 'productTransaction', 'pocRequest'])
				->whereHas('productTransaction', function ($q) {
					return $q->whereNotIn('status_id', [7, 9]);
				})
				->where('seller_id', auth()->user()->id)->orderBy('id', 'desc')->paginate(10);
		}

		return view('frontend.escrows.escrows', $data);
	}
	/**
	 * escrows_approve
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function escrows_approve(Request $request)
	{
		$input = $request->all();

		$status = $input['status'];
		$id = decode($input['id']);

		if ($status <> '' && $id <> '') {
			$data = array(
				'status' => $status,
			);
			$product = EscrowProducts::findOrFail($id);
			$product->update($data);

			/**
			 * SEND MESSAGE
			 */
			$thread_id = $product->threads->id;
			$msg = [
				'thread_id' => $thread_id,
				'sender_id' => $product->buyer_id,
				'receiver_id' => $product->seller_id,
				'message' =>  auth()->user()->full_name() . ' approved your Escrow Transaction',
				'is_private' => 0,
				'is_admin' => 0,
				'is_dispute' => 0
			];
			$this->sendMessageFromTrait($msg);

			$request->session()->flash('success', 'Transaction Status Approved successfully!');
		} else {
			$request->session()->flash('error', 'Error occured. Transaction approval status not updated!');
		}
		return redirect()->back();
	}

	/**
	 * transaction_detail
	 *
	 * @param  mixed $id
	 * @param  mixed $type
	 * @return void
	 */
	public function transaction_detail($id, $type, Request $request)
	{




		$data = [];

		$id = decode($id);
		if ($id) {
			$data['product'] = $product = EscrowProducts::with(['seller', 'buyer', 'productCurrency', 'productTransaction', 'pocRequest'])->whereHas('productTransaction', function ($q) {
				return $q->whereNotIn('status_id', [7]);
			})->where('id', $id)->first();

			if (!$data['product']) {
				$request->session()->flash('error', 'You are not allowed to view this Transaction or transaction does not exist.');
				return redirect()->back();
			}
		}

		if ($type == 'update') {
			$data['status'] = 'update';
		}
		 else if ($type == 'cancel') {
			$data['status'] = 'cancel';
		} else if ($type == 'complete') {
			$data['status'] = 'complete';
		} else if ($type == 'request-poc') {
			$data['status'] = 'request-poc';
		} else if ($type == 'deposit') {
			$data['status'] = 'deposit';
		} else {
			$data['status'] = 'detail';
		}
		$data['dispute_transaction'] = \DB::table('dispute_transaction')->where('product_id', $id)->get();

		$data['current_status'] = $product->productTransaction->status_id;
		return view('frontend.escrows.transaction_status', $data);
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

		$data = [];
		$input = $request->all();
		$user = auth()->user();
		$pt_id = $id;
		$id = decode($id);

		

		if ($id) {
			$data['product'] = $product = EscrowProducts::with(['seller', 'buyer', 'productCurrency', 'productTransaction'])->whereHas('productTransaction', function ($q) {
				return $q->whereNotIn('status_id', [7, 9]);
			})->where('id', $id)->first();
			if (!$data['product']) {
				$request->session()->flash('error', 'You are not allowed to view this Transaction or transaction does not exist.');
				return redirect()->back();
			}
		}

		if ($input && $type == 'update') {
			if(isset($input['sender_wallet_address']) && 
				!empty($input['sender_wallet_address']) && 
				isset($input['receiver_wallet_address']) && 
				!empty($input['receiver_wallet_address'])) {
				$validation = $request->validate([
					'sender_wallet_address' => ['required'],
					'receiver_wallet_address' => ['required'],
					'message' => ['required', 'string']
				]);
				

			} else {
				$validation = $request->validate([
					'reference_no' => ['required', 'string', 'unique:transactions'],
					'message' => ['required', 'string']
				]);
			}
			

			DB::beginTransaction();
			try {
				$update_status_value = '';
				$update_status_text = 'Payment';
				if(isset($input['sender_wallet_address']) && 
					!empty($input['sender_wallet_address']) && 
					isset($input['receiver_wallet_address']) && 
					!empty($input['receiver_wallet_address'])&&
					empty($input['reference_no'])) {

						// pre populate msg

					$trans_msg = \DB::table('transaction_messages');
					$trans_msg->where('product_id', $id);
					$trans_msg->where('transaction_type', 0);
					$trans_msg->where('product_status', 1);
					if(auth()->user()->user_type == 1) {
						$trans_msg->where('seller_id',  auth()->user()->id);
					} else {
						$trans_msg->where('buyer_id',  auth()->user()->id);
					}
					$trans_result = $trans_msg->first();
					if(!empty($trans_result)) {
						$affected_msg = DB::table('transaction_messages');
						$affected_msg->where('product_id', $id);
						$affected_msg->where('transaction_type', 0);
						$affected_msg->where('product_status', 1);
						if(auth()->user()->user_type == 1) {
							$affected_msg->where('seller_id',  auth()->user()->id);
						} else {
							$affected_msg->where('buyer_id',  auth()->user()->id);
						}

						$affected_msg->update(['message' => $input['message']]);

					} else {
						$userv = [];
						if(auth()->user()->user_type == 1) {
							\DB::table('transaction_messages')->insert([
								'product_id' => $id,
								'product_status' => 1,
								'transaction_type' => 0,
								'seller_id' =>  auth()->user()->id,
								'message' => $input['message'],
							]);
						} else {
							\DB::table('transaction_messages')->insert([
								'product_id' => $id,
								'product_status' => 1,
								'transaction_type' => 0,
								'buyer_id' =>  auth()->user()->id,
								'message' => $input['message'],
							]);
						}
						
					}
					
				} else {
					$update_status_value = [
						'reference_no' => $input['reference_no'], 
						'status_id' => 2
					];
					$update_status_text = 'Transaction';
					Transaction::whereId($input['id'])->update($update_status_value);
				}


				
			   
				/**
				 * after payment escrow activated
				 */
				if(!isset($input['sender_wallet_address']) && !isset($input['receiver_wallet_address']))
				{
					if ($product->immediate_release == 1) {
						$product->completion_time = date('Y-m-d H:i:s', strtotime('+ ' . settingValue('immediate_release_hours') . ' hours'));
					} else {
						$product->completion_time = date('Y-m-d H:i:s', strtotime('+ ' . $product->completion_days . ' days'));
					}
				}
				


				$product->save();


				/**
				 * SEND MESSAGE
				 */
				$thread_id = $product->threads->id;
				$msg = [
					'thread_id' => $thread_id,
					'sender_id' => $product->buyer_id,
					'receiver_id' => $product->seller_id,
					'message' =>   $input['message'], //'Escrowed ' . $total_amount . ' by ' . $user->full_name() . ' at ' . date('m/d/Y G:i A'),
					'is_private' => 0,
					'is_admin' => 0,
					'is_dispute' => 0,
				];
				$this->sendMessageFromTrait($msg);


				DB::commit();

				$request->session()->flash('success', $update_status_text.' status updated successfully and email has been sent to admin.');
				return redirect()->to('escrows');
			} catch (\Exception $e) {
				DB::rollback();
				$request->session()->flash('error', $e->getMessage());

				return redirect()->back()->withErrors($validation)->withInput();
			}
		}

		if ($input && $type == 'cancel') {

			$validation = $request->validate([
				'message' => ['required', 'string']
			]);

			DB::beginTransaction();
			try {
				if ($product->seller_id == auth()->user()->id) {
					$cancelled_by = 2;
				} else {
					$cancelled_by = 3;
				}

				Transaction::whereId($input['id'])->update(['status_id' => 5, 'cancelled_by' => $cancelled_by]);

				if ($product->seller_id == auth()->user()->id) {
					$sender = $product->seller_id;
					$receiver = $product->buyer_id;
				} else {
					$sender = $product->buyer_id;
					$receiver = $product->seller_id;
				}
				/**
				 * SEND MESSAGE
				 */
				$thread_id = $product->threads->id;
				$msg = [
					'thread_id' => $thread_id,
					'sender_id' => $sender,
					'receiver_id' => $receiver,
					'message' =>   $input['message'],
					'is_private' => 0,
					'is_admin' => 0,
					'is_dispute' => 0
				];
				$this->sendMessageFromTrait($msg);

				DB::commit();
				$request->session()->flash('success', 'Transaction Cancelled successfully and email has been sent to admin.');
				return redirect()->to('escrows');
			} catch (\Exception $e) {
				DB::rollback();
				$request->session()->flash('error', $e->getMessage());

				return redirect()->back()->withErrors($validation)->withInput();
			}
		}

		if ($input && $type == 'dispute') {
			$validation = $request->validate([
				'message' => ['required', 'string']
			]);

			DB::beginTransaction();
			try {
				$product->status = 2;
				$product->save();
				Transaction::whereId($input['id'])->update(['status_id' => 8]);

				if ($product->seller_id == auth()->user()->id) {
					$sender = $product->seller_id;
					$receiver = $product->buyer_id;
				} else {
					$sender = $product->buyer_id;
					$receiver = $product->seller_id;
				}
				/**
				 * SEND MESSAGE
				 */
				$thread_id = $product->threads->id;
				$msg = [
					'thread_id' => $thread_id,
					'sender_id' => $sender,
					'receiver_id' => $receiver,
					'message' =>   $input['message'],
					'is_private' => 0,
					'is_admin' => 0,
					'is_dispute' => 1
				];
				$msg = $this->sendMessageFromTrait($msg);

				DB::commit();
				$request->session()->flash('success', 'Dispute Started successfully and email has been sent to admin.');
				return redirect()->to('escrows');
			} catch (\Exception $e) {
				DB::rollback();
				$request->session()->flash('error', $e->getMessage());

				return redirect()->back()->withErrors($validation)->withInput();
			}
		}

		if ($input && $type == 'complete') {

			DB::beginTransaction();
			try {

				Transaction::where('product_id', $id)->update(['status_id' => 7]);
				
				if ($product->seller_id == auth()->user()->id) {
					$sender = $product->seller_id;
					$receiver = $product->buyer_id;
				} else {
					$sender = $product->buyer_id;
					$receiver = $product->seller_id;
				}

				/**
				 * SEND MESSAGE
				 */
				$link = url('/review/' . encode($product->id));
				$thread_id = $product->threads->id;
				$msg = [
					'thread_id' => $thread_id,
					'sender_id' => $sender,
					'receiver_id' => $receiver,
					'message' =>   'Please Review Seller against this product "' . $product->transaction_id . '". link is shown below'  . "\n\n" . '<a href="' . $link . '">' . $link . '</a>',
					'is_private' => 0,
					'is_admin' => 0,
					'is_dispute' => 1
				];
				$this->sendMessageFromTrait($msg);
				$link = url('/seller-review/' . encode($product->id));
				$msg = [
					'thread_id' => $thread_id,
					'sender_id' => $receiver,
					'receiver_id' => $sender,
					'message' =>   'Please Review Buyer against this product "' . $product->transaction_id . '". link is shown below' .  "\n\n" . '<a href="' . $link . '">' . $link . '</a>',
					'is_private' => 0,
					'is_admin' => 0,
					'is_dispute' => 1
				];
				$this->sendMessageFromTrait($msg);

				DB::commit();
				$request->session()->flash('success', 'Transaction status Completed successfully.');
				return redirect()->to('escrows');
			} catch (\Exception $e) {
				DB::rollback();
				$request->session()->flash('error', $e->getMessage());

				return redirect()->back();
			}
		}

		if ($input && $type == 'deposit') {
			if(isset($input['sender_wallet_address']) && 
				!empty($input['sender_wallet_address']) && 
				isset($input['receiver_wallet_address']) && 
				!empty($input['receiver_wallet_address'])) {
				$validation = $request->validate([
					'sender_wallet_address' => ['required'],
					'receiver_wallet_address' => ['required'],
				]);
				

			} else {
				$validation = $request->validate([
					'reference_no' => ['required'],
				
				]);
			}


			DB::beginTransaction();
			  try {
				if (isset($input['reference_no']) && $input['reference_no'] == null) {
					$request->session()->flash('error', 'Please enter reference no.');
					return redirect()->back();
				}
				$update_status_value = '';
				$update_status_text = 'Payment';
				if(isset($input['sender_wallet_address']) && 
					!empty($input['sender_wallet_address']) && 
					isset($input['receiver_wallet_address']) && 
					!empty($input['receiver_wallet_address'])) {


					$trans_msg = \DB::table('transaction_messages');
					$trans_msg->where('product_id', $id);
					$trans_msg->where('transaction_type', 1);
					$trans_msg->where('product_status', 2);
					if(auth()->user()->user_type == 1) {
						$trans_msg->where('seller_id',  auth()->user()->id);
					} else {
						$trans_msg->where('buyer_id',  auth()->user()->id);
					}
					$trans_result = $trans_msg->first();
					// pre populate msg
					if(!empty($trans_result)) {
						$affected_msg = DB::table('transaction_messages');
						$affected_msg->where('product_id', $id);
						$affected_msg->where('transaction_type', 1);
						$affected_msg->where('product_status', 2);
						if(auth()->user()->user_type == 1) {
							$affected_msg->where('seller_id',  auth()->user()->id);
						} else {
							$affected_msg->where('buyer_id',  auth()->user()->id);
						}

						$affected_msg->update(['message' => $input['message']]);

					} else {
						$userv = [];
						if(auth()->user()->user_type == 1) {
							\DB::table('transaction_messages')->insert([
								'product_id' => $id,
								'product_status' => 2,
								'transaction_type' => 1,
								'seller_id' =>  auth()->user()->id,
								'message' => $input['message'],
							]);
						} else {
							\DB::table('transaction_messages')->insert([
								'product_id' => $id,
								'product_status' => 2,
								'transaction_type' => 1,
								'buyer_id' =>  auth()->user()->id,
								'message' => $input['message'],
							]);
						}
						
					}
					$request->session()->flash('success', 'Payment status updated successfully');
					
				} else {
					$update_status_value = [
						'reference_no' => $input['reference_no'], 
						'deposit_amount' => $input['deposit_amount'],
						'level' => 3
					];
					$update_status_text = 'Transaction';
					$request->session()->flash('success', 'Skip dispute to level 3 successfully.');
					\DB::table('dispute_transaction')
					->where('product_id',  $input['product_id'])
					->update($update_status_value);
				}
				
				
				$input['message'] = 'Skip dispute to level 3.';
				$thread_id = $product->threads->id;
				if ($product->seller_id == auth()->user()->id) {
					$sender = $product->seller_id;
					$receiver = $product->buyer_id;
				} else {
					$sender = $product->buyer_id;
					$receiver = $product->seller_id;
				}
				$msg = [
					'thread_id' => $thread_id,
					'sender_id' => $sender,
					'receiver_id' => $receiver,
					'message' =>   $input['message'],
					'is_private' => 0,
					'is_admin' => 0,
					'is_dispute' => 1
				];
				$this->sendMessageFromTrait($msg);
			  
				DB::commit();
				return redirect('dispute-messages/' . $pt_id);
			} catch (\Exception $e) {
				DB::rollback();
				$request->session()->flash('error', $e->getMessage());

				return redirect()->back()->withErrors($validation)->withInput();
			}
		}
	

	}

	public function create_dispute(Request $request)
	{

		$input = $request->all();

		$user = auth()->user();
		if ($input['product_id']) {
			$data['product'] = $product = EscrowProducts::with(['seller', 'buyer', 'productCurrency', 'productTransaction'])->whereHas('productTransaction', function ($q) {
				return $q->whereNotIn('status_id', [7, 9]);
			})->where('id', $input['product_id'])->first();
			if (!$data['product']) {
				$request->session()->flash('error', 'You are not allowed to view this Transaction or transaction does not exist.');
				return redirect()->back();
			}
		}
		if ($input && $input['type'] == 'dispute') {

			DB::beginTransaction();
			try {
				if (isset($input['discount_offer']) && $input['discount_offer'] == null && $input['level'] == 1) {
					$request->session()->flash('error', 'Please enter discount offer.');
					return redirect()->back();
				}
				$product->status = 2;
				$product->save();
				Transaction::whereId($input['id'])->update(['status_id' => 8]);

				$sender = $product->buyer_id;
				$receiver = $product->seller_id;

				$mesage = '.';
				if (isset($input['discount_offer']) && $input['discount_offer'] > 0) {
					$mesage = ' with a discount offer of ' . $input['discount_offer'];
				} else {
					$input['discount_offer'] = 0;
				}
				/**
				 * SEND MESSAGE
				 */
				$thread_id = $product->threads->id;
				$msg = [
					'thread_id' => $thread_id,
					'sender_id' => $sender,
					'receiver_id' => $receiver,
					'message' =>   'Buyer created a dispute' . $mesage,
					'is_private' => 0,
					'is_admin' => 0,
					'is_dispute' => 1
				];
				$response = $this->sendMessageFromTrait($msg);
				$level = $input['level'];

				$dis = [
					'user_id' => $user->id,
					'message_id' => $response['id'],
					'discount_offer' =>  $input['discount_offer'],
					'product_id' =>  $product->id,
					'level' =>  $level,
					'offer_expire_time' =>   Carbon::now()->addHours(settingValue('level' . $level . '_time'))->format('Y-m-d H:i:s'),
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
				];
				\DB::table('dispute_transaction')->insertGetId($dis);

				/**
				 * dispatch JOB
				 */
				$jobData = $msg;
				$jobData['level'] = $level;
				$jobData['product_id'] = $product->id;
				$jobData['user_id'] = $user->id;
				$seconds = (settingValue('level' . $level . '_time') * 3600);
				DisputeProcessCheck::dispatch($jobData)->delay(Carbon::now()->addSeconds($seconds));


				DB::commit();
				$request->session()->flash('success', 'Dispute created successfully.');
				return redirect()->to('escrows');
			} catch (\Exception $e) {
				DB::rollback();
				$request->session()->flash('error', $e->getMessage());

				return redirect()->back()->withInput();
			}
		}
	}

	/**
	 * buyer_request_poc
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function buyer_request_poc(Request $request)
	{
		$input = $request->all();

		if ($request->all()) {

			$data = [];
			$id = decode($input['id']);
			if ($id) {
				$product = EscrowProducts::with(['seller', 'buyer', 'productCurrency'])->where('id', $id)->first();

				if (!$product) {
					$request->session()->flash('error', 'You are not allowed for request of POC or Product does not exist.');
					return redirect()->back();
				}
			}

			/** Validate */
			$validation = $request->validate([
				'buyer_request_poc' => ['required']
			]);

			DB::beginTransaction();
			try {

				$wallet_address = $product->wallet_address;


				$input['poc_amount'] = $payable_amount = (($product->price * $input['buyer_request_poc']) / 100);
				$input['product_id'] = $product->id;
				$input['poc_percentage'] = $input['buyer_request_poc'];
				$result = RequestedPoc::create($input);

				$product->buyer_request_poc = 1;
				$product->save();


				$message = $product->buyer->full_name() . " requested for POC of " . $input['buyer_request_poc'] . "% of total price.\n\n here is wallet address \n\n" . $wallet_address;
				/**
				 * SEND MESSAGE
				 */
				$thread_id = $product->threads->id;
				$msg = [
					'thread_id' => $thread_id,
					'sender_id' => $product->buyer_id,
					'receiver_id' => $product->seller_id,
					'message' =>  $message,
					'is_private' => 0,
					'is_admin' => 0,
					'is_dispute' => 0
				];
				$this->sendMessageFromTrait($msg);

				DB::commit();

				$request->session()->flash('success', 'Request for POC created successfully and email sent to Seller.');

				return redirect()->back();
			} catch (\Exception $e) {
				DB::rollback();
				$request->session()->flash('error', $e->getMessage());

				return redirect()->back()->withErrors($validation);
			}
		}
	}

	/**
	 * respond_poc
	 *
	 * @param  mixed $id
	 * @return void
	 */
	public function respond_poc($id, Request $request)
	{
		$data = [];

		$id = decode($id);
		if ($id) {
			$data['product'] = $product = EscrowProducts::with(['seller', 'buyer', 'productCurrency', 'productTransaction', 'pocRequest'])->where('id', $id)->first();

			if (!$data['product']) {
				$request->session()->flash('error', 'You are not allowed to view this Transaction or transaction does not exist.');
				return redirect()->back();
			}
		}

		$data['current_status'] = $product->productTransaction->status_id;
		return view('frontend.escrows.respond_poc', $data);
	}

	public function update_poc_request($id, Request $request)
	{
		$data = [];
		$input = $request->all();
		$user = auth()->user();
		$id = decode($id);
		if ($id) {
			$data['product'] = $product = EscrowProducts::with(['seller', 'buyer', 'productCurrency', 'productTransaction'])->where('id', $id)->first();
			if (!$data['product']) {
				$request->session()->flash('error', 'You are not allowed to view this Transaction or transaction does not exist.');
				return redirect()->back();
			}
		}

		if ($input) {
			if(isset($input['sender_wallet_address']) && 
				!empty($input['sender_wallet_address']) && 
				isset($input['receiver_wallet_address']) && 
				!empty($input['receiver_wallet_address'])) {
				$validation = $request->validate([
					'sender_wallet_address' => ['required'],
					'receiver_wallet_address' => ['required'],
					'message' => ['required', 'string']
				]);
			} else {
				$validation = $request->validate([
					'reference_no' => ['required', 'string', 'unique:transactions'],
					'message' => ['required', 'string']
				]);
			}
			
		

			DB::beginTransaction();
			try {
				$status = 1;
				$update_status_value = '';
				$update_status_text = 'Payment';
				if(isset($input['sender_wallet_address']) && 
					!empty($input['sender_wallet_address']) && 
					isset($input['receiver_wallet_address']) && 
					!empty($input['receiver_wallet_address'])&&
					empty($input['reference_no'])) {

					
						// pre populate msg
					$trans_msg = \DB::table('transaction_messages');
					$trans_msg->where('product_id', $id);
					$trans_msg->where('transaction_type', 2);
					$trans_msg->where('product_status', 1);
					if(auth()->user()->user_type == 1) {
						$trans_msg->where('seller_id',  auth()->user()->id);
					} else {
						$trans_msg->where('buyer_id',  auth()->user()->id);
					}

					$trans_result = $trans_msg->first();
					if(!empty($trans_result)) {
						$affected_msg = DB::table('transaction_messages');
						$affected_msg->where('product_id', $id);
						$affected_msg->where('transaction_type', 2);
						$affected_msg->where('product_status', 1);
						if(auth()->user()->user_type == 1) {
							$affected_msg->where('seller_id',  auth()->user()->id);
						} else {
							$affected_msg->where('buyer_id',  auth()->user()->id);
						}

						$affected_msg->update(['message' => $input['message']]);

					} else {
						$userv = [];
						if(auth()->user()->user_type == 1) {
							\DB::table('transaction_messages')->insert([
								'product_id' => $id,
								'product_status' => 1,
								'transaction_type' => 2,
								'seller_id' =>  auth()->user()->id,
								'message' => $input['message'],
							]);
						} else {
							\DB::table('transaction_messages')->insert([
								'product_id' => $id,
								'product_status' => 1,
								'transaction_type' => 2,
								'buyer_id' =>  auth()->user()->id,
								'message' => $input['message'],
							]);
						}
						
					}
					
				} else {
					$update_status_value = [
						'reference_no' => $input['reference_no'], 
						'status' => 1
					];
					$update_status_text = 'Transaction';
					RequestedPoc::whereId($input['id'])->update($update_status_value);
				}
				

				/**
				 * SEND MESSAGE
				 */
				$thread_id = $product->threads->id;
				$msg = [
					'thread_id' => $thread_id,
					'sender_id' => $product->buyer_id,
					'receiver_id' => $product->seller_id,
					'message' =>   $input['message'], //'Escrowed ' . $total_amount . ' by ' . $user->full_name() . ' at ' . date('m/d/Y G:i A'),
					'is_private' => 0,
					'is_admin' => 0,
					'is_dispute' => 0
				];
				$this->sendMessageFromTrait($msg);

				DB::commit();
				$request->session()->flash('success', $update_status_text.' status updated successfully and email has been sent to admin.');
				return redirect()->to('escrows');
			} catch (\Exception $e) {
				DB::rollback();
				$request->session()->flash('error', $e->getMessage());

				return redirect()->back()->withErrors($validation)->withInput();
			}
		}
	}

	public function dispute_escrow($id)
	{

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
		// dd($product);
		$data['status'] = 'dispute';
		$data['current_status'] = $product->productTransaction->status_id;
		return view('frontend.escrows.transaction_status', $data);
	}
   
}
