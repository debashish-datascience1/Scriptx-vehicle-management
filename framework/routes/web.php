<?php
// Route::get('/', 'FrontendController@index')->middleware('IsInstalled');

//if (env('front_enable') == 'no') {
    Route::get('/', function () {
        return redirect('admin');
    })->middleware('IsInstalled');
//} else {
   // Route::get('/', 'FrontendController@index')->middleware('IsInstalled');
//}

Route::get('installation', 'LaravelWebInstaller@index');
Route::post('installed', 'LaravelWebInstaller@install');
Route::get('installed', 'LaravelWebInstaller@index');
Route::get('migrate', 'LaravelWebInstaller@db_migration');
Route::get('migration', 'LaravelWebInstaller@migration');
Route::get('upgrade', 'UpdateVersion@upgrade')->middleware('canInstall');
Route::get('upgrade3', 'UpdateVersion@upgrade3')->middleware('canInstall');
Route::get('upgrade4', 'UpdateVersion@upgrade4')->middleware('canInstall');
Route::get('upgrade4.0.2', 'UpdateVersion@upgrade402')->middleware('canInstall');
Route::get('upgrade4.0.3', 'UpdateVersion@upgrade403')->middleware('canInstall');

// stripe payment integration
Route::get('stripe/{booking_id}', 'PaymentController@stripe');
Route::get('stripe-success', 'PaymentController@stripe_success');
Route::get('stripe-cancel', 'PaymentController@stripe_cancel');

// razorpay payment integration
Route::get('razorpay/{booking_id}', 'PaymentController@razorpay');
Route::post('razorpay-success', 'PaymentController@razorpay_success');
Route::get('razorpay-failed', 'PaymentController@razorpay_failed');

// cash payment
Route::get('cash/{booking_id}', 'PaymentController@cash');

Route::get('sample-payment', function () {
    return view('payments.test_pay');
});

// Route::post('redirect-payment', 'PaymentController@redirect_payment');

// Route::get('all-data', function () {
//     $bookings = BookingPaymentsModel::latest()->get();
//     foreach ($bookings as $booking) {
//         if ($booking->payment_details != null) {
//             echo "<pre>";
//             print_r(json_decode($booking->payment_details));
//             echo "---------------------------------------------<br>";
//         }
//     }
// });
Route::get('clear_cache', function () {

    \Artisan::call('cache:clear');

    dd("Cache is cleared");

});
