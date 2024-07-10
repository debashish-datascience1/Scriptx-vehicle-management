<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TestimonialRequest;
use App\Model\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestimonialController extends Controller
{
    public function index()
    {
        $data = Testimonial::orderBy('id', 'desc')->get();
        return view('testimonial.index', compact('data'));
    }

    public function create()
    {
        return view('testimonial.create');
    }

    public function store(TestimonialRequest $request)
    {
        $data = Testimonial::create(['name' => $request->name, 'details' => $request->details]);
        $file = $request->file('image');

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $destinationPath = './uploads'; // upload path
            $extension = $file->getClientOriginalExtension();

            $fileName1 = Str::uuid() . '.' . $extension;

            $file->move($destinationPath, $fileName1);
            $data->image = $fileName1;
            $data->save();
        }
        return redirect('admin/testimonials');
    }

    public function edit($id)
    {
        $data = Testimonial::find($id);
        return view('testimonial.edit', compact('data'));
    }

    public function update(TestimonialRequest $request)
    {
        $data = Testimonial::find($request->id);
        $data->name = $request->name;
        $data->details = $request->details;
        $data->save();
        $file = $request->file('image');

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $destinationPath = './uploads'; // upload path
            $extension = $file->getClientOriginalExtension();

            $fileName1 = Str::uuid() . '.' . $extension;

            $file->move($destinationPath, $fileName1);
            $data->image = $fileName1;
            $data->save();
        }
        return redirect('admin/testimonials');
    }

    public function destroy(Request $request)
    {
        Testimonial::find($request->id)->delete();
        return redirect('admin/testimonials');
    }

    public function bulk_delete(Request $request)
    {
        Testimonial::whereIn('id', $request->ids)->delete();
        return back();
    }
}
