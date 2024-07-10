<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\UserRequest;
use App\Model\User;
use App\Model\VehicleGroupModel;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Redirect;

class UsersController extends Controller {
	public function index() {
		$index['data'] = User::whereUser_type("O")->orWhere('user_type', 'S')->get();
		return view("users.index", $index);
	}

	public function create() {
		$index['groups'] = VehicleGroupModel::all();
		return view("users.create", $index);
	}

	public function destroy(Request $request) {
		User::find($request->get('id'))->delete();
		return redirect()->route('users.index');
	}

	private function upload_file($file, $field, $id) {
		$destinationPath = './uploads'; // upload path
		$extension = $file->getClientOriginalExtension();
		$fileName1 = Str::uuid() . '.' . $extension;

		$file->move($destinationPath, $fileName1);
		$user = User::find($id);
		$user->setMeta([$field => $fileName1]);
		$user->save();

	}

	public function store(UserRequest $request) {
		// dd($request->get('module'));
		if ($request->get('is_admin') == '1') {
			$user_type = 'S';
		} else {
			$user_type = 'O';
		}
		$id = User::create([
			"name" => $request->get("first_name") . " " . $request->get("last_name"),
			"email" => $request->get("email"),
			"password" => bcrypt($request->get("password")),
			"user_type" => $user_type,
			"group_id" => $request->get("group_id"),
			'api_token' => str_random(60),
		])->id;
		$user = User::find($id);
		$user->module = serialize($request->get('module'));
		$user->language = 'English-en';
		$user->save();

		if ($request->file('profile_image') && $request->file('profile_image')->isValid()) {
			$this->upload_file($request->file('profile_image'), "profile_image", $id);
		}
		return Redirect::route("users.index");

	}
	public function edit(User $user) {

		$groups = VehicleGroupModel::all();
		return view("users.edit", compact("user", 'groups'));
	}

	public function update(EditUserRequest $request) {

		$user = User::whereId($request->get("id"))->first();
		$user->name = $request->get("first_name") . " " . $request->get("last_name");
		$user->email = $request->get("email");
		$user->group_id = $request->get("group_id");
		$user->module = serialize($request->get('module'));

		// $user->profile_image = $request->get('profile_image');
		if ($request->get('is_admin') == '1') {
			$user->user_type = 'S';
		} else {
			$user->user_type = 'O';
		}
		if (Auth::user()->user_type == "S" && $user->id == Auth::user()->id) {
			$user->user_type = 'S';
		}
		$user->save();
		if ($request->file('profile_image') && $request->file('profile_image')->isValid()) {
			$this->upload_file($request->file('profile_image'), "profile_image", $user->id);
		}
		$modules = unserialize($user->getMeta('module'));
		// if (Auth::user()->id == $user->id && !(in_array(0, $modules))) {
		//     return redirect('admin/');
		// }
		return Redirect::route("users.index");
	}

}
