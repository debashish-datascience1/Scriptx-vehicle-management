<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartsCategoryRequest;
use App\Model\PartsCategoryModel;
use Illuminate\Http\Request;

class PartsCategoryController extends Controller
{
    public function index()
    {
        $records = PartsCategoryModel::orderBy('id', 'desc')->get();
        return view('parts_category.index', compact('records'));
    }

    public function create()
    {
        return view('parts_category.create');
    }

    public function store(PartsCategoryRequest $request)
    {
        PartsCategoryModel::create(['name' => $request->name]);
        return redirect()->route('parts-category.index');
    }

    public function edit($id)
    {
        $data = PartsCategoryModel::find($id);
        return view('parts_category.edit', compact('data'));
    }

    public function update(PartsCategoryRequest $request)
    {
        PartsCategoryModel::where('id', $request->id)->update(['name' => $request->name]);
        return redirect()->route('parts-category.index');
    }

    public function destroy(Request $request)
    {
        PartsCategoryModel::find($request->id)->delete();
        return redirect()->route('parts-category.index');
    }

    public function bulk_delete(Request $request)
    {
        PartsCategoryModel::whereIn('id', $request->ids)->delete();
        return back();
    }
}
