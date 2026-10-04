@extends('layouts.admin')

@section('page_title', 'Gestión de Empaques')

@section('admin_content')
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="admin-card-title">Empaques & Presentaciones</h2>
            <p style="color: var(--color-text-muted); font-size: var(--text-xs); margin-top: 2px;">
                Define las opciones de empaque (bolsas, cajas de unidades específicas, etc.) que el cliente puede elegir al finalizar su compra en el checkout.
            </p>
        </div>
        <a href="{{ route('admin.packagings.create') }}" class="btn btn-primary btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nuevo Empaque
        </a>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">Mover</th>
                    <th style="width: 60px;">Orden</th>
                    <th style="width: 60px;">Imagen</th>
                    <th>Nombre & Descripción</th>
                    <th>Capacidad</th>
                    <th>Costo Adicional</th>
                    <th>Predeterminado</th>
                    <th>Estado</th>
                    <th style="width: 120px;">Acciones</th>
                </tr>
            </thead>
            <tbody id="sortablePackagingsBody">
                @forelse($packagings as $index => $pack)
                    <tr data-id="{{ $pack->id }}" class="packaging-row">
                        <td style="text-align: center; vertical-align: middle;">
                            <span class="drag-handle" title="Arrastra para cambiar el orden">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                            </span>
                        </td>
                        <td class="packaging-order-num" style="font-weight: 700; color: var(--color-primary-dark);">
                            #{{ $index + 1 }}
                        </td>
                        <td>
                            @if($pack->image_path)
                                <img src="{{ asset($pack->image_path) }}" alt="{{ $pack->name }}" class="admin-table-img">
                            @else
                                <div style="width: 40px; height: 40px; border-radius: var(--radius-xs); background: var(--color-surface-soft); border: 1px solid var(--color-border-light); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                                    🎁
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong style="color: var(--color-text); font-size: var(--text-sm);">{{ $pack->name }}</strong>
                            @if($pack->description)
                                <div style="font-size: var(--text-xs); color: var(--color-text-muted); margin-top: 2px;">
                                    {{ Str::limit($pack->description, 80) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($pack->capacity)
                                <span class="badge badge-subtle" style="font-size: 0.75rem;">
                                    📦 {{ $pack->capacity }}
                                </span>
                            @else
                                <span style="color: var(--color-text-muted); font-size: var(--text-xs);">Sin especificar</span>
                            @endif
                        </td>
                        <td>
                            @if((float)$pack->price <= 0)
                                <span style="color: var(--color-success); font-weight: 600; font-size: var(--text-xs); background-color: var(--color-success-bg); padding: 3px 8px; border-radius: var(--radius-xs);">
                                    Gratis / Incluido
                                </span>
                            @else
                                <strong style="color: var(--color-primary-dark); font-size: var(--text-sm);">
                                    +${{ number_format($pack->price, 2) }} US$
                                </strong>
                            @endif
                        </td>
                        <td>
                            @if($pack->is_default)
                                <span class="badge" style="background-color: var(--color-primary-light); color: var(--color-primary-dark); font-weight: 600;">
                                    ⭐ Principal
                                </span>
                            @else
                                <span style="color: var(--color-text-muted); font-size: var(--text-xs);">-</span>
                            @endif
                        </td>
                        <td>
                            @if($pack->is_active)
                                <span class="badge badge-confirmed">Activo</span>
                            @else
                                <span class="badge badge-cancelled">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btns">
                                <a href="{{ route('admin.packagings.edit', $pack->id) }}" class="btn-icon-table" title="Editar Empaque">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.packagings.destroy', $pack->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este empaque?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon-table" title="Eliminar Empaque" style="color: var(--color-error);">
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
                        <td colspan="9" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
                            No hay empaques configurados. Agrega el primero para que los clientes puedan elegirlo en el checkout.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Floating Toast Notification -->
<div id="packagingToast" class="admin-floating-toast toast-success">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
    </svg>
    <span id="packagingToastMessage">Orden de empaques guardado correctamente.</span>
</div>
@endsection

@push('scripts')
<!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('sortablePackagingsBody');
    const toast = document.getElementById('packagingToast');
    const toastMessage = document.getElementById('packagingToastMessage');
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
        const rows = tableBody.querySelectorAll('tr.packaging-row');
        rows.forEach((row, index) => {
            const numCell = row.querySelector('.packaging-order-num');
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

                const rows = Array.from(tableBody.querySelectorAll('tr.packaging-row'));
                const order = rows.map(r => parseInt(r.getAttribute('data-id'))).filter(Boolean);

                // Send updated order to backend
                fetch("{{ route('admin.packagings.reorder') }}", {
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
                    showToast(data.message || 'Orden de empaques actualizado correctamente.');
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
