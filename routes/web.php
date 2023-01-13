<?php

use App\Http\Controllers\Frontend\MessaingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes(['verify' => false]);

Route::get('/clear-cache', function () {
    // Artisan::call('storage:link'); //
    Artisan::call('optimize:clear'); //storage:link
    // Artisan::call('debugbar:clear');
    return redirect('/');
});
Route::get('/migrate', function() {
    $re = Artisan::call('migrate');
    return redirect('/');
});

Route::namespace('Auth')->group(function () {
    //Login Routes
    // Route::get('/', 'LoginController@showLoginForm')->name('login');
    // Route::get('/login', 'LoginController@showLoginForm')->name('login');
    Route::post('/password/username', 'ForgotPasswordController@check_username')->name('password.username');

    Route::get('/logout', 'LoginController@logout')->name('logout');
    Route::get('/complete-signup', 'RegisterController@complete_signup')->name('register.complete');
});
Route::post('/2fa')->name('2fa')->middleware('2fa', 'guest');





Route::group(['namespace' => 'Frontend'], function () {

    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/get-started', 'HomeController@get_started');
    Route::post('/get-started', 'HomeController@get_started')->name('get-started');
    Route::get('/success', 'HomeController@success');


    Route::get('/buyer-login', 'HomeController@buyer_login');
    Route::post('/buyer-login', 'HomeController@buyer_login')->name('buyer-login');
    Route::get('/setup-2fa', 'HomeController@setup_2fa');
    Route::post('/setup-2fa', 'HomeController@setup_2fa')->name('setup-2fa');


    $pages = \DB::table('cms_pages')->select('seo_url')->where('is_active', 1)->get();
    foreach ($pages as $page) {
        Route::get($page->seo_url, 'HomeController@cms_pages');
    }
    Route::get('contact-us', 'HomeController@contact_us');
    Route::post('contact-us', 'HomeController@contact_us')->name('contact-us');

    // Route::get('about-us', 'HomeController@about_us');
    Route::get('faqs', 'HomeController@faqs');
    Route::get('forum', 'HomeController@forum');

});


Route::group(['namespace' => 'Frontend', 'middleware' => ['auth:web', '2fa']], function () {

    Route::get('/escrows', 'EscrowsController@index')->name('fescrows.index');
    Route::post('/escrows', 'EscrowsController@index')->name('escrows');
    Route::post('/escrows/approve', 'EscrowsController@escrows_approve');

    Route::get('/transaction-detail/{id?}/{type}', 'EscrowsController@transaction_detail');
    // Route::get('/update-transaction/{id?}/complete', 'EscrowsController@update_complete_status');
    Route::post('/update-transaction/{id?}/{type}', 'EscrowsController@update_transaction_status')->name('update-transaction');

    Route::get('/dispute/{id?}', 'EscrowsController@dispute_escrow');
    Route::post('/create-dispute', 'EscrowsController@create_dispute')->name('create-dispute');

    Route::post('/request-poc', 'EscrowsController@buyer_request_poc')->name('request.poc');

    Route::get('/respond-poc/{id?}', 'EscrowsController@respond_poc');
    Route::post('/update-poc-request/{id?}', 'EscrowsController@update_poc_request')->name('update-poc-request');

    Route::get('/profile', 'UserController@index')->name('profile');
    Route::post('/profile-update', 'UserController@profile_update')->name('profile-update');
    Route::get('/change-password', 'UserController@change_password');
    Route::post('/change-password', 'UserController@change_password')->name('change-password');

    Route::get('/2fa-setup', 'UserController@authenticate_2fa');
    Route::post('/2fa-setup', 'UserController@authenticate_2fa')->name('2fa-setup');


    Route::get('/feedback', 'FeedbackController@index');
    Route::get('/review/{id}', 'FeedbackController@buyer_review');
    Route::post('/review/{id}', 'FeedbackController@buyer_review')->name('buyer.review');

    // New Module
    Route::get('/pending-feedback', 'FeedbackController@pending_review')->name('pending.review');
    Route::get('/review/{id}', 'FeedbackController@buyer_review');
    Route::post('/review/{id}', 'FeedbackController@buyer_review')->name('buyer.review');

    /**ALL public page reviews */
    Route::get('reviews', 'FeedbackController@reviews');
    Route::get('review-details/{user_id}', 'FeedbackController@reviewDetails')->name('review-details');

    Route::get('/seller-review/{id}', 'FeedbackController@seller_review');
    Route::post('/seller-review/{id}', 'FeedbackController@seller_review')->name('seller.review');

    Route::get('/admin-review/{id}', 'FeedbackController@admin_review');
    Route::post('/admin-review/{id}', 'FeedbackController@admin_review')->name('admin.review');


    Route::get('/verification-status', 'EtlController@index');
    Route::post('/verification-status', 'EtlController@etl')->name('verification-status');
    Route::get('/whitelist', 'WhitelistController@index');
    Route::get('/escalate-decision',  'EscalateDecisionController@index');


    Route::get('/dispute-messages/{id?}', 'EscalateDecisionController@dispute_messages');
    Route::post('/send-dispute-message', 'EscalateDecisionController@send_dispute_message')->name('send_dispute_message');
    Route::post('/update-dispute-status', 'EscalateDecisionController@update_dispute_status')->name('update_dispute_status');


    Route::get('/messages/{id?}', 'ChatsController@index')->name('messages');
    Route::post('/sendMessage', 'ChatsController@sendMessage')->name('sendMessage');

    Route::resource('/support-ticket', 'SupportTicketsController');
    Route::get('/support-ticket/view/{id?}', 'SupportTicketsController@view');
    Route::get('/support-ticket/change-status/{id?}/{type?}', 'SupportTicketsController@change_status_ticket');
    Route::post('/support-ticket/reply', 'SupportTicketsController@reply');
});


Route::prefix('/admin')->name('admin.')->namespace('Admin')->group(function () {

    Route::namespace('Auth')->group(function () {
        //Login Routes
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::get('/login', 'LoginController@showLoginForm')->name('login');
        Route::post('/login', 'LoginController@login')->name('login.submit');
        Route::get('/logout', 'LoginController@logout')->name('logout');
    });
});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => 'auth:admin'], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::get('/site-settings', 'SiteSettingsController@index');
    Route::post('/site-settings/update', 'SiteSettingsController@update')->name('site-settings.update');

    Route::get('/escrow-settings', 'SiteSettingsController@escrow_index');
    Route::post('/escrow-settings/update', 'SiteSettingsController@escrow_update')->name('escrow-settings.update');

    Route::get('/announcement-settings', 'SiteSettingsController@announcement_index');
    Route::post('/announcement-settings/update', 'SiteSettingsController@announcement_update')->name('announcement-settings.update');

    Route::resource('/roles', 'RolesController');
    Route::resource('/permissions', 'PermissionsController');
    //Admin Users
    Route::resource('/users', 'UsersController');
    Route::get('/profile', 'UsersController@profile');
    Route::post('/update-profile', 'UsersController@updateProfile');
    Route::post('users/update-status', 'UsersController@update_status');

    Route::resource('cms-pages', 'CmsPagesController');
    Route::post('cms-pages/update-status', 'CmsPagesController@update_status');

    Route::resource('sellers', 'SellersController');
    Route::post('sellers/update-status', 'SellersController@update_status');
    Route::post('sellers/approve-etl', 'SellersController@approve_etl');

    Route::resource('buyers', 'BuyersController');
    Route::post('buyers/update-status', 'BuyersController@update_status');

    //templates
    Route::resource('templates', 'TemplatesController');
    Route::post('templates/update-status', 'TemplatesController@update_status');

    Route::get('/messages', 'MessagesController@index');
    Route::post('/messages', 'MessagesController@index')->name('messages.index');

    Route::get('/messages/{id?}/view', 'MessagesController@view');
    Route::post('/send-message', 'MessagesController@send_message')->name('messages.sendMessage');

    Route::get('/escrows', 'EscrowsController@index');
    Route::post('/escrows', 'EscrowsController@index')->name('escrows.index');

    Route::get('/transactions', 'EscrowsController@transactions');
    Route::post('/transactions', 'EscrowsController@transactions')->name('escrows.transactions');

    Route::get('/transaction-status/{id?}/{type}', 'EscrowsController@transaction_status');
    Route::post('/update-transaction/{id?}/{type}', 'EscrowsController@update_transaction_status')->name('escrow.update-transaction');

    // Route::get('/dispute-history/{id?}/finish', 'EscrowsController@dispute_history');
    Route::get('/dispute-history/{id?}/{type?}', 'EscrowsController@dispute_history');
    Route::post('/send-dispute-message', 'EscrowsController@send_dispute_message')->name('messages.send_dispute_message');



    Route::get('/reviews', 'ReviewsController@index');
    Route::post('/reviews', 'ReviewsController@index')->name('reviews.index');
    Route::post('reviews/update-status', 'ReviewsController@update_status');

    Route::get('/logs', 'LogsController@index');
    Route::post('/logs', 'LogsController@index')->name('logs.index');

    Route::resource('contactus-log', 'ContactUsController');
    Route::get('/contactus-log', 'ContactUsController@index');
    Route::post('/contactus-log', 'ContactUsController@index')->name('contactus-log.index');
    Route::get('/contactus-log/detail/{id?}', 'ContactUsController@detail');
    Route::post('/contactus-log/send_email', 'ContactUsController@reply')->name('contactus-log.send_email');

    Route::get('/support-ticket', 'SupportTicketsController@index');
    Route::post('/support-ticket', 'SupportTicketsController@index')->name('messages.index');
    Route::get('/support-ticket/{id?}/view', 'SupportTicketsController@view');
    Route::get('/support-ticket/change-status/{id?}/{type?}', 'SupportTicketsController@change_status_ticket');
    Route::post('/support-ticket/reply', 'SupportTicketsController@reply');

    //Faq Categories
    Route::resource('faq-categories', 'FaqCategoriesController');
    Route::post('faq-categories/update-status', 'FaqCategoriesController@update_status');
    //Faq
    Route::resource('faqs', 'FaqsController');
    Route::post('faqs/update-status', 'FaqsController@update_status');
});
