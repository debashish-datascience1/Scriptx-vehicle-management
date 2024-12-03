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
                        ->groupBy('fastag');

        // Calculate totals for each group
        $fastags = $fastags->map(function ($group) {
            return [
                'entries' => $group,
                'total' => $group->sum('amount'),
                'fastag' => $group->first()->fastag,
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
        
        // Filter bank accounts to only include those with 'fastag' in the bank name
        $data['bank_accounts'] = BankAccount::where(function ($query) {
            $query->whereRaw('LOWER(bank) LIKE ?', ['%fastag%'])
                ->orWhereRaw('LOWER(bank) LIKE ?', ['%fast tag%']);
        })->get();
        
        return view('fastag.create', $data);
    }

    public function store(Request $request)
    {
        \Log::info('Fastag Data:', $request->all());

        $validatedData = $request->validate([
            'vehicle_id' => 'required|array',
            'vehicle_id.*' => 'exists:vehicles,id',
            'fastag' => 'required|exists:bank_account,id',
            'date' => 'required|array',
            'date.*' => 'required|date',
            'toll_gate_name' => 'required|array',
            'toll_gate_name.*' => 'required|string|max:255',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
        ]);

        $transaction_id = 'SALE' . uniqid();
        $total = $request->grand_total;
        $fastagId = $validatedData['fastag'];

        try {
            DB::beginTransaction();

            foreach ($validatedData['vehicle_id'] as $key => $vehicleId) {
                // Fetch the vehicle registration number
                $vehicle = VehicleModel::findOrFail($vehicleId);
                $registrationNumber = "{$vehicle->make} - {$vehicle->model} - {$vehicle->license_plate}";
                $bankAccount = BankAccount::findOrFail($fastagId);
                $fastagNumber = "{$bankAccount->bank} - {$bankAccount->account_no}";
        
                // Create FastTag entry with explicit column mapping
                Fastag::create([
                    'id' => null,  // Let the database auto-increment handle this
                    'toll_gate_name' => $validatedData['toll_gate_name'][$key],
                    'amount' => $validatedData['amount'][$key],
                    'fastag' => $fastagNumber,  // Make sure this column name matches your database
                    'date' => $validatedData['date'][$key],
                    'vehicle_id' => $vehicleId,  // Explicitly set the vehicle_id
                    'registration_number' => $registrationNumber,
                    'transaction_id' => $transaction_id,
                    'total_amount' => $total,
                    'bank_account_id' => $fastagId
                ]);
            }

            DB::commit();

            return redirect()->route('fastag.index')
                ->with('success', 'Fastag entries created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('FastTag Creation Error: ' . $e->getMessage());
            
            return redirect()->route('fastag.index')
                ->with('error', 'Error creating Fastag entries. Please try again.');
        }
    }

    private function createFastagEntry($tollGateName, $amount, $fastagId, $date, $registrationNumber, $vehicleId, $transaction_id, $total)
    {
        // Fetch the bank account details
        $bankAccount = BankAccount::findOrFail($fastagId);
        $fastagNumber = "{$bankAccount->bank} - {$bankAccount->account_no}";

        // Create the Fastag entry with vehicle_id
        Fastag::create([
            'toll_gate_name' => $tollGateName,
            'amount' => $amount,
            // 'fastag_id' => $fastagId,  
            'fastag' => $fastagNumber, 
            'vehicle_id' => $vehicleId, 
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
        
        // Filter bank accounts to only include those with 'fastag' in the bank name
        $data['bank_accounts'] = BankAccount::where(function ($query) {
            $query->whereRaw('LOWER(bank) LIKE ?', ['%fastag%'])
                ->orWhereRaw('LOWER(bank) LIKE ?', ['%fast tag%']);
        })->get();
        
        $data['fastag'] = $fastag;
        $data['fastagEntries'] = Fastag::where('transaction_id', $fastag->transaction_id)->get();
        
        return view('fastag.edit', $data);
   }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'vehicle_id' => 'required|array',
            'vehicle_id.*' => 'exists:vehicles,id',
            'fastag' => 'required|exists:bank_account,id',
            'date' => 'required|array',
            'date.*' => 'required|date',
            'toll_gate_name' => 'required|array',
            'toll_gate_name.*' => 'required|string|max:255',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $fastag = Fastag::findOrFail($id);
            $transaction_id = $fastag->transaction_id;

            // Delete existing entries for this transaction
            Fastag::where('transaction_id', $transaction_id)->delete();

            $total = $request->grand_total;
            $fastagId = $validatedData['fastag'];

            foreach ($validatedData['vehicle_id'] as $key => $vehicleId) {
                // Fetch the vehicle registration number
                $vehicle = VehicleModel::findOrFail($vehicleId);
                $registrationNumber = "{$vehicle->make} - {$vehicle->model} - {$vehicle->license_plate}";
                
                // Fetch bank account details
                $bankAccount = BankAccount::findOrFail($fastagId);
                $fastagNumber = "{$bankAccount->bank} - {$bankAccount->account_no}";

                // Create new Fastag entry with bank_account_id
                Fastag::create([
                    'toll_gate_name' => $validatedData['toll_gate_name'][$key],
                    'amount' => $validatedData['amount'][$key],
                    'fastag' => $fastagNumber,
                    'date' => $validatedData['date'][$key],
                    'vehicle_id' => $vehicleId,
                    'registration_number' => $registrationNumber,
                    'transaction_id' => $transaction_id,
                    'total_amount' => $total,
                    'bank_account_id' => $fastagId  // Add bank_account_id
                ]);
            }

            DB::commit();
            return redirect()->route('fastag.index')
                ->with('success', 'Fastag entries updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('FastTag Update Error: ' . $e->getMessage());
            return redirect()->route('fastag.index')
                ->with('error', 'Error updating Fastag entries. Please try again.');
        }
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
