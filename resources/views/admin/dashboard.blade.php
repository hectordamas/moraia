@extends('layouts.admin')

@section('page_title', 'Dashboard General')

@section('admin_content')
<!-- Metric Stat Cards -->
<div class="stats-grid">
    <!-- Stat 1: Total Revenue -->
    <div class="stat-card">
        <div class="stat-icon-wrap">
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

    <!-- Stat 3: Pending Orders -->
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

    <!-- Stat 4: Total Products -->
    <div class="stat-card">
        <div class="stat-icon-wrap" style="background-color: #F0F5FA; color: #5A7E9E;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
            </svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Productos Activos</span>
            <span class="stat-val">{{ $totalProducts }}</span>
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Últimas Órdenes Registradas</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">Ver Todas las Órdenes</a>
    </div>

    @if($recentOrders->isEmpty())
        <p style="color: var(--color-text-muted); padding: 20px 0; text-align: center;">
            Aún no se han recibido órdenes.
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
