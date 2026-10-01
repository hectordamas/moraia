@extends('layouts.admin')

@section('page_title', 'Gestión de Órdenes')

@section('admin_content')
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="admin-card-title">Órdenes y Pedidos</h2>
            <p style="color: var(--color-text-muted); font-size: var(--text-xs); margin-top: 2px;">
                Revisa pedidos registrados, cambia estados y contacta a los clientes por WhatsApp con un solo clic.
            </p>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.orders.index') }}" style="display: flex; gap: var(--space-4); margin-bottom: var(--space-6); flex-wrap: wrap;">
        <input type="text" name="q" class="form-input" placeholder="Buscar por código, cliente o teléfono..." value="{{ request('q') }}" style="max-width: 340px;">
        
        <select name="status" class="form-select" style="max-width: 200px;" onchange="this.form.submit()">
            <option value="">Todos los Estados</option>
            <option value="Pendiente" {{ request('status') === 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="Confirmada" {{ request('status') === 'Confirmada' ? 'selected' : '' }}>Confirmada</option>
            <option value="Entregada" {{ request('status') === 'Entregada' ? 'selected' : '' }}>Entregada</option>
            <option value="Cancelada" {{ request('status') === 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
        </select>

        <button type="submit" class="btn btn-secondary btn-sm">Filtrar</button>
        @if(request('q') || request('status'))
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">Limpiar</a>
        @endif
    </form>

    <!-- Table -->
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Cliente</th>
                    <th>WhatsApp Directo</th>
                    <th>Ciudad / Entrega</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" style="font-weight: 700; color: var(--color-primary-dark);">
                                #{{ $order->order_code }}
                            </a>
                        </td>
                        <td>
                            <strong>{{ $order->full_name }}</strong>
                            @if($order->is_gift)
                                <span class="badge badge-subtle" style="margin-left: 4px; font-size: 0.65rem;">🎁 Regalo</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ $order->generateAdminWhatsAppUrl() }}" target="_blank" rel="noopener" class="btn-whatsapp-action" title="Abrir chat en WhatsApp">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                </svg>
                                {{ $order->customer_whatsapp }}
                            </a>
                        </td>
                        <td>
                            <div>{{ $order->delivery_city }}</div>
                            <span style="font-size: var(--text-xs); color: var(--color-text-muted);">{{ $order->delivery_method_label }}</span>
                        </td>
                        <td>
                            <strong>${{ number_format($order->total, 2) }}</strong>
                        </td>
                        <td>
                            <span class="badge {{ $order->status_badge_class }}">{{ $order->status }}</span>
                        </td>
                        <td style="font-size: var(--text-xs); color: var(--color-text-muted);">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline btn-sm">
                                Ver Detalle
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px;">No se encontraron órdenes registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div style="margin-top: var(--space-6); display: flex; justify-content: center;">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
