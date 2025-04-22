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
    public function dashboard(Request $request)
    {
        $title = 'Thống kê tổng quan';

        $period = $request->input('period', 'month');

        $startDate = Carbon::now();
        $endDate = Carbon::now();
        $groupByFormat = 'MONTH(created_at)';

        switch ($period) {
            case 'day':
                $startDate = Carbon::today();
                $groupByFormat = 'DAY(created_at)';
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $groupByFormat = 'WEEK(created_at)';
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $groupByFormat = 'MONTH(created_at)';
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $groupByFormat = 'YEAR(created_at)';
                break;
        }

        $totalUsers = User::count();
        $usersThisPeriod = User::where('created_at', '>=', $startDate)
                              ->where('created_at', '<=', $endDate)
                              ->count();
        $usersLastPeriod = User::where('created_at', '>=', $startDate->copy()->sub($period, 1))
                              ->where('created_at', '<', $startDate)
                              ->count();
        $usersComparedToLastPeriod = $usersThisPeriod - $usersLastPeriod;
        $usersPercentageChange = $usersLastPeriod > 0
            ? (($usersThisPeriod - $usersLastPeriod) / $usersLastPeriod) * 100
            : ($usersThisPeriod > 0 ? 100 : 0);

        $totalOrders = Orders::count();
        $totalRevenue = Orders::where('status', 'completed')
                             ->where('created_at', '>=', $startDate)
                             ->where('created_at', '<=', $endDate)
                             ->sum('total_price');

        $completedOrders = Orders::where('status', 'completed')
                                ->where('created_at', '>=', $startDate)
                                ->where('created_at', '<=', $endDate)
                                ->with('orderItems.product', 'orderItems.variation')
                                ->get();
        $totalProfit = 0;

        foreach ($completedOrders as $order) {
            $totalOriginalPrice = 0;
            if ($order->orderItems && $order->orderItems->count() > 0) {
                foreach ($order->orderItems as $item) {
                    $originalPrice = $item->variation && $item->variation->original_price
                        ? $item->variation->original_price
                        : ($item->product ? $item->product->original_price : 0);
                    $totalOriginalPrice += $originalPrice * $item->quantity;
                }
            }
            $totalProfit += ($order->total_price - $totalOriginalPrice - 20000);
        }

        $monthlyRevenue = Orders::select(
                DB::raw("{$groupByFormat} as period"),
                DB::raw('SUM(total_price) as revenue')
            )
            ->where('status', 'completed')
            ->where('created_at', '>=', $startDate->copy()->startOfYear())
            ->where('created_at', '<=', $endDate)
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->pluck('revenue', 'period')
            ->toArray();

        switch ($period) {
            case 'day':
                $periodCount = 24; 
                break;
            case 'week':
                $periodCount = 7; 
                break;
            case 'month':
                $periodCount = 30; 
                break;
            case 'year':
            default:
                $periodCount = 12; 
                break;
        }

        $monthlyRevenueData = array_fill(1, $periodCount, 0);
        foreach ($monthlyRevenue as $p => $revenue) {
            $monthlyRevenueData[$p] = $revenue;
        }

        $monthlyProfitData = array_fill(1, $periodCount, 0);
        foreach ($completedOrders as $order) {
            switch ($period) {
                case 'day':
                    $periodIndex = Carbon::parse($order->created_at)->hour;
                    break;
                case 'week':
                    $periodIndex = Carbon::parse($order->created_at)->dayOfWeek;
                    break;
                case 'month':
                    $periodIndex = Carbon::parse($order->created_at)->day;
                    break;
                case 'year':
                default:
                    $periodIndex = Carbon::parse($order->created_at)->month;
                    break;
            }

            $totalOriginalPrice = 0;
            if ($order->orderItems && $order->orderItems->count() > 0) {
                foreach ($order->orderItems as $item) {
                    $originalPrice = $item->variation && $item->variation->original_price
                        ? $item->variation->original_price
                        : ($item->product ? $item->product->original_price : 0);
                    $totalOriginalPrice += $originalPrice * $item->quantity;
                }
            }
            $profit = $order->total_price - $totalOriginalPrice - 20000;
            $monthlyProfitData[$periodIndex] += $profit;
        }

        $totalStock = Variations::sum('stock');
        $totalInventoryValue = Variations::sum(DB::raw('price * stock'));

        $revenueByCategory = DB::table('orders_item')
            ->join('products', 'orders_item.product_id', '=', 'products.id')
            ->join('categories', 'products.categories_id', '=', 'categories.id')
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(orders_item.price * orders_item.quantity) as revenue')
            )
            ->whereIn('orders_item.order_id', Orders::where('status', 'completed')
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->pluck('id'))
            ->groupBy('categories.name')
            ->get();

        return view('admin.index', compact(
            'totalUsers',
            'usersThisPeriod',
            'usersLastPeriod',
            'usersComparedToLastPeriod',
            'usersPercentageChange',
            'totalOrders',
            'totalRevenue',
            'totalProfit',
            'totalStock',
            'totalInventoryValue',
            'monthlyRevenueData',
            'monthlyProfitData',
            'revenueByCategory',
            'title',
            'period'
        ));
    }
}