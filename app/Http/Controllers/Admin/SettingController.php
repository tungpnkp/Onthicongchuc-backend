<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class SettingController extends Controller
{
    public function index()
    {
        return view('/');
    }
}
