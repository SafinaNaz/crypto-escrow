<?php

use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;


if (!function_exists('btc_address_validate')) {

	/**
	 * btc_address_validate
	 *
	 * @param  mixed $string
	 * @return void
	 */
	function btc_address_validate($string = '')
	{
		return \LinusU\Bitcoin\AddressValidator::isValid($string, \LinusU\Bitcoin\AddressValidator::MAINNET);
	}
}
if (!function_exists('xmr_address_validate')) {

	/**
	 * xmr_address_validate
	 *
	 * @param  mixed $string
	 * @return void
	 */
	function xmr_address_validate($string = '')
	{
		if ($string[0] != '4' || !preg_match('/([0-9]|[A-B])/', substr($string, 1)) || strlen($string) != 95) {
			return false;
		}
		return true;
	}
}


function _asset($path, $secure = null)
{
	return asset(trim($path, '/'), $secure); //. '?var=' . config('constants.ASSET_VERSION');
}

function settingValue($key)
{

	$setting = \DB::table('site_settings')->select($key)->first();
	if ($setting)
		return $setting->$key;
	else
		return '';
}

//Encode Helper
function encode($string = '')
{
	return Hashids::encode($string);
}

function decode($string = '')
{

	try {
		$id =  Hashids::decode($string);
		if ($id) {
			return $id[0];
		} else {
			abort(404);
		}
	} catch (Exception $e) {
		abort(404);
	}
}


function removeHtmlUrls($string)
{
	return preg_replace("/((http(s)?(\:\/\/))+(www\.)?([\w\-\.\/])*(\.[a-zA-Z]{2,3}\/?)|(www\.)?([\w\-\.\/])*(\.[a-zA-Z]{2,3}\/?))[^\s\b\n|]*[^.,;:\?\!\@\^\$ -]|<.*?>/", "", $string);
}

function encryptText($message)
{
	$pathToPrivateKey = storage_path('keys/privateKey');
	$privateKey = PrivateKey::fromFile($pathToPrivateKey, config('constants.ENCRIPTION_PASSWORD'));
	return $message = $privateKey->encrypt($message);
}

function decryptText($message)
{
	$pathToPublicKey = storage_path('keys/publicKey');
	$publicKey = PublicKey::fromFile($pathToPublicKey, config('constants.ENCRIPTION_PASSWORD'));
	return $decryptedData = $publicKey->decrypt($message); // returns 'my secret data'
}

function aasort(&$array, $key, $order = 0)
{

	$sorter = array();
	$ret = array();
	reset($array);
	foreach ($array as $ii => $va) {
		$sorter[$ii] = $va[$key];
	}
	if ($order == 0) {
		asort($sorter);
	} else {
		arsort($sorter);
	}
	foreach ($sorter as $ii => $va) {
		$ret[$ii] = $array[$ii];
	}
	$array = $ret;
}

function footer_menu($limit, $slugs)
{

	$where = '';
	if (!empty($slugs) && is_array($slugs)) {
		$where .= '(';
		foreach ($slugs as $slg) {
			$where .= 'LOWER(cms_pages.seo_url) LIKE "%' . strtolower($slg) . '%" OR ';
		}
		$where = rtrim($where, 'OR ');
		$where .= ')';
	}

	$result = \DB::table('cms_pages')
		->select('*')
		->where('show_in_footer', 1)
		->where('is_active', 1)
		->whereRaw($where)
		->orderBy('sort_by', 'ASC')
		->limit($limit)
		->get();

	return $result;
}
function header_menu($limit)
{
	$result = \DB::table('cms_pages')
		->select('*')
		->where('show_in_header', 1)
		->where('is_active', 1)
		->orderBy('sort_by', 'ASC')
		->limit($limit)
		->get();

	return $result;
}
function home_cms_pages($limit = 10)
{
	$result = \DB::table('cms_pages')
		->select('*')
		->where('is_home', 1)
		->where('is_active', 1)
		->orderBy('sort_by', 'ASC')
		->limit($limit)
		->get();

	return $result;
}

function checkImage($path = '', $img = 'large', $profile = 0)
{
	$extension = pathinfo($path, PATHINFO_EXTENSION);

	if ($extension == 'svg') {
		return $path;
	} else {
		if (@getimagesize($path)) {
			return $path;
		} else {
			if ($profile == 1) {
				return asset('frontend/dashboard/images/user-thumb-sm.png');
			} else {
				if ($img == 'large') {
					return asset('backend/images/no_img.jpg');
				} else {
					return asset('backend/images/no_image.jpg');
				}
			}
		}
	}
}
function checkProfileImage($user_type)
{
	if ($user_type == 1) {
		return asset('frontend/dashboard/images/user-thumb-seller.png');
	} else {
		return asset('frontend/dashboard/images/user-thumb-buyer.png');
	}
}

function getVal($col, $table, $where = '', $criteria = '')
{
	$res = \DB::table($table)
		->selectRaw($col)
		->where($where, $criteria)
		->first();
	if ($res) {
		return $res->$col;
	} else {
		return '';
	}
}
function review_exit($id, $reviewer_id)
{
	$result = \DB::table('reviews')
	->where('product_id', $id)
	->where('reviewer_id', $reviewer_id)
	->first();
  
	return $result;
}

function no_of_transaction($id)
{
	$result = \DB::table('escrow_products');
	if(auth()->user()->user_type == 2) {
		$result->where('seller_id', $id);
	} else {
		$result->where('buyer_id', $id);
	}
		$result->get();

	$result_count = $result->count();
  
	return $result_count;
}
function trans_before_msg($pro_id, $type, $status,$user_id) {
	$trans_msg = \DB::table('transaction_messages');
	$trans_msg ->select('message');
	$trans_msg->where('product_id', $pro_id);
	$trans_msg->where('transaction_type', $type);
	$trans_msg->where('product_status', $status);
	if(auth()->user()->user_type == 1) {
		$trans_msg->where('seller_id', $user_id);
	} elseif(auth()->user()->user_type == 2) {
		$trans_msg->where('buyer_id', $user_id);
	}
	$result = $trans_msg->first();
	return $result;
}
function trans_admin_msg($pro_id,$user_id,$type) {
	$trans_msg = \DB::table('transaction_messages');
	$trans_msg ->select('message');
	$trans_msg->where('product_id', $pro_id);
	$trans_msg->where('transaction_type', $type);
	$trans_msg->where('admin_id', $user_id);
	$result = $trans_msg->first();
	return $result;
}

function user_info($user_id) {
	$users = \DB::table('users');
	$users->where('id', $user_id);
	$result = $users->first();
	return $result;
}



