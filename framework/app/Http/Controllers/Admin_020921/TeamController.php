<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamRequest;
use App\Model\TeamModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    public function index()
    {
        $data = TeamModel::orderBy('id', 'desc')->get();
        return view('team.index', compact('data'));
    }

    public function create()
    {
        return view('team.create');
    }

    public function store(TeamRequest $request)
    {
        $data = TeamModel::create(['name' => $request->name, 'details' => $request->details, 'designation' => $request->designation]);
        $file = $request->file('image');

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $destinationPath = './uploads'; // upload path
            $extension = $file->getClientOriginalExtension();

            $fileName1 = Str::uuid() . '.' . $extension;

            $file->move($destinationPath, $fileName1);
            $data->image = $fileName1;
            $data->save();
        }
        return redirect('admin/team');
    }

    public function edit($id)
    {
        $data = TeamModel::find($id);
        return view('team.edit', compact('data'));
    }

    public function update(TeamRequest $request)
    {
        $data = TeamModel::find($request->id);
        $data->name = $request->name;
        $data->details = $request->details;
        $data->designation = $request->designation;
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
        return redirect('admin/team');
    }

    public function destroy(Request $request)
    {
        TeamModel::find($request->id)->delete();
        return redirect('admin/team');
    }

    public function bulk_delete(Request $request)
    {
        TeamModel::whereIn('id', $request->ids)->delete();
        return back();
    }
}
