<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\AdvanceDriver;
use App\Model\DailyAdvance;
use App\Model\User;
use App\Model\Fastag;
use App\Model\Params;
use App\Model\Transaction;
use App\Model\IncomeExpense;
use App\Model\Payroll;
use App\Model\BankAccount;
use App\Model\BankTransaction;
use App\Model\Bookings;
use App\Model\VehicleModel;
use DB;
use Auth;
use Illuminate\Http\Request;
use Helper;

class FastagController extends Controller
{

    // public function index()
    // {
    //     $fastags = Fastag::orderBy('id', 'DESC')->paginate(50);
    //     return view('fastag.index', compact('fastags'));
    // }

    public function index()
    {
        $perPage = 4; // Number of groups per page
    
        $fastags = Fastag::orderBy('created_at', 'desc')
                         ->get()
                         ->groupBy('transaction_id');
    
        // Calculate totals for each group
        $fastags = $fastags->map(function ($group) {
            return [
                'entries' => $group,
                'total' => $group->sum('amount'),
                'vehicle' => $group->first()->registration_number,
                'transaction_id' => $group->first()->transaction_id,
                'date' => $group->first()->created_at,
            ];
        });
    
        // Manual pagination
        $page = request()->get('page', 1);
        $slicedData = $fastags->slice(($page - 1) * $perPage, $perPage);
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $slicedData,
            $fastags->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    
        return view('fastag.index', compact('paginatedData'));
    }

    public function create()
    {
        $user = Auth::user()->group_id;
        if ($user == null) {
            $data['vehicles'] = VehicleModel::whereIn_service("1")->get();
        } else {
            $data['vehicles'] = VehicleModel::where([['group_id', $user], ['in_service', '1']])->get();
        }
        $data['bank_accounts'] = BankAccount::all();
        return view('fastag.create', $data);
    }

    // public function store(Request $request)
    // {
    //     \Log::info('Fastag Data:', $request->all());  // Add this line

    //     $validatedData = $request->validate([
    //         'toll_gate_name' => 'required|string|max:255',
    //         'amount' => 'required|numeric|min:0',
    //         'fastag' => 'required|string|max:255',
    //         'registration_number' => 'required|string|max:255',
    //     ]);

    //     Fastag::create($validatedData);

    //     return redirect()->route('fastag.index')
    //         ->with('success', 'Fastag entry created successfully.');
    // }
    public function store(Request $request)
    {
        \Log::info('Fastag Data:', $request->all());

        $validatedData = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'fastag' => 'required|array',
            'fastag.*' => 'required|exists:bank_account,id',
            'date' => 'required|array',
            'date.*' => 'required|date',
            'toll_gate_name' => 'required|array',
            'toll_gate_name.*' => 'required|string|max:255',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:0',
        ]);

        // Fetch the vehicle registration number
        $vehicle = VehicleModel::findOrFail($validatedData['vehicle_id']);
        $registrationNumber = "{$vehicle->make} - {$vehicle->model} - {$vehicle->license_plate}";
        $transaction_id = 'SALE'.uniqid();
        $total = $request->grand_total;

        // Create Fastag entries
        foreach ($validatedData['fastag'] as $key => $fastagId) {
            $this->createFastagEntry(
                $validatedData['toll_gate_name'][$key],
                $validatedData['amount'][$key],
                $fastagId,
                $validatedData['date'][$key],
                $registrationNumber,
                $transaction_id,
                $total
            );
        }

        return redirect()->route('fastag.index')
            ->with('success', 'Fastag entries created successfully.');
    }

    private function createFastagEntry($tollGateName, $amount, $fastagId, $date, $registrationNumber,$transaction_id,$total)
    {
        // Fetch the bank account details
        $bankAccount = BankAccount::findOrFail($fastagId);
        $fastagNumber = "{$bankAccount->bank} - {$bankAccount->account_no}";
        // dd($date);

        // Create the Fastag entry
        Fastag::create([
            'toll_gate_name' => $tollGateName,
            'amount' => $amount,
            'fastag' => $fastagNumber,
            'registration_number' => $registrationNumber,
            'date' => $date,
            'transaction_id' => $transaction_id,
            'total_amount' => $total
        ]);
    }

    public function edit($id)
    {
        $fastag = Fastag::findOrFail($id);
        $user = Auth::user()->group_id;
        
        if ($user == null) {
            $data['vehicles'] = VehicleModel::whereIn_service("1")->get();
        } else {
            $data['vehicles'] = VehicleModel::where([['group_id', $user], ['in_service', '1']])->get();
        }
        
        $data['bank_accounts'] = BankAccount::all();
        $data['fastag'] = $fastag;
        $data['fastagEntries'] = Fastag::where('transaction_id', $fastag->transaction_id)->get();
        
        return view('fastag.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'fastag' => 'required|array',
            'fastag.*' => 'required|exists:bank_account,id',
            'date' => 'required|array',
            'date.*' => 'required|date',
            'toll_gate_name' => 'required|array',
            'toll_gate_name.*' => 'required|string|max:255',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:0',
        ]);

        $fastag = Fastag::findOrFail($id);
        $transaction_id = $fastag->transaction_id;

        // Fetch the vehicle registration number
        $vehicle = VehicleModel::findOrFail($validatedData['vehicle_id']);
        $registrationNumber = "{$vehicle->make} - {$vehicle->model} - {$vehicle->license_plate}";

        // Delete existing entries for this transaction
        Fastag::where('transaction_id', $transaction_id)->delete();

        $total = $request->grand_total;

        // Create new Fastag entries
        foreach ($validatedData['fastag'] as $key => $fastagId) {
            $this->createFastagEntry(
                $validatedData['toll_gate_name'][$key],
                $validatedData['amount'][$key],
                $fastagId,
                $validatedData['date'][$key],
                $registrationNumber,
                $transaction_id,
                $total
            );
        }

        return redirect()->route('fastag.index')
            ->with('success', 'Fastag entries updated successfully.');
    }

    public function destroy($id)
    {
        $fastag = Fastag::find($id);
        if($fastag){
            // $fastag->delete();
            Fastag::where('transaction_id', $fastag->transaction_id)->delete();
            return redirect()->route('fastag.index')->with('success', 'Fastag entry deleted successfully');
        }
        return redirect()->route('fastag.index')->with('error', 'Fastag entry not found');
    }
}
