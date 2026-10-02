@extends('layouts.admin')

@section('page_title', 'Dashboard & Métricas')

@section('admin_content')
<!-- Dashboard Filter Section -->
<div class="dashboard-header-bar">
    <div class="dashboard-filter-card">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: var(--space-2);">
            <div class="filter-period-pills">
                <span style="font-size: var(--text-xs); font-weight: 700; color: var(--color-text); margin-right: 4px;">Periodo:</span>
                <a href="{{ route('admin.dashboard', ['period' => 'today']) }}" class="period-pill {{ $period === 'today' ? 'active' : '' }}">Hoy</a>
                <a href="{{ route('admin.dashboard', ['period' => 'yesterday']) }}" class="period-pill {{ $period === 'yesterday' ? 'active' : '' }}">Ayer</a>
                <a href="{{ route('admin.dashboard', ['period' => 'this_week']) }}" class="period-pill {{ $period === 'this_week' ? 'active' : '' }}">Esta Semana</a>
                <a href="{{ route('admin.dashboard', ['period' => 'last_7_days']) }}" class="period-pill {{ $period === 'last_7_days' ? 'active' : '' }}">Últimos 7 días</a>
                <a href="{{ route('admin.dashboard', ['period' => 'this_month']) }}" class="period-pill {{ $period === 'this_month' ? 'active' : '' }}">Este Mes</a>
                <a href="{{ route('admin.dashboard', ['period' => 'last_30_days']) }}" class="period-pill {{ $period === 'last_30_days' ? 'active' : '' }}">Últimos 30 días</a>
                <a href="{{ route('admin.dashboard', ['period' => 'this_year']) }}" class="period-pill {{ $period === 'this_year' ? 'active' : '' }}">Este Año</a>
                <a href="{{ route('admin.dashboard', ['period' => 'all']) }}" class="period-pill {{ $period === 'all' ? 'active' : '' }}">Histórico</a>
            </div>

            <div class="period-badge-indicator">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5" />
                </svg>
                <span>{{ $periodLabel }}</span>
            </div>
        </div>

        <!-- Custom Date Range Selector -->
        <div class="custom-range-row">
            <span style="color: var(--color-text-muted); font-weight: 600;">O selecciona un rango personalizado:</span>
            <form action="{{ route('admin.dashboard') }}" method="GET" class="custom-range-form">
                <input type="hidden" name="period" value="custom">
                <label for="date_from" style="color: var(--color-text-muted); font-weight: 500;">Desde:</label>
                <input type="date" id="date_from" name="date_from" value="{{ $dateFrom ?? '' }}" required>

                <label for="date_to" style="color: var(--color-text-muted); font-weight: 500;">Hasta:</label>
                <input type="date" id="date_to" name="date_to" value="{{ $dateTo ?? '' }}" required>

                <button type="submit" class="btn btn-primary btn-sm" style="padding: 0.35rem 0.85rem; font-size: var(--text-xs);">
                    Aplicar Rango
                </button>

                @if($period === 'custom')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-sm" style="padding: 0.35rem 0.65rem; font-size: var(--text-xs);">
                        Limpiar
                    </a>
                @endif
            </form>
        </div>
    </div>
</div>

<!-- Metric Stat Cards -->
<div class="stats-grid">
    <!-- Stat 1: Total Revenue -->
    <div class="stat-card">
        <div class="stat-icon-wrap" style="background-color: #F9ECEE; color: var(--color-primary-dark);">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Ventas Registradas</span>
            <span class="stat-val">${{ number_format($totalRevenue, 2) }}</span>
        </div>
    </div>

    <!-- Stat 2: Total Orders -->
    <div class="stat-card">
        <div class="stat-icon-wrap" style="background-color: #EBF5F0; color: #4E8B71;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
            </svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Total Órdenes</span>
            <span class="stat-val">{{ $totalOrders }}</span>
        </div>
    </div>

    <!-- Stat 3: Ticket Promedio -->
    <div class="stat-card">
        <div class="stat-icon-wrap" style="background-color: #F3EDF8; color: #7B5EA7;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6H2.25m0 0v10.5m0-10.5h15.75m0 0a.75.75 0 0 1 .75.75v.75m0 0H3.75m15.75 0V18.75m0 0h.75a.75.75 0 0 0 .75-.75V6.75a.75.75 0 0 0-.75-.75H3.75" />
            </svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Ticket Promedio</span>
            <span class="stat-val">${{ number_format($averageOrderValue, 2) }}</span>
        </div>
    </div>

    <!-- Stat 4: Pending Orders -->
    <div class="stat-card">
        <div class="stat-icon-wrap" style="background-color: #FEF7EE; color: #D9822B;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Órdenes Pendientes</span>
            <span class="stat-val">{{ $pendingOrdersCount }}</span>
        </div>
    </div>

    <!-- Stat 5: Delivered Orders -->
    <div class="stat-card">
        <div class="stat-icon-wrap" style="background-color: #E8F5E9; color: #2E7D32;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Entregadas / Éxito</span>
            <span class="stat-val">{{ $deliveredOrdersCount }}</span>
        </div>
    </div>

    <!-- Stat 6: Total Products -->
    <div class="stat-card">
        <div class="stat-icon-wrap" style="background-color: #F0F5FA; color: #5A7E9E;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
            </svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Productos Activos</span>
            <span class="stat-val">{{ $activeProductsCount }} <span style="font-size: var(--text-xs); color: var(--color-text-muted); font-weight: 500;">/ {{ $totalProducts }}</span></span>
        </div>
    </div>
</div>

<!-- Primary Charts Grid: Sales Trend & Status Breakdown -->
<div class="charts-grid-main">
    <!-- Chart 1: Sales & Orders Timeline Trend -->
    <div class="chart-card">
        <div class="chart-card-header">
            <h3 class="chart-card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="color: var(--color-primary);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                </svg>
                Tendencia de Ventas & Pedidos
            </h3>
            <span style="font-size: var(--text-xs); color: var(--color-text-muted); font-weight: 600;">Evolución en el periodo</span>
        </div>
        <div class="chart-wrapper">
            <canvas id="salesTrendChart"></canvas>
        </div>
    </div>

    <!-- Chart 2: Order Status Doughnut -->
    <div class="chart-card">
        <div class="chart-card-header">
            <h3 class="chart-card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="color: #D9822B;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                </svg>
                Estado de Órdenes
            </h3>
            <span style="font-size: var(--text-xs); color: var(--color-text-muted); font-weight: 600;">Distribución de estados</span>
        </div>
        <div class="chart-wrapper">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

<!-- Secondary Charts Grid: Delivery Methods, Gifts vs Personal, and Top Products -->
<div class="charts-grid-secondary">
    <!-- Chart 3: Delivery Methods Pie -->
    <div class="chart-card">
        <div class="chart-card-header">
            <h3 class="chart-card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="color: #5A7E9E;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                </svg>
                Métodos de Entrega
            </h3>
        </div>
        <div class="chart-wrapper-pie">
            <canvas id="deliveryChart"></canvas>
        </div>
    </div>

    <!-- Chart 4: Gift vs Personal Purchases Doughnut -->
    <div class="chart-card">
        <div class="chart-card-header">
            <h3 class="chart-card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="color: var(--color-primary-dark);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H4.5a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                </svg>
                Propósito de Compra
            </h3>
        </div>
        <div class="chart-wrapper-pie">
            <canvas id="giftChart"></canvas>
        </div>
    </div>

    <!-- Widget 5: Top Best Selling Products -->
    <div class="chart-card">
        <div class="chart-card-header">
            <h3 class="chart-card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="color: #D4AF37;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007-4.5V5.25A2.25 2.25 0 0 0 12.25 3h-.5a2.25 2.25 0 0 0-2.25 2.25v5.625m5.007 0a4.5 4.5 0 0 1-5.007 0m5.007 0H9.493" />
                </svg>
                Top Productos Vendidos
            </h3>
            <span style="font-size: var(--text-xs); color: var(--color-text-muted); font-weight: 600;">En el periodo</span>
        </div>
        <div class="top-products-list">
            @forelse($topProducts as $index => $prod)
                <div class="top-product-item">
                    <div class="top-product-left">
                        <span class="top-product-rank rank-{{ $index + 1 }}">{{ $index + 1 }}</span>
                        @if($prod->product_image)
                            <img src="{{ asset($prod->product_image) }}" alt="{{ $prod->product_name }}" class="top-product-img">
                        @else
                            <div class="top-product-img" style="display: flex; align-items: center; justify-content: center; background: #FAF0EE; color: var(--color-primary);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                            </div>
                        @endif
                        <div class="top-product-details">
                            <span class="top-product-name" title="{{ $prod->product_name }}">{{ $prod->product_name }}</span>
                            <span class="top-product-sub">${{ number_format($prod->total_revenue, 2) }} generados</span>
                        </div>
                    </div>
                    <div class="top-product-right">
                        <div class="top-product-sales">{{ $prod->total_sold }} un.</div>
                    </div>
                </div>
            @empty
                <p style="color: var(--color-text-muted); font-size: var(--text-xs); text-align: center; padding: 2rem 0;">
                    No hay ventas registradas en este periodo.
                </p>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="admin-card-title">Órdenes Recientes del Periodo</h2>
            <p style="font-size: var(--text-xs); color: var(--color-text-muted); margin-top: 2px;">
                Mostrando las últimas órdenes registradas correspondientes a: <strong>{{ $periodLabel }}</strong>
            </p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">Ver Todas las Órdenes</a>
    </div>

    @if($recentOrders->isEmpty())
        <p style="color: var(--color-text-muted); padding: 30px 0; text-align: center; font-size: var(--text-sm);">
            No se encontraron órdenes en el rango de fechas seleccionado.
        </p>
    @else
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Cliente</th>
                        <th>WhatsApp</th>
                        <th>Entrega</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $ord)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $ord->id) }}" style="font-weight: 700; color: var(--color-primary-dark);">
                                    #{{ $ord->order_code }}
                                </a>
                            </td>
                            <td>
                                <strong>{{ $ord->full_name }}</strong>
                                @if($ord->is_gift)
                                    <span class="badge badge-subtle" style="display: inline-block; margin-left: 4px; font-size: 0.65rem;">🎁 Regalo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ $ord->generateAdminWhatsAppUrl() }}" target="_blank" rel="noopener" class="btn-whatsapp-action">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                    </svg>
                                    {{ $ord->customer_whatsapp }}
                                </a>
                            </td>
                            <td>{{ $ord->delivery_city }}</td>
                            <td>
                                <strong>${{ number_format($ord->total, 2) }}</strong>
                            </td>
                            <td>
                                <span class="badge {{ $ord->status_badge_class }}">{{ $ord->status }}</span>
                            </td>
                            <td style="font-size: var(--text-xs); color: var(--color-text-muted);">
                                {{ $ord->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="{{ route('admin.orders.show', $ord->id) }}" class="btn-icon-table" title="Ver Detalles">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Moraia Brand Color Palette
    const colors = {
        primary: '#D87F86',
        primaryDark: '#A95058',
        primaryLight: '#E8A5AB',
        gold: '#D4AF37',
        green: '#4E8B71',
        amber: '#D9822B',
        blue: '#5A7E9E',
        dark: '#2A2626',
        muted: '#8C7A9E',
        red: '#C05C5C',
        border: '#E8DFDC'
    };

    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#7A6E6D';

    // -------------------------------------------------------------
    // 1. Sales & Orders Trend Line & Bar Chart
    // -------------------------------------------------------------
    const salesCtx = document.getElementById('salesTrendChart');
    if (salesCtx) {
        const timelineLabels = @json($salesTimeline['labels']);
        const revenueData = @json($salesTimeline['revenue']);
        const ordersData = @json($salesTimeline['orders']);

        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: timelineLabels,
                datasets: [
                    {
                        label: 'Ventas ($ USD)',
                        data: revenueData,
                        borderColor: colors.primaryDark,
                        backgroundColor: 'rgba(216, 127, 134, 0.18)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: colors.primaryDark,
                        pointBorderColor: '#FFFFFF',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Cant. Pedidos',
                        data: ordersData,
                        borderColor: colors.blue,
                        backgroundColor: 'rgba(90, 126, 158, 0.25)',
                        borderWidth: 2,
                        borderDash: [4, 4],
                        fill: false,
                        tension: 0.3,
                        pointBackgroundColor: colors.blue,
                        pointBorderColor: '#FFFFFF',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            padding: 15,
                            font: { weight: '600', size: 11 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(36, 32, 32, 0.95)',
                        padding: 10,
                        cornerRadius: 6,
                        titleFont: { weight: '700' },
                        callbacks: {
                            label: function(context) {
                                if (context.dataset.yAxisID === 'y') {
                                    return ' ' + context.dataset.label + ': $' + Number(context.raw).toFixed(2);
                                }
                                return ' ' + context.dataset.label + ': ' + context.raw + ' orden(es)';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: 12
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        beginAtZero: true,
                        grid: {
                            color: '#F0ECE8',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false,
                            drawBorder: false
                        },
                        ticks: {
                            precision: 0,
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // -------------------------------------------------------------
    // 2. Order Status Breakdown (Doughnut Chart)
    // -------------------------------------------------------------
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        const pendingCount = {{ $pendingOrdersCount }};
        const confirmedCount = {{ $confirmedOrdersCount }};
        const deliveredCount = {{ $deliveredOrdersCount }};
        const cancelledCount = {{ $cancelledOrdersCount }};
        const hasStatusData = (pendingCount + confirmedCount + deliveredCount + cancelledCount) > 0;

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pendientes', 'Confirmadas', 'Entregadas', 'Canceladas'],
                datasets: [{
                    data: hasStatusData ? [pendingCount, confirmedCount, deliveredCount, cancelledCount] : [1],
                    backgroundColor: hasStatusData 
                        ? [colors.amber, colors.blue, colors.green, colors.red]
                        : ['#EFEBE8'],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 6,
                            padding: 8,
                            font: { weight: '600', size: 10 }
                        }
                    },
                    tooltip: {
                        enabled: hasStatusData,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const val = context.raw;
                                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return ` ${context.label}: ${val} (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // -------------------------------------------------------------
    // 3. Delivery Methods Breakdown (Pie Chart)
    // -------------------------------------------------------------
    const deliveryCtx = document.getElementById('deliveryChart');
    if (deliveryCtx) {
        const caracas = {{ $deliveryCaracas }};
        const nacional = {{ $deliveryNacional }};
        const pickup = {{ $deliveryPickup }};
        const hasDeliveryData = (caracas + nacional + pickup) > 0;

        new Chart(deliveryCtx, {
            type: 'pie',
            data: {
                labels: ['Delivery Caracas', 'Envío Nacional', 'Pick-up / Retiro'],
                datasets: [{
                    data: hasDeliveryData ? [caracas, nacional, pickup] : [1],
                    backgroundColor: hasDeliveryData 
                        ? [colors.primary, colors.muted, colors.blue]
                        : ['#EFEBE8'],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 6,
                            padding: 8,
                            font: { weight: '600', size: 10 }
                        }
                    },
                    tooltip: {
                        enabled: hasDeliveryData,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const val = context.raw;
                                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return ` ${context.label}: ${val} (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // -------------------------------------------------------------
    // 4. Gift vs Personal Purchases (Doughnut Chart)
    // -------------------------------------------------------------
    const giftCtx = document.getElementById('giftChart');
    if (giftCtx) {
        const gifts = {{ $giftOrdersCount }};
        const personal = {{ $personalOrdersCount }};
        const hasGiftData = (gifts + personal) > 0;

        new Chart(giftCtx, {
            type: 'doughnut',
            data: {
                labels: ['🎁 Para Regalo', '🛍️ Uso Personal'],
                datasets: [{
                    data: hasGiftData ? [gifts, personal] : [1],
                    backgroundColor: hasGiftData 
                        ? [colors.primaryDark, colors.dark]
                        : ['#EFEBE8'],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 6,
                            padding: 8,
                            font: { weight: '600', size: 10 }
                        }
                    },
                    tooltip: {
                        enabled: hasGiftData,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const val = context.raw;
                                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return ` ${context.label}: ${val} (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
