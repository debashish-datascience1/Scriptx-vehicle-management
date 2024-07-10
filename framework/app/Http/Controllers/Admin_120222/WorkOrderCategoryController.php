<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkOrderCategoryRequest;
use App\Model\WorkOrderCategory;
use Illuminate\Http\Request;

class WorkOrderCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orderCategories = WorkOrderCategory::descending()->get();
        return view('work_order_categories.index', compact('orderCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $workOrderCategory = new WorkOrderCategory();
        return view('work_order_categories.create', compact('workOrderCategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WorkOrderCategoryRequest $request)
    {
        // dd($request->all());
        WorkOrderCategory::create($request->all());
        return redirect()->route('work-order-category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WorkOrderCategory  $workOrderCategory
     * @return \Illuminate\Http\Response
     */
    public function show(WorkOrderCategory $workOrderCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WorkOrderCategory  $workOrderCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkOrderCategory $workOrderCategory)
    {
        // dd($workOrderCategory);
        return view('work_order_categories.edit', compact('workOrderCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WorkOrderCategory  $workOrderCategory
     * @return \Illuminate\Http\Response
     */
    public function update(WorkOrderCategoryRequest $request, WorkOrderCategory $workOrderCategory)
    {
        $formData = $request->all();
        unset($formData['_method']);
        unset($formData['_token']);
        WorkOrderCategory::whereId($workOrderCategory->id)->update($formData);
        return redirect()->route('work-order-category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WorkOrderCategory  $workOrderCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkOrderCategory $workOrderCategory)
    {
        $workOrderCategory->delete();
        return redirect()->back();
    }
}
