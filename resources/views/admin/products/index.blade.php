@extends('layouts.admin')

@section('page_title', 'Gestión de Productos')

@section('admin_content')
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="admin-card-title">Listado de Productos</h2>
            <p style="color: var(--color-text-muted); font-size: var(--text-xs); margin-top: 2px;">
                Administra los artículos de tu tienda, precios, imágenes, stock y visibilidad.
            </p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nuevo Producto
        </a>
    </div>

    <!-- Filters & Search Toolbar -->
    <form method="GET" action="{{ route('admin.products.index') }}" style="display: flex; gap: var(--space-4); margin-bottom: var(--space-6); flex-wrap: wrap;">
        <input type="text" name="q" class="form-input" placeholder="Buscar por nombre o SKU..." value="{{ request('q') }}" style="max-width: 320px;">
        
        <select name="category_id" class="form-select" style="max-width: 240px;" onchange="this.form.submit()">
            <option value="">Todas las Categorías</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-secondary btn-sm">Filtrar</button>
        @if(request('q') || request('category_id'))
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">Limpiar</a>
        @endif
    </form>

    <!-- Table -->
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Producto & SKU</th>
                    <th>Categoría</th>
                    <th>Línea</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <img src="{{ asset($product->cover_image_url) }}" alt="{{ $product->name }}" class="admin-table-img">
                        </td>
                        <td>
                            <strong style="color: var(--color-text);">{{ $product->name }}</strong>
                            <div style="font-size: var(--text-xs); color: var(--color-text-muted);">SKU: {{ $product->sku }}</div>
                        </td>
                        <td>{{ $product->category?->name ?? 'Sin categoría' }}</td>
                        <td>
                            @if($product->target_audience === 'moraia_intimo')
                                <span class="badge badge-rose" style="font-size: 0.65rem;">Íntimo (18+)</span>
                            @else
                                <span class="badge badge-subtle" style="font-size: 0.65rem;">Moraia</span>
                            @endif
                        </td>
                        <td>
                            <strong>${{ number_format($product->price, 2) }}</strong>
                            @if($product->compare_at_price)
                                <div style="font-size: var(--text-xs); color: var(--color-text-soft); text-decoration: line-through;">
                                    ${{ number_format($product->compare_at_price, 2) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <span style="font-weight: 600; color: {{ $product->stock_quantity > 0 ? 'var(--color-success)' : 'var(--color-error)' }};">
                                {{ $product->stock_quantity }} unid.
                            </span>
                        </td>
                        <td>
                            @if($product->is_active)
                                <span class="badge badge-confirmed">Activo</span>
                            @else
                                <span class="badge badge-cancelled">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btns">
                                <a href="{{ route('product', $product->slug) }}" target="_blank" class="btn-icon-table" title="Ver en Tienda">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-icon-table" title="Editar Producto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon-table" title="Eliminar Producto" style="color: var(--color-error);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px;">No se encontraron productos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div style="margin-top: var(--space-6); display: flex; justify-content: center;">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
