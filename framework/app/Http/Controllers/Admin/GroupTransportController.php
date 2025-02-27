<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\GroupTransport;
use Illuminate\Http\Request;

class GroupTransportController extends Controller
{

    public function index()
    {
        $data = GroupTransport::orderBy('id', 'desc')->get();
        return view('groups.index', compact('data'));
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_name' => 'required|unique:group_transport,group_name|max:255'
        ]);

        $group = GroupTransport::create([
            'group_name' => $request->group_name
        ]);

        return redirect()->route('groups.index')->with('success', 'Group created successfully');
    }

    public function edit($id)
    {
        $data = GroupTransport::findOrFail($id);
        return view('groups.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'group_name' => 'required|max:255|unique:group_transport,group_name,'.$id
        ]);

        $group = GroupTransport::findOrFail($id);
        $group->update([
            'group_name' => $request->group_name
        ]);

        return redirect()->route('groups.index')->with('success', 'Group updated successfully');
    }

    public function destroy($id)
    {
        $group = GroupTransport::findOrFail($id);
        $group->delete();

        return redirect()->route('groups.index')->with('success', 'Group deleted successfully');
    }

}