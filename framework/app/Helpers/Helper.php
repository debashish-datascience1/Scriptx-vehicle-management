<?php

namespace App\Helpers;

use App\Model\BankAccount;
use App\Model\BankTransaction;
use DB;
use App\Model\Params;
use App\Model\IncomeExpense;
use App\Model\Transaction;
use App\Model\Payroll;
use App\Model\FuelModel;
use App\Model\PartsModel;
use App\Model\Leave;
use App\Model\Bookings;
use App\Model\DailyAdvance;
use App\Model\EmiModel;
use App\Model\FuelType;
use App\Model\OtherAdjust;
use App\Model\OtherAdvance;
use App\Model\PartsInvoice;
use App\Model\PurchaseInfo;
use App\Model\ShortageModel;
use App\Model\VehicleDocs;
use App\Model\VehicleModel;
use App\Model\Vendor;
use App\Model\User;
use App\Model\WorkOrders;
use DateInterval;
use DateTimeZone;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

use function GuzzleHttp\json_encode;

class Helper
{
    public static function sayHello()
    {
        return "sayHello";
    }

    public static function getMonth($m, $type = true)
    {
        if (empty($m)) return false;
        else {
            $letter = $type ? 'F' : 'M';
            $month = $m < 10 ? '0' . $m : $m;
            return date("$letter", strtotime(date('Y') . '-' . $month . '-01'));
        }
    }
    public static function getCanonicalDate($date, $type = true)
    {
        // dd($type);
        if (empty($date)) return false;
        else {
            $letter = $type === true ? 'd-F-Y' : ($type == 'default' ? 'd-m-Y' : 'd-M-Y');
            // dd($letter);
            return date($letter, strtotime($date));
        }
    }

    public static function getCanonicalDateTime($date, $type = true)
    {
        if (empty($date)) return false;
        else {
            $letter = $type === true ? 'd-F-Y g:i:s A' : ($type == 'default' ? 'd-m-Y g:i:s A' : 'd-M-Y g:i:s A');
            return date($letter, strtotime($date));
        }
    }

    public static function properDecimals($deci, $param = true)
    {
        if ($param)
            $ret = number_format($deci, 2, '.', '');
        else
            $ret = number_format($deci, 2);
        return $ret;
    }

    public static function limitText($string, $limit)
    {
        if (strlen($string) > $limit) {
            $string = substr($string, 0, $limit - 3) . "...";
        }
        return $string;
    }

    public static function string_exists($needle, $haystack)
    {
        if (strpos($haystack, $needle) !== false) {
            return true;
        }
    }

    public static function dateSearch($date)
    {
        if (strlen($date) > 3 && self::string_exists('-', $date)) {
            return implode("-", array_reverse(explode("-", $date)));
        }
    }

    public static function getActualTransactionDate($from_id, $param_id)
    {
        if ($param_id == 18) { //booking
            $shalom = Bookings::where('id', $from_id);
            $date = $shalom->exists() ? date("Y-m-d", strtotime($shalom->first()->pickup)) : null;
            $organisation = $shalom->exists() ? !empty($shalom->first()->customer) ? $shalom->first()->customer->name : null : null;
        } else if ($param_id == 19) { //payroll
            $shalom = Payroll::where('id', $from_id);
            $date = $shalom->exists() ? $shalom->first()->date : null;
            $organisation = $shalom->exists() ? !empty($shalom->first()->driver) ? $shalom->first()->driver->name : null : null;
        } else if ($param_id == 20) { //fuel
            $shalom = FuelModel::where('id', $from_id);
            $date = $shalom->exists() ? $shalom->first()->date : null;
            $organisation = $shalom->exists() ? !empty($shalom->first()->vendor) ? $shalom->first()->vendor->name : null : null;
        } else if ($param_id == 25) { //salary advance
            $shalom = DailyAdvance::where('id', $from_id);
            $date = $shalom->exists() ? $shalom->first()->date : null;
            $organisation = $shalom->exists() ? !empty($shalom->first()->driver) ? $shalom->first()->driver->name : null : null;
        } else if ($param_id == 26) { //parts invoice
            $shalom =  PartsInvoice::where('id', $from_id);
            $date = $shalom->exists() ? $shalom->first()->date_of_purchase : null;
            $organisation = $shalom->exists() ? !empty($shalom->first()->vendor) ? $shalom->first()->vendor->name : null : null;
        } else if ($param_id == 27) { //refund
            $shalom = Transaction::where(['from_id' => $from_id, 'param_id' => 27]);
            $date = $shalom->exists() ? date("Y-m-d", strtotime($shalom->first()->created_at)) : null;
            $organisation = $shalom->exists() ? "Ranisati" : null;
        } else if ($param_id == 28) { //work order
            $shalom = WorkOrders::where('id', $from_id);
            $date = $shalom->exists() ?  $shalom->first()->required_by : null;
            $organisation = $shalom->exists() ? !empty($shalom->first()->vendor) ? $shalom->first()->vendor->name : null : null;
        } else if ($param_id == 29) { //starting amount
            $shalom = Transaction::where(['from_id' => $from_id, 'param_id' => 29]);
            $date = $shalom->exists() ? date("Y-m-d", strtotime($shalom->first()->created_at)) : null;
            $organisation = $shalom->exists() ? BankAccount::find($from_id)->bank : null;
        } else if ($param_id == 30) { //deposits
            $shalom = BankTransaction::where('id', $from_id);
            $date = $shalom->exists() ?  $shalom->first()->date : null;
            $organisation = $shalom->exists() ? !empty($shalom->first()->bank) ? $shalom->first()->bank->bank : null : null;
        } else if ($param_id == 31) { //shortage
            $shalom = ShortageModel::where('id', $from_id);
            $tt = Transaction::where(['from_id' => $from_id, 'param_id' => 31]);
            $date = $shalom->exists() ?  $shalom->first()->date : null;
            if ($tt->exists()) {
                $from_transac = Transaction::where('id', $tt->first()->from_transaction);
                $organisation = $from_transac->exists() ? !empty($from_transac->first()->customer) ? $from_transac->first()->customer->name : null : null;
            } else {
                $organisation = null;
            }
        } else if ($param_id == 32) { //driver liability
            $shalom = DailyAdvance::where(['id' => $from_id, 'from_id' => !null]);
            $date = $shalom->exists() ?  $shalom->first()->date : null;
            $organisation = $shalom->exists() ? !empty($shalom->first()->driver) ? $shalom->first()->driver->name : null : null;
        } else if ($param_id == 35) { //document
            $shalom = VehicleDocs::where('id', $from_id);
            $date = $shalom->exists() ?  $shalom->first()->date : null;
            $organisation = $shalom->exists() ? !empty($shalom->first()->vendor) ? $shalom->first()->vendor->name : null : null;
        } else if ($param_id == 43) { //other advance
            $shalom =  OtherAdvance::where('id', $from_id);
            $date = $shalom->exists() ?  $shalom->first()->date : null;
            $organisation = $shalom->exists() ? !empty($shalom->first()->driver) ? $shalom->first()->driver->name : null : null;
        } else if ($param_id == 44) { //other refund
            $shalom = OtherAdjust::where('id', $from_id);
            $date = $shalom->exists() ?  $shalom->first()->date : null;
            $organisation = $shalom->exists() ? !empty($shalom->first()->driver) ? $shalom->first()->driver->name : null : null;
        } else if ($param_id == 49) { //down payment
            $shalom = PurchaseInfo::where('id', $from_id);
            $date = $shalom->exists() ?  $shalom->first()->purchase_date : null;
            $organisation = $shalom->exists() ? !empty($shalom->first()->bank_details) ? $shalom->first()->bank_details->bank : null : null;
        } else if ($param_id == 50) { //emi
            $shalom = EmiModel::where('id', $from_id);
            $date = $shalom->exists() ?  $shalom->first()->date : null;
            $organisation = $shalom->exists() ? !empty($shalom->first()->bank) ? $shalom->first()->bank->bank : null : null;
        } else {
            dd($param_id);
        }

        return Helper::toCollection(['date' => $date, 'org' => $organisation]);
    }

    public static function getAllParts($select = true)
    {
        $allPart = DB::table('parts')
            ->select("parts.id", "parts.item", "parts.stock", "parts_category.name as catname", "manufacturer.name as manufac")
            ->join('parts_category', 'parts_category.id', '=', 'parts.category_id')
            ->join('manufacturer', 'manufacturer.id', '=', 'parts.manufacturer')
            ->get();
        if ($select) {
            foreach ($allPart as $p) {
                $options[$p->id] = $p->item . " " . $p->catname . " (" . $p->manufac . ")";
            }
            return !isset($options) ? null : $options;
        } else {
            return $allPart;
        }
    }

    public static function getFullPartName($part_id)
    {
        $p = PartsModel::find($part_id);
        $p_cat = $p->category;
        $p_manu = $p->manufacturer_details;
        return $p->item . " " . $p_cat->name . " (" . $p_manu->name . ")";
    }

    public static function encode($variable)
    {
        for ($i = 1; $i <= 5; $i++) {
            $variable = base64_encode(serialize($variable));
        }
        return $variable;
    }
    public static function decode($variable)
    {
        for ($i = 1; $i <= 5; $i++) {
            $variable = unserialize(base64_decode($variable));
        }
        return $variable;
    }

    public static function str_replace_first($search, $replace, $subject)
    {
        $search = '/' . preg_quote($search, '/') . '/';
        return preg_replace($search, $replace, $subject, 1);
    }

    public static function getMonths($all = false)
    {
        if ($all == true)
            $arr[0] = "All";
        for ($i = 1; $i <= 12; $i++) {
            $j = $i < 10 ? '0' . $i : $i;
            $monthName = date('F', strtotime(date('Y-' . $j . '-01')));
            $arr[$i] = $monthName;
        }
        return $arr;
    }

    public static function getParamFromID($param_id = 2)
    { //if not given then 2->bags
        return Params::find($param_id);
    }

    public static function getYears(array $array)
    {
        if (isset($array['from']) && !empty($array['from']) && isset($array['to']) && !empty($array['to'])) {
            $from = $array['from'];
            $to = $array['to'];

            for ($i = $to; $i >= $from; $i--) {
                $years[$i] = $i;
            }
            return $years;
        }
    }

    public static function getYear()
    {
        $current = date('Y');
        for ($i = $current - 1; $i <= $current + 1; $i++) {
            $arr[$i] = $i;
        }
        return $arr;
    }

    public  static function salaried_employees()
    {
        return User::meta()->where(function ($query) {
            $query->where('users_meta.key', '=', 'salary')
                ->whereRaw('users_meta.value IS NOT NULL');
        });
    }

    public static function arrayFlatten(array $array, $showCount = true)
    {
        if (empty($array)) {
            $newArray = [];
            return collect($newArray);
        }

        foreach ($array as $key => $arr) {
            $newArray['count'] = count($array);
            // $newArray['param_id'] = '';
            // $newArray['value'] = 0;

            foreach ($arr as $indv) {

                $arrayVal[$indv->param_id]['param_id'] = $indv->param_id;
                $paramval[$indv->param_id]['value'][] = $indv->value;
                $arrayVal[$indv->param_id]['value'] = array_sum($paramval[$indv->param_id]['value']);
                $arrayVal[$indv->param_id]['param_name'] = DB::table('params')->where('id', $indv->param_id)->first('label')->label;
            }
            $newArray['array'] = $arrayVal;
        }
        return collect($newArray);
    }

    public static function getLeaveTypes()
    {
        return [1 => 'Present', 2 => 'Absent', 3 => '1st Half Leave', 4 => '2nd Half Leave'];
    }

    public static function transaction_id(array $invarr)
    {
        if (count($invarr) != 1) {
            if ($invarr['type'] == 23)
                $type = "IN";
            if ($invarr['type'] == 24)
                $type = "EX";
            if ($invarr['type'] == 48)
                $type = "NON";

            foreach (Params::where('code', 'PaymentFrom')->get() as $par) {
                // $str = str_replace(" ","_",strtolower($par->label));
                $code[$par->id]['name'] = $par->label;
                $code[$par->id]['code'] = in_array($par->id, [25, 26, 30, 31, 35, 43, 49, 50]) ? substr(strtoupper($par->label), 0, 3) : substr(strtoupper($par->label), 0, 2);
            }
        }

        return $type . "-" . $code[$invarr['from']]['code'] . "-" . $invarr['id'];
        // return "VM00".$id;
        // return $code;
    }

    public static function getBanks($self = false)
    {
        if ($self)
            $bankAccounts = BankAccount::select('id', DB::raw("CONCAT(bank,'(',account_no,')') as name"))->pluck('name', 'id');
        else
            $bankAccounts = BankAccount::select('id', DB::raw("CONCAT(bank,'(',account_no,')') as name"))->where('id', '!=', 1)->pluck('name', 'id');

        return $bankAccounts;
    }

    public static function getMethods()
    {
        return Params::where('code', 'PaymentMethod')->pluck('label', 'id');
    }

    public static function isEligible($id, $param)
    { //needed for disable edit
        $tran =  Transaction::where(['param_id' => $param, 'from_id' => $id]);
        $transaction_id = $tran->exists() ? $tran->first()->id : null;
        $iedata = IncomeExpense::where('id', $transaction_id);
        // dd($transaction_id);
        $transaction_data = Transaction::where('id', $transaction_id)->select('is_completed');
        $incomeExpenseRows = empty($iedata) || $iedata->count() > 1 ? false : true;
        // dd($transaction_data->first()->is_completed);
        $is_completed = $transaction_data->exists() ? ($transaction_data->first()->is_completed == null ?  true : false) : false;
        // dd($incomeExpenseRows);
        // dd($is_completed);
        $retval = $incomeExpenseRows && $is_completed ? true : false;
        // dd($incomeExpenseRows,$is_completed);
        return $retval;
    }


    public static function getTransaction($from, $param)
    { //get transaction_id by $from_id and $param_id
        if ($param != 25)
            $tt = Transaction::where(['from_id' => $from, 'param_id' => $param])->select('id', 'transaction_id')->where(function ($query) {
                return $query->where('advance_for', null)
                    ->orWhere('advance_for', '!=', 21);
            });
        else
            $tt = Transaction::where(['from_id' => $from, 'param_id' => $param])->select('id', 'transaction_id')->where('advance_for', 21);
        return $tt->exists() ? $tt->first() : null;
    }

    public static function toJSON(array $array)
    {
        // dd($array);
        if (!empty($array)) {
            if ($array['param_id'] == 18)
                $d = Bookings::get()->reverse();
            else if ($array['param_id'] == 19)
                $d = Payroll::get();
            else if ($array['param_id'] == 20)
                $d = FuelModel::get();
            else if ($array['param_id'] == 26)
                $d = PartsInvoice::get();
            else if ($array['param_id'] == 'leave')
                $d = Leave::get();
            else if ($array['param_id'] == 'transaction') {
                //transaction code
                $d = Helper::listTransaction();
            } else if ($array['param_id'] == 'transaction-bank') {
                //transaction bank code
                $d = Helper::listTransactionBank();
            } else if ($array['param_id'] == 'upcoming-renewals') {
                //transaction bank code
                $d = Helper::upcomingRenewals();
            }
            // dd($d);
            // dd(json_encode($d));
            // dd( response()->json($d));
            // $data = json_encode($d);
            $fileName = Helper::fileFromParam($array['param_id']);
            // dd($fileName);
            Storage::disk("public")->put($fileName, json_encode($d));
            // Storage::disk("public")->put($fileName, response()->json($d));
        }
    }

    public static function housekeeping($param_id)
    {
        //Auto Update after 2 hours when page is accessed
        $fileName = Helper::fileFromParam($param_id);
        $paramArray = ["param_id" => $param_id];
        $exists = Storage::disk('local')->exists($fileName);
        if ($exists) {
            $lastModified = Storage::disk('public')->lastModified($fileName);
            $lastModified = DateTime::createFromFormat("U", $lastModified);
            $lastModified = Helper::lastCreatedDate($lastModified);
            $elapsedTime = strtotime('+2hours', strtotime($lastModified));
            // $abc = strtotime(date("Y-m-d H:i:s")) .">=". $elapsedTime;
            // dd($abc);
            $abc = strtotime(date("Y-m-d H:i:s")) >= $elapsedTime ? true : false;
            // dd($abc);
            if (strtotime(date("Y-m-d H:i:s")) >= $elapsedTime)
                Helper::toJSON($paramArray); // replace the old json
        } else {
            Helper::toJSON($paramArray); // create new json
        }
    }

    // public static function getDataFromJSON($param_id){
    //     $put = Storage::disk('public')->get(Helper::fileFromParam($param_id));
    //     $object = (array)json_decode($put);
    //     $collection = Bookings::hydrate($object);
    //     // dd($collection);
    //     return $collection->flatten();   // get rid of unique_id_XXX
    // }

    //Converting unix time to normal date time
    public static function lastCreatedDate($lastModified)
    {
        $date_time_format = $lastModified->format('Y-m-d H:i:s');
        $time_zone_from = "UTC";
        $time_zone_to = 'Asia/Kolkata';
        $display_date = new DateTime($date_time_format, new DateTimeZone($time_zone_from));
        // Date time with specific timezone
        $display_date->setTimezone(new DateTimeZone($time_zone_to));
        return $display_date->format('d-m-Y H:i:s');
    }


    public static function fileFromParam($param_id)
    {
        $fileName = [
            18 => "bookings.json",
            19 => "payroll.json",
            20 => "fuel.json",
            26 => "parts.json",
            "leave" => "leave.json",
            "transaction" => "transaction.json",
            "transaction-bank" => "transaction-bank.json",
            "upcoming-renewals" => "upcoming-renewals.json",
        ];
        return $fileName[$param_id];
    }

    public static function listTransaction()
    {
        $index['transactions'] = Transaction::orderBy('id', 'ASC')->get();
        foreach ($index['transactions'] as $k => $t) {
            $transaction = Transaction::where(['from_id' => $t->from_id, 'param_id' => $t->param_id]);
            if ($transaction->exists()) {
                // dd($transaction->first());
                $income = IncomeExpense::where('transaction_id', $transaction->first()->id)->withTrashed()->first();
                // dd($transaction->first()->id);
                // dd($income);
                // empty($income) ? dd($transaction->get()) : false;
                $t->method = $income->payment_method;
                $t->amt = $income->amount;
                $exdata = IncomeExpense::where('transaction_id', $t->id)->orderBy('id', 'DESC')->take(1);

                $t->rem = $exdata->exists() ? $exdata->first() : null;

                if ($k == 0) {
                    $t->prev = 0;
                    $t->grandtotal = $t->prev - $t->total;
                } else {
                    $t->prev = $index['transactions'][$k - 1]->grandtotal;
                    if ($t->type == 23)
                        $t->grandtotal = $index['transactions'][$k - 1]->grandtotal + $t->total;
                    else
                        $t->grandtotal = $index['transactions'][$k - 1]->grandtotal - $t->total;
                }
            } else {
                $t->method = null;
                $t->amt = null;
                $t->rem = null;
                $t->prev = 0;
            }
            // dd($t);
        }
        return $index['transactions']->reverse();
    }

    public function listTransactionBank()
    {
        $index['transactions'] = Transaction::get();
        $bankArr = [];
        foreach ($index['transactions'] as $k => $t) {
            $transaction = Transaction::where(['from_id' => $t->from_id, 'param_id' => $t->param_id]);
            if ($transaction->exists()) {
                // dd($transaction->first());
                $income = IncomeExpense::where('transaction_id', $transaction->first()->id)->withTrashed()->first();

                $t->method = $income->payment_method;
                $t->amt = $income->amount;
                $exdata = IncomeExpense::where('transaction_id', $t->id)->orderBy('id', 'DESC')->take(1);

                $t->rem = $exdata->exists() ? $exdata->first() : null;

                // Bank ID
                $bank_id = ($t->bank_id == null || $t->bank_id == 0) ? 0 : $t->bank_id;


                if (array_key_exists($bank_id, $bankArr)) {
                    $prev = $bankArr[$bank_id][count($bankArr[$bank_id]) - 1]['prev'];
                    $prev_total = $bankArr[$bank_id][count($bankArr[$bank_id]) - 1]['gtotal'];
                    $gtotal = $t->type == 23 ? $prev_total + $t->total : $prev_total - $t->total;
                    $bankArr[$bank_id][] = [
                        'prev' => $prev_total,
                        'amount' => $t->total,
                        'gtotal' => $gtotal
                    ];
                } else {
                    $gtotal = $t->type == 23 ? 0 + $t->total : 0 - $t->total;
                    $bankArr[$bank_id][] = [
                        'prev' => 0,
                        'amount' => $t->total,
                        'gtotal' => $gtotal
                    ];
                }
                $t->prev =  $bankArr[$bank_id][count($bankArr[$bank_id]) - 1]['prev'];
                $t->grandtotal =  $bankArr[$bank_id][count($bankArr[$bank_id]) - 1]['gtotal'];
            } else {
                $t->method = null;
                $t->amt = null;
                $t->rem = null;
                $t->prev = 0;
            }
            // dd($t);
        }
        return $index['transactions']->reverse();
    }

    public static function getMonthsBetweenDates($begin, $end, $duration = null)
    {
        $begin = new DateTime('2014-07-01');
        $end = new DateTime('2016-08-01');
        $end = $end->modify('+1 month');
        $interval = DateInterval::createFromDateString('1 month');

        $period = new DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            $dateArray[] = $dt->format("d-m-Y");
        }
        return $dateArray;
    }

    public static function toCollection($array)
    {
        return json_decode(json_encode($array), FALSE);
    }

    public static function toRequest($array)
    {
        return request($array);
    }

    public static function removeKM(String $distance)
    {
        $value = explode(" ", $distance)[0];
        $value = str_replace(',', '', $value);
        $valueDecimal = (float) Helper::properDecimals($value);
        return $valueDecimal;
    }


    public static function totalBookingIncome(Request $request)
    {
        // $years = collect(DB::select("select distinct year(date) as years from fuel  as where deleted_at is null order by years desc"))->toArray();
        $years = FuelModel::select('year')->distinct();
        //Getting Years from Booking

        dd($years);
        $y = array();
        foreach ($years as $year) {

            $y[$year->years] = $year->years;
        }

        if ($years == null) {
            $y[date('Y')] = date('Y');
        }
        if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
            $data['vehicles'] = VehicleModel::get()->toArray();
        } else {
            $data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get()->toArray();
        }

        $data['vehicle_id'] = $request->get('vehicle_id');
        $data['year_select'] = $request->get('year');
        $data['month_select'] = $request->get('month');
        $data['years'] = $y;
        if ($request->get('date1') == null)
            $start = FuelModel::orderBy('date', 'asc')->take(1)->first('date')->date;
        else
            $start = date('Y-m-d', strtotime($request->get('date1')));

        if ($request->get('date2') == null)
            $end = FuelModel::orderBy('date', 'desc')->take(1)->first('date')->date;
        else
            $end = date('Y-m-d', strtotime($request->get('date2')));

        // dd($start);
        // dd($end);


        $v = " and vehicle_id=" . $data['vehicle_id'];

        if ($request->get('month') == '0' && $request->get('vehicle_id') == '') {
            $data['fuel'] = FuelModel::whereYear('date', $data['year_select'])->whereBetween('date', [$start, $end])->get();
        } else if ($request->get('month') == '0') {
            $data['fuel'] = FuelModel::whereYear('date', $data['year_select'])->where('vehicle_id', $request->get('vehicle_id'))->whereBetween('date', [$start, $end])->get();
        } else if ($request->get('vehicle_id') == '') {
            $data['fuel'] = FuelModel::whereYear('date', $data['year_select'])->whereMonth('date', $data['month_select'])->whereBetween('date', [$start, $end])->get();
        } else {
            $data['fuel'] = FuelModel::whereYear('date', $data['year_select'])->whereMonth('date', $data['month_select'])->where('vehicle_id', $request->get('vehicle_id'))->whereBetween('date', [$start, $end])->get();
        }
    }

    public static function timeArray(Int $times)
    {
        for ($i = 0; $i <= $times; $i++) {
            $counter = $i < 10 ? "0" . $i : $i;
            $array[$counter] = $counter;
        }
        return $array;
    }

    public static function digitsToTime($string)
    { //09:45
        $timeAray = explode(":", $string);
        if (empty($timeAray[0]) || $timeAray[0] == "00") {
            $hour = "";
            $hourtxt = "";
        } else if ((int) $timeAray[0] == 1) {
            $hour = (int) $timeAray[0];
            $hourtxt = "hour";
        } else if ((int) $timeAray[0] > 1) {
            $hour = (int) $timeAray[0];
            $hourtxt = "hours";
        }

        if (empty($timeAray[1]) || $timeAray[1] == "00") {
            $min = "";
            $mintxt = "";
        } else if ((int) $timeAray[1] == 1) {
            $min = (int) $timeAray[1];
            $mintxt = "min";
        } else if ((int) $timeAray[1] > 1) {
            $min = (int) $timeAray[1];
            $mintxt = "mins";
        }

        $timeString = $hour . " " . $hourtxt . " " . $min . " " . $mintxt;
        if (empty(trim($timeString)))
            return "";
        else
            return $timeString;
    }

    public static function toMinutes($string)
    {
        $timeArray = explode(" ", $string);


        if (count($timeArray) == 4)
            $inMinTotal = ((int) $timeArray[0] * 60) + (int) $timeArray[2];
        else if (count($timeArray) == 2)
            $inMinTotal = (int) $timeArray[2];
        else $inMinTotal = 0;
        return $inMinTotal;
        // $now = date("Y-m-d H:i:s");
        // $future = date("Y-m-d H:i:s",strtotime($string));
        // round(abs(strtotime($now) - strtotime($future))/86400);
    }
    public static function greater($e, $p)
    {
        return $e > $p ? true : false;
    }

    public static function leaveTypes()
    {
        return [
            "1" => 'Present',
            "2" => 'Full Leave',
            "3" => '1st Half Leave',
            "4" => '2nd Half Leave'
        ];
    }

    public static function ymd($value = null)
    {
        if (!is_null($value))
            $value = date("Y-m-d", strtotime($value));
        return $value;
    }

    public static function renewLastday($date)
    {
        $to = Carbon::now();
        $from = Carbon::createFromFormat('Y-m-d', $date);
        return $to->diffInDays($from, false);
        // dd($to->diffInDays($from,false));
    }

    public static function accountBalance($id)
    {
        $bank_account = BankAccount::find($id);
        $starting_amount = $bank_account->starting_amount;
        $sendToSelf = BankTransaction::where(['bank_id' => 1, 'refer_bank' => $id])->sum('amount'); //deducted from bank account
        $depositToAccount = BankTransaction::where('bank_id', $id)->sum('amount'); // deposited or added to bank account
        $transactionMade = Transaction::where('bank_id', $id)->where('is_completed', 1)->sum('total'); // deposited or added to bank account
        return bcdiv(($starting_amount + $depositToAccount) - ($sendToSelf + $transactionMade), 1, 2);
    }

    public static function indianDateFormat($value = null)
    {
        if (!is_null($value))
            $value = date("d-m-Y", strtotime($value));
        return $value;
    }

    public static function dynamicLastDate($vehicle_id, $param_id)
    {
        $docRenewed = VehicleDocs::where(['vehicle_id' => $vehicle_id, 'param_id' => $param_id]);
        $response = $docRenewed->exists() ? ['status' => true, 'date' => $docRenewed->orderBy('id', 'DESC')->first()->till] : ['status' => false];
        return self::toCollection($response);
    }

    public static function fromDateArray(array $dates)
    { //fromDateArray(array,greatest or smallest flag)
        $mostRecent = 0;
        foreach ($dates as $date) {
            if (!empty($date) || !is_null($date)) {
                $curDate = strtotime($date);
                if ($curDate > $mostRecent) {
                    $mostRecent = $curDate;
                } else {
                    $mostRecent = $curDate;
                }
            }
        }
        if ($mostRecent == strtotime("1970-01-01") || $mostRecent == 0) return null;
        else return date("Y-m-d", $mostRecent);
    }

    public static  function upcomingRenewals()
    {
        $vehicles =  VehicleModel::where('in_service', 1)->get();
        $docparams = Params::where('code', 'RenewDocuments')->get();
        $docparamArray = [
            36 => ['ins_renew_duration', 'ins_renew_amount', 'ins_exp_date'],
            37 => ['fitness_renew_duration', 'fitness_renew_amount', 'fitness_expdate'],
            38 => ['roadtax_renew_duration', 'roadtax_renew_amount', 'road_expdate'],
            39 => ['permit_renew_duration', 'permit_renew_amount', 'permit_expdate'],
            40 => ['pollution_renew_duration', 'pollution_renew_amount', 'pollution_expdate']
        ];
        $newArray = array();
        foreach ($vehicles as $vehi) {
            foreach ($docparams as $param) {
                //driver
                $vehicleDoc = VehicleDocs::where(['vehicle_id' => $vehi->id, 'param_id' => $param->id]);
                if ($vehicleDoc->exists()) {
                    $renew_data = $vehicleDoc->orderBy('id', 'DESC')->first();
                    // dd($renew_data->driver_id);
                    // if(!empty($renew_data->driver_id))
                    //     dd($renew_data->driver_id);
                    $driver_id = !empty($renew_data->driver_id) || !is_null($renew_data->driver_id) ? User::find($renew_data->driver_id)->name : null;
                    $vendor_id = !empty($renew_data->vendor_id) || !is_null($renew_data->vendor_id) ? Vendor::find($renew_data->vendor_id)->name : null;
                    $date = $renew_data->date;
                    $till = $renew_data->till;
                    $amount = $renew_data->amount;
                    $status = $renew_data->status;
                    $remarks = $renew_data->remarks;
                    $method = $renew_data->method;
                    $ddno = $renew_data->ddno;
                } else {
                    $driver_id =  !empty($vehi->driver) && !empty($vehi->driver->assigned_driver) ? $vehi->driver->assigned_driver->name : null;
                    $vendor_id = null;
                    $date = null;
                    $till = !empty($vehi->getMeta($docparamArray[$param->id][2])) ? $vehi->getMeta($docparamArray[$param->id][2]) : null;
                    $amount = null;
                    $status = null;
                    $remarks = null;
                    $method = null;
                    $ddno = null;
                }
                $createdArray['vid'] = $vehi->id;
                $createdArray['vehicle_id'] = $vehi->make . "-" . $vehi->model . "-" . $vehi->license_plate;
                $createdArray['driver_id'] = $driver_id;
                $createdArray['vendor_id'] = $vendor_id;
                $createdArray['pid'] = $param->id;
                $createdArray['param_id'] = Params::find($param->id)->label;
                $createdArray['date'] = $date;
                $createdArray['till'] = $till;
                $createdArray['daysleft'] = !empty($till) ? self::renewLastday($till) : null;
                $createdArray['amount'] = $amount;
                $createdArray['status'] = $status;
                $createdArray['remarks'] = $remarks;
                $createdArray['method'] = $method;
                $createdArray['ddno'] = $ddno;
                // $createdArray['vehObj'] = VehicleModel::where("id",$vehi->id)->select("id")->first();

                array_push($newArray, $createdArray);
            }
        }
        return self::toCollection($newArray);
    }

    public static function checkEligibleRenewalVehicle($vehicle_id, $date_offset = 0) //$date_offset is used for how many days before the user can see the upcoming renew vehicles
    {
        $renewDocs = Params::where('code', 'RenewDocuments')->get();
        $docparamArray = [
            36 => ['ins_renew_duration', 'ins_renew_amount', 'ins_exp_date'],
            37 => ['fitness_renew_duration', 'fitness_renew_amount', 'fitness_expdate'],
            38 => ['roadtax_renew_duration', 'roadtax_renew_amount', 'road_expdate'],
            39 => ['permit_renew_duration', 'permit_renew_amount', 'permit_expdate'],
            40 => ['pollution_renew_duration', 'pollution_renew_amount', 'pollution_expdate']
        ];
        $ez_array = array();
        foreach ($renewDocs as $rn) {
            $dbDoc = VehicleDocs::where(['vehicle_id' => $vehicle_id, 'param_id' => $rn->id]);
            if ($dbDoc->exists()) {
                $date = $dbDoc->orderBy('id', 'DESC')->first()->till;
            } else {
                $date = VehicleModel::find($vehicle_id)->getMeta($docparamArray[$rn->id][2]);
            }

            if (!empty($date)) {
                if (!empty($date_offset) && $date_offset != 0)
                    $is_eligible = strtotime(date("Y-m-d") . "-$date_offset days") >= strtotime($date) ? 1 : 0;
                else
                    $is_eligible = strtotime(date("Y-m-d")) >= strtotime($date) ? 1 : 0;
            } else {
                $is_eligible = 0;
            }
            array_push($ez_array, $is_eligible);
        }

        if (array_sum($ez_array) > 0) {
            $resp['status'] = true;
        } else {
            $resp['status'] = false;
        }
        return Helper::toCollection($resp);
    }

    public static function fuelPackageData(String $string = null)
    {

        if ($string === 'ltr') {
            $response = [1, 2];
        } else if ($string === 'pc') {
            $response = FuelType::whereNotIn('id', [1, 2])->pluck("id")->toArray();
        } else {
            $response = FuelType::pluck("id")->toArray();
        }
        // dd($response);
        return $response;
    }

    public static function durationUnits()
    {
        return [
            'days' => 'Days',
            'months' => 'Months',
            'years' => 'Years',
        ];
    }

    public static function isGlobalSearch()
    {
        $url = url()->previous();
        return Str::contains($url, "global");
    }
    
    public static function properDecimal($val){
       
            $dividend = $val;
        
        $divisor = "1";
        $scale = 2;
        // dd(bcdiv($dividend,$divisor,$scale),var_dump($val));
        // dd($val);
        
        // die();
        return bcdiv($dividend,$divisor,$scale);
    }
}
