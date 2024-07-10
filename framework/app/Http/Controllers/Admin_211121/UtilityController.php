<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditProfileRequest;
use App\Http\Requests\PasswordRequest;
use App\Model\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Storage;

class UtilityController extends Controller {

	public function changepass($id) {
		$data['languages'] = Storage::disk('views')->directories('');

		$data['user_data'] = User::find(Auth::user()->id);

		return view('utilities.changepass', $data);

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

	public function changepassword(EditProfileRequest $request) {
		// dd($request->all());
		$id = Auth::id();
		$user = User::find($id);
		$user->name = $request->name;
		$user->email = $request->email;
		$user->language = $request->get('language');
		// $user->password = bcrypt($request->passwd);
		$user->save();
		if ($user->user_type == "D") {
			$field = "driver_image";
		} elseif ($user->user_type == "C") {
			$field = "profile_pic";
		} else {
			$field = "profile_image";
		}
		if ($request->file('image') && $request->file('image')->isValid()) {

			$this->upload_file($request->file('image'), $field, $user->id);
		}
		return back();
		// return redirect()->route('changepass', $data);
	}

	public function password_change(Request $request) {
		// $id = $request->get('id');
		$user = User::find($request->get("driver_id"));
		$user->password = bcrypt($request->get("passwd"));
		$user->save();
	}

	public function change() {
		return view('utilities.password');
	}

	public function change_post(PasswordRequest $request) {
		$user = User::find($request->get('id'));
		$user->password = bcrypt($request->get('password'));
		$user->save();
		return redirect()->back();
	}
}