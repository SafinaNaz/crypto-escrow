<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CmsPages;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\User;
use App\Models\EscrowProducts;
use App\Models\Templates;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\MessageThreads;
use App\Models\Currency;
use Mail;
use View;
use App\Mail\MasterMail;
use App\Models\ContactUs;
use App\Models\Transaction;
use App\Models\FaqCategories;
use App\Traits\SanitizedRequest;

class HomeController extends Controller
{
	use SanitizedRequest;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('auth');
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
		$data['currencies'] = Currency::where('is_active', 1)->get();
		return view('frontend.home.view', $data);
	}

	/**
	 * cms_pages
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function cms_pages(Request $request)
	{
		$data = array();
		$slug = $request->segment(1);
		if ($slug <> "") {
			$data['cmsPage'] = $cmsPage = CmsPages::select('*')
				->where('seo_url', $slug)
				->where('is_active', 1)
				->first();

			if ($cmsPage) {
				$data['meta_title'] = $cmsPage->meta_title;
				$data['meta_keywords'] = $cmsPage->meta_keywords;
				$data['meta_descrition'] = $cmsPage->meta_description;
			} else {
				$data['meta_title'] = '';
				$data['meta_keywords'] = '';
				$data['meta_descrition'] = '';
			}
		}

		return view("frontend.layouts.pages")->with($data);
	}

	public function contact_us(Request $request)
	{

		$data = [];

		$data['cmsPage'] = $cmsPage = CmsPages::select('*')
			->where('seo_url', 'contact-us')
			->where('is_active', 1)
			->first();

		if ($cmsPage) {
			$data['meta_title'] = $cmsPage->meta_title;
			$data['meta_keywords'] = $cmsPage->meta_keywords;
			$data['meta_descrition'] = $cmsPage->meta_description;
		}

		if ($request->all()) {

			$validation = $request->validate([
				'username' => ['required', 'max:30'],
				'email' => ['required', 'max:50', 'email'],
				'subject' => ['required', 'string',  'max:100'],
				'message' => ['required', 'string',  'max:500']
			]);

			DB::beginTransaction();
			try {
				$input = $request->all();

				ContactUs::create($input);

				$template = Templates::where('template_type', 1)->where('is_active', 1)->where('email_type', 'contact_us')->first();
				if ($template != '') {

					$subject = $template->subject;
					$to_replace = ['[USERNAME]', '[EMAIL]', '[SUBJECT]', '[MESSAGE]'];
					$with_replace = [$input['username'], $input['email'], $input['subject'], nl2br(removeUrls(removeHtml($input['message'])))];
					$header = $template->header;
					$footer = $template->footer;
					$content = $template->content;
					$html_header = str_replace($to_replace, $with_replace, $header);
					$html_footer = str_replace($to_replace, $with_replace, $footer);
					$html_body = str_replace($to_replace, $with_replace, $content);

					$mailContents = View::make('email_templete.message', ["data" => $html_body, "header" => $html_header, "footer" => $html_footer])->render();
					Mail::queue(new MasterMail(INQUIRY_EMAIL, SITE_NAME, NO_REPLY_EMAIL, $subject, $mailContents));
				}

				DB::commit();

				return redirect()->back()->with('success', 'Your contact us inquiry has been successfully submitted.');
			} catch (\Exception $e) {
				DB::rollback();
				return redirect()->back()->withErrors($validation)->withInput()->with('error', $e->getMessage());
			}
		}

		return view("frontend.home.contact_us")->with($data);
	}
	/**
	 * get_started
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function get_started(Request $request)
	{
		$this->sanitize_request($request);
		if (auth()->user() && auth()->user()->user_type == 2) {
			return redirect()->back();
		}
		$data = [];
		if (old()) {
			$data['row'] = old();
		}
		if ($request->all()) {
			$data['row'] = $request->all();

			if ($request->has('encrypted_text')) {

				/** Validate */
				$validation = $request->validate([
					'buying_selling_option' => ['required'],
					// 'product_name' => ['required'],
					'buyer_username' => ['required', 'string', 'min:5', 'max:30'],
					'price' => ['required'],
					'currency_id' => ['required'],
					'encrypted_text' => ['required', 'string',  'max:500'],
					'non_encrypted_text' => ['required', 'string', 'max:500'],
					'escrow_fee_payer' => ['required'],
					// 'completion_days' => ['required'],
					'immediate_release' => ['required']
				]);



				if (!auth()->user() && $request->user_type == 'old') {
					$loginAuth = array();
					$loginAuth['password'] = $request['password'];
					$loginAuth['is_active'] = 1;
					$loginAuth['username'] = $request['username'];

					if (Auth::attempt($loginAuth, true)) {
						User::where('id', auth()->user()->id)
							->update(['last_login_on' => date('Y-m-d H:i:s')]);

						$request->session()->regenerate();
					} else {

						return redirect()->back()->withInput()->with('error', 'Invalid Credentials. Please try correct username and password.');;
					}
				}

				if (auth()->user()) {

					if (auth()->user()->btc_address == null || auth()->user()->monero_address == null) {

						return redirect()->to('profile')->with('error', 'Please complete your profile first to get started.');;
					}
					if ($request->immediate_release == 0) {
						$validation = $request->validate([
							'completion_days' => ['required'],
						],['completion_days.required' => 'Please enter completion days.']);
					}
					DB::beginTransaction();
					try {

						$input = $request->all();

						if ($input['currency_id'] == 1) {
							$input['commission'] = ESCROW_FEE_BTC;
						} else {
							$input['commission'] = ESCROW_FEE_MONERO;
						}
						$input['seller_id'] = auth()->user()->id;

						if ($input['buyer_username']) {

							$isExist = User::where(['username' => $input['buyer_username'], 'user_type' => 2])->first();
							if ($isExist) {
								$buyer_id = $isExist->id;
							} else {
								// $buyer = new User();
								// $buyer->username = $input['buyer_username'];
								// $buyer->email_verified_at = date('Y-m-d H:i:s');
								// $buyer->password = Hash::make(Str::random(8));
								// $buyer->remember_token = Str::random(60);
								// $buyer->is_active = 1;
								// $buyer->user_type = 2;
								// $buyer->save();
								// $buyer_id = $buyer->id;

								return redirect()->back()->withInput()->with('error', 'Buyer doesn\'t exist please enter the correct username of buyer by verifying it');
							}
							$input['buyer_id'] = $buyer_id;

							$input['transaction_id'] = encode($buyer_id) . '-' . Str::random(32);
						}

						$encryptedData = encryptText($input['encrypted_text']);

						if ($input['currency_id'] == 1) {
							$wallet_address = BTC_ADDRESS;
							$commission = number_format(($input['price'] * ESCROW_FEE_BTC) / 100, 2, '.', '');
						} else {
							$wallet_address = MONERO_ADDRESS;
							$commission = number_format(($input['price'] * ESCROW_FEE_MONERO) / 100, 2, '.', '');
						}

						if ($input['escrow_fee_payer'] == 1) {
							$input['total_price']  = ($input['price']); // buyer will pay
						} elseif ($input['escrow_fee_payer'] == 2) {
							$input['total_price'] = ($input['price']); // seller will pay
						}

						$input['wallet_address'] = $wallet_address;
						$input['completion_days'] = $input['completion_days'];
						$result = EscrowProducts::create($input);
						$result->encrypted_text = $encryptedData;
						$result->save();

						/**
						 * Messages START
						 */
						$thread = MessageThreads::create([
							'seller_id' => auth()->user()->id,
							'buyer_id' => $buyer_id,
							'product_id' => $result->id
						]);

						$thread->messages()->createMany([
							[
								'sender_id' => auth()->user()->id,
								'receiver_id' => $buyer_id,
								'message' => $encryptedData,
								'is_private' => 1
							],
							[
								'sender_id' => auth()->user()->id,
								'receiver_id' => $buyer_id,
								'message' => $input['non_encrypted_text']
							],
						]);

						/**
						 * Messages END
						 */

						/**
						 * Transaction
						 */
						$transaction = [
							'product_id'  => $result->id,
							'currency_id' => $result->currency_id,
							'total_amount' => $result->total_price,
							'commission' => $commission,
							'status_id' => 1

						];
						Transaction::create($transaction);

						DB::commit();

						
						return redirect()->to('escrows');
					} catch (\Exception $e) {
						DB::rollback();

						return redirect()->back()->with('error', $e->getMessage());
					}
				} else {

					return redirect()->back()->withErrors($validation)->withInput()->with('error', 'Please Register first and complete your profile to get started.');
				}
			}
		}
		$data['currencies'] = Currency::where('is_active', 1)->get();

		return view('frontend.escrows.get_started', $data);
	}

	public function success()
	{
		$data = [];
		// return view('frontend.home.success', $data);
		return view('frontend.dashboard.success', $data);
	}
	/**
	 * buyer_login
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function buyer_login(Request $request)
	{
		$data = [];
		if ($request->all()) {
			$tid = explode('-', $request['login_id']);
			$buyer = decode($tid[0]);
			if ($buyer) {
				$isExist = User::where(['id' => $buyer, 'user_type' => 2])->first()->toArray();
				if ($isExist) {
					if ($isExist['buyer_password_created'] == 0) {
						session(['buyer_login_data' => array('user' => $isExist, 'password' => $request['password'], 'login_id' => $request['login_id'])]);
						return redirect()->to(url('/setup-2fa'));
					} else {
						if (auth()->attempt(array('id' => $isExist['id'], 'password' => $request['password']))) {
							return redirect()->to(url('/profile'));
						} else {
							return redirect()->route('buyer-login')->with('error', 'Transaction ID / Password are incorrect. Please enter correct credentials.');
						}
					}
				} else {

					return redirect()->back()->with('error', 'Invalid Transaction Id.');
				}
			} else {
				return redirect()->back()->with('error', 'Invalid Transaction Id.');
			}
		}

		return view('frontend.home.buyer_login', $data);
	}

	public function setup_2fa(Request $request)
	{
		$data = [];
		$user = session('buyer_login_data');

		$input = $request->all();

		if ($request->has('one_time_password') && $input['one_time_password'] <> '' && $input['google2fa'] <> '') {

			try {
				$google2fa = app('pragmarx.google2fa');

				$valid = $google2fa->verifyGoogle2FA($input['google2fa'], $input['one_time_password']);
				if ($valid) {
					$buyer = User::where(['id' => $user['user']['id'], 'user_type' => 2])->first();
					$buyer->google2fa_secret = $request['google2fa'];
					$buyer->password = Hash::make($user['password']);
					$buyer->remember_token = Str::random(60);
					$buyer->buyer_password_created = 1;
					$buyer->save();


					Auth::loginUsingId($buyer->id, true);
					$request->session()->flash('success', '2FA setup successfully.');
					return redirect()->to(url('/profile'));
				} else {
					$request->session()->flash('error', 'Please scan QR again and enter one time password.');

					return redirect()->back();
				}
			} catch (\Exception $e) {

				$request->session()->flash('error', $e->getMessage());

				return redirect()->back();
			}
		}


		// initialise the 2FA class
		$google2fa = app('pragmarx.google2fa');


		// add the secret key to the registration data
		$data['secret'] = $google2fa_secret = $google2fa->generateSecretKey();
		// generate the QR image
		$data['QR_Image'] = $google2fa->getQRCodeInline(
			config('app.name'),
			$user['login_id'],
			$google2fa_secret
		);

		return view('frontend.home.setup_2fa', $data);
	}

	public function about_us()
	{
		$data = [];
		return view('frontend.home.about_us', $data);
	}
	public function faqs()
	{
		$data = [];
		$data['faq_categories'] = FaqCategories::with(['faqs' => function ($q) {
			$q->where('is_active', 1);
		}])->whereHas('faqs', function ($q) {
			$q->where('is_active', 1);
		})->where('is_active', 1)->get();

		return view('frontend.home.faqs', $data);
	}
	public function forum()
	{
		$data = [];
		return view('frontend.home.forum', $data);
	}

}
