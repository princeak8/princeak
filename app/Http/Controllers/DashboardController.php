<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use Illuminate\Support\Facades\Auth;

use App\Enums\Role;

class DashboardController extends Controller
{
    public function index()
    {
        if(Auth::user()->role == Role::ADMIN->value) return redirect('admin/');
        return Inertia::render('Dashboard');
    }
}
