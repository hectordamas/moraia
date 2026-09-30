<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with('items')->orderBy('id', 'desc');

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_lastname', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('customer_whatsapp', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders', 'search', 'status'));
    }

    public function show(Order $order): View
    {
        $order->load('items.product');
        $whatsAppCustomerUrl = $order->generateAdminWhatsAppUrl();

        return view('admin.orders.show', compact('order', 'whatsAppCustomerUrl'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Nueva,Confirmada,Preparando,Lista,Entregada,Cancelada',
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', "Estado de orden actualizado a '{$validated['status']}'.");
    }
}
