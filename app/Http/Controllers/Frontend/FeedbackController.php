<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\EscrowProducts;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\User;
use DB;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        if ($request->has('review') && $request->review <> '') {
            $review_type = $request->review;
        } else {
            $review_type = 'buyer_reviews';
        }

        if ($review_type == 'buyer_reviews') {

            $data['reviews'] = Review::with(['product', 'reviewer', 'review_to_user'])
                ->where('reviewer_id', auth()->user()->id)
                ->where('admin_review', '<>', 1)
                ->where('is_active', 1)
                ->orderByDesc('id')->paginate(20);
            $data['user'] = 'review_to_user';
        } else if ($review_type == 'seller_reviews') {

            $data['reviews'] = Review::with(['product', 'reviewer', 'review_to_user'])
                ->where('review_to', auth()->user()->id)
                ->where('admin_review', '<>', 1)
                ->where('is_active', 1)
                ->orderByDesc('id')->paginate(20);
            $data['user'] = 'reviewer';
        } else if ($review_type == 'admin_reviews') {

            $data['reviews'] = Review::with(['product', 'reviewer', 'review_to_user'])
                ->where('reviewer_id', auth()->user()->id)
                ->where('admin_review', 1)
                ->where('is_active', 1)
                ->orderByDesc('id')->paginate(20);
            $data['user'] = 'reviewer';
        }
        // dd($data['reviews']);

        $data['review_type'] = $review_type;
        return view('frontend.feedback.feedback', $data);
    }

    public function buyer_review($id, Request $request)
    {
        $data = [];

        $id = decode($id);
        if ($id) {
            $data['product'] = $product = EscrowProducts::with(['buyer', 'review', 'seller'])->where('id', $id)->first();
            if (!$data['product']) {
                return redirect()->to('/')->with('error', 'You are not allowed to review this product.');
            }
            if ($product->buyer_id <> auth()->user()->id && $product->seller_id == auth()->user()->id) {
                return redirect()->to('/')->with('error', 'You are not allowed to review this product.');
            }
          
        }

        if ($request->all()) {
            $input = $request->all();
            /** Validate */
            $validation = $request->validate([
                'rating' => ['required'],
                'feedback' => ['required', 'string', 'max:500'],
            ]);
            DB::beginTransaction();
            try {

                $review = [
                    'product_id'  => $product->id,
                    'rating' => $input['rating'],
                    'review' => removeHtmlUrls($input['feedback']),
                    'is_active' => 1,
                    'admin_review' => 3,
                    'review_to' => $product->seller_id,
                    'reviewer_id' => auth()->user()->id
                ];
                Review::create($review);

                DB::commit();

                return redirect()->to('/pending-feedback')->with('success', 'Thank You for your feedback.');
            } catch (\Exception $e) {
                DB::rollback();

                return redirect()->back()->withErrors($validation)->with('error', $e->getMessage());
            }
        }
        $data['username'] = $product->seller->username;
        $data['route'] = route('buyer.review', ['id' => encode($product->id)]);
        return view('frontend.home.review', $data);
    }

    public function seller_review($id, Request $request)
    {
        $data = [];

        $id = decode($id);
        if ($id) {
            $data['product'] = $product = EscrowProducts::with(['buyer', 'review', 'seller'])->where('id', $id)->first();
            if (!$data['product']) {

                return redirect()->to('/')->with('error', 'You are not allowed to review this product.');
            }
            if ($product->seller_id <> auth()->user()->id && $product->buyer_id == auth()->user()->id) {
                return redirect()->to('/')->with('error', 'You are not allowed to review this product.');
            }
       
        }

        if ($request->all()) {
            $input = $request->all();
            /** Validate */
            $validation = $request->validate([
                'rating' => ['required'],
                'feedback' => ['required', 'string', 'max:500'],
            ]);

            DB::beginTransaction();
            try {

                $review = [
                    'product_id'  => $product->id,
                    'rating' => $input['rating'],
                    'review' => removeHtmlUrls($input['feedback']),
                    'is_active' => 1,
                    'admin_review' => 2,
                    'review_to' => $product->buyer_id,
                    'reviewer_id' => auth()->user()->id
                ];
                Review::create($review);

                DB::commit();

                return redirect()->to('/pending-feedback')->with('success', 'Thank You for your feedback.');
            } catch (\Exception $e) {
                DB::rollback();

                return redirect()->back()->withErrors($validation)->with('error', $e->getMessage());
            }
        }

        $data['username'] = $product->buyer->username;
        $data['route'] = route('seller.review', ['id' => encode($product->id)]);
        return view('frontend.home.review', $data);
    }

    public function admin_review($id, Request $request)
    {
        $data = [];

        $id = decode($id);
        if ($id) {
            $data['product'] = $product = EscrowProducts::with(['buyer', 'seller'])->where('id', $id)->first();

            if (!$data['product']) {

                return redirect()->to('/')->with('error', 'You are not allowed to review this product.');
            }
            $reviews = Review::where(
                [
                    'reviewer_id' => auth()->user()->id,
                    'product_id' => $id,
                    'admin_review' => 1
                ]
            )->count();

            if ($reviews > 0) {
                return redirect()->to('/')->with('error', 'You are not allowed to review this product.');
            }
         
        }

        if ($request->all()) {
            $input = $request->all();
            /** Validate */
            $validation = $request->validate([
                'rating' => ['required'],
                'feedback' => ['required', 'string', 'max:500'],
            ]);

            DB::beginTransaction();
            try {

                $review = [
                    'product_id'  => $product->id,
                    'rating' => $input['rating'],
                    'review' => removeHtmlUrls($input['feedback']),
                    'is_active' => 1,
                    'admin_review' => 1,
                    'review_to' => null,
                    'reviewer_id' => auth()->user()->id
                ];
                Review::create($review);

                DB::commit();

                return redirect()->to('/')->with('success', 'Thank You for your feedback.');
            } catch (\Exception $e) {
                DB::rollback();

                return redirect()->back()->withErrors($validation)->with('error', $e->getMessage());
            }
        }

        $data['username'] = 'Admin';
        $data['route'] = route('admin.review', ['id' => encode($product->id)]);
        return view('frontend.home.review', $data);
    }

    public function reviews(Request $request)
    {
        $data = [];

        $data['q'] = $q = '';
        if ($request->has('q') && $request->q <> '') {
            $data['q'] = $q = strtolower($request->q);
        }
        $data_query = User::with(['reviews','reviews.product', 'reviews.reviewer'])->whereHas('reviews',function($query){
            $query->where('admin_review', ((auth()->user()->user_type == 2) ? 3 : 2));
        });
        if ($q <> '') {
            $data_query->where(\DB::raw('LOWER(username)'), 'LIKE', '%' . $q . '%');
        }

        $data['users'] = $data_query->paginate(10);
       

        $data['user'] = 'review_to_user';
        return view('frontend.feedback.reviews', $data);
    }
    public function pending_review(Request $request) {
        $data = [];
        $products = DB::table('escrow_products');
            $products->leftJoin('reviews', 'escrow_products.id', '=', 'reviews.product_id');
            $products->join('transactions', 'escrow_products.id', '=', 'transactions.product_id');
            $products->where('transactions.status_id', 7);
             if(auth()->user()->user_type == 1) {
                 $products->where('escrow_products.seller_id', auth()->user()->id);

             } elseif(auth()->user()->user_type == 2) {
                $products->where('escrow_products.buyer_id', auth()->user()->id);
             }
        $data['reviews'] =   $products->paginate(20);
        return view('frontend.feedback.pending', $data);
    }
    public function reviewDetails($user_id)
    {

        $user_id = decode($user_id);
        $data = [];
        $data['reviews'] = Review::with(['product', 'reviewer', 'review_to_user'])
            ->where('review_to', $user_id)
            ->where('is_active', 1)
            ->orderByDesc('id')
            ->paginate(20);
        $data['user'] = User::where('id', $user_id)->first();
        return view('frontend.feedback.review-details', $data);

    }
}
