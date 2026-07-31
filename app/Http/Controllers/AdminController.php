<?php

namespace App\Http\Controllers;
use App\Models\Appointment;
use App\Models\User;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $appointments = Appointment::latest()->get();

        return view('admin.index', compact('appointments'));
    }
}
