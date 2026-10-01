<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $period = $request->input('period', 'this_month');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $now = Carbon::now();
        $startDate = null;
        $endDate = null;

        switch ($period) {
            case 'today':
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                $periodLabel = 'Hoy ('.$startDate->format('d/m/Y').')';
                break;
            case 'yesterday':
                $startDate = $now->copy()->subDay()->startOfDay();
                $endDate = $now->copy()->subDay()->endOfDay();
                $periodLabel = 'Ayer ('.$startDate->format('d/m/Y').')';
                break;
            case 'this_week':
                $startDate = $now->copy()->startOfWeek();
                $endDate = $now->copy()->endOfWeek();
                $periodLabel = 'Esta Semana ('.$startDate->format('d/m').' - '.$endDate->format('d/m').')';
                break;
            case 'last_7_days':
                $startDate = $now->copy()->subDays(6)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                $periodLabel = 'Últimos 7 días';
                break;
            case 'last_30_days':
                $startDate = $now->copy()->subDays(29)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                $periodLabel = 'Últimos 30 días';
                break;
            case 'this_year':
                $startDate = $now->copy()->startOfYear();
                $endDate = $now->copy()->endOfYear();
                $periodLabel = 'Año '.$now->year;
                break;
            case 'all':
                $startDate = null;
                $endDate = null;
                $periodLabel = 'Histórico Total';
                break;
            case 'custom':
                $startDate = $dateFrom ? Carbon::parse($dateFrom)->startOfDay() : null;
                $endDate = $dateTo ? Carbon::parse($dateTo)->endOfDay() : null;
                $periodLabel = 'Personalizado ('.($startDate ? $startDate->format('d/m/Y') : 'Inicio').' - '.($endDate ? $endDate->format('d/m/Y') : 'Hoy').')';
                break;
            case 'this_month':
            default:
                $period = 'this_month';
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                $periodLabel = 'Este Mes ('.$startDate->translatedFormat('F Y').')';
                break;
        }

        // Base Orders query for the filtered period
        $ordersQuery = Order::query();
        if ($startDate && $endDate) {
            $ordersQuery->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $ordersQuery->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $ordersQuery->where('created_at', '<=', $endDate);
        }

        // Metrics / KPIs
        $totalOrders = (clone $ordersQuery)->count();
        $totalRevenue = (clone $ordersQuery)->where('status', '!=', 'Cancelada')->sum('total');
        $averageOrderValue = $totalOrders > 0
            ? ((clone $ordersQuery)->where('status', '!=', 'Cancelada')->avg('total') ?? 0)
            : 0;

        $pendingOrdersCount = (clone $ordersQuery)->where('status', 'Pendiente')->count();
        $confirmedOrdersCount = (clone $ordersQuery)->where('status', 'Confirmada')->count();
        $deliveredOrdersCount = (clone $ordersQuery)->where('status', 'Entregada')->count();
        $cancelledOrdersCount = (clone $ordersQuery)->where('status', 'Cancelada')->count();

        // Catalog and General Indicators
        $totalProducts = Product::count();
        $activeProductsCount = Product::where('is_active', true)->count();
        $unreadMessagesCount = ContactMessage::where('status', 'Pendiente')->count();

        // Timeline Sales Chart Data
        $salesTimeline = $this->buildSalesTimeline($startDate, $endDate, $period);

        // Delivery Methods Distribution
        $deliveryBreakdown = (clone $ordersQuery)
            ->selectRaw('delivery_method, count(*) as count')
            ->groupBy('delivery_method')
            ->pluck('count', 'delivery_method')
            ->toArray();

        $deliveryCaracas = $deliveryBreakdown['delivery_caracas'] ?? 0;
        $deliveryNacional = $deliveryBreakdown['envio_nacional'] ?? 0;
        $deliveryPickup = $deliveryBreakdown['pickup'] ?? 0;

        // Gift vs Personal Purchase Distribution
        $giftOrdersCount = (clone $ordersQuery)->where('is_gift', true)->count();
        $personalOrdersCount = (clone $ordersQuery)->where('is_gift', false)->count();

        // Top 5 Best Selling Products in Period
        $topProductsQuery = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'Cancelada');

        if ($startDate && $endDate) {
            $topProductsQuery->whereBetween('orders.created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $topProductsQuery->where('orders.created_at', '>=', $startDate);
        } elseif ($endDate) {
            $topProductsQuery->where('orders.created_at', '<=', $endDate);
        }

        $topProducts = $topProductsQuery
            ->selectRaw('order_items.product_id, order_items.product_name, order_items.product_image, SUM(order_items.quantity) as total_sold, SUM(order_items.total_price) as total_revenue')
            ->groupBy('order_items.product_id', 'order_items.product_name', 'order_items.product_image')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // Recent Orders in the Selected Period
        $recentOrders = (clone $ordersQuery)
            ->with('items')
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'period',
            'dateFrom',
            'dateTo',
            'periodLabel',
            'totalOrders',
            'totalRevenue',
            'averageOrderValue',
            'pendingOrdersCount',
            'confirmedOrdersCount',
            'deliveredOrdersCount',
            'cancelledOrdersCount',
            'totalProducts',
            'activeProductsCount',
            'unreadMessagesCount',
            'salesTimeline',
            'deliveryCaracas',
            'deliveryNacional',
            'deliveryPickup',
            'giftOrdersCount',
            'personalOrdersCount',
            'topProducts',
            'recentOrders'
        ));
    }

    /**
     * Build continuous timeline data for Chart.js sales and orders chart.
     */
    protected function buildSalesTimeline(?Carbon $startDate, ?Carbon $endDate, string $period): array
    {
        $query = Order::query()->where('status', '!=', 'Cancelada');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        $orders = $query->get(['id', 'total', 'created_at']);

        $labels = [];
        $revenueData = [];
        $ordersData = [];

        if ($period === 'today' || $period === 'yesterday') {
            for ($hour = 0; $hour < 24; $hour += 2) {
                $labels[] = sprintf('%02d:00', $hour);
                $bucketOrders = $orders->filter(function ($order) use ($hour) {
                    $h = (int) $order->created_at->format('H');

                    return $h >= $hour && $h < ($hour + 2);
                });
                $revenueData[] = round((float) $bucketOrders->sum('total'), 2);
                $ordersData[] = $bucketOrders->count();
            }
        } elseif ($period === 'this_year') {
            $months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
            for ($m = 1; $m <= 12; $m++) {
                $labels[] = $months[$m - 1];
                $bucketOrders = $orders->filter(function ($order) use ($m) {
                    return (int) $order->created_at->format('n') === $m;
                });
                $revenueData[] = round((float) $bucketOrders->sum('total'), 2);
                $ordersData[] = $bucketOrders->count();
            }
        } elseif ($startDate && $endDate && $startDate->diffInDays($endDate) <= 45) {
            $cursor = $startDate->copy()->startOfDay();
            $end = $endDate->copy()->endOfDay();

            while ($cursor->lte($end)) {
                $dayKey = $cursor->format('Y-m-d');
                $labels[] = $cursor->format('d/m');

                $bucketOrders = $orders->filter(function ($order) use ($dayKey) {
                    return $order->created_at->format('Y-m-d') === $dayKey;
                });

                $revenueData[] = round((float) $bucketOrders->sum('total'), 2);
                $ordersData[] = $bucketOrders->count();

                $cursor->addDay();
            }
        } else {
            $minDate = $startDate ?? ($orders->min('created_at') ? Carbon::parse($orders->min('created_at')) : Carbon::now()->subMonths(5)->startOfMonth());
            $maxDate = $endDate ?? Carbon::now()->endOfMonth();

            $cursor = $minDate->copy()->startOfMonth();
            while ($cursor->lte($maxDate)) {
                $monthKey = $cursor->format('Y-m');
                $labels[] = $cursor->translatedFormat('M Y');

                $bucketOrders = $orders->filter(function ($order) use ($monthKey) {
                    return $order->created_at->format('Y-m') === $monthKey;
                });

                $revenueData[] = round((float) $bucketOrders->sum('total'), 2);
                $ordersData[] = $bucketOrders->count();

                $cursor->addMonth();
            }
        }

        return [
            'labels' => $labels,
            'revenue' => $revenueData,
            'orders' => $ordersData,
        ];
    }
}
