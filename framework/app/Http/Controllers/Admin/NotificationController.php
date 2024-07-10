<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\ServiceReminderModel;
use App\Model\User;
use App\Model\VehicleModel;

class NotificationController extends Controller {

	public function vehicle_notification($type) {
		$vehicle = VehicleModel::get();
		return view('notifications.vehicle_notification', compact('type', 'vehicle'));
	}

	public function driver_notification($type) {
		$driver = User::where('user_type', "D")->get();
		return view('notifications.driver_notification', compact('type', 'driver'));

	}

	public function service_reminder($type) {
		$reminder = ServiceReminderModel::get();
		return view('notifications.service_reminder', compact('type', 'reminder'));
	}

}