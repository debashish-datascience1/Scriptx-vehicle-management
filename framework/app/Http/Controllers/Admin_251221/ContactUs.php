<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\MessageModel;

class ContactUs extends Controller
{
    public function index()
    {
        $data['messages'] = MessageModel::orderBy('id', 'desc')->get();
        return view('contactus', $data);

    }
}
