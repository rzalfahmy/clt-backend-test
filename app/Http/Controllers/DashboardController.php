<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Layup;
use App\Models\Layer;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'suppliers' => Supplier::count(),
            'layups' => Layup::count(),
            'layers' => Layer::count(),
            'latestSuppliers' => Supplier::latest()->take(5)->get(),
        ]);
    }
}
