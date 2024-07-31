<?php
Auth::routes();
Route::namespace('Admin')->group(function () {
    // Route::get('export-events', 'HomeController@export_calendar');

    Route::get("/clear_cache", function () {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        dd("success");
    });

    Route::get("/", 'HomeController@index')->middleware(['lang_check', 'auth']);
    Route::group(['middleware' => ['lang_check', 'auth', 'officeadmin']], function () {
        // Route::get('test', function () {
        //     return view('geocode');
        // });
        Route::get('refresh-json/{id}', 'SettingsController@refresh_json')->middleware('userpermission:S');
        Route::post('clear-database', 'SettingsController@clear_database')->middleware('userpermission:S');
        Route::post('cancel-booking', 'BookingsController@cancel_booking');
        Route::resource('team', 'TeamController');
        Route::resource('company-services', 'CompanyServicesController')->middleware('userpermission:S');
        Route::get('parts-used/{id}', 'WorkOrdersController@parts_used')->middleware('userpermission:7');
        Route::get('remove-part/{id}', 'WorkOrdersController@remove_part')->middleware('userpermission:7');
        Route::post('add-stock', 'PartsController@add_stock');
        Route::resource('booking-quotation', 'BookingQuotationController')->middleware('userpermission:3');
        Route::post('add-booking', 'BookingQuotationController@add_booking');
        Route::get('booking-quotation/invoice/{id}', 'BookingQuotationController@invoice')->middleware('userpermission:3');
        Route::get('print-quote/{id}', 'BookingQuotationController@print')->middleware('userpermission:3');
        Route::get('booking-quotation/approve/{id}', 'BookingQuotationController@approve')->middleware('userpermission:3');
        Route::post('import-vehicles', 'VehiclesController@importVehicles');
        Route::post('import-drivers', 'DriversController@importDrivers');
        Route::post('import-income', 'IncomeCategories@importIncome');
        Route::post('import-expense', 'ExpenseCategories@importExpense');
        Route::post('import-customers', 'CustomersController@importCutomers');
        Route::post('import-vendors', 'VendorController@importVendors');
        Route::get('frontend-settings', 'SettingsController@frontend')->middleware('userpermission:S');
        Route::post('frontend-settings', 'SettingsController@store_frontend');
        Route::resource('testimonials', 'TestimonialController')->middleware('userpermission:15');
        // routes for bulk delete action
        Route::post('delete-team', 'TeamController@bulk_delete');
        Route::post('delete-company-services', 'CompanyServicesController@bulk_delete');
        Route::post('delete-testimonials', 'TestimonialController@bulk_delete');
        Route::post('delete-reasons', 'ReasonController@bulk_delete');
        Route::post('delete-income', 'Income@bulk_delete');
        Route::post('delete-expense', 'ExpenseController@bulk_delete');
        Route::post('delete-reminders', 'ServiceReminderController@bulk_delete');
        Route::post('delete-service-items', 'ServiceItemsController@bulk_delete');
        Route::post('delete-parts', 'PartsController@bulk_delete');
        Route::post('delete-work-orders', 'WorkOrdersController@bulk_delete');
        Route::post('delete-parts-category', 'PartsCategoryController@bulk_delete');
        Route::post('delete-users', 'CustomersController@bulk_delete');
        Route::post('delete-drivers', 'DriversController@bulk_delete');
        Route::post('delete-vehicles', 'VehiclesController@bulk_delete');
        Route::post('delete-fuel', 'FuelController@bulk_delete');
        Route::post('delete-vendors', 'VendorController@bulk_delete');
        Route::post('delete-bookings', 'BookingsController@bulk_delete');
        Route::post('delete-quotes', 'BookingQuotationController@bulk_delete');
        Route::post('delete-vehicle-types', 'VehicleTypeController@bulk_delete');
        Route::post('delete-vehicle-groups', 'VehicleGroupController@bulk_delete');
        Route::post('delete-vehicle-reviews', 'VehiclesController@bulk_delete_reviews');
        Route::post('purchase-info', 'VehiclesController@updateVehicleInfo')->name('purchase-info');


        Route::get('reports/income', 'ReportsController@income')->middleware('userpermission:4');
        Route::post('reports/income', 'ReportsController@income_post')->middleware('userpermission:4');
        Route::post('print-income', 'ReportsController@income_print');

        Route::get('reports/expense', 'ReportsController@expense')->middleware('userpermission:4');
        Route::post('reports/expense', 'ReportsController@expense_post')->middleware('userpermission:4');
        Route::post('print-expense', 'ReportsController@expense_print');

        Route::get('work_order/logs', 'WorkOrdersController@logs')->middleware('userpermission:7');
        Route::get('reports/vendor-work-order', 'ReportsController@workOrderReport_vendor')->name('reports.vendor-work-order')->middleware('userpermission:7');
        Route::post('reports/vendor-work-order', 'ReportsController@workOrderReport_vendorpost')->name('reports.vendor-work-order')->middleware('userpermission:7');
        Route::post('print-work-order-vendor-report', 'ReportsController@workOrderReport_vendorprint')->middleware('userpermission:7');
        Route::resource('parts-category', 'PartsCategoryController')->middleware('userpermission:14');

        Route::get('driver-logs', 'VehiclesController@driver_logs')->middleware('userpermission:1');
        Route::resource('/vehicle-types', 'VehicleTypeController')->middleware('userpermission:1');
        Route::get('print-vehicle-review/{id}', 'VehiclesController@print_vehicle_review')->middleware('userpermission:1');
        Route::get('view-vehicle-review/{id}', 'VehiclesController@view_vehicle_review')->middleware('userpermission:1');
        Route::get('vehicle-reviews-create', 'VehiclesController@vehicle_review')->middleware('userpermission:1');
        Route::get('vehicle-reviews', 'VehiclesController@vehicle_review_index')->name('vehicle_reviews')->middleware('userpermission:1');
        Route::get('vehicle-tax/{id}', 'VehiclesController@vehicle_tax')->name('vehicle_tax')->middleware('userpermission:1');
        Route::put('vehicle-tax-update/', 'VehiclesController@vehicle_tax_update')->name('vehicle_tax_update')->middleware('userpermission:1');
        Route::get('vehicle-review/{id}/edit', 'VehiclesController@review_edit')->middleware('userpermission:1');
        Route::post('vehicle-review-update', 'VehiclesController@update_vehicle_review')->middleware('userpermission:1');
        Route::post('store-vehicle-review', 'VehiclesController@store_vehicle_review')->middleware('userpermission:1');
        Route::delete('delete-vehicle-review/{id}', 'VehiclesController@destroy_vehicle_review')->middleware('userpermission:1');
        //maps
        Route::get('single-driver/{id}', 'DriversController@single_driver');
        Route::get('drivers/event/{id}', 'DriversController@view_event');
        Route::get('driver-maps/', 'DriversController@driver_maps')->middleware('userpermission:12');
        Route::get('markers/', 'DriversController@markers');
        Route::get('track-driver/{id}', 'DriversController@track_driver');

        Route::post('print-users-report', 'ReportsController@print_users');


        Route::post('print-customer-report', 'ReportsController@print_customer');
        Route::get('print-vendor-report', 'ReportsController@print_vendor');
        Route::post('print-driver-report', 'ReportsController@print_driver');
        Route::post('print-yearly-report', 'ReportsController@print_yearly');
        Route::post('print-fuel-report', 'ReportsController@print_fuel');
        Route::get('print_booking_new/{id}', 'BookingsController@print_booking_new'); //new added 
        Route::post('print-booking-report', 'ReportsController@print_booking');
        Route::post('print-stock-report', 'ReportsController@print_stock');
        Route::post('print-deliquent-report', 'ReportsController@print_deliquent');
        Route::post('print-monthly-report', 'ReportsController@print_monthly');
        // Route::get('print-bookings', 'BookingsController@print_bookings');
        Route::get('reviews', 'ReviewRatings@index')->middleware('userpermission:10');
        Route::get('messages', 'ContactUs@index');
        Route::get('api-settings', 'SettingsController@api_settings')->middleware('userpermission:S');
        Route::post('api-settings', 'SettingsController@store_settings')->middleware('userpermission:S');

        Route::get('reports/service-reminder', 'ReportsController@serviceReminder')->name('reports.service-reminder')->middleware('userpermission:6');
        Route::post('reports/service-reminder', 'ReportsController@serviceReminder_post')->name('reports.service-reminder')->middleware('userpermission:6');
        Route::post('print-service-reminder-report', 'ReportsController@serviceReminder_print')->middleware('userpermission:6');

        Route::get('fb', 'SettingsController@fb_create')->name('fb');
        Route::post('firebase-settings', 'SettingsController@firebase')->middleware('userpermission:S');
        Route::get('fare-settings', 'SettingsController@fare_settings')->middleware('userpermission:S');
        Route::post('fare-settings', 'SettingsController@store_fareSettings')->middleware('userpermission:S');
        Route::post('store-key', 'SettingsController@store_key')->middleware('userpermission:S');
        Route::get('test-key', 'SettingsController@test_key')->middleware('userpermission:S');
        Route::post('store-api', 'SettingsController@store_api')->middleware('userpermission:S');
        Route::resource('service-reminder', 'ServiceReminderController')->middleware('userpermission:9');
        Route::resource('service-item', 'ServiceItemsController')->middleware('userpermission:9');


        Route::get('/reports/upcoming-renewal', 'ReportsController@upcomingreport')->name('reports.upcoming-report')->middleware('userpermission:4');
        Route::post('/reports/upcoming-renewal', 'ReportsController@upcomingreport_post')->name('reports.upcoming-report')->middleware('userpermission:4');
        Route::post('/print-upcoming-renewal-report', 'ReportsController@print_upcomingreport')->middleware('userpermission:4');
        Route::get('/reports/vehicle-docs', 'ReportsController@documentRenewReport')->name('reports.vehicle-docs')->middleware('userpermission:4');
        Route::post('/reports/vehicle-docs', 'ReportsController@documentRenewReport_post')->name('reports.vehicle-docs')->middleware('userpermission:4');
        Route::post('/print-vehicle-docs-report', 'ReportsController@documentRenewReport_print')->middleware('userpermission:4');
        Route::get('/vehicle-docs/view_event/{id}', 'VehicleDocsController@view_event')->middleware('userpermission:1');
        Route::get('/vehicle-docs/renew-vehicles/{id}', 'VehicleDocsController@renewVehicles')->name('vehicle-docs.renew-vehicles')->middleware('userpermission:1');

        Route::post('/vehicle-docs/single-save', 'VehicleDocsController@singleStore')->name('vehicle-docs.single-save')->middleware('userpermission:1');
        Route::post('/vehicle-docs/get-next-date', 'VehicleDocsController@getNextDate')->name('vehicle-docs.getNext')->middleware('userpermission:1');
        Route::resource('/vehicle-docs', 'VehicleDocsController')->middleware('userpermission:1');


        Route::resource('/vehicle_group', 'VehicleGroupController')->middleware('userpermission:1');
        Route::post('/income_records', 'Income@income_records')->middleware('userpermission:2');
        Route::post('/expense_records', 'ExpenseController@expense_records')->middleware('userpermission:2');
        Route::post('/store_insurance', 'VehiclesController@store_insurance');
        Route::get('vehicle/event/{id}', 'VehiclesController@view_event')->middleware('userpermission:1');
        Route::post('assignDriver', 'VehiclesController@assign_driver');
        Route::get('/work_order/itemno_get/{used}', 'WorkOrdersController@itemno_get')->name("work_order.itemno_get")->middleware('userpermission:7');
        Route::post('/work_order/itemno_store', 'WorkOrdersController@itemno_store')->name("work_order.itemno_store")->middleware('userpermission:7');
        Route::post('/work_order/add_parts/', 'WorkOrdersController@add_parts')->name("work_order.add_parts")->middleware('userpermission:7');
        Route::get('/work_order/new_part/', 'WorkOrdersController@new_part')->name("work_order.new_part")->middleware('userpermission:7');
        Route::post('/work_order/new_part/', 'WorkOrdersController@new_partpost')->name("work_order.new_part")->middleware('userpermission:7');
        Route::get('/work_order/add_order_head/{workOrder}', 'WorkOrdersController@storeOrderHead')->name("work_order.store-order-head")->middleware('userpermission:7');
        Route::patch('/work_order/add_order_head/{workOrder}', 'WorkOrdersController@storeOrderHeadPost')->name("work_order.store-order-head")->middleware('userpermission:7');
        Route::get('/work_order/view_event/{id}', 'WorkOrdersController@view_event')->middleware('userpermission:7');
        Route::post('/work_order/wo_gstcalculate', 'WorkOrdersController@wo_gstcalculate')->name('work_order.wo_gstcalculate')->middleware('userpermission:3');
        Route::post('/work_order/wo_calcgst', 'WorkOrdersController@wo_calcgst')->name('work_order.wo_calcgst')->middleware('userpermission:3');
        Route::post('/work_order/othercalc', 'WorkOrdersController@othercalc')->name('work_order.othercalc')->middleware('userpermission:3');
        Route::get('/get-tyre-numbers', 'WorkOrdersController@getTyreNumbers')->name('get.tyre.numbers')->middleware('userpermission:7');
        Route::get('/get-part-category', 'WorkOrdersController@getPartCategory')->name('get.part.category')->middleware('userpermission:7');
        Route::get('/get-edit-tyre-numbers', 'WorkOrdersController@getEditTyreNumbers')->name('get.edit.tyre.numbers')->middleware('userpermission:7');
        Route::resource('/work_order', 'WorkOrdersController')->middleware('userpermission:7');
        Route::resource('/work-order-category', 'WorkOrderCategoryController')->middleware('userpermission:7');

        Route::get('vendors/view_event/{id}', 'VendorController@view_event')->middleware('userpermission:6');
        Route::get('reports/vendor-report', 'ReportsController@vendorReport')->name('reports.vendor-report')->middleware('userpermission:6');
        Route::post('reports/vendor-report', 'ReportsController@vendorReport_post')->name('reports.vendor-report')->middleware('userpermission:6');
        Route::post('print-vendor-vehicle-fuel-report', 'ReportsController@vendorReport_print')->middleware('userpermission:6');
        Route::post('reports/export/vendor-report-export', 'ReportsController@vendorReport_export')->middleware('userpermission:6');
        Route::resource('/vendors', 'VendorController')->middleware('userpermission:6');
        Route::post('print-transaction-report', 'ReportsController@transactionReport_print')->middleware('userpermission:6');
        //vendor payment print
        Route::post('print-vendor-payment', 'ReportsController@vendor_payment')->middleware('userpermission:6');
        //customer payment report
        Route::post('print-customer-payment', 'ReportsController@customer_payment')->middleware('userpermission:6');
        Route::get('/reports/vehicle-fuel-type', 'ReportsController@fuelTypeReport_vehicle')->name('reports.vehicle-fuel-type')->middleware('userpermission:4');
        Route::post('/reports/vehicle-fuel-type', 'ReportsController@fuelTypeReport_vehiclepost')->name('reports.vehicle-fuel-type')->middleware('userpermission:4');
        Route::post('/print-vehicle-fuel-type-report', 'ReportsController@fuelTypeReport_vehicleprint')->middleware('userpermission:4');

        Route::get('/reports/fuel-type', 'ReportsController@fuelTypeReport')->name('reports.fuel-type')->middleware('userpermission:4');
        Route::post('/reports/fuel-type', 'ReportsController@fuelTypeReport_post')->name('reports.fuel-type')->middleware('userpermission:4');
        Route::post('print-fuel-vendor-report', 'ReportsController@fuelTypeReport_print');


        Route::get('/fuel/getFuelList', 'FuelController@getFuelList')->name('fuel.getFuelList')->middleware('userpermission:3');
        Route::post('/fuel/fuel_gstcalculate', 'FuelController@fuel_gstcalculate')->name('fuel.fuel_gstcalculate')->middleware('userpermission:3');
        Route::resource('/fuel', 'FuelController')->middleware('userpermission:5');
        Route::resource('/drivers', 'DriversController')->middleware('userpermission:0');
        Route::resource('/parts', 'PartsController')->middleware('userpermission:14');
        Route::get('/fuel/view_event/{id}', 'FuelController@view_event')->middleware('userpermission:7');
        Route::post('/parts/getparts_form', 'PartsController@get_parts_form')->name('parts.get_parts_form')->middleware('userpermission:14');
        Route::get('/parts/view_event/{id}', 'PartsController@view_event')->middleware('userpermission:14');
        Route::post('/parts/parts_gstcalculate', 'PartsController@parts_gstcalculate')->name('fuel.parts_gstcalculate')->middleware('userpermission:3');

        //debi start

        Route::get('/reports/view_fuel_details/{id}', 'ReportsController@view_fuel_details')->middleware('userpermission:5');
        //Route::get('/fuel/print-fuel-details-report', 'FuelController@print_fuel_details');
        Route::post('print-fuel-modal-report', 'FuelController@print_fuel_details');
        Route::get('/reports/view_vehicle_fuel_details/{id}', 'ReportsController@view_vehicle_fuel_details')->middleware('userpermission:5');
        //Route::get('/fuel/print-fuel-details-report', 'FuelController@print_fuel_details');
        Route::post('print-vehicle-fuel-modal-report', 'FuelController@print_vehicle_fuel_details');
        Route::post('print-booking-modal-report', 'ReportsController@print_booking_details');

        //customer booking report view
        Route::get('/reports/view_booking_details/{id}', 'ReportsController@view_booking_details')->middleware('userpermission:5');
        //new added
        Route::post('vehicles/vehicle-emi/pay-show', 'EmiController@showPayModal')->middleware('userpermission:1')->name('vehicle-emi.pay-show');
        Route::get('vehicles/vehicle-emi/view_event/{emi}', 'EmiController@view_event')->name("vehicle-emi.view_event")->middleware('userpermission:1');
        Route::post('vehicles/vehicle-emi/search', 'EmiController@search')->middleware('userpermission:1')->name('vehicle-emi.search');
        Route::resource('vehicles/vehicle-emi', 'EmiController')->middleware('userpermission:1');
        Route::get('reports/vehicles-overview', 'ReportsController@vehicleOverview')->name('reports.vehicles-overview')->middleware('userpermission:6');
        Route::post('reports/vehicles-overview', 'ReportsController@vehicleOverview_post')->name('reports.vehicles-overview')->middleware('userpermission:6');
        Route::post('print-vehicle-overview-report', 'ReportsController@vehicleOverview_print')->middleware('userpermission:6');
        Route::get('/customers/event/{id}', 'CustomersController@view_event')->name("customers.view_event")->middleware('userpermission:1');
        // Route::resource('/emi', 'EmiController')->middleware('userpermission:1');
        Route::resource('/vehicles', 'VehiclesController')->middleware('userpermission:1');
        Route::resource('/bookings', 'BookingsController')->middleware('userpermission:3');
        Route::post('/prev-address', 'BookingsController@prev_address');
        Route::get('print/{id}', 'BookingsController@print')->middleware('userpermission:3');
        Route::resource('/acquisition', 'AcquisitionController');
        Route::resource('/income', 'Income')->middleware('userpermission:2');
        Route::resource('/settings', 'SettingsController')->middleware('userpermission:S');
        Route::resource('/customers', 'CustomersController')->middleware('userpermission:0');
        Route::resource('/expense', 'ExpenseController')->middleware('userpermission:2');
        Route::resource('/expensecategories', 'ExpenseCategories')->middleware('userpermission:S');
        Route::resource('/incomecategories', 'IncomeCategories')->middleware('userpermission:S');
        Route::resource('/payroll', 'PayrollController')->middleware('userpermission:S');
        Route::post('/daily-advance/isPayrollChecked', 'DailyAdvanceController@isPayrollChecked')->name('daily-advance.ispaychecked')->middleware('userpermission:4');
        Route::get('/reports/salary-advance', 'ReportsController@salaryAdvanceReport')->name('reports.salary-advance')->middleware('userpermission:4');
        Route::post('print-salary-advance-report', 'ReportsController@print_salaryAdvance');
        Route::post('/reports/salary-advance', 'ReportsController@salaryAdvanceReport_post')->name('reports.salary-advance')->middleware('userpermission:4');
        Route::resource('/daily-advance', 'DailyAdvanceController')->middleware('userpermission:S');
        Route::get('/daily-advance/view_event/{id}', 'DailyAdvanceController@view_event')->middleware('userpermission:1');
        Route::get('/daily-advance/get_remarks/{id}', 'DailyAdvanceController@get_remarks');


        Route::get('/reports/other-adjust', 'ReportsController@otherAdjust')->name('reports.other-adjust')->middleware('userpermission:4');
        Route::post('/reports/other-adjust', 'ReportsController@otherAdjust_post')->name('reports.other-adjust')->middleware('userpermission:4');
        Route::post('print-other-adjust-report', 'ReportsController@otherAdjust_print');
        Route::get('/other-adjust/view_event/{id}', 'OtherAdjustController@view_event')->middleware('userpermission:1');
        Route::get('/other-adjust/adjust/{id}', 'OtherAdjustController@adjust')->middleware('userpermission:1');
        Route::post('/other-adjust/addmore', 'OtherAdjustController@addmore')->name('other-adjust.addmore')->middleware('userpermission:1');
        Route::post('/other-adjust/calculate', 'OtherAdjustController@calculate')->name('other-adjust.calculate')->middleware('userpermission:1');
        Route::resource('/other-adjust', 'OtherAdjustController')->middleware('userpermission:S');
        Route::get('/other-advance/view_event/{id}', 'OtherAdvanceController@view_event')->middleware('userpermission:1');
        Route::get('/reports/other-advance', 'ReportsController@otherAdvanceReport')->name('reports.other-advance')->middleware('userpermission:4');
        Route::post('/reports/other-advance', 'ReportsController@otherAdvanceReport_post')->name('reports.other-advance')->middleware('userpermission:4');
        Route::post('print-other-advance-report', 'ReportsController@otherAdvanceReport_print');
        Route::resource('/other-advance', 'OtherAdvanceController')->middleware('userpermission:S');
        Route::resource('/manufacturer', 'ManufacturerController')->middleware('userpermission:S');
        Route::resource('/unit', 'UnitController')->middleware('userpermission:S');
        Route::resource('/stock', 'StockController')->middleware('userpermission:S');

        Route::post('/parts-invoice/getparts_form', 'PartsInvoiceController@get_parts_form')->name('parts-invoice.get_parts_form')->middleware('userpermission:14');
        Route::post('/parts-invoice/pi_gstcalculate', 'PartsInvoiceController@pi_gstcalculate')->name('parts-invoice.pi_gstcalculate')->middleware('userpermission:3');
        Route::get('/parts-invoice/view_event/{id}', 'PartsInvoiceController@view_event')->name('parts-invoice.view_event')->middleware('userpermission:1');
        Route::resource('/parts-invoice', 'PartsInvoiceController')->middleware('userpermission:S');
        Route::get('/get-category-info', 'PartsInvoiceController@getCategoryInfo')->name('get.category.info')->middleware('userpermission:4');
        // Route::get('/get-tyre-numbers', 'WorkOrdersController@getTyreNumbers')->name('get.tyre.numbers')->middleware('userpermission:7');



        Route::post('/payroll/payabletype', 'PayrollController@payabletype')->name('payroll.payabletype')->middleware('userpermission:4');
        Route::post('/payroll/purse', 'PayrollController@purse')->name('payroll.purse')->middleware('userpermission:4');
        Route::get("/manage-payroll", "PayrollController@manage_payroll")->name('payroll.managepayroll')->middleware('userpermission:4');
        Route::get('/payroll/single_pay/{id}', 'PayrollController@single_pay')->middleware('userpermission:S');
        Route::post('/payroll/getWorkingDays', 'PayrollController@getWorkingDays')->middleware('userpermission:S');
        Route::get('/payroll/view_event/{id}', 'PayrollController@view_event')->middleware('userpermission:1');


        // Route::get("/reports/fuel", "ReportsController@fuel")->name("reports.fuel")->middleware('userpermission:4');
        Route::get("reports/leave", "ReportsController@leaveReport")->name("reports.leave")->middleware('userpermission:4');
        Route::post("reports/leave", "ReportsController@leaveReport_post")->name("reports.leave")->middleware('userpermission:4');
        Route::post("print-leave-report", "ReportsController@leaveReport_print")->middleware('userpermission:4');
        Route::resource('/leave', 'LeaveController')->middleware('userpermission:4');


        Route::resource('/bulk_leave', 'BulkLeaveController')->middleware('userpermission:4');
        Route::post('/bulk_leave/getDatesPage', 'BulkLeaveController@getDatesPage')->name('bulk_leave.getDatesPage')->middleware('userpermission:4');

        Route::get('accounting/transaction-search-bank', 'ReportsController@trashSearchBank')->name('transaction.search-bank')->middleware('userpermission:4');
        Route::post('accounting/transaction-search-bank', 'ReportsController@trashSearchBank_post')->name('transaction.search-bank')->middleware('userpermission:4');
        Route::post('print-transaction-bank', 'ReportsController@trashSearchBank_print');

        Route::get('accounting/transaction-search', 'ReportsController@trashSearch')->name('transaction.search')->middleware('userpermission:4');
        Route::post('accounting/transaction-search', 'ReportsController@trashSearch_post')->name('transaction.search')->middleware('userpermission:4');
        Route::post('print-transaction', 'ReportsController@trashSearch_print');

        Route::get("/accounting/getTransactionBankList", "TransactionController@getTransactionBankList")->name("accounting.getTransactionBankList")->middleware('userpermission:4');
        Route::get('/accounting/getTransactionList', 'TransactionController@getTransactionList')->name('accounting.getTransactionList')->middleware('userpermission:3');
        Route::get("/reports/transaction", "ReportsController@transactionReport")->name("reports.transaction")->middleware('userpermission:4');
        Route::post("/reports/transaction", "ReportsController@transactionReport_post")->name("reports.transaction")->middleware('userpermission:4');
        Route::get("/accounting/transaction-bank", "TransactionController@reportbank")->name("accounting.index-bank")->middleware('userpermission:4');

        Route::post("/transaction-accounting-report-print/", "TransactionController@report_printpost")->middleware('userpermission:4');
        Route::get("/accounting/view_bank_event/{id}", "TransactionController@view_bank_event")->middleware('userpermission:4');
        Route::get("/accounting/view_event/{id}", "TransactionController@view_event")->middleware('userpermission:4');
        Route::get("/accounting/adjust/{id}", "TransactionController@adjust")->middleware('userpermission:4');
        Route::get("/accounting/where_from/{id}", "TransactionController@where_from")->middleware('userpermission:4');
        Route::get("/accounting/advance_for/{id}", "TransactionController@advance_for")->middleware('userpermission:4');

        Route::resource('/accounting', 'TransactionController')->middleware('userpermission:S');
        Route::get("/bank-account/deposit/view_event/{id}", "BankAccountController@deposit_view_event")->name('bank-account.deposit_view_event')->middleware('userpermission:4');
        Route::get("/bank-account/deposit/{id}/edit", "BankAccountController@deposit_edit")->name('bank-account.deposit_edit')->middleware('userpermission:4');
        Route::patch("/bank-account/deposit/{id}", "BankAccountController@deposit_save")->name('bank-account.deposit_save')->middleware('userpermission:4');
        Route::get("/bank-account/deposit", "BankAccountController@deposit")->name('bank-account.deposit')->middleware('userpermission:4');
        Route::post("/bank-account/add_amount/store", "BankAccountController@addAmountStore")->name('addamount.store')->middleware('userpermission:4');
        // Route::get("/bank-account/add_amount/{id}", "BankAccountController@add_amount")->middleware('userpermission:4');
        Route::get("/bank-account/bulk_pay/view_event/{id}", "BankAccountController@bulk_viewevent")->name('bulk_pay.view_event')->middleware('userpermission:4');
        Route::get("/bank-account/bulk_pay/manage", "BankAccountController@bulk_manage")->name('bulk_pay.manage')->middleware('userpermission:4');
        Route::post("/bank-account/bulk_pay/compareValues", "BankAccountController@compareValues")->name('bulk_pay.compareValues')->middleware('userpermission:4');
        Route::post("/bank-account/bulk_pay/getSelectedAmount", "BankAccountController@getAmount")->name('bulk_pay.getAmount')->middleware('userpermission:4');

        Route::post("/bank-account/bulk_pay/store", "BankAccountController@bulk_store")->name('bulk_pay.store')->middleware('userpermission:4');
        Route::post("/bank-account/bulk_pay", "BankAccountController@bulk_paypost")->name('bank-account.bulk_pay')->middleware('userpermission:4');
        Route::get("/bank-account/bulk_pay", "BankAccountController@bulk_pay")->name('bank-account.bulk_pay')->middleware('userpermission:4');

        Route::post("/bank-account/bulk_receive/store", "BankAccountController@bulk_store")->name('bulk_receive.store')->middleware('userpermission:4');
        Route::post("/bank-account/bulk_receive", "BankAccountController@bulk_paypost")->name('bank-account.bulk_receive')->middleware('userpermission:4');
        Route::get("/bank-account/bulk_receive", "BankAccountController@bulk_pay")->name('bank-account.bulk_receive')->middleware('userpermission:4');

        Route::get("/bank-account/view_event/{id}", "BankAccountController@view_event")->middleware('userpermission:4');
        Route::resource('/bank-account', 'BankAccountController')->middleware('userpermission:S');
        Route::get("/ob-balance/view_event/{id}", "OBBalanceController@view_event")->middleware('userpermission:4');
        Route::resource('/ob-balance', 'OBBalanceController')->middleware('userpermission:S');
        Route::get('/leave/get_remarks/{id}', 'LeaveController@get_remarks');
        Route::get('/leave/view_event/{id}', 'LeaveController@view_event')->middleware('userpermission:4');
        Route::get('/bulk_leave/view_event/{id}', 'BulkLeaveController@view_event')->middleware('userpermission:4');
        Route::get('/bulk_leave/edit_bulk', 'BulkLeaveController@edit_bulk')->name('bulk_leave.edit_bulk')->middleware('userpermission:4');
        Route::get('/bookings/complete/{id}', 'BookingsController@complete')->middleware('userpermission:3');
        Route::get('/bookings/receipt/{id}', 'BookingsController@receipt')->middleware('userpermission:3');
        Route::get('/bookings/payment/{id}', 'BookingsController@payment')->middleware('userpermission:3');
        Route::post('/bookings/markascomplete/others', 'BookingsController@getOthers')->middleware('userpermission:3');
        Route::get("/reports/monthly", "ReportsController@monthly")->name("reports.monthly")->middleware('userpermission:4');
        Route::get("/reports/vendors", "ReportsController@vendors")->name("reports.vendors")->middleware('userpermission:4');
        Route::post("/reports/vendors", "ReportsController@vendors_post")->name("reports.vendors")->middleware('userpermission:4');
        Route::get("reports/drivers", "ReportsController@drivers")->name("reports.drivers")->middleware('userpermission:4');
        Route::post("reports/drivers", "ReportsController@drivers_post")->name("reports.drivers")->middleware('userpermission:4');

        Route::get("reports/vehicle-emi", "ReportsController@vehicleEmi")->name("reports.vehicle-emi")->middleware('userpermission:4');
        Route::post("reports/vehicle-emi", "ReportsController@vehicleEmi_post")->name("reports.vehicle-emi")->middleware('userpermission:4');
        Route::post("print-vehicle-emi", "ReportsController@vehicleEmi_print")->middleware('userpermission:4');

        Route::get("reports/statement", "ReportsController@statement")->name("reports.statement")->middleware('userpermission:4');
        Route::post("reports/statement", "ReportsController@statement_post")->name("reports.statement")->middleware('userpermission:4');
        Route::post("print-statement", "ReportsController@statement_print")->middleware('userpermission:4');


        Route::get("reports/drivers-report", "ReportsController@driverspayroll")->name("reports.drivers-report")->middleware('userpermission:4');
        Route::post("reports/drivers-report", "ReportsController@driverspayroll_post")->name("reports.drivers-report")->middleware('userpermission:4');
        Route::post("print-drivers-report", "ReportsController@driverspayroll_print")->middleware('userpermission:4');

        Route::get("reports/drivers-advance-report", "ReportsController@driversAdvance")->name("reports.drivers-advance-report")->middleware('userpermission:4');
        Route::post("reports/drivers-advance-report", "ReportsController@driversAdvance_post")->name("reports.drivers-advance-report")->middleware('userpermission:4');
        Route::post("print-drivers-advance-report", "ReportsController@driversAdvance_print")->middleware('userpermission:4');

        Route::get("reports/salary-report", "ReportsController@salaryReport")->name("reports.salary-report")->middleware('userpermission:4');
        Route::post("reports/salary-report", "ReportsController@salaryReport_post")->name("reports.salary-report")->middleware('userpermission:4');
        Route::post("print-salary-report", "ReportsController@salaryReport_print")->middleware('userpermission:4');
        Route::post("export-salary-report", "ReportsController@exportReport_print")->middleware('userpermission:4');

        Route::get("reports/vehicle-advance/vehicle-head-advance-report/{id}", "ReportsController@vehicleHeadAdvance")->name("reports.vehicle-head-advance-report")->middleware('userpermission:4');
        Route::post("print-vehicle-head-advance-report", "ReportsController@vehicleHeadAdvance_print")->middleware('userpermission:4');

        Route::get("reports/vehicle-advance-report", "ReportsController@vehicleAdvance")->name("reports.vehicle-advance-report")->middleware('userpermission:4');
        Route::post("reports/vehicle-advance-report", "ReportsController@vehicleAdvance_post")->name("reports.vehicle-advance-report")->middleware('userpermission:4');
        Route::post("print-vehicle-advance-report", "ReportsController@vehicleAdvance_print")->middleware('userpermission:4');


        Route::get("/global-search", "ReportsController@globalSearch")->name("reports.global-search")->middleware('userpermission:4');
        Route::post("/global-search", "ReportsController@globalSearch_post")->name("reports.global-search")->middleware('userpermission:4');
        Route::post("print-global-search", "ReportsController@globalSearch_print")->middleware('userpermission:4');

        Route::get("reports/salary-processing", "ReportsController@salaryProcessing")->name("reports.salary-processing")->middleware('userpermission:4');
        Route::post("reports/salary-processing", "ReportsController@salaryProcessing_post")->name("reports.salary-processing")->middleware('userpermission:4');
        Route::post("print-salary-processing", "ReportsController@salaryProcessing_print")->middleware('userpermission:4');

        Route::get("reports/salary-report", "ReportsController@salaryReport")->name("reports.salary-report")->middleware('userpermission:4');
        Route::post("reports/salary-report", "ReportsController@salaryReport_post")->name("reports.salary-report")->middleware('userpermission:4');
        Route::post("print-salary-report", "ReportsController@salaryReport_print")->middleware('userpermission:4');

        Route::get("reports/customers", "ReportsController@customers")->name("reports.customers")->middleware('userpermission:4');
        Route::post("reports/customers", "ReportsController@customers_post")->name("reports.customers")->middleware('userpermission:4');
        Route::get("/reports/booking", "ReportsController@booking")->name("reports.booking")->middleware('userpermission:4');
        Route::get("/reports/stock", "ReportsController@stock")->name("reports.stock")->middleware('userpermission:4');
        //vendor payment
        Route::get("/reports/vendor-payment", "ReportsController@vendorPayment")->name("reports.vendorPayment")->middleware('userpermission:4');
        Route::post("/reports/vendor-payment", "ReportsController@vendorPayment_post")->name("reports.vendorPayment")->middleware('userpermission:4');
        //customer payment detail
        Route::get("/reports/customer-payment", "ReportsController@customerPayment")->name("reports.customerPayment")->middleware('userpermission:4');
        Route::post("/reports/customer-payment", "ReportsController@customerPayment_post")->name("reports.customerPayment")->middleware('userpermission:4');
        Route::get("/reports/delinquent", "ReportsController@delinquent")->name("reports.delinquent")->middleware('userpermission:4');
        Route::get("/reports/users", "ReportsController@users")->name("reports.users")->middleware('userpermission:4');
        Route::post("/reports/users", "ReportsController@users_post")->name("reports.users")->middleware('userpermission:4');
        Route::get('/calendar', 'BookingsController@calendar');
        Route::get('/calendar/event/{id}', 'BookingsController@calendar_event');
        Route::get("/drivers/enable/{id}", 'DriversController@enable');
        Route::get("/drivers/disable/{id}", 'DriversController@disable');

        Route::get("/reports/vehicle", "ReportsController@vehicle")->name("reports.vehicle");
        Route::post("/reports/booking", "ReportsController@booking_post")->name("reports.booking")->middleware('userpermission:4');
        Route::post("/reports/stock", "ReportsController@stock_post")->name("reports.stock")->middleware('userpermission:4');
        Route::post("/reports/fuel", "ReportsController@fuel_post")->name("reports.fuel_post")->middleware('userpermission:4');
        Route::get("/reports/fuel", "ReportsController@fuel")->name("reports.fuel")->middleware('userpermission:4');
        Route::get("/reports/yearly", "ReportsController@yearly")->name("reports.yearly")->middleware('userpermission:4');
        Route::post("/reports/yearly", "ReportsController@yearly_post")->name("reports.yearly")->middleware('userpermission:4');

        Route::post('/customer/ajax_save', 'CustomersController@ajax_store')->name('customers.ajax_store');
        Route::get("/bookings_calendar", 'BookingsController@calendar_view')->name("bookings.calendar")->middleware('userpermission:3');
        Route::get('/calendar/event/calendar/{id}', 'BookingsController@calendar_event');
        Route::get('/calendar/event/service/{id}', 'BookingsController@service_view');
        Route::get('/calendar', 'BookingsController@calendar');
        Route::post('/get_driver', 'BookingsController@get_driver');
        Route::post('/get_vehicle', 'BookingsController@get_vehicle');
        Route::post('/showall_vehicle', 'BookingsController@showAllVehicle');
        Route::post('/bookings/get_required', 'BookingsController@get_required');
        Route::post('/bookings/get_lat', 'BookingsController@get_lat');
        Route::post('/bookings/get_distanecfromaddress', 'BookingsController@getDistanceFromAddress');
        Route::get('/bookings/modalcomplete/{id}', 'BookingsController@modalcomplete');
        Route::get('/bookings/modalroute/{id}', 'BookingsController@modalroute');
        Route::get('/bookings/modal-late-driver-advance/{id}', 'BookingsController@lateDriverAdvance');
        Route::post('/bookings/modal_save', 'BookingsController@modal_save');
        Route::post('/bookings/getMileageDate', 'BookingsController@getMileageDate')->name('bookings.getMileageDate');
        Route::post('/bookings/addroute_save', 'BookingsController@addroute_save')->name('bookings.addroute_save');
        Route::post('/bookings/lateadvanceSave', 'BookingsController@lateadvanceSave')->name('bookings.lateadvanceSave');
        Route::post('/bookings/complete', 'BookingsController@complete_post')->name("bookings.complete");
        Route::get('/bookings/complete', 'BookingsController@complete_post')->name("bookings.complete")->middleware('userpermission:3');
        Route::post('/bookings/getblob', 'BookingsController@getblob')->name("bookings.getblob")->middleware('userpermission:S');
        Route::get('/bookings/event/{id}', 'BookingsController@view_event')->name("bookings.event")->middleware('userpermission:1');
        Route::post('/bookings/dropofftime', 'BookingsController@dropofftime')->name("bookings.dropofftime")->middleware('userpermission:4');
        Route::post('/bookings/timeperltr', 'BookingsController@timeperltr')->name("bookings.timeperltr")->middleware('userpermission:4');
        Route::post('/greater', 'BookingsController@greater')->name("bookings.greater")->middleware('userpermission:4');

        Route::post("/reports/monthly", "ReportsController@monthly_post")->name("reports.monthly")->middleware('userpermission:4');
        Route::post("/reports/booking", "ReportsController@booking_post")->name("reports.booking")->middleware('userpermission:4');
        Route::post("/reports/stock", "ReportsController@stock_post")->name("reports.stock")->middleware('userpermission:4');
        Route::post("/reports/delinquent", "ReportsController@delinquent_post")->name("reports.delinquent")->middleware('userpermission:4');
        Route::get("/send-email", "SettingsController@send_email")->middleware('userpermission:S');
        Route::post("/email-settings", "SettingsController@email_settings")->middleware('userpermission:S');
        Route::post('enable-mail', 'SettingsController@enable_mail')->middleware('userpermission:S');
        Route::get("/set-email", "SettingsController@set_email")->middleware('userpermission:S');
        Route::post("/set-content/{type}", "SettingsController@set_content")->middleware('userpermission:S');

        Route::get('ajax-api-store/{api}', 'SettingsController@ajax_api_store');

        Route::get('payment-settings', 'SettingsController@payment_settings');
        Route::post('payment-settings', 'SettingsController@payment_settings_post');
    });

    Route::group(['middleware' => ['lang_check', 'auth']], function () {
        Route::post('delete-notes', 'NotesController@bulk_delete');
        Route::get('changepassword/{id}', 'UtilityController@change');
        Route::post('changepassword/{id}', 'UtilityController@change_post');
        Route::get('/change-details/{id}', 'UtilityController@changepass')->name("changepass");
        Route::post('/change-details/{id}', 'UtilityController@changepassword')->name("changepass");
        Route::post('/change_password', 'UtilityController@password_change');
        Route::get('/vehicle_notification/{type}', 'NotificationController@vehicle_notification');
        Route::resource('/notes', 'NotesController')->middleware('userpermission:8');
        Route::get('driver_notification/{type}', 'NotificationController@driver_notification');
        Route::get('/reminder/{type}', 'NotificationController@service_reminder');
        Route::get('/my_bookings', 'DriversController@my_bookings')->name('my_bookings');

        // driver reports
        Route::get("/driver-reports/yearly", "DriversController@yearly")->name("dreports.yearly");
        Route::post("/driver-reports/yearly", "DriversController@yearly_post")->name("dreports.yearly");
        Route::get("/driver-reports/monthly", "DriversController@monthly")->name("dreports.monthly");
        Route::post("/driver-reports/monthly", "DriversController@monthly_post")->name("dreports.monthly");
        Route::get('/addresses', 'AddressController@index');

        Route::resource('/cancel-reason', 'ReasonController')->middleware('userpermission:3');
    });

    Route::group(['middleware' => ['lang_check', 'auth', 'superadmin']], function () {
        Route::resource('/users', 'UsersController')->middleware('userpermission:0');
    });
});
