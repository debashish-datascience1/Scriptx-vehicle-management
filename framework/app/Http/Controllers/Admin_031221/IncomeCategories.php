<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Http\Requests\IncomeCatRequest;
use App\Model\IncCats;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Importer;

class IncomeCategories extends Controller {

	public function importIncome(ImportRequest $request) {
		$file = $request->excel;
		$destinationPath = './assets/samples/'; // upload path
		$extension = $file->getClientOriginalExtension();
		$fileName = Str::uuid() . '.' . $extension;
		$file->move($destinationPath, $fileName);

		$excel = Importer::make('Excel');
		$excel->load('assets/samples/' . $fileName);
		$collection = $excel->getCollection()->toArray();
		array_shift($collection);
		foreach ($collection as $income) {
			if ($income[0] != null || $income[0] != " ") {
				IncCats::create([
					"name" => $income[0],
					"user_id" => Auth::id(),
					"type" => "u",
				]);
			}
		}
		return back();
	}

	public function index(Request $request) {
		$data['data'] = IncCats::get();

		return view("income.cats", $data);
	}
	public function create() {

		return view("income.catadd");
	}

	public function destroy(Request $request) {
		IncCats::find($request->get('id'))->income()->delete();
		IncCats::find($request->get('id'))->delete();

		return redirect()->route('incomecategories.index');
	}

	public function store(IncomeCatRequest $request) {

		IncCats::create([
			"name" => $request->get("name"),
			"user_id" => Auth::id(),
			"type" => "u",

		]);

		return redirect()->route("incomecategories.index");

	}

	public function edit(IncCats $incomecategory) {

		return view("income.catedit", compact("incomecategory"));
	}

	public function update(IncomeCatRequest $request) {

		$user = IncCats::whereId($request->get("id"))->first();
		$user->name = $request->get("name");
		$user->user_id = Auth::id();
		$user->save();

		return redirect()->route("incomecategories.index");
	}

}