<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartsRequest;
use App\Model\PartsCategoryModel;
use App\Model\PartsModel;
use App\Model\PartsDetails;
use App\Model\Transaction;
use App\Model\IncomeExpense;
use App\Model\Manufacturer;
use App\Model\PartsInvoice;
use App\Model\PartStock;
use App\Model\UnitModel;
use App\Model\Vendor;
use Auth;
use Helper;
use Illuminate\Http\Request;
use App\Model\PartsSellModel;
use Illuminate\Support\Facades\DB;

class PartSellController extends Controller
{

    public function index()
    {
        $perPage = 4; // Number of items per page

        $data = PartsSellModel::orderBy('date_of_sell', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->groupBy('transaction_id');

        $items = Helper::getAllParts();

        // Manually paginate the grouped data
        $page = request()->get('page', 1);
        $slicedData = $data->slice(($page - 1) * $perPage, $perPage);
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $slicedData,
            $data->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view("parts_sell.index", [
            'data' => $paginatedData,
            'items' => $items
        ]);
    }

    public function getAllData()
    {
        $data = PartsSellModel::orderBy('id', 'desc')->get()->groupBy('transaction_id');
        $items = Helper::getAllParts();
        
        return response()->json(['data' => $data, 'items' => $items]);
    }
    public function create()
    {
        $categories = PartsCategoryModel::pluck('name','id')->toArray();
        $manufacturers = Manufacturer::pluck('name','id')->toArray();
        $units = UnitModel::pluck('name','id')->toArray();
        $data['categories'] = $categories;
        $data['manufacturers'] = $manufacturers;
        $data['units'] = $units;
        $data['items'] = Helper::getAllParts();

        return view("parts_sell.create", $data);
    }
    public function store(Request $request)
    {
        $request->validate([
            'sell_to' => 'required|string',
            'date' => 'required|date',
            'item' => 'required|array',
            'quantity' => 'required|array',
            'tyre_numbers' => 'nullable|array',
            'amount' => 'required|array',
            'total' => 'required|array',
            'grand_total' => 'required|numeric',
        ]);

        DB::beginTransaction();

        $transaction_id = 'SALE'.uniqid();

        try {
            foreach ($request->item as $key => $item) {
                $tyreNumbers = isset($request->tyre_numbers_grouped[$key]) ? $request->tyre_numbers_grouped[$key] : null;

                PartsSellModel::create([
                    'sell_to' => $request->sell_to,
                    'date_of_sell' => $request->date,
                    'item' => $item,
                    'quantity' => $request->quantity[$key],
                    'tyre_numbers' => $tyreNumbers,
                    'amount' => $request->amount[$key],
                    'total' => $request->total[$key],
                    'grand_total' => $request->grand_total,
                    'transaction_id' => $transaction_id
                ]);
            }

            DB::commit();
            return redirect()->route('parts-sell.index')->with('success', 'Parts sold successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'An error occurred while saving the data: ' . $e->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'sell_to' => 'required|string',
            'date' => 'required|date',
            'item' => 'required|array',
            'quantity' => 'required|array',
            'tyre_numbers' => 'nullable|array',
            'amount' => 'required|array',
            'total' => 'required|array',
            'grand_total' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $mainRecord = PartsSellModel::findOrFail($id);
            $transaction_id = $mainRecord->transaction_id;

            // Delete all existing records for this transaction
            PartsSellModel::where('transaction_id', $transaction_id)->delete();

            // Create new records with updated data
            foreach ($request->item as $key => $item) {
                PartsSellModel::create([
                    'sell_to' => $request->sell_to,
                    'date_of_sell' => $request->date,
                    'item' => $item,
                    'quantity' => $request->quantity[$key],
                    'tyre_numbers' => $request->tyre_numbers_grouped[$key],
                    'amount' => $request->amount[$key],
                    'total' => $request->total[$key],
                    'grand_total' => $request->grand_total,
                    'transaction_id' => $transaction_id
                ]);
            }

            DB::commit();
            return redirect()->route('parts-sell.index')->with('success', 'Parts sale updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'An error occurred while updating the data: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $data = PartsSellModel::where('id', $id)->first();
        if (!$data) {
            return redirect()->route('parts-sell.index')->with('error', 'Parts sale not found');
        }

        $allParts = PartsSellModel::where('transaction_id', $data->transaction_id)->get();
        $items = Helper::getAllParts();
        // dd($allParts);
        
        return view("parts_sell.edit", compact('data', 'allParts', 'items'));
    }

    public function destroy($id)
    {
        try {
            $part = PartsSellModel::findOrFail($id);
            PartsSellModel::where('transaction_id', $part->transaction_id)->delete();
            return redirect()->route('parts-sell.index')->with('success', 'Parts sale and all associated parts deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the data: ' . $e->getMessage());
        }
    }
}