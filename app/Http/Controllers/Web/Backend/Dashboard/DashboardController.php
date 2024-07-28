<?php

namespace App\Http\Controllers\Web\Backend\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'mods' => 'dashboard',
        ];
        return customView('dashboard.index', $data, 'backend');
    }
}
