<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Variations;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $title = 'Thống kê tổng quan';
        $totalUsers = User::count();
        $usersThisMonth = User::whereMonth('created_at', Carbon::now()->month)
                              ->whereYear('created_at', Carbon::now()->year)
                              ->count();
        $usersLastMonth = User::whereMonth('created_at', Carbon::now()->subMonth()->month)
                              ->whereYear('created_at', Carbon::now()->subMonth()->year)
                              ->count();
        $usersComparedToLastMonth = $usersThisMonth - $usersLastMonth;
        $usersPercentageChange = $usersLastMonth > 0 
            ? (($usersThisMonth - $usersLastMonth) / $usersLastMonth) * 100 
            : ($usersThisMonth > 0 ? 100 : 0);

        $totalOrders = Orders::count();
        $totalRevenue = Orders::where('status', 'completed')->sum('total_price');
        $totalStock = Variations::sum('stock');
        $totalInventoryValue = Variations::sum(DB::raw('price * stock'));

        return view('admin.index', compact(
            'totalUsers',
            'usersThisMonth',
            'usersLastMonth',
            'usersComparedToLastMonth',
            'usersPercentageChange',
            'totalOrders',
            'totalRevenue',
            'totalStock',
            'totalInventoryValue',
            'title'
        ));
    }
}