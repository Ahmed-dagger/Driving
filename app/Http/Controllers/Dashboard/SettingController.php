<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class SettingController extends Controller
{
    public function index() {
        return view('dashboard.Admin.settings', ['pageTitle' => trans('dashboard/header.settings')]);
    }

    public function store(Request $request) {
        setting($request->all())->save();
        session()->flash('success', 'Data added successfully');
        return redirect()->back();
    }
}
