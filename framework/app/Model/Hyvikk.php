<?php
namespace App\Model;
use App\Model\ApiSettings;
use App\Model\EmailContent;
use App\Model\FareSettings;
use App\Model\FrontendModel;
use App\Model\Settings;

Class Hyvikk {
	public static function get($key) {
		// dd(Settings::all()->toArray());
		$settings = array_pluck(Settings::all()->toArray(), 'value', 'name');
		return (is_array($key)) ? array_only($settings, $key) : $settings[$key];
	}

	public static function set($key, $val) {
		$settings = Settings::firstOrNew(array('name' => $key));
		$settings->value = $val;
		$settings->save();
		Cache::flush();
	}

	public static function api($key) {
		$settings = array_pluck(ApiSettings::all()->toArray(), 'key_value', 'key_name');
		return (is_array($key)) ? array_only($settings, $key) : $settings[$key];
	}

	public static function fare($key) {
		$settings = array_pluck(FareSettings::all()->toArray(), 'key_value', 'key_name');
		return (is_array($key)) ? array_only($settings, $key) : $settings[$key];
	}

	public static function email_msg($key) {
		$settings = array_pluck(EmailContent::all()->toArray(), 'value', 'key');
		return (is_array($key)) ? array_only($settings, $key) : $settings[$key];
	}

	public static function frontend($key) {
		$settings = array_pluck(FrontendModel::all()->toArray(), 'key_value', 'key_name');
		return (is_array($key)) ? array_only($settings, $key) : $settings[$key];
	}

	public static function payment($key) {
		$settings = array_pluck(PaymentSettings::all()->toArray(), 'value', 'name');
		return (is_array($key)) ? array_only($settings, $key) : $settings[$key];
	}
}