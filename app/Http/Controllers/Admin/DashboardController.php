<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', '!=', 'Cancelada')->sum('total');
        $pendingOrdersCount = Order::where('status', 'Nueva')->count();
        $totalProducts = Product::count();
        $unreadMessagesCount = ContactMessage::where('status', 'Pendiente')->count();

        $recentOrders = Order::with('items')
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        $recentMessages = ContactMessage::orderBy('id', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'pendingOrdersCount',
            'totalProducts',
            'unreadMessagesCount',
            'recentOrders',
            'recentMessages'
        ));
    }
}
