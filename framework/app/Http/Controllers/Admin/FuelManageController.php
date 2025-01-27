<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\FuelManagement;
use App\Model\VehicleModel;
use App\Model\FuelPurchase;
use Illuminate\Http\Request;
use Auth;


class FuelManageController extends Controller
{
    public function index()
    {
        $fuel_managements = FuelManagement::with('vehicle')->paginate(10);
        return view('fuel_manage.index', compact('fuel_managements'));
    }

    public function create()
    {
        if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
            $vehicles = VehicleModel::whereIn_service("1")->get();
        } else {
            $vehicles = VehicleModel::where('group_id', Auth::user()->group_id)->whereIn_service("1")->get();
        }
        
        return view('fuel_manage.create', compact('vehicles'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'vehicle_id' => 'required|exists:vehicles,id',
            'quantity' => 'required|numeric|min:0',
        ]);

        try {
            FuelManagement::create($request->all());

            return redirect()->route('fuel_manage.index')
                ->with('success', __('fleet.fuel_management_saved'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('fleet.save_error'));
        }
    }

    public function edit($id)
    {
        $fuel_management = FuelManagement::findOrFail($id);

        $user = Auth::user();
        if ($user->group_id == null || $user->user_type == "S") {
            $vehicles = VehicleModel::whereIn_service("1")->get();
        } else {
            $vehicles = VehicleModel::where('group_id', $user->group_id)->whereIn_service("1")->get();
        }

        return view('fuel_manage.edit', compact('fuel_management', 'vehicles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'vehicle_id' => 'required|exists:vehicles,id',
            'quantity' => 'required|numeric|min:0',
            'stock' => 'required|in:own'
        ]);

        try {
            $fuel_management = FuelManagement::findOrFail($id);
            $fuel_management->update($request->all());

            return redirect()->route('fuel_manage.index')
                ->with('success', __('fleet.fuel_management_updated'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('fleet.update_error'));
        }
    }

    public function destroy($id)
    {
        try {
            $fuel_management = FuelManagement::findOrFail($id);
            $fuel_management->delete();

            return redirect()->route('fuel_manage.index')
                ->with('success', __('fleet.fuel_management_deleted'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('fleet.delete_error'));
        }
    }

    public function getAvailableStock(Request $request)
    {
        // Calculate available stock
        $availableStock = FuelPurchase::sum('quantity');

        // Optional: Subtract used stock
        $usedStock = FuelManagement::sum('quantity');
        $availableStock -= $usedStock;

        return response()->json([
            'available_stock' => max(0, $availableStock)
        ]);
    }
}
