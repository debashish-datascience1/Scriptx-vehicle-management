<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\FuelPurchase;
use App\Model\Vendor;
use App\Model\FuelType;
use Illuminate\Http\Request;

class FuelPurchaseController extends Controller
{
    public function index()
    {
        $fuel_purchases = FuelPurchase::paginate(10); // 10 is the number of items per page
        return view('fuel_purchase.index', compact('fuel_purchases'));
    }

    public function create()
    {
        $vendors = Vendor::where('type', 'Fuel')->pluck('name', 'id');
        $data['oil'] = FuelType::pluck('fuel_name', 'id');
        return view('fuel_purchase.create', compact('vendors', 'data'));
    }

    public function store(Request $request)
    {
        // Debug: Log entire request
        \Log::info('Fuel Purchase Request Data:', $request->all());

        $validatedData = $request->validate([
            'date' => 'required|date',
            'vendor_id' => 'required|exists:vendors,id',
            'fuel_type_id' => 'required|exists:fuel_type,id',
            'quantity' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string'
        ]);

        // Debug: Log validated data before creating
        \Log::info('Validated Data:', $validatedData);

        FuelPurchase::create($validatedData);

        return redirect()->route('fuel_purchase.index')
            ->with('success', 'Fuel purchase recorded successfully.');
    }

    public function edit($id)
    {
        $fuel_purchase = FuelPurchase::findOrFail($id);
        $vendors = Vendor::where('type', 'Fuel')->pluck('name', 'id');
        $data['oil'] = FuelType::pluck('fuel_name', 'id');
        return view('fuel_purchase.edit', compact('fuel_purchase', 'vendors', 'data'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'vendor_id' => 'required|exists:vendors,id',
            'fuel_type_id' => 'required|exists:fuel_type,id',
            'quantity' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string'
        ]);

        $fuel_purchase = FuelPurchase::findOrFail($id);
        $fuel_purchase->update($validatedData);

        return redirect()->route('fuel_purchase.index')
            ->with('success', 'Fuel purchase updated successfully.');
    }

    public function destroy($id)
    {
        $fuel_purchase = FuelPurchase::findOrFail($id);
        $fuel_purchase->delete();

        return redirect()->route('fuel_purchase.index')
            ->with('success', 'Fuel purchase deleted successfully.');
    }
}