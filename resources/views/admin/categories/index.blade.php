@extends('layouts.admin')

@section('page_title', 'Gestión de Categorías')

@section('admin_content')
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="admin-card-title">Categorías de la Tienda</h2>
            <p style="color: var(--color-text-muted); font-size: var(--text-xs); margin-top: 2px;">
                Organiza las líneas de productos. Puedes <strong>arrastrar y soltar las filas</strong> para cambiar el orden en el menú y en la página principal.
            </p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nueva Categoría
        </a>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">Mover</th>
                    <th style="width: 60px;">Orden</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Slug</th>
                    <th>Productos</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="sortableCategoriesBody">
                @forelse($categories as $index => $cat)
                    <tr data-id="{{ $cat->id }}" class="category-row">
                        <td style="text-align: center; vertical-align: middle;">
                            <span class="drag-handle" title="Arrastra para cambiar el orden">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                            </span>
                        </td>
                        <td class="category-order-num" style="font-weight: 700; color: var(--color-primary-dark);">
                            #{{ $index + 1 }}
                        </td>
                        <td>
                            @if($cat->image_path)
                                <img src="{{ asset($cat->image_path) }}" alt="{{ $cat->name }}" class="admin-table-img">
                            @else
                                <span style="font-size: 1.5rem;">📁</span>
                            @endif
                        </td>
                        <td>
                            <strong style="color: var(--color-text); font-size: var(--text-sm);">{{ $cat->name }}</strong>
                            <div style="font-size: var(--text-xs); color: var(--color-text-muted);">{{ Str::limit($cat->description, 60) }}</div>
                        </td>
                        <td><code>{{ $cat->slug }}</code></td>
                        <td>
                            <span class="badge badge-subtle">{{ $cat->products_count }} productos</span>
                        </td>
                        <td>
                            @if($cat->is_active)
                                <span class="badge badge-confirmed">Activa</span>
                            @else
                                <span class="badge badge-cancelled">Inactiva</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btns">
                                <a href="{{ route('category', $cat->slug) }}" target="_blank" class="btn-icon-table" title="Ver en Tienda">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn-icon-table" title="Editar Categoría">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta categoría?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon-table" title="Eliminar" style="color: var(--color-error);">
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
                        <td colspan="8" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
                            No hay categorías registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Floating Toast Notification -->
<div id="categoryToast" class="admin-floating-toast toast-success">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
    </svg>
    <span id="toastMessage">Orden de categorías guardado correctamente.</span>
</div>
@endsection

@push('scripts')
<!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('sortableCategoriesBody');
    const toast = document.getElementById('categoryToast');
    const toastMessage = document.getElementById('toastMessage');
    let toastTimeout = null;

    function showToast(message, isError = false) {
        if (toastTimeout) clearTimeout(toastTimeout);
        toastMessage.textContent = message;
        toast.className = `admin-floating-toast show ${isError ? 'toast-error' : 'toast-success'}`;
        toastTimeout = setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    function updateVisualOrderNumbers() {
        const rows = tableBody.querySelectorAll('tr.category-row');
        rows.forEach((row, index) => {
            const numCell = row.querySelector('.category-order-num');
            if (numCell) numCell.textContent = `#${index + 1}`;
        });
    }

    if (tableBody) {
        new Sortable(tableBody, {
            handle: '.drag-handle',
            animation: 180,
            ghostClass: 'sortable-row-ghost',
            chosenClass: 'sortable-row-chosen',
            onEnd: function() {
                updateVisualOrderNumbers();

                const rows = Array.from(tableBody.querySelectorAll('tr.category-row'));
                const order = rows.map(r => parseInt(r.getAttribute('data-id'))).filter(Boolean);

                // Send updated order to backend
                fetch("{{ route('admin.categories.reorder') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ order: order })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error al guardar el nuevo orden');
                    return response.json();
                })
                .then(data => {
                    showToast(data.message || 'Orden de categorías actualizado correctamente.');
                })
                .catch(err => {
                    console.error(err);
                    showToast('Ocurrió un error al guardar el orden.', true);
                });
            }
        });
    }
});
</script>
@endpush
