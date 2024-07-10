<?php
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
 */

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Model\User::class, function (Faker $faker) {
	static $password;

	return [
		'name' => $faker->name,
		'email' => $faker->unique()->safeEmail,
		'user_type' => "D",
		'api_token' => str_random(60),
		'password' => bcrypt('secret'),
		'remember_token' => str_random(10),
	];
});

$factory->define(App\Model\UserMeta::class, function (Faker $faker) {
	// $date = $faker->dateTimeThisCentury->format('Y-m-d');
	$start_date = Carbon\Carbon::today()->toDateString();
	$date = strtotime(date('Y-m-d'));
	$newDate = date('Y-m-d', strtotime('+1 month', $date));

	$issue_date = Carbon\Carbon::today()->toDateString();
	$expDate = date('Y-m-d', strtotime('+2 month', $date));
	$user = factory('App\Model\User')->create();
	$name = explode(" ", $user->name);
	// $num = $faker->e164PhoneNumber . "55555";

	return [

		'user_id' => $user->id,
		'first_name' => $name[0],
		'last_name' => $name[1],
		'address' => $faker->address,
		'phone' => $faker->e164PhoneNumber,
		'issue_date' => $issue_date,
		'end_date' => $newDate,
		'exp_date' => $newDate,
		'start_date' => $start_date,

		'license_number' => $faker->unique()->numberBetween($min = 100000, $max = 900000),
		'contract_number' => $faker->unique()->numberBetween($min = 1000, $max = 9000),
		'emp_id' => $faker->unique()->randomNumber,
	];
});

$factory->define(App\Model\Bookings::class, function (Faker $faker) {
	// $customer = factory('App\Model\Customers')->create();
	$date = $faker->dateTimeThisMonth($max = 'now', $timezone = date_default_timezone_get());
	$drop = $faker->dateTimeInInterval($startDate = $date, $interval = '+ 2 days', $timezone = date_default_timezone_get());

	return [
		'customer_id' => $faker->randomElement([4, 5]),
		'user_id' => 1,
		'vehicle_id' => 1,
		'driver_id' => $faker->randomElement([6, 7]),
		'pickup' => $date,
		'dropoff' => $drop, //add 2 days
		'duration' => '2880',
		'pickup_addr' => $faker->address,
		'dest_addr' => $faker->address,
		'note' => 'sample note',
		'travellers' => $faker->randomElement([1, 2, 3, 4]),
		'status' => 0,

	];
});

$factory->define(App\Model\Vendor::class, function (Faker $faker) {

	return [
		'name' => $faker->name,
		'email' => $faker->unique()->safeEmail,
		'type' => $faker->randomElement(['Fuel', 'Machinaries', 'Parts']),
		'website' => "http://www.example.com",
		'note' => 'default vendor',
		'phone' => str_replace('+', 0, $faker->e164PhoneNumber),
		'address1' => $faker->address,
		'city' => $faker->city,
	];
});

$factory->define(App\Model\WorkOrders::class, function (Faker $faker) {
	$vendor = factory(App\Model\Vendor::class)->create();
	return [
		'vehicle_id' => $faker->randomElement([1, 2]),
		'vendor_id' => $vendor->id,
		'created_on' => date('Y-m-d'),
		'required_by' => date('Y-m-d', strtotime(' +5 day')),
		'status' => $faker->randomElement(["Pending", "Processing", "Completed"]),
		'description' => "Sample work order",
		'meter' => mt_rand(1000, 3000),
		'price' => $faker->randomElement([1000, 2000, 3000]),
		'note' => "sample work order",

	];
});